<x-layout title="Sale #{{ $sale->id }}" subtitle="Official Receipt - {{ $sale->created_at->format('F d, Y h:i A') }}">
    <div class="max-w-4xl mx-auto">
        <div class="page-header">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales
            </a>
            <button onclick="window.print()" class="btn btn-primary no-print">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Receipt
            </button>
        </div>

        <!-- Receipt Container -->
        <div class="bg-white shadow-sm border border-gray-200">
            <div class="p-8">
                <!-- Header -->
                <div class="text-center mb-6 border-b-2 border-gray-300 pb-4">
                    <h1 class="text-2xl font-bold text-gray-800">iPharma Mart Management System</h1>
                    <p class="text-sm text-gray-600 mt-1">Pharmacy Management System</p>
                    <h2 class="text-lg font-bold text-gray-700 mt-3">Official Receipt</h2>
                </div>

                <!-- Receipt Info -->
                <div class="mb-4 text-sm border-b border-gray-200 pb-4">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <div><strong>Receipt #:</strong> RCP-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div><strong>Date:</strong> {{ $sale->created_at->format('F d, Y') }}</div>
                        <div><strong>Time:</strong> {{ $sale->created_at->format('h:i A') }}</div>
                        <div><strong>Cashier:</strong> {{ $sale->user->name }}</div>
                    </div>
                </div>

                <!-- Customer Info -->
                @if ($sale->customer && $sale->customer->name !== 'Walk-in Customer')
                    <div class="text-sm mb-4 border-b border-gray-200 pb-4">
                        <h3 class="font-semibold mb-2">Customer Information</h3>
                        <div><strong>Name:</strong> {{ $sale->customer->name }}</div>
                        <div><strong>Phone:</strong> {{ $sale->customer->phone }}</div>
                        @if ($sale->customer->address)
                            <div><strong>Address:</strong> {{ $sale->customer->address }}</div>
                        @endif
                    </div>
                @else
                    <div class="text-sm mb-4 text-gray-600 italic border-b border-gray-200 pb-4">
                        Walk-in Customer
                    </div>
                @endif

                <!-- Discount Info -->
                @if ($discountInfo)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4 text-sm">
                        <div class="font-semibold text-yellow-800 mb-2">🎫 Discount Applied</div>
                        <div><strong>Type:</strong> {{ $discountInfo['type'] }}</div>
                        <div><strong>ID Number:</strong> {{ $discountInfo['id_number'] }}</div>
                        <div><strong>Name:</strong> {{ $discountInfo['name'] }}</div>
                        <div><strong>Discount:</strong> {{ $discountInfo['percentage'] }}%
                            (₱{{ number_format($discountInfo['amount'], 2) }})</div>
                    </div>
                @endif

                <!-- Items Table -->
                <div class="mb-6">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-2 font-semibold">Item</th>
                                <th class="text-center py-2 font-semibold w-16">Qty</th>
                                <th class="text-right py-2 font-semibold w-24">Price</th>
                                <th class="text-right py-2 font-semibold w-28">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $item)
                                <tr class="border-b border-gray-200">
                                    <td class="py-2">{{ $item->product->name }}</td>
                                    <td class="text-center py-2">{{ $item->quantity }}</td>
                                    <td class="text-right py-2">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-right py-2">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="border-t-2 border-gray-300 pt-3 mb-4">
                    @php
                        $subtotal = $sale->items->sum('subtotal');
                        $discountAmount = $discountInfo ? $discountInfo['amount'] : 0;
                        $subtotalAfterDiscount = $subtotal - $discountAmount;
                        $vat = $subtotalAfterDiscount * 0.12;
                        $total = $subtotalAfterDiscount + $vat;
                    @endphp

                    <div class="flex justify-end text-sm mb-2">
                        <div class="w-64">
                            <div class="flex justify-between mb-1">
                                <span>Subtotal:</span>
                                <span>₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if ($discountAmount > 0)
                                <div class="flex justify-between mb-1 text-yellow-700">
                                    <span>Discount ({{ $discountInfo['percentage'] }}%):</span>
                                    <span>-₱{{ number_format($discountAmount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between mb-1">
                                <span>VAT (12%):</span>
                                <span>₱{{ number_format($vat, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg border-t-2 border-gray-300 pt-2 mt-2">
                                <span>TOTAL:</span>
                                <span>₱{{ number_format($sale->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border-2 border-gray-300 rounded p-4 mb-4 text-sm">
                    <div class="font-semibold mb-2">Payment Details</div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <div><strong>Payment Method:</strong></div>
                        <div class="text-right">{{ ucfirst($sale->payment_method) }}</div>

                        @if ($sale->reference_number)
                            <div><strong>Reference #:</strong></div>
                            <div class="text-right">{{ $sale->reference_number }}</div>
                        @endif

                        <div><strong>Amount Paid:</strong></div>
                        <div class="text-right">₱{{ number_format($sale->paid_amount, 2) }}</div>

                        <div><strong>Change:</strong></div>
                        <div class="text-right">₱{{ number_format($sale->change_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center text-sm border-t-2 border-gray-300 pt-4">
                    <p class="font-semibold">Thank you for your purchase!</p>
                    <p class="text-xs text-gray-600 mt-2">This serves as your official receipt</p>
                </div>
            </div>
        </div>

        <!-- Print Styles -->
        <style>
            @media print {

                .sidebar,
                .main-header,
                .page-header,
                .no-print {
                    display: none !important;
                }

                .main-layout {
                    margin-left: 0 !important;
                }

                body {
                    margin: 0;
                    padding: 0;
                }

                .bg-white {
                    box-shadow: none !important;
                    border: none !important;
                }
            }
        </style>
</x-layout>
