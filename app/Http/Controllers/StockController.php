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

    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user', 'batch'])
            ->orderBy('created_at', 'desc');

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by product name search
        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product . '%');
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by reference number
        if ($request->filled('reference_number')) {
            $query->where('reference_number', 'like', '%' . $request->reference_number . '%');
        }

        $perPage = $request->input('per_page', 20);
        $movements = $query->paginate($perPage)->appends($request->except('page'));
        $products = Product::orderBy('name')->get();

        // Calculate summary statistics
        $totalStockIn = StockMovement::whereDate('created_at', today())->sum('in');
        $totalStockOut = StockMovement::whereDate('created_at', today())->sum('out');
        $weeklyMovements = StockMovement::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return view('stock.index', compact('movements', 'products', 'totalStockIn', 'totalStockOut', 'weeklyMovements'));
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

        return view('stock.show', compact('stockMovement', 'relatedMovements', 'productBatches'));
    }

    public function stockIn()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('stock.stock-in', compact('products'));
    }

    public function processStockIn(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:100000',
                'location' => 'required|in:shelf,back',
                'expiry_date' => 'nullable|date|after:today',
                'manufacture_date' => 'nullable|date|before_or_equal:today',
                'reference_number' => 'nullable|string|max:255',
                'reason' => 'nullable|string|max:1000',
            ], [
                'quantity.max' => 'Quantity cannot exceed 100,000 units in a single transaction.',
                'expiry_date.after' => 'Expiry date must be in the future.',
                'manufacture_date.before_or_equal' => 'Manufacture date cannot be in the future.',
            ]);

            DB::transaction(function () use ($validated) {
                $product = Product::findOrFail($validated['product_id']);
                $previousStock = $product->total_stock;

                // Generate unique batch number
                $batchNumber = 'BATCH-' . strtoupper(uniqid());

                // Create product batch
                $batch = \App\Models\ProductBatch::create([
                    'product_id' => $product->id,
                    'batch_number' => $batchNumber,
                    'quantity' => $validated['quantity'],
                    'shelf_quantity' => $validated['location'] === 'shelf' ? $validated['quantity'] : 0,
                    'back_quantity' => $validated['location'] === 'back' ? $validated['quantity'] : 0,
                    'expiry_date' => $validated['expiry_date'] ?? null,
                    'manufacture_date' => $validated['manufacture_date'] ?? null,
                    'supplier_invoice' => $validated['reference_number'] ?? null,
                    'notes' => $validated['reason'] ?? null,
                ]);

                // Update product stock
                if ($validated['location'] === 'shelf') {
                    $product->increment('shelf_stock', $validated['quantity']);
                } else {
                    $product->increment('back_stock', $validated['quantity']);
                }

                $product->refresh();
                $newStock = $product->total_stock;

                // Create stock movement record
                StockMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $batch->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'stock_in' => $validated['quantity'],
                    'stock_out' => 0,
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'reference_number' => $validated['reference_number'] ?? null,
                    'reason' => $validated['reason'] ?? 'Stock replenishment',
                ]);

                // Log activity
                self::logActivity(
                    'stock_in',
                    "Stock IN: {$product->name} (+{$validated['quantity']} units to {$validated['location']}) - Batch: {$batchNumber}",
                    $product,
                    ['stock' => $previousStock],
                    ['stock' => $newStock, 'batch' => $batchNumber, 'reference' => $validated['reference_number'] ?? 'N/A']
                );
            });

            return redirect()->route('stock.index')
                ->with('success', 'Stock added successfully with batch tracking.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add stock: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function stockOut()
    {
        $products = Product::with('category')
            ->whereRaw('(shelf_stock + back_stock) > 0')
            ->orderBy('name')
            ->get();
        return view('stock.stock-out', compact('products'));
    }

    public function processStockOut(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:100000',
                'location' => 'required|in:shelf,back',
                'reference_number' => 'nullable|string|max:255',
                'reason' => 'required|string|max:1000',
            ], [
                'quantity.max' => 'Quantity cannot exceed 100,000 units in a single transaction.',
                'reason.required' => 'Reason for stock removal is required.',
            ]);

            DB::transaction(function () use ($validated) {
                $product = Product::findOrFail($validated['product_id']);
                $previousStock = $product->total_stock;

                // Check stock availability
                $availableStock = $validated['location'] === 'shelf' ? $product->shelf_stock : $product->back_stock;

                if ($availableStock < $validated['quantity']) {
                    throw new \Exception("Insufficient stock in {$validated['location']}. Available: " . $availableStock);
                }

                // Decrease stock from specified location
                if ($validated['location'] === 'shelf') {
                    $product->decrement('shelf_stock', $validated['quantity']);
                } else {
                    $product->decrement('back_stock', $validated['quantity']);
                }

                // Update batch quantities (FIFO - First In First Out)
                $remainingQty = $validated['quantity'];
                $batches = $product->batches()
                    ->where($validated['location'] . '_quantity', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($remainingQty <= 0) break;

                    $field = $validated['location'] . '_quantity';
                    $batchQty = $batch->$field;
                    $deductQty = min($batchQty, $remainingQty);

                    $batch->decrement($field, $deductQty);
                    $batch->decrement('quantity', $deductQty);
                    $remainingQty -= $deductQty;
                }

                $product->refresh();
                $newStock = $product->total_stock;

                // Create stock movement record
                StockMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => null,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'stock_in' => 0,
                    'stock_out' => $validated['quantity'],
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'reference_number' => $validated['reference_number'] ?? null,
                    'reason' => $validated['reason'],
                ]);

                // Log activity
                self::logActivity(
                    'stock_out',
                    "Stock OUT: {$product->name} (-{$validated['quantity']} units from {$validated['location']}) - Reason: {$validated['reason']}",
                    $product,
                    ['stock' => $previousStock],
                    ['stock' => $newStock, 'reference' => $validated['reference_number'] ?? 'N/A']
                );
            });

            return redirect()->route('stock.index')
                ->with('success', 'Stock removed successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to remove stock: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function restock()
    {
        $products = Product::with('category', 'batches')
            ->where('back_stock', '>', 0)
            ->orderBy('name')
            ->get();
        return view('stock.restock', compact('products'));
    }

    public function processRestock(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'batch_id' => 'nullable|exists:product_batches,id',
                'quantity' => 'required|integer|min:1|max:100000',
                'remarks' => 'nullable|string|max:1000',
            ], [
                'quantity.max' => 'Quantity cannot exceed 100,000 units in a single transaction.',
            ]);

            DB::transaction(function () use ($validated) {
                $product = Product::findOrFail($validated['product_id']);

                // Check back stock availability
                if ($product->back_stock < $validated['quantity']) {
                    throw new \Exception('Insufficient back stock. Available: ' . $product->back_stock);
                }

                $previousShelf = $product->shelf_stock;
                $previousBack = $product->back_stock;

                // Move stock from back to shelf
                $product->decrement('back_stock', $validated['quantity']);
                $product->increment('shelf_stock', $validated['quantity']);

                // Update batch quantities if batch specified
                if (!empty($validated['batch_id'])) {
                    $batch = \App\Models\ProductBatch::findOrFail($validated['batch_id']);

                    if ($batch->back_quantity < $validated['quantity']) {
                        throw new \Exception('Insufficient quantity in specified batch.');
                    }

                    $batch->decrement('back_quantity', $validated['quantity']);
                    $batch->increment('shelf_quantity', $validated['quantity']);
                }

                $product->refresh();

                // Create shelf movement record
                \App\Models\ShelfMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $validated['batch_id'] ?? null,
                    'user_id' => Auth::id(),
                    'quantity' => $validated['quantity'],
                    'previous_shelf_stock' => $previousShelf,
                    'new_shelf_stock' => $product->shelf_stock,
                    'previous_back_stock' => $previousBack,
                    'new_back_stock' => $product->back_stock,
                    'remarks' => $validated['remarks'] ?? 'Shelf restocking',
                ]);

                // Log activity
                self::logActivity(
                    'shelf_restock',
                    "Shelf Restock: {$product->name} ({$validated['quantity']} units moved from back to shelf)",
                    $product,
                    ['shelf' => $previousShelf, 'back' => $previousBack],
                    ['shelf' => $product->shelf_stock, 'back' => $product->back_stock]
                );
            });

            return redirect()->route('stock.index')
                ->with('success', 'Shelf restocked successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to restock shelf: ' . $e->getMessage())
                ->withInput();
        }
    }
}