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
    public function index()
    {
        $products = Product::with(['category', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('inventory.index', compact('products'));
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
