<x-layout title="Inventory" subtitle="Manage products, stock levels, and batches">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex gap-2">
                <button onclick="toggleFilters()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filter-btn-text">Show Filters</span>
                </button>
                <div class="flex gap-2">
                    @if (in_array(auth()->user()->role, ['admin', 'inventory_manager']))
                        <a href="{{ route('reports.index', ['type' => 'inventory']) }}" class="btn btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Generate Report
                        </a>
                    @endif
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Item
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Type Tabs -->
    <div class="bg-white border border-[var(--color-border-light)] mb-4">
        <div class="p-4">
            <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-wrap gap-2">
                <button type="submit" name="product_type" value="pharmacy"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('product_type', 'pharmacy') === 'pharmacy' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pharmacy Products
                </button>
                <button type="submit" name="product_type" value="mini_mart"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('product_type') === 'mini_mart' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Mini Mart Products
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white border border-[var(--color-border-light)] mb-6 hidden">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="product_type" value="{{ request('product_type') }}">

                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="search" class="form-label">Search Product</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Product name..." class="form-input">
                </div>

                <div class="form-group mb-0" style="min-width: 150px;">
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0" style="min-width: 150px;">
                    <label for="stock_status" class="form-label">Stock Status</label>
                    <select id="stock_status" name="stock_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="ok" {{ request('stock_status') === 'ok' ? 'selected' : '' }}>OK</option>
                        <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="critical" {{ request('stock_status') === 'critical' ? 'selected' : '' }}>
                            Critical</option>
                        <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock
                        </option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('inventory.index', ['product_type' => request('product_type')]) }}"
                        class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-6">

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">
                    {{ request('product_type') === 'mini_mart' ? 'Mini Mart' : 'Pharmacy' }} Products
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[18%]">
                                Product Details</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Batch #</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Exp. Date</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[7%]">
                                Barcode</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Category</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[6%]">
                                Unit</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">
                                Shelf</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">
                                Back</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">
                                Total</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[7%]">
                                Price</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Supplier</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[7%]">
                                Status</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            @php
                                $batches = $product->batches()->orderBy('expiry_date', 'asc')->get();
                                $hasBatches = $batches->count() > 0;
                                $rowspan = $hasBatches ? $batches->count() : 1;
                            @endphp

                            @if ($hasBatches)
                                @foreach ($batches as $index => $batch)
                                    @php
                                        $daysUntilExpiry = now()->diffInDays($batch->expiry_date, false);
                                        $expiryColor =
                                            $daysUntilExpiry <= 0
                                                ? '#dc2626'
                                                : ($daysUntilExpiry <= 30
                                                    ? '#f59e0b'
                                                    : ($daysUntilExpiry <= 90
                                                        ? '#fb923c'
                                                        : '#6b7280'));
                                    @endphp
                                    <tr class="hover:bg-gray-50 {{ $index > 0 ? 'border-t border-gray-100' : '' }}">
                                        @if ($index === 0)
                                            <td class="px-6 py-4" rowspan="{{ $rowspan }}">
                                                @if ($product->brand_name)
                                                    <div class="text-sm font-bold text-gray-900">
                                                        {{ $product->brand_name }}</div>
                                                @endif
                                                <div
                                                    class="text-sm {{ $product->brand_name ? 'text-gray-700' : 'font-bold text-gray-900' }}">
                                                    {{ $product->name }}
                                                </div>
                                                @if ($product->generic_name && $product->product_type === 'pharmacy')
                                                    <div class="text-xs text-gray-500 italic">Generic:
                                                        {{ $product->generic_name }}</div>
                                                @endif
                                                <div class="text-xs mt-1 text-gray-600">
                                                    📦 {{ $batches->count() }}
                                                    {{ $batches->count() == 1 ? 'batch' : 'batches' }}
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-4 py-3 text-sm">
                                            <span class="font-mono text-xs font-semibold text-blue-700">
                                                {{ $batch->batch_number }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="font-medium" style="color: {{ $expiryColor }}">
                                                {{ $batch->expiry_date->format('M d, Y') }}
                                            </span>
                                            @if ($daysUntilExpiry <= 0)
                                                <div class="text-xs text-red-600 font-semibold">EXPIRED</div>
                                            @elseif ($daysUntilExpiry <= 30)
                                                <div class="text-xs" style="color: {{ $expiryColor }}">
                                                    {{ $daysUntilExpiry }}d left</div>
                                            @endif
                                        </td>
                                        @if ($index === 0)
                                            <td class="px-4 py-3 text-sm" rowspan="{{ $rowspan }}">
                                                <span
                                                    class="font-mono text-xs text-gray-700">{{ $product->barcode ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900"
                                                rowspan="{{ $rowspan }}">
                                                {{ $product->category->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900"
                                                rowspan="{{ $rowspan }}">
                                                {{ $product->unit }} ({{ $product->unit_quantity }})
                                            </td>
                                        @endif
                                        <td class="px-4 py-3 text-sm text-right">
                                            <span
                                                class="font-medium text-blue-600">{{ $batch->shelf_quantity }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <span
                                                class="font-medium text-green-600">{{ $batch->back_quantity }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <span
                                                class="font-bold text-gray-900">{{ $batch->shelf_quantity + $batch->back_quantity }}</span>
                                        </td>
                                        @if ($index === 0)
                                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900"
                                                rowspan="{{ $rowspan }}">
                                                ₱{{ number_format($product->price, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900"
                                                rowspan="{{ $rowspan }}">
                                                {{ $product->supplier->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center" rowspan="{{ $rowspan }}">
                                                @if ($product->isDangerStock())
                                                    <span class="badge-danger">Critical</span>
                                                @elseif ($product->isLowStock())
                                                    <span class="badge-warning">Low</span>
                                                @else
                                                    <span class="badge-success">OK</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center" rowspan="{{ $rowspan }}">
                                                <div class="flex gap-2 justify-center">
                                                    <a href="{{ route('inventory.edit', $product->id) }}"
                                                        class="text-[var(--color-brand-green)] hover:underline text-xs">
                                                        Edit
                                                    </a>
                                                    <button type="button"
                                                        onclick="openVoidModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                        class="text-[var(--color-danger)] hover:underline text-xs">
                                                        Void
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        @if ($product->brand_name)
                                            <div class="text-sm font-bold text-gray-900">{{ $product->brand_name }}
                                            </div>
                                        @endif
                                        <div
                                            class="text-sm {{ $product->brand_name ? 'text-gray-700' : 'font-bold text-gray-900' }}">
                                            {{ $product->name }}
                                        </div>
                                        @if ($product->generic_name && $product->product_type === 'pharmacy')
                                            <div class="text-xs text-gray-500 italic">Generic:
                                                {{ $product->generic_name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-400" colspan="2">
                                        <span class="italic">No batches</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">
                                            {{ $product->barcode ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $product->category->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $product->unit }}
                                        ({{ $product->unit_quantity }})
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <span class="font-medium text-blue-600">{{ $product->shelf_stock }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <span class="font-medium text-green-600">{{ $product->back_stock }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <span class="font-bold text-gray-900">{{ $product->total_stock }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">
                                        ₱{{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $product->supplier->name }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        @if ($product->isDangerStock())
                                            <span class="badge-danger">Critical</span>
                                        @elseif ($product->isLowStock())
                                            <span class="badge-warning">Low</span>
                                        @else
                                            <span class="badge-success">OK</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <div class="flex gap-2 justify-center">
                                            <a href="{{ route('inventory.edit', $product->id) }}"
                                                class="text-[var(--color-brand-green)] hover:underline text-xs">
                                                Edit
                                            </a>
                                            <button type="button"
                                                onclick="openVoidModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                class="text-[var(--color-danger)] hover:underline text-xs">
                                                Void
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="13" class="px-6 py-8 text-center text-gray-500">
                                    No products found. <a href="{{ route('inventory.create') }}"
                                        class="text-[var(--color-brand-green)] hover:underline">Add your first
                                        product</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Admin Authorization Modal for Void -->
    <div id="voidModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Admin Authorization Required</h3>
            </div>
            <form id="voidForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600 mb-4">
                        You are about to void the product: <span id="productName"
                            class="font-bold text-gray-900"></span>
                    </p>
                    <p class="text-sm text-red-600 mb-4">
                        ⚠️ This action requires admin authorization and cannot be undone.
                    </p>

                    <div class="form-group">
                        <label for="admin_username" class="form-label">Admin Username *</label>
                        <input type="text" id="admin_username" name="admin_username" class="form-input" required
                            autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="admin_password" class="form-label">Admin Password *</label>
                        <input type="password" id="admin_password" name="admin_password" class="form-input" required
                            autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="void_reason" class="form-label">Reason for Void *</label>
                        <textarea id="void_reason" name="void_reason" class="form-textarea" rows="3" required
                            placeholder="Enter reason for voiding this product..."></textarea>
                    </div>

                    <div id="authError"
                        class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex gap-3 justify-end">
                    <button type="button" onclick="closeVoidModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Void Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openVoidModal(productId, productName) {
            const modal = document.getElementById('voidModal');
            const form = document.getElementById('voidForm');
            const productNameSpan = document.getElementById('productName');

            // Set form action
            form.action = `/inventory/${productId}/void`;

            // Set product name
            productNameSpan.textContent = productName;

            // Clear form
            form.reset();
            document.getElementById('authError').classList.add('hidden');

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeVoidModal() {
            const modal = document.getElementById('voidModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVoidModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('voidModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeVoidModal();
            }
        });

        // Filter toggle functionality
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
        document.addEventListener('DOMContentLoaded', function() {
            const hasActiveFilters =
                {{ request('search') || request('category_id') || request('stock_status') ? 'true' : 'false' }};
            if (hasActiveFilters) {
                const filtersSection = document.getElementById('filters-section');
                const filterBtnText = document.getElementById('filter-btn-text');
                filtersSection.classList.remove('hidden');
                filterBtnText.textContent = 'Hide Filters';
            }
        });
    </script>
</x-layout>
