<x-layout title="Shelf Movement Details"
    subtitle="Movement #{{ $shelfMovement->id }} - {{ $shelfMovement->created_at->format('M d, Y h:i A') }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('inventory.shelf-movements.index') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Movements
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Movement Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Movement Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Movement ID</label>
                            <p class="mt-1 text-lg font-semibold">#{{ $shelfMovement->id }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Date & Time</label>
                            <p class="mt-1 font-semibold">{{ $shelfMovement->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Movement Type</label>
                            @php
                                $isRestock = $shelfMovement->new_shelf_stock > $shelfMovement->previous_shelf_stock;
                            @endphp
                            <p class="mt-1">
                                @if ($isRestock)
                                    <span class="badge-success">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Restock (Back to Shelf)
                                    </span>
                                @else
                                    <span class="badge-warning">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Destock (Shelf to Back)
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Quantity Moved</label>
                            <p class="mt-1 text-xl font-bold text-[var(--color-brand-green)]">
                                {{ number_format($shelfMovement->quantity) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Performed By</label>
                            <p class="mt-1 font-medium">{{ $shelfMovement->user->name ?? 'System' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Timestamp</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $shelfMovement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if ($shelfMovement->remarks)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-600">Remarks</label>
                            <p class="mt-2 p-3 bg-gray-50 rounded-md">{{ $shelfMovement->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stock Changes -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Stock Level Changes</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shelf Stock -->
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-blue-900">Shelf Stock</h4>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">Before</div>
                                    <div class="text-2xl font-bold text-gray-800">
                                        {{ number_format($shelfMovement->previous_shelf_stock) }}</div>
                                </div>
                                <div class="flex-1 flex justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">After</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ number_format($shelfMovement->new_shelf_stock) }}</div>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                @php
                                    $shelfChange =
                                        $shelfMovement->new_shelf_stock - $shelfMovement->previous_shelf_stock;
                                @endphp
                                <span
                                    class="text-sm font-medium {{ $shelfChange > 0 ? 'text-green-600' : ($shelfChange < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ $shelfChange > 0 ? '+' : '' }}{{ number_format($shelfChange) }}
                                </span>
                            </div>
                        </div>

                        <!-- Back Stock -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">Back Stock</h4>
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">Before</div>
                                    <div class="text-2xl font-bold text-gray-800">
                                        {{ number_format($shelfMovement->previous_back_stock) }}</div>
                                </div>
                                <div class="flex-1 flex justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">After</div>
                                    <div class="text-2xl font-bold text-gray-600">
                                        {{ number_format($shelfMovement->new_back_stock) }}</div>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                @php
                                    $backChange = $shelfMovement->new_back_stock - $shelfMovement->previous_back_stock;
                                @endphp
                                <span
                                    class="text-sm font-medium {{ $backChange > 0 ? 'text-green-600' : ($backChange < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ $backChange > 0 ? '+' : '' }}{{ number_format($backChange) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Product Information -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Product Information</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Product Name</label>
                            <p class="mt-1 font-semibold text-lg">{{ $shelfMovement->product->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Product Code</label>
                            <p class="mt-1 font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                {{ $shelfMovement->product->code }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Category</label>
                            <p class="mt-1">{{ $shelfMovement->product->category }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Unit Price</label>
                            <p class="mt-1 font-semibold">₱{{ number_format($shelfMovement->product->price, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Batch Information -->
            @if ($shelfMovement->batch)
                <div class="bg-white border border-[var(--color-border-light)]">
                    <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                        <h3 class="text-lg font-semibold">Batch Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Batch Number</label>
                                <p class="mt-1 font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                    {{ $shelfMovement->batch->batch_number }}</p>
                            </div>

                            @if ($shelfMovement->batch->expiry_date)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Expiry Date</label>
                                    <p class="mt-1">
                                        {{ $shelfMovement->batch->expiry_date->format('M d, Y') }}
                                        @php
                                            $daysToExpiry = $shelfMovement->batch->expiry_date->diffInDays(now());
                                            $isExpired = $shelfMovement->batch->expiry_date->isPast();
                                            $isExpiringSoon = $daysToExpiry <= 30 && !$isExpired;
                                        @endphp
                                        @if ($isExpired)
                                            <span class="badge-danger ml-2">Expired</span>
                                        @elseif($isExpiringSoon)
                                            <span class="badge-warning ml-2">{{ $daysToExpiry }} days left</span>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if ($shelfMovement->batch->manufacture_date)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Manufacture Date</label>
                                    <p class="mt-1">{{ $shelfMovement->batch->manufacture_date->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Current Stock Levels -->
            <div class="bg-white border border-[var(--color-border-light)]">
                <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                    <h3 class="text-lg font-semibold">Current Stock Levels</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Shelf Stock</span>
                            <span
                                class="font-semibold">{{ number_format($shelfMovement->product->shelf_stock ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Back Stock</span>
                            <span
                                class="font-semibold">{{ number_format($shelfMovement->product->back_stock ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="font-medium">Total Stock</span>
                            <span
                                class="font-bold text-lg">{{ number_format(($shelfMovement->product->shelf_stock ?? 0) + ($shelfMovement->product->back_stock ?? 0)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Movements -->
    @if ($relatedMovements->count() > 0)
        <div class="mt-8 bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                <h3 class="text-lg font-semibold">Recent Movements for This Product</h3>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Movement</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Shelf Stock Change</th>
                            <th>User</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($relatedMovements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    @if ($movement->new_shelf_stock > $movement->previous_shelf_stock)
                                        <span class="badge-success">Restock</span>
                                    @else
                                        <span class="badge-warning">Destock</span>
                                    @endif
                                </td>
                                <td class="text-right font-semibold">{{ number_format($movement->quantity) }}</td>
                                <td class="text-right">
                                    @php
                                        $change = $movement->new_shelf_stock - $movement->previous_shelf_stock;
                                    @endphp
                                    <span
                                        class="{{ $change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ $change > 0 ? '+' : '' }}{{ number_format($change) }}
                                    </span>
                                </td>
                                <td>{{ $movement->user->name ?? 'System' }}</td>
                                <td>
                                    <a href="{{ route('inventory.shelf-movements.show', $movement) }}"
                                        class="btn btn-sm btn-secondary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <style>
        @media print {

            .btn,
            .no-print {
                display: none !important;
            }
        }
    </style>
</x-layout>
