<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizenTransaction;
use App\Models\PwdTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UnifiedDiscountController extends Controller
{
    public function index(Request $request)
    {
        $discountType = $request->input('type', 'senior-citizen');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($discountType === 'pwd') {
            $transactions = PwdTransaction::with(['sale.customer', 'sale.user'])
                ->whereHas('sale', function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $totalDiscount = PwdTransaction::whereHas('sale', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })->sum('discount_amount');
        } else {
            $transactions = SeniorCitizenTransaction::with(['sale.customer', 'sale.user'])
                ->whereHas('sale', function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $totalDiscount = SeniorCitizenTransaction::whereHas('sale', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })->sum('discount_amount');
        }

        $totalTransactions = $transactions->total();

        return view('discounts.index', compact('discountType', 'startDate', 'endDate', 'transactions', 'totalDiscount', 'totalTransactions'));
    }
}
