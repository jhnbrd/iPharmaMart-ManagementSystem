<x-layout title="Inventory Report">
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Inventory Report</h1>
                <p class="text-[var(--color-text-secondary)] mt-1">Generate and print inventory reports</p>
            </div>
            <button onclick="window.print()" class="btn btn-primary print-hide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6 print-hide">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.inventory') }}" class="flex flex-wrap gap-4 items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0" style="min-width: 180px;">
                    <label for="product_type" class="form-label">Product Type</label>
                    <select id="product_type" name="product_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="pharmacy" {{ $productType === 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                        <option value="mini_mart" {{ $productType === 'mini_mart' ? 'selected' : '' }}>Mini Mart
                        </option>
                    </select>
                </div>

                <div class="form-group mb-0" style="min-width: 180px;">
                    <label for="stock_status" class="form-label">Stock Status</label>
                    <select id="stock_status" name="stock_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="low" {{ $stockStatus === 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="critical" {{ $stockStatus === 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="out" {{ $stockStatus === 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Header (Printable) -->
    <div class="print-only text-center mb-6">
        <h1 class="text-2xl font-bold">iPharma Mart Management System</h1>
        <h2 class="text-xl font-semibold mt-2">Inventory Report</h2>
        <p class="text-sm text-gray-600 mt-2">Generated: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Inventory Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Total Products
                    </p>
                    <p class="text-3xl font-bold">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Stock Value</p>
                    <p class="text-3xl font-bold text-[var(--color-brand-green)]">
                        ₱{{ number_format($totalStockValue, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Low Stock</p>
                    <p class="text-3xl font-bold text-[var(--color-accent-orange)]">{{ $lowStockCount }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Critical</p>
                    <p class="text-3xl font-bold text-[var(--color-danger)]">{{ $criticalStockCount }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Out of Stock</p>
                    <p class="text-3xl font-bold text-gray-600">{{ $outOfStockCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Inventory Table -->
    <div class="bg-white border border-[var(--color-border-light)]">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Product Details</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Supplier</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="font-semibold">{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>
                                <span class="badge-{{ $product->product_type === 'pharmacy' ? 'info' : 'success' }}">
                                    {{ ucfirst($product->product_type) }}
                                </span>
                            </td>
                            <td>{{ $product->supplier->name }}</td>
                            <td class="text-right">₱{{ number_format($product->price, 2) }}</td>
                            <td class="text-center font-semibold">{{ $product->total_stock }} {{ $product->unit }}
                            </td>
                            <td class="text-center">
                                @if ($product->total_stock == 0)
                                    <span class="badge-danger">OUT</span>
                                @elseif ($product->total_stock <= $product->stock_danger_level)
                                    <span class="badge-danger">CRITICAL</span>
                                @elseif ($product->total_stock <= $product->low_stock_threshold)
                                    <span class="badge-warning">LOW</span>
                                @else
                                    <span class="badge-success">OK</span>
                                @endif
                            </td>
                            <td class="text-right font-semibold text-[var(--color-brand-green)]">
                                ₱{{ number_format($product->total_stock * $product->price, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-[var(--color-text-secondary)]">
                                No products found matching the selected filters
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            .print-hide {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page-header,
            nav,
            .sidebar {
                display: none !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>
</x-layout>
