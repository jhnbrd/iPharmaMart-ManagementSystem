<x-layout title="Stock Movement Details">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="page-title">Stock Movement Details</h1>
                <p class="text-[var(--color-text-secondary)] mt-1">
                    Movement #{{ $stockMovement->id }} - {{ $stockMovement->created_at->format('M d, Y h:i A') }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('inventory.stock-movements.index') }}" class="btn btn-secondary">
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
                            <p class="mt-1 text-lg font-semibold">#{{ $stockMovement->id }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Date & Time</label>
                            <p class="mt-1 font-semibold">{{ $stockMovement->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Movement Type</label>
                            <p class="mt-1">
                                @if ($stockMovement->type === 'stock_in')
                                    <span class="badge-success">Stock In</span>
                                @elseif($stockMovement->type === 'stock_out')
                                    <span class="badge-danger">Stock Out</span>
                                @else
                                    <span class="badge-warning">Adjustment</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Reference Number</label>
                            <p class="mt-1">
                                @if ($stockMovement->reference_number)
                                    <span
                                        class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $stockMovement->reference_number }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Performed By</label>
                            <p class="mt-1 font-medium">{{ $stockMovement->user->name ?? 'System' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Timestamp</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $stockMovement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if ($stockMovement->reason)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-600">Reason</label>
                            <p class="mt-2 p-3 bg-gray-50 rounded-md">{{ $stockMovement->reason }}</p>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Stock In -->
                        @if ($stockMovement->stock_in > 0)
                            <div class="p-4 bg-green-50 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-green-900">Stock In</h4>
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600">
                                        +{{ number_format($stockMovement->stock_in) }}</div>
                                    <div class="text-sm text-green-700">units added</div>
                                </div>
                            </div>
                        @endif

                        <!-- Stock Out -->
                        @if ($stockMovement->stock_out > 0)
                            <div class="p-4 bg-red-50 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-red-900">Stock Out</h4>
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-red-600">
                                        -{{ number_format($stockMovement->stock_out) }}</div>
                                    <div class="text-sm text-red-700">units removed</div>
                                </div>
                            </div>
                        @endif

                        <!-- Total Stock Change -->
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-blue-900">Total Stock</h4>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">Before</div>
                                    <div class="text-2xl font-bold text-gray-800">
                                        {{ number_format($stockMovement->previous_stock) }}</div>
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
                                        {{ number_format($stockMovement->new_stock) }}</div>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                @php
                                    $change = $stockMovement->new_stock - $stockMovement->previous_stock;
                                @endphp
                                <span
                                    class="text-sm font-medium {{ $change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ $change > 0 ? '+' : '' }}{{ number_format($change) }}
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
                            <p class="mt-1 font-semibold text-lg">{{ $stockMovement->product->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Product Code</label>
                            <p class="mt-1 font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                {{ $stockMovement->product->code }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Category</label>
                            <p class="mt-1">{{ $stockMovement->product->category }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Unit Price</label>
                            <p class="mt-1 font-semibold">₱{{ number_format($stockMovement->product->price, 2) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Current Total Stock</label>
                            <p class="mt-1 text-xl font-bold text-[var(--color-brand-green)]">
                                {{ number_format($stockMovement->product->total_stock) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Batch Information -->
            @if ($stockMovement->batch)
                <div class="bg-white border border-[var(--color-border-light)]">
                    <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                        <h3 class="text-lg font-semibold">Batch Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Batch Number</label>
                                <p class="mt-1 font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                    {{ $stockMovement->batch->batch_number }}</p>
                            </div>

                            @if ($stockMovement->batch->expiry_date)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Expiry Date</label>
                                    <p class="mt-1">
                                        {{ $stockMovement->batch->expiry_date->format('M d, Y') }}
                                        @php
                                            $daysToExpiry = $stockMovement->batch->expiry_date->diffInDays(now());
                                            $isExpired = $stockMovement->batch->expiry_date->isPast();
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

                            @if ($stockMovement->batch->manufacture_date)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Manufacture Date</label>
                                    <p class="mt-1">{{ $stockMovement->batch->manufacture_date->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-600">Batch Quantity</label>
                                <p class="mt-1 font-semibold">{{ number_format($stockMovement->batch->quantity) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Product Batches -->
            @if (isset($productBatches) && $productBatches->count() > 0)
                <div class="bg-white border border-[var(--color-border-light)]">
                    <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                        <h3 class="text-lg font-semibold">Available Batches</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($productBatches as $batch)
                                <div
                                    class="p-3 border border-gray-200 rounded-lg {{ $stockMovement->batch_id == $batch->id ? 'bg-blue-50 border-blue-200' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-mono text-xs">{{ $batch->batch_number }}</p>
                                            @if ($batch->expiry_date)
                                                <p class="text-xs text-gray-500">Exp:
                                                    {{ $batch->expiry_date->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold">{{ number_format($batch->quantity) }}</p>
                                            @if ($batch->expiry_date && $batch->expiry_date->diffInDays(now()) <= 30)
                                                <span class="text-xs badge-warning">Expiring</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
                            <th>Type</th>
                            <th>Reference #</th>
                            <th class="text-right">Stock In</th>
                            <th class="text-right">Stock Out</th>
                            <th class="text-right">Stock Change</th>
                            <th>User</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($relatedMovements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('M d, Y h:i A') }}</td>
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
                                        <span class="text-xs font-mono">{{ $movement->reference_number }}</span>
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
                                    @php
                                        $change = $movement->new_stock - $movement->previous_stock;
                                    @endphp
                                    <span
                                        class="{{ $change > 0 ? 'text-green-600' : ($change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ $change > 0 ? '+' : '' }}{{ number_format($change) }}
                                    </span>
                                </td>
                                <td>{{ $movement->user->name ?? 'System' }}</td>
                                <td>
                                    <a href="{{ route('inventory.stock-movements.show', $movement) }}"
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
