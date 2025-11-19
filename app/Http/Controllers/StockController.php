<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $movements = StockMovement::with(['product.category', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('stock.index', compact('movements'));
    }

    public function stockIn()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('stock.stock-in', compact('products'));
    }

    public function processStockIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            $previousStock = $product->stock;
            $newStock = $previousStock + $validated['quantity'];

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reference_number' => $validated['reference_number'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Update product stock
            $product->update(['stock' => $newStock]);

            // Log activity
            self::logActivity(
                'stock_in',
                "Stock IN: {$product->name} (+{$validated['quantity']} units) - New Stock: {$newStock}",
                $product,
                ['stock' => $previousStock],
                ['stock' => $newStock, 'reference' => $validated['reference_number'] ?? 'N/A']
            );
        });

        return redirect()->route('stock.index')
            ->with('success', 'Stock added successfully.');
    }

    public function stockOut()
    {
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();
        return view('stock.stock-out', compact('products'));
    }

    public function processStockOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            $previousStock = $product->stock;

            if ($previousStock < $validated['quantity']) {
                throw new \Exception('Insufficient stock. Available: ' . $previousStock);
            }

            $newStock = $previousStock - $validated['quantity'];

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'quantity' => $validated['quantity'],
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reference_number' => $validated['reference_number'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Update product stock
            $product->update(['stock' => $newStock]);

            // Log activity
            self::logActivity(
                'stock_out',
                "Stock OUT: {$product->name} (-{$validated['quantity']} units) - New Stock: {$newStock}",
                $product,
                ['stock' => $previousStock],
                ['stock' => $newStock, 'reference' => $validated['reference_number'] ?? 'N/A']
            );
        });

        return redirect()->route('stock.index')
            ->with('success', 'Stock removed successfully.');
    }
}
