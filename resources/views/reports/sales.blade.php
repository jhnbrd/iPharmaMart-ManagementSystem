<x-layout title="Sales Report" subtitle="Generate and print sales reports">
    <div class="page-header mb-6">
        <div class="flex items-center justify-between w-full">
            <div>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleFilters()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filter-btn-text">Show Filters</span>
                </button>
                <button onclick="window.print()" class="btn btn-primary print-hide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white border border-[var(--color-border-light)] mb-6 print-hide hidden">
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
    <div class="bg-gray-50 border border-gray-200 rounded mb-3">
        <div class="px-3 py-1.5 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-600">Summary</h3>
        </div>
        <div class="p-2">
            <div class="flex justify-between items-center space-x-4">
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Total Sales</div>
                    <div class="text-base font-semibold text-[var(--color-brand-green)]">
                        ₱{{ number_format($totalRevenue, 2) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Transactions</div>
                    <div class="text-base font-semibold">{{ number_format($totalTransactions) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Avg Sale</div>
                    <div class="text-base font-semibold">₱{{ number_format($averageSale, 2) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Days</div>
                    <div class="text-base font-semibold">
                        {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="bg-white border border-[var(--color-border-light)] mb-4">
        <div class="px-4 py-2 border-b border-[var(--color-border-light)]">
            <h3 class="text-base font-medium text-gray-700">Payment Method Breakdown</h3>
        </div>
        <div class="table-container">
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

    <script>
        function toggleFilters() {
            const filtersSection = document.getElementById('filters-section');
            const filterBtnText = document.getElementById('filter-btn-text');

            if (filtersSection.classList.contains('hidden')) {
                filtersSection.classList.remove('hidden');
                filterBtnText.textContent = 'Hide Filters';
            } else {
                filtersSection.classList.add('hidden');
                filterBtnText.textContent = 'Show Filters';
            }
        }

        // Show filters if any filter is active  
        @if (request()->hasAny(['start_date', 'end_date', 'payment_method']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
