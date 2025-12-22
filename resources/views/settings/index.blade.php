<x-layout title="General Settings" subtitle="Configure system preferences and options">
    <div class="mb-6">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-4">
                {{ session('info') }}
            </div>
        @endif

        <!-- Expiry Alerts -->
        @if ($expiringBatches->count() > 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ Expiry Alerts</h3>
                        <p class="text-sm text-red-700 mb-3">The following products are expiring within
                            {{ $settings['expiry_alert_days'] }} days:</p>
                        <div class="space-y-2">
                            @foreach ($expiringBatches as $batch)
                                <div class="bg-white rounded p-3 border border-red-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $batch->product->name }}</p>
                                            <p class="text-sm text-gray-600">Batch: {{ $batch->batch_number }}</p>
                                            <p class="text-sm text-gray-600">Stock:
                                                {{ $batch->shelf_quantity + $batch->back_quantity }} units</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-red-600">
                                                Expires: {{ $batch->expiry_date->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-red-500">
                                                {{ $batch->expiry_date->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Grid Layout for Settings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Settings Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold">Configuration Settings</h2>
                </div>

                <form method="POST" action="{{ route('settings.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Display Settings -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Display Settings</h3>
                            <div>
                                <label for="pagination_per_page"
                                    class="block text-sm font-medium text-gray-700 mb-2">Records Per Page</label>
                                <input type="number" id="pagination_per_page" name="pagination_per_page"
                                    value="{{ old('pagination_per_page', $settings['pagination_per_page']) }}"
                                    min="5" max="100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Number of records per page (5-100)</p>
                                @error('pagination_per_page')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Data Management -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Data Management</h3>
                            <div>
                                <label for="data_retention_years"
                                    class="block text-sm font-medium text-gray-700 mb-2">Data Retention Period
                                    (Years)</label>
                                <input type="number" id="data_retention_years" name="data_retention_years"
                                    value="{{ old('data_retention_years', round($settings['data_deletion_age_days'] / 365)) }}"
                                    min="3" max="10"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Records older than this will be archived (3-10
                                    years)</p>
                                @error('data_retention_years')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alert Settings -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Alert Settings</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="expiry_alert_days"
                                        class="block text-sm font-medium text-gray-700 mb-2">Product Expiry Alert
                                        (Days)</label>
                                    <input type="number" id="expiry_alert_days" name="expiry_alert_days"
                                        value="{{ old('expiry_alert_days', $settings['expiry_alert_days']) }}"
                                        min="1" max="90"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Show alert when products expire within this
                                        many days (1-90)</p>
                                    @error('expiry_alert_days')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="low_stock_alert_enabled" name="low_stock_alert_enabled"
                                        value="1" {{ $settings['low_stock_alert_enabled'] ? 'checked' : '' }}
                                        class="w-4 h-4 text-[var(--color-brand-green)] border-gray-300 rounded focus:ring-[var(--color-brand-green)]">
                                    <label for="low_stock_alert_enabled" class="ml-2 text-sm text-gray-700">Enable Low
                                        Stock Alerts</label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 px-6 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                Save Settings
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="flex-1 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center font-semibold">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: System Actions & Database Backup -->
            <div class="space-y-6">
                <!-- System Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold">System Actions</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-lg"
                            style="background-color: #f9fafb !important;">
                            <div>
                                <h4 class="font-medium text-gray-900">Clear System Cache</h4>
                                <p class="text-sm text-gray-600">Clear all cached data to refresh system settings</p>
                            </div>
                            <form method="POST" action="{{ route('settings.clear-cache') }}">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-semibold shadow-md hover:shadow-lg">
                                    Clear Cache
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-lg"
                            style="background-color: #fef2f2 !important;">
                            <div>
                                <h4 class="font-medium text-gray-900">Archive Old Data</h4>
                                <p class="text-sm text-gray-600">Review and archive records older than retention period
                                </p>
                            </div>
                            <form method="POST" action="{{ route('settings.delete-old-data') }}"
                                onsubmit="return confirm('This will flag old records for archival. Continue?');">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-semibold shadow-md hover:shadow-lg">
                                    Review Old Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Database Backup Management -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold">Database Backup</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Automatic Backup Setting -->
                        <form method="POST" action="{{ route('settings.update') }}"
                            class="p-4 rounded-lg border border-gray-200"
                            style="background-color: #f0fdf4 !important;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="pagination_per_page"
                                value="{{ $settings['pagination_per_page'] }}">
                            <input type="hidden" name="data_retention_years"
                                value="{{ round($settings['data_deletion_age_days'] / 365) }}">
                            <input type="hidden" name="expiry_alert_days"
                                value="{{ $settings['expiry_alert_days'] }}">
                            <input type="hidden" name="low_stock_alert_enabled"
                                value="{{ $settings['low_stock_alert_enabled'] ? '1' : '0' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox" id="auto_backup_enabled_db" name="auto_backup_enabled"
                                        value="1" {{ $settings['auto_backup_enabled'] ? 'checked' : '' }}
                                        class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <label for="auto_backup_enabled_db"
                                        class="ml-2 text-sm font-medium text-gray-900">
                                        Enable Automatic Daily Backups
                                    </label>
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold">
                                    Save Setting
                                </button>
                            </div>
                            <p class="text-xs text-gray-600 mt-2 ml-6">Automatic backups will run daily at 2:00 AM</p>
                        </form>

                        <div class="flex items-center justify-between p-4 rounded-lg"
                            style="background-color: #eff6ff !important;">
                            <div>
                                <h4 class="font-medium text-gray-900">Create Manual Backup</h4>
                                <p class="text-sm text-gray-600">Create an immediate backup of your database</p>
                            </div>
                            <form method="POST" action="{{ route('settings.backup-database') }}">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold shadow-md hover:shadow-lg">
                                    Backup Now
                                </button>
                            </form>
                        </div>

                        <div class="p-4 rounded-lg" style="background-color: #f9fafb !important;">
                            <h4 class="font-medium text-gray-900 mb-3 text-sm">Backup History</h4>
                            <div id="backup-list" class="space-y-2 max-h-64 overflow-y-auto">
                                <p class="text-sm text-gray-500">Loading backups...</p>
                            </div>
                        </div>

                        <div class="p-3 rounded-lg border border-amber-200"
                            style="background-color: #fffbeb !important;">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h5 class="font-medium text-amber-900 text-xs">Auto Backup Info</h5>
                                    <p class="text-xs text-amber-700 mt-1">
                                        Daily backups at 2:00 AM when enabled. Backups older than 30 days are
                                        auto-deleted.
                                        Stored in <code class="px-1 py-0.5 rounded text-xs"
                                            style="background-color: #fef3c7 !important;">storage/app/backups/</code>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load backup list
        function loadBackups() {
            fetch('{{ route('settings.list-backups') }}')
                .then(response => response.json())
                .then(data => {
                    const backupList = document.getElementById('backup-list');

                    if (data.length === 0) {
                        backupList.innerHTML =
                            '<p class="text-sm text-gray-500">No backups available. Create your first backup above.</p>';
                        return;
                    }

                    backupList.innerHTML = data.map(backup => {
                        const date = new Date(backup.date * 1000);
                        const size = (backup.size / 1024 / 1024).toFixed(2);

                        return `
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">${backup.filename}</p>
                                <p class="text-xs text-gray-500">${date.toLocaleString()} • ${size} MB</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="/settings/download-backup/${encodeURIComponent(backup.filename)}"
                                    class="px-4 py-2 text-sm font-semibold bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                                    Download
                                </a>
                                <button onclick="deleteBackup('${backup.filename}')"
                                    class="px-3 py-1 text-sm font-medium bg-red-500 text-white rounded hover:bg-red-600 transition-colors shadow-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error loading backups:', error);
                    document.getElementById('backup-list').innerHTML =
                        '<p class="text-sm text-red-500">Error loading backups. Please refresh the page.</p>';
                });
        }

        function deleteBackup(filename) {
            if (!confirm('Are you sure you want to delete this backup?')) {
                return;
            }

            fetch(`/settings/delete-backup/${encodeURIComponent(filename)}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadBackups();
                    } else {
                        alert('Error deleting backup: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error deleting backup:', error);
                    alert('Error deleting backup. Please try again.');
                });
        }

        // Load backups on page load
        document.addEventListener('DOMContentLoaded', loadBackups);
    </script>
</x-layout>
