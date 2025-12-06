<x-layout title="{{ $inventory->name }}" subtitle="Product Code: {{ $inventory->code }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Inventory
                </a>
                <a href="{{ route('inventory.edit', $inventory) }}" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Product
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Product Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Product Type</label>
                            <p class="mt-1">
                                @if ($inventory->product_type === 'pharmacy')
                                    <span class="badge-info">Pharmacy</span>
                                @else
                                    <span class="badge-warning">Mini Mart</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Product Code</label>
                            <p class="mt-1 font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $inventory->code }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Product Name</label>
                            <p class="mt-1 text-lg font-semibold">{{ $inventory->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Brand Name</label>
                            <p class="mt-1 text-lg font-semibold">{{ $inventory->brand_name ?? 'N/A' }}</p>
                        </div>

                        @if ($inventory->product_type === 'pharmacy' && $inventory->generic_name)
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-600">Generic Name</label>
                                <p class="mt-1 text-lg font-semibold text-blue-600">{{ $inventory->generic_name }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-600">Category</label>
                            <p class="mt-1">{{ $inventory->category->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Supplier</label>
                            <p class="mt-1">{{ $inventory->supplier->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Unit Price</label>
                            <p class="mt-1 text-xl font-bold text-[var(--color-brand-green)]">
                                ₱{{ number_format($inventory->price, 2) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Unit Quantity</label>
                            <p class="mt-1 font-semibold">{{ $inventory->unit_quantity }}</p>
                        </div>
                    </div>

                    @if ($inventory->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-600">Description</label>
                            <p class="mt-2 text-gray-800">{{ $inventory->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stock Levels -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Stock Levels</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Shelf Stock -->
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-blue-900">Shelf Stock</h4>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-blue-600">
                                {{ number_format($inventory->shelf_stock ?? 0) }}</div>
                        </div>

                        <!-- Back Stock -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">Back Stock</h4>
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-gray-600">
                                {{ number_format($inventory->back_stock ?? 0) }}</div>
                        </div>

                        <!-- Total Stock -->
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-green-900">Total Stock</h4>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-green-600">
                                {{ number_format(($inventory->shelf_stock ?? 0) + ($inventory->back_stock ?? 0)) }}
                            </div>
                        </div>
                    </div>

                    <!-- Stock Thresholds -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Low Stock Threshold</span>
                                <span
                                    class="font-semibold">{{ number_format($inventory->low_stock_threshold ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Danger Level</span>
                                <span
                                    class="font-semibold text-red-600">{{ number_format($inventory->stock_danger_level ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Batches -->
            @if ($inventory->batches && $inventory->batches->count() > 0)
                <div class="bg-white border border-[var(--color-border-light)]">
                    <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                        <h3 class="text-lg font-semibold">Available Batches</h3>
                    </div>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Batch Number</th>
                                    <th>Quantity</th>
                                    <th>Manufacture Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventory->batches as $batch)
                                    <tr>
                                        <td>
                                            <span
                                                class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $batch->batch_number }}</span>
                                        </td>
                                        <td class="font-semibold">{{ number_format($batch->quantity) }}</td>
                                        <td>
                                            @if ($batch->manufacture_date)
                                                {{ $batch->manufacture_date->format('M d, Y') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($batch->expiry_date)
                                                {{ $batch->expiry_date->format('M d, Y') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($batch->expiry_date)
                                                @php
                                                    $daysToExpiry = $batch->expiry_date->diffInDays(now());
                                                    $isExpired = $batch->expiry_date->isPast();
                                                    $isExpiringSoon = $daysToExpiry <= 30 && !$isExpired;
                                                @endphp
                                                @if ($isExpired)
                                                    <span class="badge-danger">Expired</span>
                                                @elseif($isExpiringSoon)
                                                    <span class="badge-warning">Expires in {{ $daysToExpiry }}
                                                        days</span>
                                                @else
                                                    <span class="badge-success">Good</span>
                                                @endif
                                            @else
                                                <span class="badge-secondary">No Expiry</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('stock.in') }}?product_id={{ $inventory->id }}"
                        class="w-full btn btn-success">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Stock In
                    </a>
                    <a href="{{ route('stock.out') }}?product_id={{ $inventory->id }}"
                        class="w-full btn btn-danger">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                        </svg>
                        Stock Out
                    </a>
                    <a href="{{ route('stock.restock') }}?product_id={{ $inventory->id }}"
                        class="w-full btn btn-warning">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        Restock Shelf
                    </a>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Stock Status</h3>
                </div>
                <div class="p-6">
                    @php
                        $totalStock = ($inventory->shelf_stock ?? 0) + ($inventory->back_stock ?? 0);
                        $lowThreshold = $inventory->low_stock_threshold ?? 0;
                        $dangerLevel = $inventory->stock_danger_level ?? 0;
                    @endphp

                    @if ($totalStock <= 0)
                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span class="font-medium text-red-800">Out of Stock</span>
                        </div>
                    @elseif($totalStock <= $dangerLevel)
                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span class="font-medium text-red-800">Critical Level</span>
                        </div>
                    @elseif($totalStock <= $lowThreshold)
                        <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span class="font-medium text-yellow-800">Low Stock</span>
                        </div>
                    @else
                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-medium text-green-800">Stock OK</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Movements -->
    @if ($recentMovements->count() > 0 || $recentShelfMovements->count() > 0)
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if ($recentMovements->count() > 0)
                <div class="bg-white border border-[var(--color-border-light)]">
                    <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                        <h3 class="text-lg font-semibold">Recent Stock Movements</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach ($recentMovements as $movement)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium">
                                        @if ($movement->type === 'stock_in')
                                            <span
                                                class="text-green-600">+{{ number_format($movement->stock_in) }}</span>
                                        @elseif($movement->type === 'stock_out')
                                            <span
                                                class="text-red-600">-{{ number_format($movement->stock_out) }}</span>
                                        @else
                                            <span class="text-blue-600">Adjustment</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $movement->created_at->format('M d, h:i A') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm">{{ $movement->user->name ?? 'System' }}</div>
                                    <div class="text-xs text-gray-500">{{ $movement->reason }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($recentShelfMovements->count() > 0)
                <div class="bg-white border border-[var(--color-border-light)]">
                    <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                        <h3 class="text-lg font-semibold">Recent Shelf Movements</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach ($recentShelfMovements as $movement)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium">
                                        @if ($movement->new_shelf_stock > $movement->previous_shelf_stock)
                                            <span class="text-green-600">Restock
                                                +{{ number_format($movement->quantity) }}</span>
                                        @else
                                            <span class="text-orange-600">Destock
                                                -{{ number_format($movement->quantity) }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $movement->created_at->format('M d, h:i A') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm">{{ $movement->user->name ?? 'System' }}</div>
                                    <div class="text-xs text-gray-500">{{ $movement->remarks }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</x-layout>
