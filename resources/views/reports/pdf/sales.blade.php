<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        @page {
            margin: 100px 50px 80px 50px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 70px;
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9pt;
            color: #666;
        }

        .page-number:before {
            content: "Page " counter(page);
        }

        h1 {
            color: #1e40af;
            font-size: 22pt;
            margin: 0;
            font-weight: bold;
        }

        h2 {
            color: #2563eb;
            font-size: 14pt;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .company-info {
            font-size: 9pt;
            color: #666;
            margin-top: 5px;
        }

        .report-meta {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }

        .report-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-meta td {
            padding: 5px;
            font-size: 10pt;
        }

        .report-meta td:first-child {
            font-weight: bold;
            width: 40%;
            color: #1e40af;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f9fafb;
        }

        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 10pt;
            color: #666;
            font-weight: normal;
        }

        .summary-card .value {
            font-size: 18pt;
            font-weight: bold;
            color: #1e40af;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
        }

        table.data-table thead {
            background-color: #1e40af;
            color: white;
        }

        table.data-table th {
            padding: 10px 5px;
            text-align: left;
            font-weight: bold;
        }

        table.data-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table.data-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .payment-breakdown {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1>iPHARMAMART MANAGEMENT SYSTEM</h1>
        <div class="company-info">
            Pharmacy & Mini Mart | Sales Report
        </div>
    </header>

    <footer>
        <div>Generated on: {{ now()->format('F d, Y h:i A') }}</div>
        <div class="page-number"></div>
    </footer>

    <main>
        <div class="report-meta">
            <table>
                <tr>
                    <td>Report Type:</td>
                    <td>Sales Report</td>
                </tr>
                <tr>
                    <td>Report Period:</td>
                    <td>{{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} to
                        {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}</td>
                </tr>
                @if ($paymentMethod)
                    <tr>
                        <td>Payment Method Filter:</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Generated By:</td>
                    <td>{{ auth()->user()->name ?? 'System' }}</td>
                </tr>
            </table>
        </div>

        <h2>Executive Summary</h2>
        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Revenue</h3>
                <div class="value">₱{{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="summary-card">
                <h3>Total Transactions</h3>
                <div class="value">{{ number_format($totalTransactions) }}</div>
            </div>
            <div class="summary-card">
                <h3>Average Sale</h3>
                <div class="value">₱{{ number_format($averageSale, 2) }}</div>
            </div>
        </div>

        @if ($paymentBreakdown->count() > 0)
            <h2>Payment Method Breakdown</h2>
            <div class="payment-breakdown">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th class="text-center">Transactions</th>
                            <th class="text-right">Total Amount</th>
                            <th class="text-right">% of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentBreakdown as $payment)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="text-center">{{ number_format($payment->count) }}</td>
                                <td class="text-right">₱{{ number_format($payment->total, 2) }}</td>
                                <td class="text-right">{{ number_format(($payment->total / $totalRevenue) * 100, 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <h2>Detailed Sales Transactions</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Sale ID</th>
                    <th>Customer</th>
                    <th>Payment</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Tax</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                        <td>#{{ $sale->id }}</td>
                        <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}
                            </span>
                        </td>
                        <td class="text-right">₱{{ number_format($sale->subtotal, 2) }}</td>
                        <td class="text-right">₱{{ number_format($sale->tax, 2) }}</td>
                        <td class="text-right"><strong>₱{{ number_format($sale->total, 2) }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No sales transactions found for the specified period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($sales->count() > 0)
                <tfoot style="background-color: #f3f4f6; font-weight: bold;">
                    <tr>
                        <td colspan="6" class="text-right" style="padding: 10px;">GRAND TOTAL:</td>
                        <td class="text-right" style="padding: 10px;">₱{{ number_format($totalRevenue, 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <div style="margin-top: 40px; font-size: 9pt; color: #666;">
            <p><strong>Notes:</strong></p>
            <ul style="margin: 5px 0;">
                <li>All amounts are in Philippine Peso (₱)</li>
                <li>Tax amount includes 12% VAT where applicable</li>
                <li>This report is system-generated and does not require a signature</li>
            </ul>
        </div>
    </main>
</body>

</html>
