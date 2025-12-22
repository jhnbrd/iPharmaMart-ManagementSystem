<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier', 'batches' => function ($q) {
            // Load active batches with stock, sorted by expiry date
            $q->where(function ($query) {
                $query->where('shelf_quantity', '>', 0)
                    ->orWhere('back_quantity', '>', 0);
            })->orderBy('expiry_date', 'asc');
        }]);

        // Filter by product name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by product type (default to pharmacy if not specified)
        $productType = $request->input('product_type', 'pharmacy');
        $query->where('product_type', $productType);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'out':
                    $query->whereRaw('(shelf_stock + back_stock) = 0');
                    break;
                case 'critical':
                    $query->whereRaw('(shelf_stock + back_stock) <= stock_danger_level AND (shelf_stock + back_stock) > 0');
                    break;
                case 'low':
                    $query->whereRaw('(shelf_stock + back_stock) <= low_stock_threshold AND (shelf_stock + back_stock) > stock_danger_level');
                    break;
                case 'ok':
                    $query->whereRaw('(shelf_stock + back_stock) > low_stock_threshold');
                    break;
            }
        }

        $perPage = $request->input('per_page', \Illuminate\Support\Facades\Cache::get('settings.pagination_per_page', 10));
        $products = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $categories = Category::all();

        return view('inventory.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('inventory.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand_name' => 'required|string|max:255',
                'generic_name' => 'required_if:product_type,pharmacy|nullable|string|max:255',
                'product_type' => 'required|in:pharmacy,mini_mart',
                'barcode' => 'nullable|string|unique:products,barcode',
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'low_stock_threshold' => 'required|integer|min:0',
                'stock_danger_level' => 'required|integer|min:0',
                'unit' => 'required|string|max:50',
                'unit_quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ], [
                'generic_name.required_if' => 'Generic name is required for pharmacy products.',
            ]);

            // Set initial stock to 0 - stock will be added through Stock In module
            $validated['shelf_stock'] = 0;
            $validated['back_stock'] = 0;

            $product = Product::create($validated);

            self::logActivity(
                'create',
                "Created product: {$product->name}",
                $product,
                null,
                $product->toArray()
            );

            return redirect()->route('inventory.index')
                ->with('success', 'Product added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Product $inventory)
    {
        $inventory->load(['category', 'supplier', 'batches' => function ($query) {
            $query->orderBy('expiry_date');
        }]);

        // Get recent stock movements for this product
        $recentMovements = \App\Models\StockMovement::with(['user', 'batch'])
            ->where('product_id', $inventory->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent shelf movements for this product
        $recentShelfMovements = \App\Models\ShelfMovement::with(['user', 'batch'])
            ->where('product_id', $inventory->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('inventory.show', compact('inventory', 'recentMovements', 'recentShelfMovements'));
    }

    public function edit(Product $inventory)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('inventory.edit', compact('inventory', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $inventory)
    {
        try {
            $oldValues = $inventory->toArray();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand_name' => 'required|string|max:255',
                'generic_name' => 'required_if:product_type,pharmacy|nullable|string|max:255',
                'product_type' => 'required|in:pharmacy,mini_mart',
                'barcode' => 'nullable|string|unique:products,barcode,' . $inventory->id,
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                // Stock is managed through Stock In/Out module only
                'low_stock_threshold' => 'required|integer|min:0',
                'stock_danger_level' => 'required|integer|min:0',
                'unit' => 'required|string|max:50',
                'unit_quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ], [
                'generic_name.required_if' => 'Generic name is required for pharmacy products.',
            ]);

            $inventory->update($validated);

            self::logActivity(
                'update',
                "Updated product: {$inventory->name}",
                $inventory,
                $oldValues,
                $inventory->fresh()->toArray()
            );

            return redirect()->route('inventory.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function void(Request $request, Product $inventory)
    {
        try {
            $validated = $request->validate([
                'admin_username' => 'required|string',
                'admin_password' => 'required|string',
                'void_reason' => 'required|string',
            ]);

            // Verify admin credentials
            $admin = \App\Models\User::where('username', $validated['admin_username'])
                ->whereIn('role', ['admin', 'superadmin'])
                ->first();

            if (!$admin || !\Illuminate\Support\Facades\Hash::check($validated['admin_password'], $admin->password)) {
                return redirect()->back()
                    ->with('error', 'Invalid admin credentials. Only admin users can void products.');
            }

            $productName = $inventory->name;
            $productData = $inventory->toArray();

            // Delete the product
            $inventory->delete();

            // Log the void action with admin authorization details
            self::logActivity(
                'delete',
                "VOIDED product: {$productName} - Reason: {$validated['void_reason']} - Authorized by: {$admin->name}",
                null,
                $productData,
                [
                    'void_reason' => $validated['void_reason'],
                    'authorized_by' => $admin->name,
                    'authorized_by_username' => $admin->username,
                ]
            );

            return redirect()->route('inventory.index')
                ->with('success', "Product '{$productName}' has been voided successfully.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to void product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $inventory)
    {
        try {
            $productName = $inventory->name;
            $productData = $inventory->toArray();

            $inventory->delete();

            self::logActivity(
                'delete',
                "Deleted product: {$productName}",
                null,
                $productData,
                null
            );

            return redirect()->route('inventory.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
