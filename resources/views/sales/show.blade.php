<x-layout title="Sale Details">
    <div class="max-w-4xl">
        <div class="page-header">
            <h1 class="page-title">Sale #{{ $sale->id }}</h1>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales
            </a>
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <!-- Header -->
            <div class="border-b border-gray-200 pb-4 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">iPharma Mart</h2>
                        <p class="text-sm text-gray-600">Pharmacy Management System</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-700">Sale Receipt</p>
                        <p class="text-sm text-gray-600">Date: {{ $sale->created_at->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-600">Time: {{ $sale->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                <!-- Customer & Staff Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Customer Information</h3>
                        <p class="text-sm text-gray-900 font-medium">{{ $sale->customer->name }}</p>
                        @if ($sale->customer->email)
                            <p class="text-sm text-gray-600">{{ $sale->customer->email }}</p>
                        @endif
                        @if ($sale->customer->phone)
                            <p class="text-sm text-gray-600">{{ $sale->customer->phone }}</p>
                        @endif
                        @if ($sale->customer->address)
                            <p class="text-sm text-gray-600">{{ $sale->customer->address }}</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Processed By</h3>
                        <p class="text-sm text-gray-900 font-medium">{{ $sale->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $sale->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Sale Items Table -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Items Purchased</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Product
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Unit
                                    Price</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Quantity
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($sale->items as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div class="font-medium">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-600">{{ $item->product->category->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                        ${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">
                                        ${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-end">
                    <div class="w-full md:w-1/2 space-y-2">
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Subtotal:</span>
                            <span class="font-medium">${{ number_format($sale->items->sum('subtotal'), 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Tax (10%):</span>
                            <span
                                class="font-medium">${{ number_format($sale->items->sum('subtotal') * 0.1, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-300">
                            <span>Total:</span>
                            <span class="text-[var(--color-brand-green)]">${{ number_format($sale->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 pt-4 border-t border-gray-200 text-center text-sm text-gray-600">
                <p>Thank you for your purchase!</p>
                <p class="text-xs mt-1">This is a computer-generated receipt.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button onclick="window.print()" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .sidebar,
            .main-header,
            .page-header a,
            .btn {
                display: none !important;
            }

            .main-layout {
                margin-left: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>
</x-layout>
