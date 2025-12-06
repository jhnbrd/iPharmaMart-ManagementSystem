<x-layout title="Suppliers" subtitle="Manage supplier information and contacts">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Supplier
        </a>
    </div>

    <!-- Suppliers Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Products Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td class="font-medium">{{ $supplier->name }}</td>
                        <td>{{ $supplier->email ?? 'N/A' }}</td>
                        <td>{{ $supplier->phone ?? 'N/A' }}</td>
                        <td>{{ Str::limit($supplier->address ?? 'N/A', 30) }}</td>
                        <td>{{ $supplier->products_count }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="text-[var(--color-brand-green)] hover:underline">
                                    Edit
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
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
                        <td colspan="6" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No suppliers found. <a href="{{ route('suppliers.create') }}"
                                class="text-[var(--color-brand-green)] hover:underline">Add your first supplier</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $suppliers->links() }}
    </div>
</x-layout>
