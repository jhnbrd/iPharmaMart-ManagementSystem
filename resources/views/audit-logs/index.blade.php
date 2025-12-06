<x-layout title="Audit Logs" subtitle="System activity and user action logs">
    <div class="page-header">
        <button onclick="toggleFilters()" class="btn btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span id="filter-btn-text">Show Filters</span>
        </button>
    </div>

    <div id="filters-section" class="bg-white p-4 shadow-sm border border-[var(--color-border-light)] mb-6 hidden">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="form-group mb-0" style="min-width: 180px; flex: 1;">
                <label for="action" class="form-label text-xs mb-1">Action</label>
                <select id="action" name="action" class="form-select text-sm py-1.5">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-0" style="min-width: 180px; flex: 1;">
                <label for="user_id" class="form-label text-xs mb-1">User</label>
                <select id="user_id" name="user_id" class="form-select text-sm py-1.5">
                    <option value="">All Users</option>
                    @foreach (\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-0" style="min-width: 160px; flex: 1;">
                <label for="date" class="form-label text-xs mb-1">Date</label>
                <input type="date" id="date" name="date" class="form-input text-sm py-1.5"
                    value="{{ request('date') }}">
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

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="font-medium">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if ($log->user)
                                <div>
                                    <p class="font-medium">{{ $log->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ '@' . $log->user->username }}</p>
                                </div>
                            @else
                                <span class="text-gray-400">System</span>
                            @endif
                        </td>
                        <td>
                            @if ($log->action === 'create')
                                <span class="badge-success">Created</span>
                            @elseif($log->action === 'update')
                                <span class="badge-info">Updated</span>
                            @elseif($log->action === 'delete')
                                <span class="badge-danger">Deleted</span>
                            @elseif($log->action === 'sale')
                                <span class="badge-success">💰 Sale</span>
                            @elseif($log->action === 'stock_in')
                                <span class="badge-success">📦 Stock In</span>
                            @elseif($log->action === 'stock_out')
                                <span class="badge-danger">📤 Stock Out</span>
                            @elseif($log->action === 'login')
                                <span class="badge-info">Login</span>
                            @elseif($log->action === 'logout')
                                <span class="badge-secondary">Logout</span>
                            @else
                                <span class="badge-warning">{{ ucfirst($log->action) }}</span>
                            @endif
                        </td>
                        <td>{{ $log->description }}</td>
                        <td class="text-sm text-gray-600">{{ $log->ip_address }}</td>
                        <td>
                            @if ($log->old_values || $log->new_values)
                                <button onclick="showDetails({{ $log->id }})"
                                    class="text-blue-600 hover:underline text-sm">
                                    View
                                </button>
                                <div id="details-{{ $log->id }}" class="hidden mt-2">
                                    @if ($log->old_values)
                                        <div class="text-xs bg-red-50 p-2 rounded mb-1">
                                            <strong>Old:</strong> {{ json_encode($log->old_values) }}
                                        </div>
                                    @endif
                                    @if ($log->new_values)
                                        <div class="text-xs bg-green-50 p-2 rounded">
                                            <strong>New:</strong> {{ json_encode($log->new_values) }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-[var(--color-text-secondary)]">
                            No audit logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>

    <script>
        function showDetails(id) {
            const element = document.getElementById('details-' + id);
            element.classList.toggle('hidden');
        }

        function toggleFilters() {
            const filtersSection = document.getElementById('filters-section');
            const filterBtnText = document.getElementById('filter-btn-text');

            if (filtersSection.classList.contains('hidden')) {
                filtersSection.classList.remove('hidden');
                filterBtnText.textContent = 'Hide Filters';
            } else {
                filtersSection.classList.add('hidden');
                filterBtnText.textContent = 'Show Filters';
            }
        }

        // Show filters if any filter is active
        @if (request()->hasAny(['action', 'user_id', 'date']))
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilters();
            });
        @endif
    </script>
</x-layout>
