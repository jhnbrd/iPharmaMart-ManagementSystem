<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get total revenue
            $totalRevenue = Sale::sum('total') ?? 0;

            // Get total products count
            $totalProducts = Product::count();

            // Get total customers count
            $totalCustomers = Customer::count();

            // Get low stock items count
            $lowStockItems = Product::whereRaw('stock <= low_stock_threshold')->count();

            // Get monthly sales (current month)
            $monthlySales = Sale::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total') ?? 0;

            // Get monthly sales count
            $monthlySalesCount = Sale::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // Stock reports
            $totalStock = Product::sum('stock') ?? 0;
            $criticalStockItems = Product::whereRaw('stock <= stock_danger_level')->count();
            $outOfStockItems = Product::where('stock', 0)->count();

            // Top products (by quantity sold in current month)
            $topProducts = DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->whereMonth('sales.created_at', Carbon::now()->month)
                ->whereYear('sales.created_at', Carbon::now()->year)
                ->select(
                    'products.id',
                    'products.name',
                    'categories.name as category_name',
                    'products.price',
                    DB::raw('SUM(sale_items.quantity) as total_sold'),
                    DB::raw('SUM(sale_items.subtotal) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'categories.name', 'products.price')
                ->orderByDesc('total_sold')
                ->take(3)
                ->get();

            // Low stock products (detailed)
            $lowStockProducts = Product::with(['category', 'supplier'])
                ->whereRaw('stock <= low_stock_threshold')
                ->orderBy('stock', 'asc')
                ->take(3)
                ->get();

            return view('dashboard', compact(
                'totalRevenue',
                'totalProducts',
                'totalCustomers',
                'lowStockItems',
                'monthlySales',
                'monthlySalesCount',
                'totalStock',
                'criticalStockItems',
                'outOfStockItems',
                'topProducts',
                'lowStockProducts'
            ));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Failed to load dashboard data: ' . $e->getMessage());
        }
    }
}