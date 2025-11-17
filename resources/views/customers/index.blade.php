<x-layout title="Customers">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Customer Management</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Customer
        </a>
    </div>

    <!-- Customers Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Total Purchases</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="font-medium">{{ $customer->name }}</td>
                        <td>{{ $customer->email ?? 'N/A' }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ Str::limit($customer->address ?? 'N/A', 30) }}</td>
                        <td>{{ $customer->sales_count }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('customers.edit', $customer) }}"
                                    class="text-[var(--color-brand-green)] hover:underline">
                                    Edit
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST"
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
                            No customers found. <a href="{{ route('customers.create') }}"
                                class="text-[var(--color-brand-green)] hover:underline">Add your first customer</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
