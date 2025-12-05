<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white p-6 border-l-4 border-[var(--color-brand-green)] shadow-sm">
        <p class="text-sm text-gray-600 uppercase mb-1">Total Transactions</p>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($data['totalTransactions']) }}</p>
    </div>
    <div class="bg-white p-6 border-l-4 border-[var(--color-accent-orange)] shadow-sm">
        <p class="text-sm text-gray-600 uppercase mb-1">Total Discount Given</p>
        <p class="text-3xl font-bold text-[var(--color-accent-orange)]">₱{{ number_format($data['totalDiscount'], 2) }}
        </p>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Senior Citizen Transactions</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SC ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Original Amount</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Discount (20%)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Final Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($data['transactions'] as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->senior_citizen_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->sale->customer->name ?? 'Walk-in' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->sale->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            ₱{{ number_format($transaction->original_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[var(--color-accent-orange)]">
                            -₱{{ number_format($transaction->discount_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                            ₱{{ number_format($transaction->discounted_amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No transactions found for the selected period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $data['transactions']->appends(request()->query())->links() }}
    </div>
</div>
