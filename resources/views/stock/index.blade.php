<x-layout title="Stock Movements">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="page-title">Stock Movements</h1>
                <p class="text-[var(--color-text-secondary)] mt-1">Manage all stock in, stock out, and inventory
                    transactions</p>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleFilters()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filter-btn-text">Show Filters</span>
                </button>
                <div class="flex gap-2">
                    <a href="{{ route('stock.in') }}" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Stock In
                    </a>
                    <a href="{{ route('stock.out') }}" class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Stock Out
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white border border-[var(--color-border-light)] mb-6 hidden">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('stock.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="product_id" class="form-label">Product</label>
                    <select id="product_id" name="product_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0" style="min-width: 150px;">
                    <label for="type" class="form-label">Movement Type</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                        <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out
                        </option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment
                        </option>
                    </select>
                </div>

                <div class="form-group mb-0" style="min-width: 150px;">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" id="date_from" name="date_from" class="form-input"
                        value="{{ request('date_from') }}">
                </div>

                <div class="form-group mb-0" style="min-width: 150px;">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" id="date_to" name="date_to" class="form-input"
                        value="{{ request('date_to') }}">
                </div>

                <div class="form-group mb-0" style="min-width: 150px;">
                    <label for="reference_number" class="form-label">Reference #</label>
                    <input type="text" id="reference_number" name="reference_number" class="form-input"
                        placeholder="Enter reference number" value="{{ request('reference_number') }}">
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('stock.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="bg-gray-50 border border-gray-200 rounded mb-3">
        <div class="px-3 py-1.5 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-600">Today's Summary</h3>
        </div>
        <div class="p-2">
            <div class="flex justify-between items-center space-x-4">
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Stock In</div>
                    <div class="text-base font-semibold text-green-600">{{ number_format($totalStockIn) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Stock Out</div>
                    <div class="text-base font-semibold text-red-600">{{ number_format($totalStockOut) }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Net Change</div>
                    <div
                        class="text-base font-semibold {{ $totalStockIn - $totalStockOut >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $totalStockIn - $totalStockOut >= 0 ? '+' : '' }}{{ number_format($totalStockIn - $totalStockOut) }}
                    </div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">This Week</div>
                    <div class="text-base font-semibold">{{ number_format($weeklyMovements) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="bg-white border border-[var(--color-border-light)]">
        <div class="px-4 py-2 border-b border-[var(--color-border-light)]">
            <h3 class="text-base font-medium text-gray-700">Movement History</h3>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Reference #</th>
                        <th class="text-right">Stock In</th>
                        <th class="text-right">Stock Out</th>
                        <th class="text-right">Stock Level</th>
                        <th>Batch</th>
                        <th>User</th>
                        <th>Reason</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td class="font-medium">
                                {{ $movement->created_at->format('M d, Y') }}<br>
                                <span
                                    class="text-xs text-gray-500">{{ $movement->created_at->format('h:i A') }}</span>
                            </td>
                            <td>
                                <div class="font-medium">{{ $movement->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->product->code }}</div>
                            </td>
                            <td>
                                @if ($movement->type === 'stock_in')
                                    <span class="badge-success">Stock In</span>
                                @elseif($movement->type === 'stock_out')
                                    <span class="badge-danger">Stock Out</span>
                                @else
                                    <span class="badge-warning">Adjustment</span>
                                @endif
                            </td>
                            <td>
                                @if ($movement->reference_number)
                                    <span
                                        class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">{{ $movement->reference_number }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($movement->stock_in > 0)
                                    <span
                                        class="font-semibold text-green-600">+{{ number_format($movement->stock_in) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($movement->stock_out > 0)
                                    <span
                                        class="font-semibold text-red-600">-{{ number_format($movement->stock_out) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="text-gray-500">{{ number_format($movement->previous_stock) }}</span>
                                <span class="mx-1">→</span>
                                <span class="font-semibold">{{ number_format($movement->new_stock) }}</span>
                            </td>
                            <td>
                                @if ($movement->batch)
                                    <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                        {{ $movement->batch->batch_number }}
                                    </span>
                                    @if ($movement->batch->expiry_date)
                                        <div class="text-xs text-gray-500">
                                            Exp: {{ $movement->batch->expiry_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm">{{ $movement->user->name ?? 'System' }}</div>
                            </td>
                            <td>
                                @if ($movement->reason)
                                    <span class="text-sm text-gray-600">{{ Str::limit($movement->reason, 30) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('stock.show', $movement) }}"
                                    class="btn btn-sm btn-secondary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-8 text-gray-500">
                                No stock movements found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($movements->hasPages())
            <div class="px-6 py-4 border-t border-[var(--color-border-light)]">
                {{ $movements->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        @endif
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
        @if (request()->hasAny(['product_id', 'type', 'date_from', 'date_to', 'reference_number']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
