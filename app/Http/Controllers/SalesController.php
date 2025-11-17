<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

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
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $saleId = DB::transaction(function () use ($validated, $request) {
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

            $total = 0;

            // Calculate total
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;
            }

            // Create sale
            $sale = Sale::create([
                'customer_id' => $customerId,
                'user_id' => auth()->id() ?? 1,
                'total' => $total,
            ]);

            // Create sale items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            return $sale->id;
        });

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully',
                'sale_id' => $saleId
            ]);
        }

        return redirect()->route('sales.index')
            ->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'user']);

        return view('sales.show', compact('sale'));
    }
}
