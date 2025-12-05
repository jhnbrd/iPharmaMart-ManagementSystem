<x-layout title="Reports">
    <div class="mb-6">
        <!-- Report Type Filter -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
            <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                <!-- Report Type Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4">
                    <button type="submit" name="type" value="sales"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $reportType === 'sales' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Sales Report
                    </button>
                    <button type="submit" name="type" value="inventory"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $reportType === 'inventory' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Inventory Report
                    </button>
                    <button type="submit" name="type" value="senior-citizen"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $reportType === 'senior-citizen' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Senior Citizen
                    </button>
                    <button type="submit" name="type" value="pwd"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $reportType === 'pwd' ? 'bg-[var(--color-brand-green)] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        PWD
                    </button>
                </div>

                <!-- Date Range Filter -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-brand-green)] focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-[var(--color-brand-green)] text-white rounded-lg hover:bg-green-700 transition-colors">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Report Content -->
        @if ($reportType === 'sales')
            @include('reports.partials.sales', ['data' => $data])
        @elseif ($reportType === 'inventory')
            @include('reports.partials.inventory', ['data' => $data])
        @elseif ($reportType === 'senior-citizen')
            @include('reports.partials.senior-citizen', ['data' => $data])
        @elseif ($reportType === 'pwd')
            @include('reports.partials.pwd', ['data' => $data])
        @endif
    </div>
</x-layout>
