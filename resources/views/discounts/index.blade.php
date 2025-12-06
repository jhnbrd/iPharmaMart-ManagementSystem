<x-layout title="Discount Transactions">
    <div class="mb-6">
        <!-- Discount Type Filter -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
            <form method="GET" action="{{ route('discounts.index') }}" class="space-y-4">
                <!-- Discount Type Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4">
                    <button type="submit" name="type" value="senior-citizen"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $discountType === 'senior-citizen' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Senior Citizen
                    </button>
                    <button type="submit" name="type" value="pwd"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $discountType === 'pwd' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        PWD
                    </button>
                </div>

                <!-- Date Range Filter -->
                <div class="grid grid-cols-3 md:grid-cols-3 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-6 border-l-4 border-[var(--color-brand-green)] shadow-sm">
                <p class="text-sm text-gray-600 uppercase mb-1">Total Transactions</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalTransactions) }}</p>
            </div>
            <div class="bg-white p-6 border-l-4 border-[var(--color-accent-orange)] shadow-sm">
                <p class="text-sm text-gray-600 uppercase mb-1">Total Discount Given</p>
                <p class="text-3xl font-bold text-[var(--color-accent-orange)]">₱{{ number_format($totalDiscount, 2) }}
                </p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">{{ $discountType === 'pwd' ? 'PWD' : 'Senior Citizen' }} Transactions
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                {{ $discountType === 'pwd' ? 'PWD ID Number' : 'SC ID Number' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Original Amount
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Discount</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Final Amount
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->sale->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="font-mono text-sm {{ $discountType === 'pwd' ? 'bg-blue-100' : 'bg-purple-100' }} px-2 py-1 rounded">
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
</x-layout>
