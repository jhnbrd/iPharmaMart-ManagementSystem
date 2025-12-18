<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-6 border-l-4 border-[var(--color-brand-green)] shadow-sm">
        <p class="text-sm text-gray-600 uppercase mb-1">Total Sales</p>
        <p class="text-3xl font-bold text-[var(--color-brand-green)]">₱{{ number_format($data['totalRevenue'], 2) }}</p>
    </div>
    <div class="bg-white p-6 border-l-4 border-[var(--color-accent-blue)] shadow-sm">
        <p class="text-sm text-gray-600 uppercase mb-1">Total Transactions</p>
        <p class="text-3xl font-bold text-[var(--color-accent-blue)]">{{ number_format($data['totalTransactions']) }}</p>
    </div>
    <div class="bg-white p-6 border-l-4 border-[var(--color-accent-orange)] shadow-sm">
        <p class="text-sm text-gray-600 uppercase mb-1">Average Transaction</p>
        <p class="text-3xl font-bold text-[var(--color-accent-orange)]">
            ₱{{ number_format($data['averageTransaction'], 2) }}</p>
    </div>
</div>

<!-- Sales Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Sales Transactions</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($data['sales'] as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sale->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sale->receipt_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sale->customer->name ?? 'Walk-in' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sale->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            {{ $sale->items->sum('quantity') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                            ₱{{ number_format($sale->total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($sale->payment_method) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No sales found for the selected period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $data['sales']->appends(request()->query())->links() }}
    </div>
</div>
