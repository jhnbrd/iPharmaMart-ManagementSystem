<x-layout title="Dashboard">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <p class="text-[var(--color-text-secondary)] mt-1">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 border-l-4 border-[var(--color-brand-green)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide">Total Revenue</p>
                        <button type="button"
                            onmousedown="document.getElementById('revenueValue').classList.remove('hidden'); document.getElementById('revenueHidden').classList.add('hidden'); this.querySelector('.eye-open').classList.add('hidden'); this.querySelector('.eye-closed').classList.remove('hidden');"
                            onmouseup="document.getElementById('revenueValue').classList.add('hidden'); document.getElementById('revenueHidden').classList.remove('hidden'); this.querySelector('.eye-open').classList.remove('hidden'); this.querySelector('.eye-closed').classList.add('hidden');"
                            onmouseleave="document.getElementById('revenueValue').classList.add('hidden'); document.getElementById('revenueHidden').classList.remove('hidden'); this.querySelector('.eye-open').classList.remove('hidden'); this.querySelector('.eye-closed').classList.add('hidden');"
                            ontouchstart="document.getElementById('revenueValue').classList.remove('hidden'); document.getElementById('revenueHidden').classList.add('hidden'); this.querySelector('.eye-open').classList.add('hidden'); this.querySelector('.eye-closed').classList.remove('hidden');"
                            ontouchend="document.getElementById('revenueValue').classList.add('hidden'); document.getElementById('revenueHidden').classList.remove('hidden'); this.querySelector('.eye-open').classList.remove('hidden'); this.querySelector('.eye-closed').classList.add('hidden');"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-brand-green)] transition-colors"
                            title="Hold to view revenue">
                            <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="text-right">
                    <p id="revenueValue" class="text-3xl font-bold text-[var(--color-brand-green)] hidden">
                        ₱{{ number_format($totalRevenue, 2) }}
                    </p>
                    <p id="revenueHidden" class="text-3xl font-bold text-[var(--color-text-secondary)]">
                        ₱••••••
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-accent-orange)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Total Products
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-[var(--color-accent-orange)]">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-accent-blue)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Total Customers
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-[var(--color-accent-blue)]">{{ $totalCustomers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-danger)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Low Stock Items
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-[var(--color-danger)]">{{ $lowStockItems }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Sales Overview -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <h2 class="text-base font-semibold">Monthly Sales - {{ now()->format('F Y') }}</h2>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                        <span class="text-sm text-[var(--color-text-secondary)]">Total Revenue</span>
                        <span
                            class="text-xl font-bold text-[var(--color-brand-green)]">₱{{ number_format($monthlySales, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                        <span class="text-sm text-[var(--color-text-secondary)]">Transactions</span>
                        <span
                            class="text-xl font-bold text-[var(--color-text-primary)]">{{ number_format($monthlySalesCount) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-[var(--color-text-secondary)]">Average Sale</span>
                        <span class="text-xl font-bold text-[var(--color-text-primary)]">
                            ₱{{ $monthlySalesCount > 0 ? number_format($monthlySales / $monthlySalesCount, 2) : '0.00' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Summary -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <h2 class="text-base font-semibold">Stock Summary</h2>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                        <span class="text-sm text-[var(--color-text-secondary)]">Total Units</span>
                        <span
                            class="text-xl font-bold text-[var(--color-text-primary)]">{{ number_format($totalStock) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                        <span class="text-sm text-[var(--color-text-secondary)]">Low Stock</span>
                        <span class="text-xl font-bold text-[var(--color-accent-orange)]">{{ $lowStockItems }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-[var(--color-text-secondary)]">Out of Stock</span>
                        <span class="text-xl font-bold text-[var(--color-danger)]">{{ $outOfStockItems }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <h2 class="text-base font-semibold">Top Products - {{ now()->format('F Y') }}</h2>
            </div>
            <div class="p-4">
                @if ($topProducts->isEmpty())
                    <p class="text-[var(--color-text-secondary)] text-center py-8 text-sm">No sales data available</p>
                @else
                    <div class="space-y-3">
                        @foreach ($topProducts as $index => $product)
                            <div
                                class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-bold text-[var(--color-brand-green)]">{{ $index + 1 }}.</span>
                                        <div>
                                            <div class="text-sm font-medium">{{ $product->name }}</div>
                                            <div class="text-xs text-[var(--color-text-secondary)]">
                                                {{ $product->category_name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl font-bold text-[var(--color-brand-green)]">
                                        ₱{{ number_format($product->total_revenue, 2) }}
                                    </div>
                                    <div class="text-xs text-[var(--color-text-secondary)]">
                                        {{ number_format($product->total_sold) }} sold
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold">Low Stock Alert</h2>
                    <span class="badge-danger text-xs">{{ $lowStockProducts->count() }}</span>
                </div>
            </div>
            <div class="p-4">
                @if ($lowStockProducts->isEmpty())
                    <p class="text-[var(--color-text-secondary)] text-center py-8 text-sm">All products are well
                        stocked</p>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach ($lowStockProducts as $product)
                            <div
                                class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                                <div class="flex-1">
                                    <div class="text-sm font-medium">{{ $product->name }}</div>
                                    <div class="text-xs text-[var(--color-text-secondary)]">
                                        {{ $product->category->name }}
                                    </div>
                                </div>
                                <div class="text-right flex items-center gap-2">
                                    @if ($product->stock == 0)
                                        <span class="badge-danger text-xs">OUT</span>
                                    @elseif ($product->stock <= $product->stock_danger_level)
                                        <span class="badge-danger text-xs">CRITICAL</span>
                                    @else
                                        <span class="badge-warning text-xs">LOW</span>
                                    @endif
                                    <span
                                        class="text-xl font-bold {{ $product->stock == 0 ? 'text-[var(--color-danger)]' : ($product->stock <= $product->stock_danger_level ? 'text-[var(--color-danger)]' : 'text-[var(--color-accent-orange)]') }}">
                                        {{ $product->stock }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
