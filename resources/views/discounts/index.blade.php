<x-layout title="Discount Transactions">
    <div class="mb-6">
        <!-- Page Header with Type Tabs and Show Filters Button -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center justify-between">
                <form method="GET" action="{{ route('discounts.index') }}" class="flex gap-2">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" name="type" value="senior-citizen"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $discountType === 'senior-citizen' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Senior Citizen
                    </button>
                    <button type="submit" name="type" value="pwd"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $discountType === 'pwd' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        PWD
                    </button>
                </form>
                <button onclick="toggleFilters()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filter-btn-text">Show Filters</span>
                </button>
            </div>
        </div>

        <!-- Date Range Filters (Hidden by default) -->
        <div id="filters-section" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 hidden">
            <form method="GET" action="{{ route('discounts.index') }}" class="flex gap-4 items-end">
                <input type="hidden" name="type" value="{{ $discountType }}">
                <div class="flex-1">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                </div>
                <div class="flex-1">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors">
                    Apply Filter
                </button>
                <a href="{{ route('discounts.index', ['type' => $discountType]) }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>

        <!-- Summary Cards (Simplified) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium">Total Transactions</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTransactions) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                <p class="text-sm text-gray-600 font-medium">Total Discount Given</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalDiscount, 2) }}</p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">{{ $discountType === 'pwd' ? 'PWD' : 'Senior Citizen' }} Transactions
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sale ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $discountType === 'pwd' ? 'PWD ID Number' : 'SC ID Number' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cashier</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Original Amount
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Discount</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Final Amount
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $transaction->sale_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->sale->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="font-mono text-sm {{ $discountType === 'pwd' ? 'text-blue-700' : 'text-purple-700' }}">
                                        {{ $discountType === 'pwd' ? $transaction->pwd_id_number : $transaction->sc_id_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $discountType === 'pwd' ? $transaction->pwd_name : $transaction->sc_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->sale->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    ₱{{ number_format($transaction->original_amount, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-[var(--color-danger)] font-medium">
                                    -₱{{ number_format($transaction->discount_amount, 2) }}
                                    ({{ $transaction->discount_percentage }}%)
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-[var(--color-success)]">
                                    ₱{{ number_format($transaction->final_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <a href="{{ route('sales.show', $transaction->sale_id) }}"
                                        class="btn btn-sm btn-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    No transactions found for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
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
        @if (request()->hasAny(['start_date', 'end_date']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
