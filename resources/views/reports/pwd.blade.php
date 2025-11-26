<x-layout title="PWD Report">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">PWD Discount Report</h1>
            <p class="text-[var(--color-text-secondary)] mt-1">Monthly report of PWD discount transactions</p>
        </div>
        <button onclick="window.print()" class="btn btn-primary print-hide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Report
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6 print-hide">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Filter Options</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.pwd') }}" class="flex flex-wrap gap-4 items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="month" class="form-label">Report Month</label>
                    <input type="month" id="month" name="month" class="form-input"
                        value="{{ request('month', now()->format('Y-m')) }}" required>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Header (Printable) -->
    <div class="print-only text-center mb-6">
        <h1 class="text-2xl font-bold">iPharma Mart Management System</h1>
        <h2 class="text-xl font-semibold mt-2">PWD Discount Report</h2>
        <p class="text-sm text-gray-600 mt-2">Generated: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="bg-white border border-[var(--color-border-light)] mb-6">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Total
                        Transactions</p>
                    <p class="text-3xl font-bold">{{ number_format($transactions->count()) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Original Amount
                    </p>
                    <p class="text-3xl font-bold">₱{{ number_format($transactions->sum('original_amount'), 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Discounts Given
                    </p>
                    <p class="text-3xl font-bold text-[var(--color-brand-green)]">
                        ₱{{ number_format($transactions->sum('discount_amount'), 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-[var(--color-text-secondary)] uppercase tracking-wide mb-2">Final Amount</p>
                    <p class="text-3xl font-bold">₱{{ number_format($transactions->sum('final_amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white border border-[var(--color-border-light)]">
        <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
            <h2 class="text-lg font-semibold">Transaction Details</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>PWD ID Number</th>
                        <th>PWD Name</th>
                        <th>Birthdate</th>
                        <th>Disability Type</th>
                        <th class="text-right">Original Amount</th>
                        <th class="text-center">Discount %</th>
                        <th class="text-right">Discount Amount</th>
                        <th class="text-right">Final Amount</th>
                        <th class="text-center">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="font-medium">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                    {{ $transaction->pwd_id_number }}
                                </span>
                            </td>
                            <td>{{ $transaction->pwd_name }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($transaction->pwd_birthdate)->format('Y-m-d') }}
                                <span class="text-xs text-[var(--color-text-secondary)]">
                                    ({{ \Carbon\Carbon::parse($transaction->pwd_birthdate)->age }} yrs)
                                </span>
                            </td>
                            <td>
                                <span class="badge-info">{{ $transaction->disability_type }}</span>
                            </td>
                            <td class="text-right">₱{{ number_format($transaction->original_amount, 2) }}</td>
                            <td class="text-center">
                                <span class="badge-warning">{{ $transaction->discount_percentage }}%</span>
                            </td>
                            <td class="text-right font-semibold text-[var(--color-brand-green)]">
                                -₱{{ number_format($transaction->discount_amount, 2) }}
                            </td>
                            <td class="text-right font-bold">₱{{ number_format($transaction->final_amount, 2) }}</td>
                            <td class="text-center">{{ $transaction->items_purchased }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-8 text-[var(--color-text-secondary)]">
                                No PWD transactions found for this month
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
