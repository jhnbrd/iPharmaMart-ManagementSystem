<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SeniorCitizenTransaction;
use App\Models\PwdTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UnifiedReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('type', 'sales');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $data = [];

        switch ($reportType) {
            case 'sales':
                $data = $this->getSalesReport($start, $end);
                break;
            case 'inventory':
                $data = $this->getInventoryReport($start, $end);
                break;
            case 'senior-citizen':
                $data = $this->getSeniorCitizenReport($start, $end);
                break;
            case 'pwd':
                $data = $this->getPwdReport($start, $end);
                break;
        }

        return view('reports.index', compact('reportType', 'startDate', 'endDate', 'data'));
    }

    private function getSalesReport($start, $end)
    {
        $perPage = request('per_page', 15);
        $sales = Sale::with(['customer', 'user', 'items.product'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $totalRevenue = Sale::whereBetween('created_at', [$start, $end])->sum('total');
        $totalTransactions = Sale::whereBetween('created_at', [$start, $end])->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        return [
            'sales' => $sales,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'averageTransaction' => $averageTransaction
        ];
    }

    private function getInventoryReport($start, $end)
    {
        $perPage = request('per_page', 15);
        $products = Product::with(['category', 'supplier'])
            ->withCount(['saleItems as total_sold' => function ($query) use ($start, $end) {
                $query->select(DB::raw('SUM(quantity)'))
                    ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                    ->whereBetween('sales.created_at', [$start, $end]);
            }])
            ->paginate($perPage);

        $totalProducts = Product::count();
        $lowStockCount = Product::whereRaw('(shelf_stock + back_stock) <= low_stock_threshold')->count();
        $outOfStockCount = Product::whereRaw('(shelf_stock + back_stock) = 0')->count();
        $totalStockValue = Product::sum(DB::raw('(shelf_stock + back_stock) * price'));

        return [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'totalStockValue' => $totalStockValue
        ];
    }

    private function getSeniorCitizenReport($start, $end)
    {
        $perPage = request('per_page', 15);
        $transactions = SeniorCitizenTransaction::with(['sale.customer', 'sale.user'])
            ->whereHas('sale', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $totalDiscount = SeniorCitizenTransaction::whereHas('sale', function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        })->sum('discount_amount');

        $totalTransactions = $transactions->total();

        return [
            'transactions' => $transactions,
            'totalDiscount' => $totalDiscount,
            'totalTransactions' => $totalTransactions
        ];
    }

    private function getPwdReport($start, $end)
    {
        $perPage = request('per_page', 15);
        $transactions = PwdTransaction::with(['sale.customer', 'sale.user'])
            ->whereHas('sale', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $totalDiscount = PwdTransaction::whereHas('sale', function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        })->sum('discount_amount');

        $totalTransactions = $transactions->total();

        return [
            'transactions' => $transactions,
            'totalDiscount' => $totalDiscount,
            'totalTransactions' => $totalTransactions
        ];
    }
}
