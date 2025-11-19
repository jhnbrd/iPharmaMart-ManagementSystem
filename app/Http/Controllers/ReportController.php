<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $paymentMethod = $request->input('payment_method', '');

        $query = Sale::with(['customer', 'user', 'items.product'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        $totalRevenue = $sales->sum('total');
        $totalTransactions = $sales->count();
        $averageSale = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Payment method breakdown
        $paymentBreakdown = Sale::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('reports.sales', compact(
            'sales',
            'startDate',
            'endDate',
            'paymentMethod',
            'totalRevenue',
            'totalTransactions',
            'averageSale',
            'paymentBreakdown'
        ));
    }

    public function inventory(Request $request)
    {
        $categoryId = $request->input('category_id', '');
        $productType = $request->input('product_type', '');
        $stockStatus = $request->input('stock_status', '');

        $query = Product::with(['category', 'supplier']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($productType) {
            $query->where('product_type', $productType);
        }

        if ($stockStatus === 'low') {
            $query->whereRaw('stock <= low_stock_threshold');
        } elseif ($stockStatus === 'critical') {
            $query->whereRaw('stock <= stock_danger_level');
        } elseif ($stockStatus === 'out') {
            $query->where('stock', 0);
        }

        $products = $query->orderBy('name')->get();

        $totalProducts = $products->count();
        $totalStockValue = $products->sum(function ($product) {
            return $product->stock * $product->price;
        });
        $lowStockCount = Product::whereRaw('stock <= low_stock_threshold')->count();
        $criticalStockCount = Product::whereRaw('stock <= stock_danger_level')->count();
        $outOfStockCount = Product::where('stock', 0)->count();

        $categories = Category::orderBy('name')->get();

        return view('reports.inventory', compact(
            'products',
            'categoryId',
            'productType',
            'stockStatus',
            'totalProducts',
            'totalStockValue',
            'lowStockCount',
            'criticalStockCount',
            'outOfStockCount',
            'categories'
        ));
    }
}
