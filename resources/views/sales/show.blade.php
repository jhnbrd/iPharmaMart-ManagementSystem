<x-layout title="Sale #{{ $sale->id }}" subtitle="Official Receipt - {{ $sale->created_at->format('F d, Y h:i A') }}">
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <div class="page-header mb-6 no-print">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Sales
                </a>
                <div class="flex gap-2">
                    @if (auth()->user()->role === 'admin' && !$sale->is_voided)
                        <button onclick="openVoidModal()" class="btn btn-danger">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Void Sale
                        </button>
                    @endif
                    <button onclick="window.print()" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Receipt
                    </button>
                </div>
            </div>

            <!-- Receipt Container -->
            <div class="bg-white shadow-sm border-2 border-gray-200 rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="text-center mb-4 border-b-2 border-gray-300 pb-3">
                        <h1 class="text-xl font-bold text-gray-800">iPharma Mart</h1>
                        <p class="text-xs text-gray-600">123 Main Street, Barangay Sample, City Name, Province,
                            Philippines</p>
                        <p class="text-xs text-gray-600">Tel: +63 912 345 6789 | Email: info@ipharmamart.com</p>
                        <p class="text-xs text-gray-600 mt-1 font-semibold">Your Health, Our Priority</p>
                        <h2 class="text-base font-bold text-gray-700 mt-2">Official Receipt</h2>
                        @if ($sale->is_voided)
                            <div class="mt-3 inline-block px-4 py-2 bg-red-100 border-2 border-red-600 rounded">
                                <span class="text-red-700 font-bold text-lg">⚠️ VOIDED TRANSACTION</span>
                            </div>
                        @endif
                    </div>

                    <!-- Receipt Info -->
                    <div class="mb-3 text-sm border-b border-gray-200 pb-3">
                        <div><strong>Receipt #:</strong> RCP-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div><strong>Date:</strong> {{ $sale->created_at->format('F d, Y') }}</div>
                        <div><strong>Time:</strong> {{ $sale->created_at->format('h:i A') }}</div>
                        <div><strong>Cashier:</strong> {{ $sale->user->name }}</div>
                        @if ($sale->is_voided)
                            <div class="mt-2 pt-2 border-t border-red-300 text-red-700">
                                <div><strong>Voided By:</strong> {{ $sale->voidedBy->name ?? 'Unknown' }}</div>
                                <div><strong>Voided At:</strong>
                                    {{ $sale->voided_at ? $sale->voided_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                @if ($sale->void_reason)
                                    <div><strong>Reason:</strong> {{ $sale->void_reason }}</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Customer Info -->
                    @if ($sale->customer && $sale->customer->name !== 'Walk-in Customer')
                        <div class="text-sm mb-3 border-b border-gray-200 pb-3">
                            <div><strong>Customer:</strong> {{ $sale->customer->name }}</div>
                            <div><strong>Phone:</strong> {{ $sale->customer->phone }}</div>
                            @if ($sale->customer->address)
                                <div><strong>Address:</strong> {{ $sale->customer->address }}</div>
                            @endif
                        </div>
                    @else
                        <div class="text-sm mb-3 text-gray-600 italic border-b border-gray-200 pb-3">
                            Walk-in Customer
                        </div>
                    @endif

                    <!-- Discount Info -->
                    @if ($discountInfo)
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-3 text-sm">
                            <div class="font-semibold text-yellow-800 mb-1">🎫 Discount Applied</div>
                            <div><strong>Type:</strong> {{ $discountInfo['type'] }}</div>
                            <div><strong>ID Number:</strong> {{ $discountInfo['id_number'] }}</div>
                            <div><strong>Discount:</strong> {{ $discountInfo['percentage'] }}%
                                (₱{{ number_format($discountInfo['amount'], 2) }})</div>
                        </div>
                    @endif

                    <!-- Items Table -->
                    <div class="mb-3">
                        <table class="w-full text-sm">
                            <thead class="border-y-2 border-gray-300">
                                <tr>
                                    <th class="text-left py-2">Item</th>
                                    <th class="text-center py-1">Qty</th>
                                    <th class="text-right py-2">Price</th>
                                    <th class="text-right py-2">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->items as $item)
                                    <tr class="{{ $item->is_voided ? 'opacity-50' : '' }}">
                                        <td class="py-1">
                                            {{ $item->product->name }}
                                            @if ($item->is_voided)
                                                <span class="text-red-600 font-semibold">(VOIDED)</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-1 {{ $item->is_voided ? 'line-through' : '' }}">
                                            {{ $item->quantity }}</td>
                                        <td class="text-right py-1 {{ $item->is_voided ? 'line-through' : '' }}">
                                            ₱{{ number_format($item->price, 2) }}</td>
                                        <td class="text-right py-1 {{ $item->is_voided ? 'line-through' : '' }}">
                                            ₱{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="border-t-2 border-gray-300 pt-3 mb-3">
                        @php
                            $subtotal = $sale->items->sum('subtotal');
                            $discountAmount = $discountInfo ? $discountInfo['amount'] : 0;
                            $subtotalAfterDiscount = $subtotal - $discountAmount;
                            $vat = $subtotalAfterDiscount * 0.12;
                            $total = $subtotalAfterDiscount + $vat;
                        @endphp

                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-gray-700">Subtotal:</span>
                            <span class="font-medium">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if ($discountAmount > 0)
                            <div class="flex justify-between text-sm mb-1.5 text-yellow-700">
                                <span>Discount ({{ $discountInfo['percentage'] }}%):</span>
                                <span class="font-medium">-₱{{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-gray-700">VAT (12%):</span>
                            <span class="font-medium">₱{{ number_format($vat, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold border-t-2 border-gray-400 pt-2 mt-2">
                            <span>TOTAL:</span>
                            <span class="text-green-700">₱{{ number_format($sale->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-3 mb-3">
                        <div class="text-center font-bold text-gray-800 mb-2 text-sm uppercase tracking-wide">Payment
                            Details</div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-semibold text-gray-700">Payment Method:</span>
                            <span class="uppercase font-medium">{{ strtoupper($sale->payment_method) }}</span>
                        </div>
                        @if ($sale->reference_number)
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="font-semibold text-gray-700">Reference #:</span>
                                <span class="font-mono">{{ $sale->reference_number }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm mb-1.5 border-t border-gray-300 pt-2 mt-2">
                            <span class="font-semibold text-gray-700">Amount Paid:</span>
                            <span
                                class="font-semibold text-green-600">₱{{ number_format($sale->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold border-t-2 border-gray-400 pt-2">
                            <span class="text-gray-800">Change:</span>
                            <span class="text-green-700">₱{{ number_format($sale->change_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center text-xs text-gray-600 border-t border-gray-300 pt-3">
                        <p class="font-semibold mb-1">Thank you for your purchase!</p>
                        <p>This serves as your official receipt</p>
                        <p class="mt-2">VAT Reg. TIN: XXX-XXX-XXX-XXX</p>
                        <p>Business Permit No.: BUS-PERMIT-XXXX-XXXX</p>
                        <p>FDA License No.: FDA-LICENSE-XXXX</p>
                        <p class="mt-2 text-gray-500">Your Health, Our Priority</p>
                    </div>
                </div>
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

    <!-- Void Sale Modal -->
    @if (auth()->user()->role === 'admin' && !$sale->is_voided)
        <div id="voidModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Void Sale #{{ $sale->id }}</h3>
                        <button onclick="closeVoidModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-4">This action will void the sale and return stock to
                            inventory. This cannot be undone.</p>

                        <div>
                            <label for="void_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for
                                Voiding *</label>
                            <textarea id="void_reason" name="void_reason" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Enter reason for voiding this sale..." required></textarea>
                            <p id="reasonError" class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeVoidModal()" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="button" onclick="confirmVoid()" class="btn btn-danger">
                            Void Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openVoidModal() {
                document.getElementById('voidModal').classList.remove('hidden');
                document.getElementById('void_reason').value = '';
                document.getElementById('reasonError').classList.add('hidden');
            }

            function closeVoidModal() {
                document.getElementById('voidModal').classList.add('hidden');
            }

            function confirmVoid() {
                const reason = document.getElementById('void_reason').value.trim();
                const reasonError = document.getElementById('reasonError');

                if (!reason) {
                    reasonError.textContent = 'Please provide a reason for voiding this sale.';
                    reasonError.classList.remove('hidden');
                    return;
                }

                if (reason.length > 500) {
                    reasonError.textContent = 'Reason must not exceed 500 characters.';
                    reasonError.classList.remove('hidden');
                    return;
                }

                reasonError.classList.add('hidden');

                // Send void request
                fetch('{{ route('sales.void', $sale) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            void_reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✓ ' + data.message);
                            window.location.reload();
                        } else {
                            alert('✗ ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('✗ An error occurred while voiding the sale.');
                    });
            }
        </script>
    @endif
</x-layout>
