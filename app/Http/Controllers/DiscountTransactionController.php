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

        // Filter by month
        if ($request->filled('month')) {
            $query->whereYear('created_at', substr($request->month, 0, 4))
                ->whereMonth('created_at', substr($request->month, 5, 2));
        } else {
            // Default to current month
            $query->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by name
        if ($request->filled('search')) {
            $query->where('sc_name', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $totalDiscounts = $query->sum('discount_amount');

        return view('discounts.senior-citizen', compact('transactions', 'totalDiscounts'));
    }

    public function pwdIndex(Request $request)
    {
        $query = PwdTransaction::with('sale');

        // Filter by month
        if ($request->filled('month')) {
            $query->whereYear('created_at', substr($request->month, 0, 4))
                ->whereMonth('created_at', substr($request->month, 5, 2));
        } else {
            // Default to current month
            $query->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by name
        if ($request->filled('search')) {
            $query->where('pwd_name', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $totalDiscounts = $query->sum('discount_amount');

        return view('discounts.pwd', compact('transactions', 'totalDiscounts'));
    }
}
