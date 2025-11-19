<x-layout title="Inventory">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Inventory Management</h1>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Item
        </a>
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
                                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[var(--color-danger)] hover:underline">
                                        Delete
                                    </button>
                                </form>
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
</x-layout>
