<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('pos.index', compact('products', 'customers', 'categories'));
    }
}
