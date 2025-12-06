<x-layout title="Inventory" subtitle="Manage products, stock levels, and batches">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex gap-3">
            @if (in_array(auth()->user()->role, ['admin', 'inventory_manager']))
                <a href="{{ route('reports.inventory') }}" class="btn btn-secondary">
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

    <div class="mb-6">
        <!-- Product Type Filter -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
            <form method="GET" action="{{ route('inventory.index') }}" class="space-y-4">
                <!-- Product Type Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4">
                    <button type="submit" name="product_type" value="pharmacy"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('product_type') === 'pharmacy' || !request('product_type') ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pharmacy Products
                    </button>
                    <button type="submit" name="product_type" value="mini_mart"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('product_type') === 'mini_mart' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Mini Mart Products
                    </button>
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search
                            Product</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Product name..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category_id" name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1">Stock
                            Status</label>
                        <select id="stock_status" name="stock_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="ok" {{ request('stock_status') === 'ok' ? 'selected' : '' }}>OK</option>
                            <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low
                            </option>
                            <option value="critical" {{ request('stock_status') === 'critical' ? 'selected' : '' }}>
                                Critical</option>
                            <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of
                                Stock</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

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
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">
                                Product Details</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                                Barcode</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                                Category</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Unit</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[7%]">
                                Shelf</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[7%]">
                                Back</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[7%]">
                                Total</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Price</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                                Supplier</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[8%]">
                                Status</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($product->brand_name)
                                        <div class="text-sm font-bold text-gray-900">{{ $product->brand_name }}</div>
                                    @endif
                                    <div
                                        class="text-sm {{ $product->brand_name ? 'text-gray-700' : 'font-bold text-gray-900' }}">
                                        {{ $product->name }}
                                    </div>
                                    @if ($product->generic_name && $product->product_type === 'pharmacy')
                                        <div class="text-xs text-gray-500 italic">Generic: {{ $product->generic_name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">
                                        {{ $product->barcode ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $product->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->unit }}
                                    ({{ $product->unit_quantity }})</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <span class="font-medium text-blue-600">{{ $product->shelf_stock }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <span class="font-medium text-green-600">{{ $product->back_stock }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <span class="font-bold text-gray-900">{{ $product->total_stock }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                    ₱{{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $product->supplier->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if ($product->isDangerStock())
                                        <span class="badge-danger">Critical</span>
                                    @elseif ($product->isLowStock())
                                        <span class="badge-warning">Low</span>
                                    @else
                                        <span class="badge-success">OK</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
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
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-8 text-center text-gray-500">
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
    </script>
</x-layout>
