<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier', 'batches' => function ($q) {
            // Get active batches sorted by expiry date (FIFO)
            $q->where(function ($query) {
                $query->where('shelf_quantity', '>', 0)
                    ->orWhere('back_quantity', '>', 0);
            })->orderBy('expiry_date', 'asc');
        }])
            ->whereRaw('(shelf_stock + back_stock) > 0');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $perPage = $request->input('per_page', \Illuminate\Support\Facades\Cache::get('settings.pagination_per_page', 8));
        $products = $query->orderBy('name')->paginate($perPage);

        $customers = Customer::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('pos.index', compact('products', 'customers', 'categories'));
    }

    public function verifyAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'action' => 'required|string',
                'item_id' => 'nullable|integer',
                'items' => 'nullable|array',
                'items.*.product_id' => 'nullable|integer',
                'items.*.quantity' => 'nullable|integer',
                'reason' => 'required|string',
            ]);

            // Verify admin credentials
            $admin = \App\Models\User::where('username', $validated['username'])
                ->whereIn('role', ['admin', 'superadmin'])
                ->first();

            if (!$admin || !\Illuminate\Support\Facades\Hash::check($validated['password'], $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid admin credentials. Only admin users can perform this action.'
                ], 401);
            }

            // Log the void action
            $actionDescription = $validated['action'] === 'void_entire_sale'
                ? "Voided entire sale in POS - Reason: {$validated['reason']} - Authorized by: {$admin->name}"
                : "Voided item from POS cart - Item ID: {$validated['item_id']} - Reason: {$validated['reason']} - Authorized by: {$admin->name}";

            \App\Traits\LogsActivity::logActivity(
                $validated['action'] === 'void_entire_sale' ? 'void_entire_sale_pos' : 'void_item_pos',
                $actionDescription,
                null,
                null,
                [
                    'action' => $validated['action'],
                    'item_id' => $validated['item_id'] ?? null,
                    'reason' => $validated['reason'],
                    'authorized_by' => $admin->name,
                    'authorized_by_username' => $admin->username,
                ]
            );

            // If this is a void of the entire sale initiated from POS (cart discard),
            // create a voided Sale record so it appears in Sales History. The client
            // should pass the cart items in 'items' when requesting this action.
            if ($validated['action'] === 'void_entire_sale' && !empty($validated['items']) && is_array($validated['items'])) {
                // Build sale and items but DO NOT decrement stock (these were only in cart)
                // Ensure a valid customer_id exists (use Walk-in Customer as default)
                $walkIn = Customer::firstOrCreate(
                    ['name' => 'Walk-in Customer'],
                    ['phone' => 'N/A', 'address' => 'N/A']
                );

                $saleData = [
                    'customer_id' => $walkIn->id,
                    'user_id' => $admin->id,
                    'total' => 0,
                    'payment_method' => 'void',
                    'paid_amount' => 0,
                    'change_amount' => 0,
                    'is_voided' => true,
                    'voided_at' => now(),
                    'voided_by' => $admin->id,
                    'void_reason' => $validated['reason'],
                ];

                $sale = \App\Models\Sale::create($saleData);

                $itemsForReceipt = [];
                foreach ($validated['items'] as $it) {
                    $product = \App\Models\Product::find($it['product_id']);
                    if (!$product) continue;
                    $quantity = intval($it['quantity'] ?? 1);
                    $price = $product->price;
                    $subtotal = 0; // voided, not counted

                    \App\Models\SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'is_voided' => true,
                    ]);

                    $itemsForReceipt[] = [
                        'name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'is_voided' => true,
                    ];
                }

                // Prepare receipt-like response for client (optional)
                $receipt = [
                    'receipt_number' => 'RCP-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                    'date' => $sale->created_at->format('F d, Y'),
                    'time' => $sale->created_at->format('h:i A'),
                    'cashier' => $admin->name,
                    'customer' => null,
                    'items' => $itemsForReceipt,
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'payment_method' => 'void',
                ];

                return response()->json([
                    'success' => true,
                    'admin_name' => $admin->name,
                    'message' => 'Authorization successful',
                    'void_sale_id' => $sale->id,
                    'receipt' => $receipt,
                ]);
            }

            return response()->json([
                'success' => true,
                'admin_name' => $admin->name,
                'message' => 'Authorization successful'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return categories relevant to a given product type.
     * Used by the POS AJAX filter to populate category options.
     */
    public function categories(Request $request)
    {
        $productType = $request->query('product_type');

        if (empty($productType)) {
            $categories = Category::orderBy('name')->get();
        } else {
            // Only return categories that have products of the given product_type
            $categories = Category::whereHas('products', function ($q) use ($productType) {
                $q->where('product_type', $productType);
            })->orderBy('name')->get();
        }

        return response()->json($categories);
    }
}
