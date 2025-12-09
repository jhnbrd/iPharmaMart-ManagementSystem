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

            // Get total transactions count (within date range)
            $totalTransactions = (clone $salesQuery)->count();

            // Get expiring products count (based on expiry_alert_days setting)
            $expiryAlertDays = \Illuminate\Support\Facades\Cache::get('settings.expiry_alert_days', 7);
            $expiringProductsCount = \App\Models\ProductBatch::where('expiry_date', '<=', Carbon::now()->addDays($expiryAlertDays))
                ->where('expiry_date', '>', Carbon::now())
                ->whereRaw('(shelf_quantity + back_quantity) > 0')
                ->count();

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

            // Top products (by quantity sold in date range)
            $topProductsQuery = DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->whereBetween('sales.created_at', [$start, $end]);

            if ($user->role === 'cashier') {
                $topProductsQuery->where('sales.user_id', $user->id);
            }

            $topProducts = $topProductsQuery
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

            // Association Rules - Products frequently bought together
            $productAssociations = DB::select("
                SELECT 
                    p1.name as product1_name,
                    p2.name as product2_name,
                    COUNT(*) as frequency,
                    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(DISTINCT sale_id) FROM sale_items), 2) as confidence
                FROM sale_items si1
                JOIN sale_items si2 ON si1.sale_id = si2.sale_id AND si1.product_id < si2.product_id
                JOIN products p1 ON si1.product_id = p1.id
                JOIN products p2 ON si2.product_id = p2.id
                JOIN sales s ON si1.sale_id = s.id
                WHERE s.created_at BETWEEN ? AND ?
                GROUP BY si1.product_id, si2.product_id, p1.name, p2.name
                HAVING frequency >= 2
                ORDER BY frequency DESC, confidence DESC
                LIMIT 3
            ", [$start, $end]);

            // ML Insights - Predictive analytics based on historical data
            $mlInsights = [];

            // 1. Sales trend prediction (simple moving average)
            $recentSales = Sale::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                ->selectRaw('DATE(created_at) as date, SUM(total) as daily_total')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take(7)
                ->get();

            if ($recentSales->count() >= 3) {
                $avgDailySales = $recentSales->avg('daily_total');
                $lastDaySales = $recentSales->first()->daily_total ?? 0;
                $trend = $lastDaySales > $avgDailySales ? 'increasing' : 'decreasing';
                $mlInsights['sales_trend'] = [
                    'trend' => $trend,
                    'avg' => $avgDailySales,
                    'last' => $lastDaySales,
                    'change_percent' => $avgDailySales > 0 ? round((($lastDaySales - $avgDailySales) / $avgDailySales) * 100, 1) : 0
                ];
            }

            // 2. Peak day of the week analysis
            $peakDay = DB::table('sales')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('CAST(strftime("%w", created_at) AS INTEGER) as day_num, COUNT(*) as count, SUM(total) as revenue')
                ->groupBy('day_num')
                ->orderByDesc('count')
                ->first();

            if ($peakDay) {
                $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $mlInsights['peak_day'] = [
                    'day' => $daysOfWeek[$peakDay->day_num],
                    'transactions' => $peakDay->count,
                    'revenue' => $peakDay->revenue
                ];
            }

            // 3. Peak hours analysis
            $peakHour = DB::table('sales')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('CAST(strftime("%H", created_at) AS INTEGER) as hour, COUNT(*) as count, SUM(total) as revenue')
                ->groupBy('hour')
                ->orderByDesc('count')
                ->first();

            if ($peakHour) {
                $mlInsights['peak_hour'] = [
                    'hour' => $peakHour->hour,
                    'transactions' => $peakHour->count,
                    'revenue' => $peakHour->revenue
                ];
            }

            // 4. Customer loyalty analysis - Repeat customers
            $repeatCustomers = DB::table('sales')
                ->whereBetween('created_at', [$start, $end])
                ->whereNotNull('customer_id')
                ->select('customer_id', DB::raw('COUNT(*) as visit_count'))
                ->groupBy('customer_id')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            $totalCustomers = DB::table('sales')
                ->whereBetween('created_at', [$start, $end])
                ->whereNotNull('customer_id')
                ->distinct('customer_id')
                ->count('customer_id');

            if ($totalCustomers > 0) {
                $repeatRate = round(($repeatCustomers->count() / $totalCustomers) * 100, 1);
                $avgVisits = round($repeatCustomers->avg('visit_count'), 1);

                $mlInsights['customer_loyalty'] = [
                    'repeat_rate' => $repeatRate,
                    'repeat_count' => $repeatCustomers->count(),
                    'total_customers' => $totalCustomers,
                    'avg_visits' => $avgVisits
                ];
            }

            // Check if custom date filter is applied
            $isFiltered = $request->has('start_date') || $request->has('end_date');

            return view('dashboard', compact(
                'totalRevenue',
                'totalTransactions',
                'expiringProductsCount',
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
                'endDate',
                'expiryAlertDays',
                'isFiltered',
                'productAssociations',
                'mlInsights'
            ));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Failed to load dashboard data: ' . $e->getMessage());
        }
    }
}
