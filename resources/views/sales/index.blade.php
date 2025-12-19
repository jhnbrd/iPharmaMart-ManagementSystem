<x-layout title="Sales" subtitle="View and manage all sales transactions">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex gap-3">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('reports.index', ['type' => 'sales']) }}" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generate Report
                </a>
            @endif
            @if (auth()->user()->role === 'cashier')
                <a href="{{ route('pos.index') }}" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Go to POS
                </a>
            @endif
            <button onclick="toggleFilters()" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span id="filter-btn-text">Show Filters</span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white p-4 shadow-sm border border-[var(--color-border-light)] mb-6 hidden">
        <form method="GET" action="{{ route('sales.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="form-group mb-0" style="min-width: 180px; flex: 1;">
                <label for="customer" class="form-label text-xs mb-1">Customer</label>
                <input type="text" id="customer" name="customer" class="form-input text-sm py-1.5"
                    value="{{ request('customer') }}" placeholder="Search customer...">
            </div>

            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="payment_method" class="form-label text-xs mb-1">Payment Method</label>
                <select id="payment_method" name="payment_method" class="form-select text-sm py-1.5">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="gcash" {{ request('payment_method') === 'gcash' ? 'selected' : '' }}>GCash</option>
                    <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                </select>
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

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Sales Transactions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale
                            ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Source
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 {{ $sale->is_voided ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                #{{ $sale->id }}
                                @if ($sale->is_voided)
                                    <span class="badge badge-danger ml-2">VOID</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">{{ $sale->customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🛒 POS
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($sale->items as $item)
                                    {{ $item->product->name }} ({{ $item->quantity }})@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($sale->payment_method === 'cash')
                                    <span class="badge badge-success">💵 Cash</span>
                                @elseif($sale->payment_method === 'gcash')
                                    <span class="badge badge-info">📱 GCash</span>
                                @elseif($sale->payment_method === 'card')
                                    <span class="badge badge-warning">💳 Card</span>
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap font-semibold {{ $sale->is_voided ? 'line-through text-gray-400' : 'text-[var(--color-brand-green)]' }}">
                                ₱{{ number_format($sale->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('sales.show', $sale) }}"
                                    class="text-[var(--color-brand-green)] hover:underline">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-[var(--color-text-secondary)]">
                                @if (auth()->user()->role === 'cashier')
                                    No sales found. <a href="{{ route('pos.index') }}"
                                        class="text-[var(--color-brand-green)] hover:underline">Go to POS to create
                                        your
                                        first
                                        sale</a>
                                @else
                                    No sales found matching the selected criteria.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $sales->links() }}
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
        @if (request()->hasAny(['customer', 'payment_method', 'date_from', 'date_to']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
