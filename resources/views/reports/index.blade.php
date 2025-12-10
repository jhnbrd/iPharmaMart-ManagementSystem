<x-layout title="Reports" subtitle="Generate and view system reports">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex gap-2">
                <button onclick="toggleFilters()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filter-btn-text">Show Filters</span>
                </button>

                @php
                    $queryParams = request()->query();
                    $pdfRoute = null;

                    switch ($reportType) {
                        case 'sales':
                            $pdfRoute = route('reports.sales.pdf', $queryParams);
                            break;
                        case 'inventory':
                            $pdfRoute = route('reports.inventory.pdf', $queryParams);
                            break;
                        case 'senior-citizen':
                            $pdfRoute = route('reports.senior-citizen.pdf', $queryParams);
                            break;
                        case 'pwd':
                            $pdfRoute = route('reports.pwd.pdf', $queryParams);
                            break;
                    }
                @endphp

                @if ($pdfRoute)
                    <a href="{{ $pdfRoute }}" class="btn btn-primary" target="_blank">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF Report
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Report Type Tabs -->
    <div class="bg-white border border-[var(--color-border-light)] mb-4">
        <div class="p-4">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-2">
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
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div id="filters-section" class="bg-white border border-[var(--color-border-light)] mb-6 hidden">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="type" value="{{ $reportType }}">

                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="form-input">
                </div>

                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-input">
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.index', ['type' => $reportType]) }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-6">

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

    <style>
        @media print {
            .print-hide {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page-header,
            nav,
            .sidebar {
                display: none !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>

    <script>
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
        document.addEventListener('DOMContentLoaded', function() {
            const hasActiveFilters = {{ request('start_date') || request('end_date') ? 'true' : 'false' }};
            if (hasActiveFilters) {
                const filtersSection = document.getElementById('filters-section');
                const filterBtnText = document.getElementById('filter-btn-text');
                filtersSection.classList.remove('hidden');
                filterBtnText.textContent = 'Hide Filters';
            }
        });
    </script>
</x-layout>
