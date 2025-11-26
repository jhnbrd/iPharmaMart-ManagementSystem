<x-layout title="Stock Movements">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Stock Movement History</h1>
        <div class="flex gap-2">
            <a href="{{ route('stock.in') }}" class="btn btn-success">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Stock In
            </a>
            <a href="{{ route('stock.out') }}" class="btn btn-danger">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
                Stock Out
            </a>
            <a href="{{ route('stock.restock') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Restock Shelf
            </a>
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
        <form method="GET" action="{{ route('stock.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="type" class="form-label text-xs mb-1">Type</label>
                <select id="type" name="type" class="form-select text-sm py-1.5">
                    <option value="">All Types</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stock Out</option>
                </select>
            </div>

            <div class="form-group mb-0" style="min-width: 180px; flex: 1;">
                <label for="product" class="form-label text-xs mb-1">Product</label>
                <input type="text" id="product" name="product" class="form-input text-sm py-1.5"
                    value="{{ request('product') }}" placeholder="Search product...">
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

    <!-- Movements Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Stock In</th>
                    <th>Stock Out</th>
                    <th>Previous Stock</th>
                    <th>New Stock</th>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if ($movement->type === 'in')
                                <span class="badge-success">Stock In</span>
                            @else
                                <span class="badge-danger">Stock Out</span>
                            @endif
                        </td>
                        <td class="font-medium">{{ $movement->product->name }}</td>
                        <td>{{ $movement->product->category->name }}</td>
                        <td>
                            @if ($movement->stock_in > 0)
                                <span class="text-[var(--color-success)] font-medium">{{ $movement->stock_in }}</span>
                            @else
                                <span class="text-gray-400">0</span>
                            @endif
                        </td>
                        <td>
                            @if ($movement->stock_out > 0)
                                <span class="text-[var(--color-danger)] font-medium">{{ $movement->stock_out }}</span>
                            @else
                                <span class="text-gray-400">0</span>
                            @endif
                        </td>
                        <td>{{ $movement->previous_stock }}</td>
                        <td>
                            <span class="font-medium">{{ $movement->new_stock }}</span>
                            @if ($movement->new_stock <= $movement->product->stock_danger_level)
                                <span class="text-xs text-[var(--color-danger)]">⚠️</span>
                            @elseif ($movement->new_stock <= $movement->product->low_stock_threshold)
                                <span class="text-xs text-[var(--color-warning)]">⚠️</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                {{ $movement->reference_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $movement->user->username }}</td>
                        <td>
                            <span class="text-sm text-[var(--color-text-secondary)]">
                                {{ Str::limit($movement->reason ?? 'N/A', 30) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No stock movements found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $movements->links() }}
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
        @if (request()->hasAny(['type', 'product', 'date_from', 'date_to']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
