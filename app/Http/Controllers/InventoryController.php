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
        $query = Product::with(['category', 'supplier']);

        // Filter by product name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'critical':
                    $query->whereColumn('stock', '<=', 'stock_danger_level')->where('stock', '>', 0);
                    break;
                case 'low':
                    $query->whereColumn('stock', '<=', 'low_stock_threshold')
                        ->whereColumn('stock', '>', 'stock_danger_level');
                    break;
                case 'ok':
                    $query->whereColumn('stock', '>', 'low_stock_threshold');
                    break;
            }
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate(10)
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:pharmacy,mini_mart',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'stock_danger_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

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
    }

    public function edit(Product $inventory)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('inventory.edit', compact('inventory', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $inventory)
    {
        $oldValues = $inventory->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
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
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
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
    }

    public function void(Request $request, Product $inventory)
    {
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
    }

    public function destroy(Product $inventory)
    {
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
    }
}
