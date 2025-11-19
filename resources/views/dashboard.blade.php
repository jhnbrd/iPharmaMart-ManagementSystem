<x-layout title="Dashboard">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 border-l-4 border-[var(--color-brand-green)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-[var(--color-brand-green)]">${{ number_format($totalRevenue, 2) }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 bg-[var(--color-brand-green)] bg-opacity-10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-brand-green)]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-accent-orange)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Total Products
                    </p>
                    <p class="text-3xl font-bold text-[var(--color-accent-orange)]">{{ $totalProducts }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-[var(--color-accent-orange)] bg-opacity-10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-accent-orange)]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-accent-blue)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Total Customers
                    </p>
                    <p class="text-3xl font-bold text-[var(--color-accent-blue)]">{{ $totalCustomers }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-[var(--color-accent-blue)] bg-opacity-10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-accent-blue)]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-danger)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Low Stock Items
                    </p>
                    <p class="text-3xl font-bold text-[var(--color-danger)]">{{ $lowStockItems }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-[var(--color-danger)] bg-opacity-10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--color-danger)]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 shadow-sm border border-[var(--color-border-light)]">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">Recent Sales</h2>
            </div>

            @if ($recentSales->isEmpty())
                <p class="text-[var(--color-text-secondary)] text-center py-8">No recent sales</p>
            @else
                <div class="space-y-4">
                    @foreach ($recentSales as $sale)
                        <div
                            class="flex items-start justify-between pb-4 border-b border-[var(--color-border-light)] last:border-0 last:pb-0">
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $sale->customer->name }}</h3>
                                <p class="text-sm text-[var(--color-text-secondary)]">
                                    @foreach ($sale->items as $item)
                                        {{ $item->product->name }} x{{ $item->quantity }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                                <p class="text-xs text-[var(--color-text-tertiary)] mt-1">
                                    {{ $sale->created_at->format('Y-m-d') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[var(--color-brand-green)]">
                                    ${{ number_format($sale->total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white p-6 shadow-sm border border-[var(--color-border-light)]">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">Low Stock Alert</h2>
            </div>

            @if ($lowStockProducts->isEmpty())
                <p class="text-[var(--color-text-secondary)] text-center py-8">All products are well stocked</p>
            @else
                <div class="space-y-4">
                    @foreach ($lowStockProducts as $product)
                        <div class="flex items-start justify-between p-4 bg-red-50 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $product->name }}</h3>
                                <p class="text-sm text-[var(--color-text-secondary)]">{{ $product->category->name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[var(--color-danger)]">{{ $product->stock }} left</p>
                                <p class="text-xs text-[var(--color-text-secondary)]">Reorder needed</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout>
