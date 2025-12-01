<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user', 'batch'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('reference_number')) {
            $query->where('reference_number', 'like', '%' . $request->reference_number . '%');
        }

        $movements = $query->paginate(20);
        $products = Product::orderBy('name')->get();

        // Calculate summary stats
        $totalStockIn = StockMovement::whereDate('created_at', today())->sum('stock_in');
        $totalStockOut = StockMovement::whereDate('created_at', today())->sum('stock_out');
        $weeklyMovements = StockMovement::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return view('inventory.stock-movements.index', compact(
            'movements',
            'products',
            'totalStockIn',
            'totalStockOut',
            'weeklyMovements'
        ));
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'user', 'batch']);

        // Get related movements for this product
        $relatedMovements = StockMovement::with(['user', 'batch'])
            ->where('product_id', $stockMovement->product_id)
            ->where('id', '!=', $stockMovement->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get product's current batches with expiry information
        $productBatches = $stockMovement->product->batches()
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();

        return view('inventory.stock-movements.show', compact(
            'stockMovement',
            'relatedMovements',
            'productBatches'
        ));
    }

    public function create()
    {
        $products = Product::with('batches')->orderBy('name')->get();
        return view('inventory.stock-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_id' => 'nullable|exists:product_batches,id',
            'type' => 'required|in:stock_in,stock_out,adjustment',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:100',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $previousStock = $product->total_stock;

        // Determine stock in/out based on type and quantity
        $stockIn = 0;
        $stockOut = 0;

        if ($validated['type'] === 'stock_in') {
            $stockIn = $validated['quantity'];
            $newStock = $previousStock + $validated['quantity'];
        } elseif ($validated['type'] === 'stock_out') {
            $stockOut = $validated['quantity'];
            $newStock = max(0, $previousStock - $validated['quantity']);
        } else { // adjustment
            if ($validated['quantity'] > $previousStock) {
                $stockIn = $validated['quantity'] - $previousStock;
                $newStock = $validated['quantity'];
            } else {
                $stockOut = $previousStock - $validated['quantity'];
                $newStock = $validated['quantity'];
            }
        }

        // Create stock movement record
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'batch_id' => $validated['batch_id'],
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'stock_in' => $stockIn,
            'stock_out' => $stockOut,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reference_number' => $validated['reference_number'],
            'reason' => $validated['reason'],
        ]);

        // Update product total stock
        $product->update(['total_stock' => $newStock]);

        return redirect()->route('inventory.stock-movements.index')
            ->with('success', 'Stock movement recorded successfully.');
    }
}