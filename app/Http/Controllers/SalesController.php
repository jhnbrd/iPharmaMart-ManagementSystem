<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SeniorCitizenTransaction;
use App\Models\PwdTransaction;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'items.product', 'user']);

        // Filter by customer
        if ($request->filled('customer')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->input('per_page', 10);
        $sales = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('stock', '>', 0)->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method' => 'required|in:cash,gcash,card',
                'paid_amount' => 'required|numeric|min:0',
                'change_amount' => 'required|numeric|min:0',
                'reference_number' => 'nullable|string|max:255',
                'discount_type' => 'nullable|in:senior_citizen,pwd',
                'discount_id_number' => 'nullable|string|max:255',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
            ], [
                'items.required' => 'Cannot process checkout - No items in cart',
                'items.min' => 'Cannot process checkout - At least one item is required',
                'items.*.product_id.required' => 'Product ID is required for all items',
                'items.*.product_id.exists' => 'One or more products are invalid',
                'items.*.quantity.required' => 'Quantity is required for all items',
                'items.*.quantity.min' => 'Quantity must be at least 1',
                'payment_method.required' => 'Payment method is required',
                'payment_method.in' => 'Invalid payment method selected',
                'paid_amount.required' => 'Payment amount is required',
                'paid_amount.min' => 'Payment amount must be greater than 0',
            ]);

            $result = DB::transaction(function () use ($validated, $request) {
                $customerId = $validated['customer_id'] ?? null;

                // If no customer_id but customer data provided, create new customer
                if (!$customerId && !empty($validated['customer_name'])) {
                    $customer = Customer::create([
                        'name' => $validated['customer_name'],
                        'phone' => $validated['customer_phone'] ?? '',
                        'address' => $validated['customer_address'] ?? '',
                    ]);
                    $customerId = $customer->id;
                }

                // If still no customer, create a default walk-in customer
                if (!$customerId) {
                    $customer = Customer::firstOrCreate(
                        ['name' => 'Walk-in Customer'],
                        ['phone' => 'N/A', 'address' => 'N/A']
                    );
                    $customerId = $customer->id;
                }

                $subtotal = 0;
                $itemsDetails = [];

                // Calculate subtotal and collect items details
                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    // Check stock availability using total_stock accessor
                    $availableStock = $product->total_stock;
                    if ($availableStock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$availableStock}, Requested: {$item['quantity']}");
                    }

                    $itemSubtotal = $product->price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    $itemsDetails[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                // Calculate VAT (inclusive - VAT is already in the price)
                // VAT = Subtotal / 1.12 * 0.12
                $tax = $subtotal - ($subtotal / 1.12); // Extract 12% VAT from subtotal
                $originalTotal = $subtotal; // Total is the subtotal since VAT is inclusive
                $total = $originalTotal;

                // Apply discount if applicable
                $discountAmount = 0;
                $discountPercentage = 0;
                $discountInfo = null;

                if (!empty($validated['discount_type']) && !empty($validated['discount_id_number'])) {
                    $discountPercentage = $validated['discount_percentage'] ?? 20;
                    $discountAmount = $originalTotal * ($discountPercentage / 100);
                    $total = $originalTotal - $discountAmount;

                    $discountInfo = [
                        'type' => $validated['discount_type'],
                        'id_number' => $validated['discount_id_number'],
                        'percentage' => $discountPercentage,
                        'amount' => $discountAmount,
                    ];
                }

                // Validate paid amount
                if ($validated['paid_amount'] < $total) {
                    throw new \Exception("Paid amount (₱" . number_format($validated['paid_amount'], 2) . ") is less than total (₱" . number_format($total, 2) . ")");
                }

                // Create sale with payment information
                $sale = Sale::create([
                    'customer_id' => $customerId,
                    'user_id' => Auth::id(),
                    'total' => $total,
                    'payment_method' => $validated['payment_method'],
                    'paid_amount' => $validated['paid_amount'],
                    'change_amount' => $validated['change_amount'],
                    'reference_number' => $validated['reference_number'] ?? null,
                ]);

                // Create sale items and update stock
                foreach ($itemsDetails as $item) {
                    $product = Product::find($item['product_id']);

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Update product stock (decrement from shelf first, then back stock if needed)
                    $quantityToDecrement = $item['quantity'];
                    if ($product->shelf_stock >= $quantityToDecrement) {
                        // Sufficient shelf stock
                        $product->decrement('shelf_stock', $quantityToDecrement);
                    } else {
                        // Use all shelf stock and remaining from back stock
                        $fromShelf = $product->shelf_stock;
                        $fromBack = $quantityToDecrement - $fromShelf;
                        $product->shelf_stock = 0;
                        $product->decrement('back_stock', $fromBack);
                        $product->save();
                    }
                }

                // Create discount transaction record if discount was applied
                if ($discountInfo) {
                    $customer = Customer::find($customerId);

                    if ($discountInfo['type'] === 'senior_citizen') {
                        SeniorCitizenTransaction::create([
                            'sale_id' => $sale->id,
                            'sc_id_number' => $discountInfo['id_number'],
                            'sc_name' => $customer->name,
                            'sc_birthdate' => $customer->birthdate ?? now()->subYears(65),
                            'original_amount' => $originalTotal,
                            'discount_percentage' => $discountInfo['percentage'],
                            'discount_amount' => $discountInfo['amount'],
                            'final_amount' => $total,
                        ]);
                    } elseif ($discountInfo['type'] === 'pwd') {
                        PwdTransaction::create([
                            'sale_id' => $sale->id,
                            'pwd_id_number' => $discountInfo['id_number'],
                            'pwd_name' => $customer->name,
                            'pwd_birthdate' => $customer->birthdate ?? now()->subYears(30),
                            'disability_type' => $customer->disability_type ?? 'Not specified',
                            'original_amount' => $originalTotal,
                            'discount_percentage' => $discountInfo['percentage'],
                            'discount_amount' => $discountInfo['amount'],
                            'final_amount' => $total,
                        ]);
                    }
                }

                // Log the sale transaction
                $customer = Customer::find($customerId);
                self::logActivity(
                    'sale',
                    "Sale transaction completed - Customer: {$customer->name}, Total: ₱" . number_format($total, 2) . ", Payment: " . strtoupper($validated['payment_method']),
                    $sale,
                    null,
                    [
                        'sale_id' => $sale->id,
                        'customer' => $customer->name,
                        'subtotal' => $subtotal,
                        'tax' => $tax,
                        'total' => $total,
                        'payment_method' => $validated['payment_method'],
                        'paid_amount' => $validated['paid_amount'],
                        'change_amount' => $validated['change_amount'],
                        'reference_number' => $validated['reference_number'] ?? null,
                        'items' => $itemsDetails,
                    ]
                );

                // Prepare receipt data
                $receiptData = [
                    'receipt_number' => 'RCP-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                    'date' => $sale->created_at->format('F d, Y'),
                    'time' => $sale->created_at->format('h:i A'),
                    'cashier' => Auth::user()->name,
                    'customer' => $customer->name !== 'Walk-in Customer' ? [
                        'name' => $customer->name,
                        'phone' => $customer->phone,
                        'address' => $customer->address,
                    ] : null,
                    'items' => $itemsDetails,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'original_total' => $originalTotal,
                    'total' => $total,
                    'payment_method' => $validated['payment_method'],
                    'paid_amount' => $validated['paid_amount'],
                    'change_amount' => $validated['change_amount'],
                    'reference_number' => $validated['reference_number'] ?? null,
                    'discount' => $discountInfo,
                ];

                return [
                    'sale_id' => $sale->id,
                    'receipt' => $receiptData,
                ];
            });

            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sale created successfully',
                    'sale_id' => $result['sale_id'],
                    'receipt' => $result['receipt'],
                ]);
            }

            return redirect()->route('sales.index')
                ->with('success', 'Sale created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'user']);

        // Load discount transactions if they exist
        $seniorTransaction = SeniorCitizenTransaction::where('sale_id', $sale->id)->first();
        $pwdTransaction = PwdTransaction::where('sale_id', $sale->id)->first();

        $discountInfo = null;
        if ($seniorTransaction) {
            $discountInfo = [
                'type' => 'Senior Citizen (20%)',
                'id_number' => $seniorTransaction->sc_id_number,
                'name' => $seniorTransaction->sc_name,
                'percentage' => $seniorTransaction->discount_percentage,
                'amount' => $seniorTransaction->discount_amount,
                'original_amount' => $seniorTransaction->original_amount,
            ];
        } elseif ($pwdTransaction) {
            $discountInfo = [
                'type' => 'PWD (20%)',
                'id_number' => $pwdTransaction->pwd_id_number,
                'name' => $pwdTransaction->pwd_name,
                'percentage' => $pwdTransaction->discount_percentage,
                'amount' => $pwdTransaction->discount_amount,
                'original_amount' => $pwdTransaction->original_amount,
            ];
        }

        return view('sales.show', compact('sale', 'discountInfo'));
    }
}
