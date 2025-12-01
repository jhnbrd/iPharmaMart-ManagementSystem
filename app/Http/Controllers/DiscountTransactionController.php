<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizenTransaction;
use App\Models\PwdTransaction;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class DiscountTransactionController extends Controller
{
    use LogsActivity;

    public function seniorCitizenIndex(Request $request)
    {
        $query = SeniorCitizenTransaction::with('sale');

        // Filter by month (using sale date)
        if ($request->filled('month')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereYear('created_at', substr($request->month, 0, 4))
                    ->whereMonth('created_at', substr($request->month, 5, 2));
            });
        } else {
            // Default to current month
            $query->whereHas('sale', function ($q) {
                $q->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
            });
        }

        // Filter by date range (using sale date)
        if ($request->filled('date_from')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        // Filter by name
        if ($request->filled('search')) {
            $query->where('sc_name', 'like', '%' . $request->search . '%');
        }

        // Get total before pagination
        $totalDiscounts = $query->sum('discount_amount');

        // Order by sale date (most recent first)
        $transactions = $query->join('sales', 'senior_citizen_transactions.sale_id', '=', 'sales.id')
            ->select('senior_citizen_transactions.*')
            ->orderBy('sales.created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('discounts.senior-citizen', compact('transactions', 'totalDiscounts'));
    }

    public function pwdIndex(Request $request)
    {
        $query = PwdTransaction::with('sale');

        // Filter by month (using sale date)
        if ($request->filled('month')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereYear('created_at', substr($request->month, 0, 4))
                    ->whereMonth('created_at', substr($request->month, 5, 2));
            });
        } else {
            // Default to current month
            $query->whereHas('sale', function ($q) {
                $q->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
            });
        }

        // Filter by date range (using sale date)
        if ($request->filled('date_from')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        // Filter by name
        if ($request->filled('search')) {
            $query->where('pwd_name', 'like', '%' . $request->search . '%');
        }

        // Get total before pagination
        $totalDiscounts = $query->sum('discount_amount');

        // Order by sale date (most recent first)
        $transactions = $query->join('sales', 'pwd_transactions.sale_id', '=', 'sales.id')
            ->select('pwd_transactions.*')
            ->orderBy('sales.created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('discounts.pwd', compact('transactions', 'totalDiscounts'));
    }
}
