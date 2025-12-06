<x-layout title="PWD Report" subtitle="Monthly report of PWD discount transactions">
    <!-- Page Header -->
    <div class="page-header">
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
            <form method="GET" action="{{ route('reports.pwd') }}" class="flex flex-wrap gap-4 items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="month" class="form-label">Report Month</label>
                    <input type="month" id="month" name="month" class="form-input"
                        value="{{ request('month', now()->format('Y-m')) }}" required>
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
        <h2 class="text-xl font-semibold mt-2">PWD Discount Report</h2>
        <p class="text-sm text-gray-600 mt-2">Generated: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="bg-gray-50 border border-gray-200 rounded mb-3">
        <div class="px-3 py-1.5 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-600">Summary</h3>
        </div>
        <div class="p-2">
            <div class="flex justify-between items-center space-x-4">
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Transactions</div>
                    <div class="text-base font-semibold">{{ number_format($transactions->count()) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Original</div>
                    <div class="text-base font-semibold">₱{{ number_format($transactions->sum('original_amount'), 2) }}
                    </div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Discounts</div>
                    <div class="text-base font-semibold text-[var(--color-brand-green)]">
                        ₱{{ number_format($transactions->sum('discount_amount'), 2) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Final</div>
                    <div class="text-base font-semibold">₱{{ number_format($transactions->sum('final_amount'), 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white border border-[var(--color-border-light)]">
        <div class="px-4 py-2 border-b border-[var(--color-border-light)]">
            <h3 class="text-base font-medium text-gray-700">Transaction Details</h3>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>PWD ID Number</th>
                        <th>PWD Name</th>
                        <th>Birthdate</th>
                        <th>Disability Type</th>
                        <th class="text-right">Original Amount</th>
                        <th class="text-center">Discount %</th>
                        <th class="text-right">Discount Amount</th>
                        <th class="text-right">Final Amount</th>
                        <th class="text-center">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="font-medium">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                    {{ $transaction->pwd_id_number }}
                                </span>
                            </td>
                            <td>{{ $transaction->pwd_name }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($transaction->pwd_birthdate)->format('Y-m-d') }}
                                <span class="text-xs text-[var(--color-text-secondary)]">
                                    ({{ \Carbon\Carbon::parse($transaction->pwd_birthdate)->age }} yrs)
                                </span>
                            </td>
                            <td>
                                <span class="badge-info">{{ $transaction->disability_type }}</span>
                            </td>
                            <td class="text-right">₱{{ number_format($transaction->original_amount, 2) }}</td>
                            <td class="text-center">
                                <span class="badge-warning">{{ $transaction->discount_percentage }}%</span>
                            </td>
                            <td class="text-right font-semibold text-[var(--color-brand-green)]">
                                -₱{{ number_format($transaction->discount_amount, 2) }}
                            </td>
                            <td class="text-right font-bold">₱{{ number_format($transaction->final_amount, 2) }}</td>
                            <td class="text-center">{{ $transaction->items_purchased }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-8 text-[var(--color-text-secondary)]">
                                No PWD transactions found for this month
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {

            .btn-primary,
            form,
            nav,
            header,
            .no-print {
                display: none !important;
            }

            .stat-card {
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
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
        @if (request()->hasAny(['month']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
