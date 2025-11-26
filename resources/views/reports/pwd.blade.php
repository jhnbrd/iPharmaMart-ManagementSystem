<x-layout>
    <x-slot:title>PWD Report</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">PWD Discount Report</h1>
                <p class="text-sm text-gray-600 mt-1">Monthly report of PWD discount transactions</p>
            </div>
            <button onclick="window.print()" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Print Report
            </button>
        </div>

        <!-- Filter Section -->
        <div class="bg-white p-6 border border-[var(--color-border-light)]">
            <form method="GET" action="{{ route('reports.pwd') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Report Month</label>
                        <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                            class="input-field">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stat-card">
                <div class="stat-title">Total Transactions</div>
                <div class="stat-value">{{ number_format($transactions->count()) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Original Amount</div>
                <div class="stat-value">₱{{ number_format($transactions->sum('original_amount'), 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Discounts Given</div>
                <div class="stat-value text-[var(--color-success)]">
                    ₱{{ number_format($transactions->sum('discount_amount'), 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Final Amount</div>
                <div class="stat-value">₱{{ number_format($transactions->sum('final_amount'), 2) }}</div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-3 border-b border-[var(--color-border-light)]">
                <h2 class="text-base font-semibold">Transaction Details</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-600 uppercase">
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">PWD ID Number</th>
                            <th class="px-6 py-3 text-left">PWD Name</th>
                            <th class="px-6 py-3 text-left">Birthdate</th>
                            <th class="px-6 py-3 text-left">Disability Type</th>
                            <th class="px-6 py-3 text-right">Original Amount</th>
                            <th class="px-6 py-3 text-right">Discount %</th>
                            <th class="px-6 py-3 text-right">Discount Amount</th>
                            <th class="px-6 py-3 text-right">Final Amount</th>
                            <th class="px-6 py-3 text-center">Items</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border-light)]">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $transaction->pwd_id_number }}</td>
                                <td class="px-6 py-4 text-sm">{{ $transaction->pwd_name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    {{ \Carbon\Carbon::parse($transaction->pwd_birthdate)->format('Y-m-d') }}
                                    <span class="text-xs text-gray-500">
                                        ({{ \Carbon\Carbon::parse($transaction->pwd_birthdate)->age }} years)
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="badge-info text-xs">{{ $transaction->disability_type }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    ₱{{ number_format($transaction->original_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right">{{ $transaction->discount_percentage }}%</td>
                                <td class="px-6 py-4 text-sm text-right text-[var(--color-success)]">
                                    ₱{{ number_format($transaction->discount_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-medium">
                                    ₱{{ number_format($transaction->final_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-center">{{ $transaction->items_purchased }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm">No PWD transactions for this month</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn-primary,
            form,
            nav,
            header,
            .no-print {
                display: none !important;
            }

            .stat-card {
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
        }
    </style>
</x-layout>
