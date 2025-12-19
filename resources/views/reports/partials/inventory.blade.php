<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-6 border-l-4 border-[var(--color-brand-green)] shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 uppercase tracking-wide">Total Products</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-[var(--color-brand-green)]">{{ number_format($data['totalProducts']) }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 border-l-4 border-[var(--color-accent-orange)] shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 uppercase tracking-wide">Low Stock</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-[var(--color-accent-orange)]">
                    {{ number_format($data['lowStockCount']) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 border-l-4 border-[var(--color-danger)] shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 uppercase tracking-wide">Out of Stock</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-[var(--color-danger)]">{{ number_format($data['outOfStockCount']) }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 border-l-4 border-[var(--color-accent-blue)] shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 uppercase tracking-wide">Stock Value</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-[var(--color-accent-blue)]">
                    ₱{{ number_format($data['totalStockValue'], 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Product Inventory</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Shelf Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Back Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sold (Period)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($data['products'] as $product)
                    @php
                        $totalStock = $product->shelf_stock + $product->back_stock;
                        $status =
                            $totalStock == 0
                                ? 'Out of Stock'
                                : ($totalStock <= $product->low_stock_threshold
                                    ? 'Low Stock'
                                    : 'In Stock');
                        $statusColor =
                            $totalStock == 0
                                ? 'text-red-600 bg-red-50'
                                : ($totalStock <= $product->low_stock_threshold
                                    ? 'text-orange-600 bg-orange-50'
                                    : 'text-green-600 bg-green-50');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->supplier->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            {{ $product->shelf_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            {{ $product->back_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                            {{ $totalStock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            {{ $product->total_sold ?? 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                            ₱{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">{{ $status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $data['products']->appends(request()->query())->links() }}
    </div>
</div>
