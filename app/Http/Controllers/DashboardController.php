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

            // Low stock items count (using new shelf + back stock)
            $lowStockItems = Product::whereRaw('(shelf_stock + back_stock) <= low_stock_threshold')->count();

            // Get monthly sales (current month)
            $monthlySales = Sale::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total') ?? 0;

            // Get monthly sales count
            $monthlySalesCount = Sale::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // Stock reports (using new structure)
            $totalStock = Product::sum(DB::raw('shelf_stock + back_stock')) ?? 0;
            $criticalStockItems = Product::whereRaw('(shelf_stock + back_stock) <= stock_danger_level')->count();
            $outOfStockItems = Product::whereRaw('(shelf_stock + back_stock) = 0')->count();

            // Expiry alerts - batches expiring within 30 days
            $expiringBatches = \App\Models\ProductBatch::with('product')
                ->where('expiry_date', '<=', Carbon::now()->addDays(30))
                ->where('expiry_date', '>', Carbon::now())
                ->whereRaw('(shelf_quantity + back_quantity) > 0')
                ->orderBy('expiry_date', 'asc')
                ->take(5)
                ->get();

            // Expired batches
            $expiredBatches = \App\Models\ProductBatch::with('product')
                ->where('expiry_date', '<=', Carbon::now())
                ->whereRaw('(shelf_quantity + back_quantity) > 0')
                ->orderBy('expiry_date', 'desc')
                ->take(5)
                ->get();

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

            // Low stock products (detailed) - using new structure
            $lowStockProducts = Product::with(['category', 'supplier'])
                ->whereRaw('(shelf_stock + back_stock) <= low_stock_threshold')
                ->orderByRaw('(shelf_stock + back_stock) ASC')
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
                'lowStockProducts',
                'expiringBatches',
                'expiredBatches'
            ));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Failed to load dashboard data: ' . $e->getMessage());
        }
    }
}