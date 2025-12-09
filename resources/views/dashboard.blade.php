<x-layout title="Dashboard" subtitle="Welcome back, {{ auth()->user()->name }}">
    <!-- Page Header -->
    <div class="page-header">
        @if (auth()->user()->role === 'cashier')
            <div>
                <p class="text-sm text-[var(--color-text-secondary)] mt-1">Showing your sales data only</p>
            </div>
        @endif
        <div class="flex gap-3">
            <button onclick="toggleFilters()" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span id="filter-btn-text">Show Filters</span>
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div id="filters-section" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 hidden">
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
            <div>
                <button type="submit"
                    class="w-full px-4 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors">
                    Apply Filter
                </button>
            </div>
            <div>
                <a href="{{ route('dashboard') }}"
                    class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </a>
            </div>
        </form>
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
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Total
                        Transactions
                    </p>
                    <p class="text-xs text-[var(--color-text-secondary)] mt-1">{{ $startDate }} to
                        {{ $endDate }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-[var(--color-accent-orange)]">
                        {{ number_format($totalTransactions) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border-l-4 border-[var(--color-accent-blue)] shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-1">Expiring Soon
                    </p>
                    <p class="text-xs text-[var(--color-text-secondary)] mt-1">Within {{ $expiryAlertDays }} days</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-[var(--color-accent-blue)]">
                        {{ number_format($expiringProductsCount) }}</p>
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

    <div class="grid grid-cols-3 gap-6 mb-6">
        <!-- Monthly Sales Overview -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <h2 class="text-base font-semibold">
                    @if ($isFiltered)
                        Sales Overview - {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to
                        {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    @else
                        Monthly Sales - {{ now()->format('F Y') }}
                    @endif
                </h2>
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

        <!-- Expiring Products -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold">Expiring Products</h2>
                    <span class="text-xs text-[var(--color-text-secondary)] bg-gray-100 px-2 py-1 rounded">
                        Alert: {{ $expiryAlertDays }} days
                    </span>
                </div>
            </div>
            <div class="p-4">
                @if ($expiringBatches->isEmpty())
                    <p class="text-[var(--color-text-secondary)] text-center py-8 text-sm">No products expiring soon
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($expiringBatches->take(3) as $batch)
                            <div class="py-3 border-b border-[var(--color-border-light)] last:border-b-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-[var(--color-text-primary)]">
                                            {{ $batch->product->name }}</div>
                                        <div class="text-xs text-[var(--color-text-secondary)] mt-1">
                                            Batch: {{ $batch->batch_number }}
                                        </div>
                                        <div class="text-xs text-[var(--color-text-secondary)]">
                                            Qty: {{ number_format($batch->shelf_quantity + $batch->back_quantity) }}
                                            units
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $daysUntilExpiry = now()->diffInDays($batch->expiry_date, false);
                                            $colorClass =
                                                $daysUntilExpiry <= 3
                                                    ? 'text-[var(--color-danger)]'
                                                    : 'text-[var(--color-accent-orange)]';
                                        @endphp
                                        <div class="text-xs font-medium {{ $colorClass }}">
                                            {{ $batch->expiry_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs {{ $colorClass }}">
                                            {{ abs($daysUntilExpiry) }}
                                            {{ abs($daysUntilExpiry) == 1 ? 'day' : 'days' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('stock.out') }}?batch_id={{ $batch->id }}"
                                        class="text-xs px-3 py-1.5 bg-[var(--color-danger)] text-white rounded hover:bg-red-700 transition-colors">
                                        Stock Out
                                    </a>
                                    <a href="{{ route('inventory.show', $batch->product_id) }}"
                                        class="text-xs px-3 py-1.5 bg-[var(--color-accent-blue)] text-white rounded hover:bg-blue-700 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($expiringBatches->count() > 3)
                        <div class="mt-3 pt-3 border-t border-[var(--color-border-light)]">
                            <a href="{{ route('settings.index') }}#expiry-alerts"
                                class="text-xs text-[var(--color-brand-green)] hover:underline">
                                +{{ $expiringBatches->count() - 3 }} more expiring products
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Association Rules - Frequently Bought Together -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold">Product Associations</h2>
                    <span class="text-xs text-[var(--color-text-secondary)] bg-purple-100 px-2 py-1 rounded">
                        Bought Together
                    </span>
                </div>
            </div>
            <div class="p-4">
                @if (empty($productAssociations) || count($productAssociations) == 0)
                    <p class="text-[var(--color-text-secondary)] text-center py-8 text-sm">No patterns found yet</p>
                @else
                    <div class="space-y-3">
                        @foreach ($productAssociations as $index => $assoc)
                            <div
                                class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)] last:border-b-0">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-purple-600">{{ $index + 1 }}.</span>
                                        <div>
                                            <div class="text-sm font-medium">
                                                {{ $assoc->product1_name }}
                                                <span class="text-purple-500 mx-1">+</span>
                                                {{ $assoc->product2_name }}
                                            </div>
                                            <div class="text-xs text-[var(--color-text-secondary)]">
                                                {{ round($assoc->confidence, 1) }}% confidence
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl font-bold text-purple-600">
                                        {{ $assoc->frequency }}x
                                    </div>
                                    <div class="text-xs text-[var(--color-text-secondary)]">
                                        frequency
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Top Products -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <h2 class="text-base font-semibold">
                    @if ($isFiltered)
                        Top Products - {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to
                        {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    @else
                        Top Products - {{ now()->format('F Y') }}
                    @endif
                </h2>
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

        <!-- Expiry Alerts -->
        @if ($expiredBatches->isNotEmpty() || $expiringBatches->isNotEmpty())
            <div class="bg-white border border-red-300">
                <div class="px-6 py-3 border-b border-red-200 bg-red-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-red-900">⚠️ Expiry Alerts</h2>
                        <span
                            class="badge-danger text-xs">{{ $expiredBatches->count() + $expiringBatches->count() }}</span>
                    </div>
                </div>
                <div class="p-4">
                    @if ($expiredBatches->isNotEmpty())
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-red-700 mb-2">Expired Batches</h3>
                            <div class="space-y-2">
                                @foreach ($expiredBatches as $batch)
                                    <div
                                        class="flex items-center justify-between py-2 border-b border-red-100 bg-red-50 px-3 rounded">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-red-900">{{ $batch->product->name }}
                                            </div>
                                            <div class="text-xs text-red-600">
                                                Batch: {{ $batch->batch_number }} • Expired:
                                                {{ $batch->expiry_date->format('Y-m-d') }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge-danger text-xs">EXPIRED</span>
                                            <div class="text-sm font-bold text-red-700">{{ $batch->total_quantity }}
                                                units</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($expiringBatches->isNotEmpty())
                        <div>
                            <h3 class="text-sm font-semibold text-orange-700 mb-2">Expiring Soon (Next 30 Days)</h3>
                            <div class="space-y-2">
                                @foreach ($expiringBatches as $batch)
                                    <div
                                        class="flex items-center justify-between py-2 border-b border-orange-100 bg-orange-50 px-3 rounded">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-orange-900">
                                                {{ $batch->product->name }}</div>
                                            <div class="text-xs text-orange-600">
                                                Batch: {{ $batch->batch_number }} • Expires:
                                                {{ $batch->expiry_date->format('Y-m-d') }}
                                                ({{ $batch->days_until_expiry }} days)
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge-warning text-xs">{{ $batch->days_until_expiry }}d
                                                left</span>
                                            <div class="text-sm font-bold text-orange-700">
                                                {{ $batch->total_quantity }} units</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

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
                                    @if ($product->total_stock == 0)
                                        <span class="badge-danger text-xs">OUT</span>
                                    @elseif ($product->total_stock <= $product->stock_danger_level)
                                        <span class="badge-danger text-xs">CRITICAL</span>
                                    @else
                                        <span class="badge-warning text-xs">LOW</span>
                                    @endif
                                    <span
                                        class="text-xl font-bold {{ $product->total_stock == 0 ? 'text-[var(--color-danger)]' : ($product->total_stock <= $product->stock_danger_level ? 'text-[var(--color-danger)]' : 'text-[var(--color-accent-orange)]') }}">
                                        {{ $product->total_stock }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- ML Insights -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold">ML Insights</h2>
                    <span class="text-xs text-[var(--color-text-secondary)] bg-indigo-100 px-2 py-1 rounded">
                        Predictive Analytics
                    </span>
                </div>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @if (isset($mlInsights['peak_hour']))
                        <div
                            class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                            <div class="flex-1">
                                <div class="text-sm font-medium">Peak Hour</div>
                                <div class="text-xs text-[var(--color-text-secondary)]">
                                    Busiest time period
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-indigo-600">
                                    {{ $mlInsights['peak_hour']['hour'] }}:00
                                </div>
                                <div class="text-xs text-[var(--color-text-secondary)]">
                                    {{ $mlInsights['peak_hour']['transactions'] }} sales
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($mlInsights['peak_day']))
                        <div
                            class="flex items-center justify-between py-2 border-b border-[var(--color-border-light)]">
                            <div class="flex-1">
                                <div class="text-sm font-medium">Peak Day</div>
                                <div class="text-xs text-[var(--color-text-secondary)]">
                                    Busiest day of week
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-indigo-600">
                                    {{ $mlInsights['peak_day']['day'] }}
                                </div>
                                <div class="text-xs text-[var(--color-text-secondary)]">
                                    {{ $mlInsights['peak_day']['transactions'] }} sales
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($mlInsights['customer_loyalty']))
                        <div class="flex items-center justify-between py-2">
                            <div class="flex-1">
                                <div class="text-sm font-medium">Customer Loyalty</div>
                                <div class="text-xs text-[var(--color-text-secondary)]">
                                    Repeat customer rate
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-green-600">
                                    {{ $mlInsights['customer_loyalty']['repeat_rate'] }}%
                                </div>
                                <div class="text-xs text-[var(--color-text-secondary)]">
                                    {{ $mlInsights['customer_loyalty']['repeat_count'] }}/{{ $mlInsights['customer_loyalty']['total_customers'] }}
                                    customers
                                </div>
                            </div>
                        </div>
                    @endif
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
