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
        try {
            // Support month parameter (default to current month)
            if ($request->filled('month')) {
                $monthDate = Carbon::parse($request->month . '-01');
                $startDate = $monthDate->startOfMonth()->format('Y-m-d');
                $endDate = $monthDate->endOfMonth()->format('Y-m-d');
            } else {
                $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
                $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
            }

            $paymentMethod = $request->input('payment_method', '');

            // Validate date inputs
            try {
                $startDateObj = Carbon::parse($startDate);
                $endDateObj = Carbon::parse($endDate);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Invalid date format provided.')
                    ->withInput();
            }

            // Validate date range
            if ($startDateObj->gt($endDateObj)) {
                return redirect()->back()
                    ->with('error', 'Start date cannot be after end date.')
                    ->withInput();
            }

            // Validate reasonable date range (not more than 5 years)
            if ($startDateObj->diffInYears($endDateObj) > 5) {
                return redirect()->back()
                    ->with('error', 'Date range cannot exceed 5 years.')
                    ->withInput();
            }

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
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate sales report: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function inventory(Request $request)
    {
        try {
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
                $query->whereRaw('(shelf_stock + back_stock) <= low_stock_threshold AND (shelf_stock + back_stock) > stock_danger_level');
            } elseif ($stockStatus === 'critical') {
                $query->whereRaw('(shelf_stock + back_stock) <= stock_danger_level AND (shelf_stock + back_stock) > 0');
            } elseif ($stockStatus === 'out') {
                $query->whereRaw('(shelf_stock + back_stock) = 0');
            }

            $products = $query->orderBy('name')->get();

            $totalProducts = $products->count();
            $totalStockValue = $products->sum(function ($product) {
                return $product->total_stock * $product->price;
            });
            $lowStockCount = Product::whereRaw('(shelf_stock + back_stock) <= low_stock_threshold AND (shelf_stock + back_stock) > stock_danger_level')->count();
            $criticalStockCount = Product::whereRaw('(shelf_stock + back_stock) <= stock_danger_level AND (shelf_stock + back_stock) > 0')->count();
            $outOfStockCount = Product::whereRaw('(shelf_stock + back_stock) = 0')->count();

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
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate inventory report: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function seniorCitizen(Request $request)
    {
        try {
            // Validate month format if provided
            if ($request->filled('month')) {
                if (!preg_match('/^\d{4}-\d{2}$/', $request->month)) {
                    return redirect()->back()
                        ->with('error', 'Invalid month format. Use YYYY-MM.')
                        ->withInput();
                }
            }

            // Support month parameter (default to current month)
            if ($request->filled('month')) {
                $monthDate = Carbon::parse($request->month . '-01');
                $startDate = $monthDate->startOfMonth()->format('Y-m-d');
                $endDate = $monthDate->endOfMonth()->format('Y-m-d');
                $month = $request->month;
            } else {
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->format('Y-m-d');
                $month = Carbon::now()->format('Y-m');
            }

            $transactions = \App\Models\SeniorCitizenTransaction::with('sale')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalDiscounts = $transactions->sum('discount_amount');
            $totalTransactions = $transactions->count();
            $totalOriginalAmount = $transactions->sum('original_amount');
            $totalFinalAmount = $transactions->sum('final_amount');

            return view('reports.senior-citizen', compact(
                'transactions',
                'totalDiscounts',
                'totalTransactions',
                'totalOriginalAmount',
                'totalFinalAmount',
                'month',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate senior citizen report: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function pwd(Request $request)
    {
        try {
            // Validate month format if provided
            if ($request->filled('month')) {
                if (!preg_match('/^\d{4}-\d{2}$/', $request->month)) {
                    return redirect()->back()
                        ->with('error', 'Invalid month format. Use YYYY-MM.')
                        ->withInput();
                }
            }

            // Support month parameter (default to current month)
            if ($request->filled('month')) {
                $monthDate = Carbon::parse($request->month . '-01');
                $startDate = $monthDate->startOfMonth()->format('Y-m-d');
                $endDate = $monthDate->endOfMonth()->format('Y-m-d');
                $month = $request->month;
            } else {
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->format('Y-m-d');
                $month = Carbon::now()->format('Y-m');
            }

            $transactions = \App\Models\PwdTransaction::with('sale')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalDiscounts = $transactions->sum('discount_amount');
            $totalTransactions = $transactions->count();
            $totalOriginalAmount = $transactions->sum('original_amount');
            $totalFinalAmount = $transactions->sum('final_amount');

            return view('reports.pwd', compact(
                'transactions',
                'totalDiscounts',
                'totalTransactions',
                'totalOriginalAmount',
                'totalFinalAmount',
                'month',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PWD report: ' . $e->getMessage())
                ->withInput();
        }
    }
}
