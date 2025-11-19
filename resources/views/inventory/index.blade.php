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
                    <th>Type</th>
                    <th>Category</th>
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
                            @if ($product->product_type === 'pharmacy')
                                <span class="badge-info">Pharmacy</span>
                            @else
                                <span class="badge-warning">Mini Mart</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->supplier->name }}</td>
                        <td>{{ $product->expiry_date ? $product->expiry_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            @if ($product->isLowStock())
                                <span class="badge-danger">Low Stock</span>
                            @else
                                <span class="badge-success">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('inventory.edit', $product) }}"
                                    class="text-[var(--color-brand-green)] hover:underline">
                                    Edit
                                </a>
                                <form action="{{ route('inventory.destroy', $product) }}" method="POST"
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
                        <td colspan="9" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No products found. <a href="{{ route('inventory.create') }}"
                                class="text-[var(--color-brand-green)] hover:underline">Add your first product</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
