<x-layout title="Sales">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Sales History</h1>
        <a href="{{ route('pos.index') }}" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Go to POS
        </a>
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
                        <td class="font-semibold text-[var(--color-brand-green)]">${{ number_format($sale->total, 2) }}
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
</x-layout>
