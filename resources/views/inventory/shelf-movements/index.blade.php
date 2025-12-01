<x-layout title="Shelf Movements">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="page-title">Shelf Movements</h1>
                <p class="text-[var(--color-text-secondary)] mt-1">Track products moving between shelf and back stock</p>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleFilters()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filter-btn-text">Show Filters</span>
                </button>
                <a href="{{ route('inventory.shelf-movements.create') }}" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Movement
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white border border-[var(--color-border-light)] mb-6 hidden">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('inventory.shelf-movements.index') }}"
                class="flex flex-wrap gap-4 items-end">
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
                    <label for="movement_type" class="form-label">Movement Type</label>
                    <select id="movement_type" name="movement_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="restock" {{ request('movement_type') == 'restock' ? 'selected' : '' }}>Restock
                            (Back→Shelf)</option>
                        <option value="destock" {{ request('movement_type') == 'destock' ? 'selected' : '' }}>Destock
                            (Shelf→Back)</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('inventory.shelf-movements.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="bg-gray-50 border border-gray-200 rounded mb-3">
        <div class="px-3 py-1.5 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-600">Summary</h3>
        </div>
        <div class="p-2">
            <div class="flex justify-between items-center space-x-4">
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Total Movements</div>
                    <div class="text-base font-semibold">{{ $movements->total() }}</div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Restock Today</div>
                    <div class="text-base font-semibold text-green-600">
                        {{ \App\Models\ShelfMovement::whereDate('created_at', today())->where('new_shelf_stock', '>', \DB::raw('previous_shelf_stock'))->count() }}
                    </div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">Destock Today</div>
                    <div class="text-base font-semibold text-orange-600">
                        {{ \App\Models\ShelfMovement::whereDate('created_at', today())->where('new_shelf_stock', '<', \DB::raw('previous_shelf_stock'))->count() }}
                    </div>
                </div>
                <div class="w-px h-8 bg-gray-300"></div>
                <div class="flex-1 text-center py-1">
                    <div class="text-xs text-gray-500">This Week</div>
                    <div class="text-base font-semibold">
                        {{ \App\Models\ShelfMovement::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                    </div>
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
                        <th>Batch</th>
                        <th>Movement</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Shelf Stock</th>
                        <th class="text-right">Back Stock</th>
                        <th>User</th>
                        <th>Remarks</th>
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
                                @php
                                    $isRestock = $movement->new_shelf_stock > $movement->previous_shelf_stock;
                                @endphp
                                @if ($isRestock)
                                    <span class="badge-success">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Restock
                                    </span>
                                @else
                                    <span class="badge-warning">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Destock
                                    </span>
                                @endif
                            </td>
                            <td class="text-right font-semibold">{{ number_format($movement->quantity) }}</td>
                            <td class="text-right">
                                <span
                                    class="text-gray-500">{{ number_format($movement->previous_shelf_stock) }}</span>
                                <span class="mx-1">→</span>
                                <span class="font-semibold">{{ number_format($movement->new_shelf_stock) }}</span>
                            </td>
                            <td class="text-right">
                                <span class="text-gray-500">{{ number_format($movement->previous_back_stock) }}</span>
                                <span class="mx-1">→</span>
                                <span class="font-semibold">{{ number_format($movement->new_back_stock) }}</span>
                            </td>
                            <td>
                                <div class="text-sm">{{ $movement->user->name ?? 'System' }}</div>
                            </td>
                            <td>
                                @if ($movement->remarks)
                                    <span
                                        class="text-sm text-gray-600">{{ Str::limit($movement->remarks, 30) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('inventory.shelf-movements.show', $movement) }}"
                                    class="btn btn-sm btn-secondary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-8 text-gray-500">
                                No shelf movements found
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
        @if (request()->hasAny(['product_id', 'date_from', 'date_to', 'movement_type']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
