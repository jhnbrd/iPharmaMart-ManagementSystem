<x-layout title="Inventory">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Inventory Management</h1>
        <div class="flex gap-3">
            <a href="{{ route('reports.inventory') }}" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generate Report
            </a>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Item
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 shadow-sm border border-[var(--color-border-light)] mb-6">
        <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="form-group mb-0" style="min-width: 180px; flex: 1;">
                <label for="search" class="form-label text-xs mb-1">Product Name</label>
                <input type="text" id="search" name="search" class="form-input text-sm py-1.5"
                    value="{{ request('search') }}" placeholder="Search product...">
            </div>

            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="product_type" class="form-label text-xs mb-1">Product Type</label>
                <select id="product_type" name="product_type" class="form-select text-sm py-1.5">
                    <option value="">All Types</option>
                    <option value="pharmacy" {{ request('product_type') === 'pharmacy' ? 'selected' : '' }}>Pharmacy
                    </option>
                    <option value="mini_mart" {{ request('product_type') === 'mini_mart' ? 'selected' : '' }}>Mini Mart
                    </option>
                </select>
            </div>

            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="category_id" class="form-label text-xs mb-1">Category</label>
                <select id="category_id" name="category_id" class="form-select text-sm py-1.5">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-0" style="min-width: 150px; flex: 1;">
                <label for="stock_status" class="form-label text-xs mb-1">Stock Status</label>
                <select id="stock_status" name="stock_status" class="form-select text-sm py-1.5">
                    <option value="">All Status</option>
                    <option value="ok" {{ request('stock_status') === 'ok' ? 'selected' : '' }}>OK</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="critical" {{ request('stock_status') === 'critical' ? 'selected' : '' }}>Critical
                    </option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock
                    </option>
                </select>
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

    <!-- Products Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Barcode</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Supplier</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="font-medium">{{ $product->name }}</td>
                        <td>
                            <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $product->barcode ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if ($product->product_type === 'pharmacy')
                                <span class="badge-info">Pharmacy</span>
                            @else
                                <span class="badge-warning">Mini Mart</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->unit }} ({{ $product->unit_quantity }})</td>
                        <td>
                            <span class="font-medium">{{ $product->stock }}</span>
                            <span class="text-xs text-[var(--color-text-secondary)]">{{ $product->unit }}</span>
                        </td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->supplier->name }}</td>
                        <td>{{ $product->expiry_date ? $product->expiry_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            @if ($product->isDangerStock())
                                <span class="badge-danger">Critical</span>
                            @elseif ($product->isLowStock())
                                <span class="badge-warning">Low Stock</span>
                            @else
                                <span class="badge-success">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('inventory.edit', $product->id) }}"
                                    class="text-[var(--color-brand-green)] hover:underline">
                                    Edit
                                </a>
                                <button type="button"
                                    onclick="openVoidModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                    class="text-[var(--color-danger)] hover:underline">
                                    Void
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No products found. <a href="{{ route('inventory.create') }}"
                                class="text-[var(--color-brand-green)] hover:underline">Add your first product</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
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
