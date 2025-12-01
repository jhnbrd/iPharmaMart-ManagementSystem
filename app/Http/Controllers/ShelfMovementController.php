<?php

namespace App\Http\Controllers;

use App\Models\ShelfMovement;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShelfMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = ShelfMovement::with(['product', 'user', 'batch'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('movement_type')) {
            if ($request->movement_type === 'restock') {
                $query->where('new_shelf_stock', '>', $request->input('previous_shelf_stock', 0));
            } elseif ($request->movement_type === 'destock') {
                $query->where('new_shelf_stock', '<', $request->input('previous_shelf_stock', 0));
            }
        }

        $movements = $query->paginate(20);
        $products = Product::orderBy('name')->get();

        return view('inventory.shelf-movements.index', compact('movements', 'products'));
    }

    public function show(ShelfMovement $shelfMovement)
    {
        $shelfMovement->load(['product', 'user', 'batch']);

        // Get related movements for this product
        $relatedMovements = ShelfMovement::with(['user', 'batch'])
            ->where('product_id', $shelfMovement->product_id)
            ->where('id', '!=', $shelfMovement->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('inventory.shelf-movements.show', compact('shelfMovement', 'relatedMovements'));
    }

    public function create()
    {
        $products = Product::with('batches')->orderBy('name')->get();
        return view('inventory.shelf-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_id' => 'nullable|exists:product_batches,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|in:restock,destock',
            'remarks' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Calculate new stock levels
        $currentShelfStock = $product->shelf_stock ?? 0;
        $currentBackStock = $product->back_stock ?? 0;

        if ($validated['movement_type'] === 'restock') {
            // Moving from back stock to shelf stock
            $newShelfStock = $currentShelfStock + $validated['quantity'];
            $newBackStock = max(0, $currentBackStock - $validated['quantity']);
        } else {
            // Moving from shelf stock to back stock
            $newShelfStock = max(0, $currentShelfStock - $validated['quantity']);
            $newBackStock = $currentBackStock + $validated['quantity'];
        }

        // Create shelf movement record
        ShelfMovement::create([
            'product_id' => $validated['product_id'],
            'batch_id' => $validated['batch_id'],
            'user_id' => Auth::id(),
            'quantity' => $validated['quantity'],
            'previous_shelf_stock' => $currentShelfStock,
            'new_shelf_stock' => $newShelfStock,
            'previous_back_stock' => $currentBackStock,
            'new_back_stock' => $newBackStock,
            'remarks' => $validated['remarks'],
        ]);

        // Update product stock levels
        $product->update([
            'shelf_stock' => $newShelfStock,
            'back_stock' => $newBackStock,
        ]);

        return redirect()->route('inventory.shelf-movements.index')
            ->with('success', 'Shelf movement recorded successfully.');
    }
}
