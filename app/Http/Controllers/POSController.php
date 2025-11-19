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
        $query = Product::with(['category', 'supplier'])
            ->where('stock', '>', 0);

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

        $products = $query->orderBy('name')->paginate(10);

        $customers = Customer::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('pos.index', compact('products', 'customers', 'categories'));
    }
}
