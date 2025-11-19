<x-layout title="Sales Report">
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Sales Report</h1>
                <p class="text-[var(--color-text-secondary)] mt-1">Generate and print sales reports</p>
            </div>
            <button onclick="window.print()" class="btn btn-primary print-hide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6 print-hide">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.sales') }}" class="flex flex-wrap gap-4 items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-input"
                        value="{{ $startDate }}" required>
                </div>

                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-input" value="{{ $endDate }}"
                        required>
                </div>

                <div class="form-group mb-0" style="min-width: 180px;">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="gcash" {{ $paymentMethod === 'gcash' ? 'selected' : '' }}>GCash</option>
                        <option value="card" {{ $paymentMethod === 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Header (Printable) -->
    <div class="print-only text-center mb-6">
        <h1 class="text-2xl font-bold">iPharma Mart Management System</h1>
        <h2 class="text-xl font-semibold mt-2">Sales Report</h2>
        <p class="text-sm text-gray-600 mt-2">
            Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
        </p>
        <p class="text-sm text-gray-600">Generated: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Total Revenue</p>
                    <p class="text-3xl font-bold text-[var(--color-brand-green)]">₱{{ number_format($totalRevenue, 2) }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Transactions</p>
                    <p class="text-3xl font-bold">{{ number_format($totalTransactions) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Average Sale</p>
                    <p class="text-3xl font-bold">₱{{ number_format($averageSale, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Date Range</p>
                    <p class="text-lg font-semibold">
                        {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} days
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Payment Method Breakdown</h2>
        </div>
        <div class="p-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th class="text-right">Transactions</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-right">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentBreakdown as $payment)
                        <tr>
                            <td class="font-semibold">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="text-right">{{ number_format($payment->count) }}</td>
                            <td class="text-right font-semibold text-[var(--color-brand-green)]">
                                ₱{{ number_format($payment->total, 2) }}</td>
                            <td class="text-right">
                                {{ $totalRevenue > 0 ? number_format(($payment->total / $totalRevenue) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Sales Transactions -->
    <div class="bg-white border border-[var(--color-border-light)]">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Transaction Details</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Cashier</th>
                        <th>Items</th>
                        <th>Payment</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td class="font-medium">{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>
                                <div class="text-sm">
                                    @foreach ($sale->items as $item)
                                        <div>{{ $item->product->name }} (×{{ $item->quantity }})</div>
                                    @endforeach
                                </div>
                            </td>
                            <td><span class="badge-info">{{ ucfirst($sale->payment_method) }}</span></td>
                            <td class="text-right font-bold text-[var(--color-brand-green)]">
                                ₱{{ number_format($sale->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-[var(--color-text-secondary)]">
                                No sales found for the selected period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            .print-hide {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page-header,
            nav,
            .sidebar {
                display: none !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>
</x-layout>
