<x-layout title="Users">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">User Management</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add User
        </a>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="font-medium">{{ $user->username }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->role === 'superadmin')
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">⚡
                                    Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="badge-danger">Admin</span>
                            @elseif($user->role === 'cashier')
                                <span class="badge-info">Cashier</span>
                            @else
                                <span class="badge-warning">Inventory Manager</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="text-[var(--color-brand-green)] hover:underline">
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST"
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
                        <td colspan="7" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No users found. <a href="{{ route('users.create') }}"
                                class="text-[var(--color-brand-green)] hover:underline">Add your first user</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-layout>
