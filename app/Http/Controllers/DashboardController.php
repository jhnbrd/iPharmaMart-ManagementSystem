<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Date filtering - default to month-to-date
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            // Role-based data filtering
            $salesQuery = Sale::whereBetween('created_at', [$start, $end]);

            if ($user->role === 'cashier') {
                // Cashiers see only their own sales
                $salesQuery->where('user_id', $user->id);
            }

            // Get total revenue
            $totalRevenue = (clone $salesQuery)->sum('total') ?? 0;

            // Get total products count
            $totalProducts = Product::count();

            // Get total customers count
            $totalCustomers = Customer::count();

            // Low stock items count (using new shelf + back stock)
            $lowStockItems = Product::whereRaw('(shelf_stock + back_stock) <= low_stock_threshold')->count();

            // Get sales within date range
            $monthlySales = (clone $salesQuery)->sum('total') ?? 0;

            // Get sales count within date range
            $monthlySalesCount = (clone $salesQuery)->count();

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
                'expiredBatches',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Failed to load dashboard data: ' . $e->getMessage());
        }
    }
}
