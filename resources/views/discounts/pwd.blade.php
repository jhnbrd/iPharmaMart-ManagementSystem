<x-layout title="PWD Discounts">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">PWD Discount Transactions</h1>
        <div class="flex gap-2">
            <button onclick="toggleFilters()" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span id="filter-btn-text">Show Filters</span>
            </button>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white border-2 border-blue-500 rounded-lg shadow-md p-5 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-600">Total Discounts Given - <span
                            class="text-xs text-gray-500">{{ request('month') ? date('F Y', strtotime(request('month') . '-01')) : date('F Y') }}</span>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-purple-600">₱{{ number_format($totalDiscounts, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white p-4 shadow-sm border border-[var(--color-border-light)] mb-6 hidden">
        <form method="GET" action="{{ route('discounts.pwd') }}" class="flex flex-wrap gap-3 items-end">
            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="month" class="form-label text-xs mb-1">Month</label>
                <input type="month" id="month" name="month" class="form-input text-sm py-1.5"
                    value="{{ request('month', now()->format('Y-m')) }}">
            </div>

            <div class="form-group mb-0" style="min-width: 180px; flex: 1;">
                <label for="search" class="form-label text-xs mb-1">PWD Name</label>
                <input type="text" id="search" name="search" class="form-input text-sm py-1.5"
                    value="{{ request('search') }}" placeholder="Search name...">
            </div>

            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="date_from" class="form-label text-xs mb-1">From Date</label>
                <input type="date" id="date_from" name="date_from" class="form-input text-sm py-1.5"
                    value="{{ request('date_from') }}">
            </div>

            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="date_to" class="form-label text-xs mb-1">To Date</label>
                <input type="date" id="date_to" name="date_to" class="form-input text-sm py-1.5"
                    value="{{ request('date_to') }}">
            </div>

            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary text-sm py-1.5 px-4">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>PWD ID Number</th>
                    <th>PWD Name</th>
                    <th>Birthdate</th>
                    <th>Disability Type</th>
                    <th>Original Amount</th>
                    <th>Discount (%)</th>
                    <th>Discount Amount</th>
                    <th>Final Amount</th>
                    <th>Sale ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="font-mono text-sm bg-blue-100 px-2 py-1 rounded">
                                {{ $transaction->pwd_id_number }}
                            </span>
                        </td>
                        <td class="font-medium">{{ $transaction->pwd_name }}</td>
                        <td>{{ $transaction->pwd_birthdate->format('Y-m-d') }}</td>
                        <td>
                            <span class="text-sm">{{ $transaction->disability_type ?? 'N/A' }}</span>
                        </td>
                        <td>₱{{ number_format($transaction->original_amount, 2) }}</td>
                        <td>
                            <span class="badge-warning">{{ $transaction->discount_percentage }}%</span>
                        </td>
                        <td>
                            <span class="text-[var(--color-danger)] font-medium">
                                -₱{{ number_format($transaction->discount_amount, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="font-bold text-[var(--color-success)]">
                                ₱{{ number_format($transaction->final_amount, 2) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $transaction->sale_id) }}"
                                class="text-[var(--color-brand-green)] hover:underline">
                                #{{ $transaction->sale_id }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No PWD discount transactions found for the selected period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>

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
        @if (request()->hasAny(['month', 'search', 'date_from', 'date_to']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
