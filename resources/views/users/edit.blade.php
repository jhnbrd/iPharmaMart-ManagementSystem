<x-layout title="Edit User" subtitle="Update user account information">
    <div class="max-w-4xl">
        <div class="page-header">
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" id="username" name="username" class="form-input"
                        value="{{ old('username', $user->username) }}" required>
                    <p class="form-help">For login purposes</p>
                    @error('username')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-input"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-input"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Role *</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Select Role</option>
                        @if (auth()->user()->role === 'superadmin')
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                        @else
                            <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>
                                Cashier</option>
                            <option value="inventory_manager"
                                {{ old('role', $user->role) == 'inventory_manager' ? 'selected' : '' }}>Inventory
                                Manager</option>
                        @endif
                    </select>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    @if (auth()->user()->role === 'superadmin')
                        <p class="form-help">Super Admin can only edit Admin accounts</p>
                    @else
                        <p class="form-help">Admin can edit Cashier and Inventory Manager accounts</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-input">
                    <p class="form-help">Leave blank to keep current password. Minimum 8 characters if changing.</p>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                    @error('password_confirmation')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
