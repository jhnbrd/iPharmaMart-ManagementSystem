<x-layout title="General Settings">
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

        <!-- Settings Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">General Settings</h2>
            </div>

            <form method="POST" action="{{ route('settings.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Pagination Settings -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Display Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="pagination_per_page"
                                class="block text-sm font-medium text-gray-700 mb-2">Records Per Page</label>
                            <input type="number" id="pagination_per_page" name="pagination_per_page"
                                value="{{ old('pagination_per_page', $settings['pagination_per_page']) }}"
                                min="5" max="100"
                                class="w-full md:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Number of records to display per page (5-100)</p>
                            @error('pagination_per_page')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Data Management Settings -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Data Management</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="data_deletion_age_days"
                                class="block text-sm font-medium text-gray-700 mb-2">Data Retention Period
                                (Days)</label>
                            <input type="number" id="data_deletion_age_days" name="data_deletion_age_days"
                                value="{{ old('data_deletion_age_days', $settings['data_deletion_age_days']) }}"
                                min="30" max="3650"
                                class="w-full md:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Records older than this will be flagged for archival
                                (30-3650 days)</p>
                            @error('data_deletion_age_days')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Alert Settings -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Alert Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="expiry_alert_days" class="block text-sm font-medium text-gray-700 mb-2">Product
                                Expiry Alert (Days)</label>
                            <input type="number" id="expiry_alert_days" name="expiry_alert_days"
                                value="{{ old('expiry_alert_days', $settings['expiry_alert_days']) }}" min="1"
                                max="90"
                                class="w-full md:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Show alert when products expire within this many days
                                (1-90)</p>
                            @error('expiry_alert_days')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="low_stock_alert_enabled" name="low_stock_alert_enabled"
                                {{ $settings['low_stock_alert_enabled'] ? 'checked' : '' }}
                                class="w-4 h-4 text-[var(--color-brand-green)] border-gray-300 rounded focus:ring-[var(--color-brand-green)]">
                            <label for="low_stock_alert_enabled" class="ml-2 text-sm text-gray-700">Enable Low Stock
                                Alerts</label>
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="pb-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">System Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="auto_backup_enabled" name="auto_backup_enabled"
                                {{ $settings['auto_backup_enabled'] ? 'checked' : '' }}
                                class="w-4 h-4 text-[var(--color-brand-green)] border-gray-300 rounded focus:ring-[var(--color-brand-green)]">
                            <label for="auto_backup_enabled" class="ml-2 text-sm text-gray-700">Enable Automatic
                                Backups</label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors">
                        Save Settings
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- System Actions -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">System Actions</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">Clear System Cache</h4>
                        <p class="text-sm text-gray-600">Clear all cached data to refresh system settings</p>
                    </div>
                    <form method="POST" action="{{ route('settings.clear-cache') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                            Clear Cache
                        </button>
                    </form>
                </div>

                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">Archive Old Data</h4>
                        <p class="text-sm text-gray-600">Review and archive records older than retention period</p>
                    </div>
                    <form method="POST" action="{{ route('settings.delete-old-data') }}"
                        onsubmit="return confirm('This will flag old records for archival. Continue?');">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Review Old Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
