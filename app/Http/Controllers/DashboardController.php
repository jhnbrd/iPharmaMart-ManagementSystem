<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total revenue
        $totalRevenue = Sale::sum('total');

        // Get total products count
        $totalProducts = Product::count();

        // Get total customers count
        $totalCustomers = Customer::count();

        // Get low stock items count
        $lowStockItems = Product::whereRaw('stock <= low_stock_threshold')->count();

        // Get recent sales with customer and items
        $recentSales = Sale::with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::with(['category', 'supplier'])
            ->whereRaw('stock <= low_stock_threshold')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'lowStockItems',
            'recentSales',
            'lowStockProducts'
        ));
    }
}
