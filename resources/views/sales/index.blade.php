<x-layout title="Sales">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Sales History</h1>
        <div class="flex gap-3">
            <a href="{{ route('reports.sales') }}" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generate Report
            </a>
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Go to POS
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 shadow-sm border border-[var(--color-border-light)] mb-6">
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
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td class="font-medium">#{{ $sale->id }}</td>
                        <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                        <td>{{ $sale->customer->name }}</td>
                        <td>
                            @foreach ($sale->items as $item)
                                {{ $item->product->name }} ({{ $item->quantity }})@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td class="font-semibold text-[var(--color-brand-green)]">₱{{ number_format($sale->total, 2) }}
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}"
                                class="text-[var(--color-brand-green)] hover:underline">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No sales found. <a href="{{ route('pos.index') }}"
                                class="text-[var(--color-brand-green)] hover:underline">Go to POS to create your first
                                sale</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $sales->links() }}
    </div>
</x-layout>
