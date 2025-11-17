<x-layout title="New Sale">
    <div class="max-w-4xl">
        <div class="page-header">
            <h1 class="page-title">Create New Sale</h1>
        </div>

        <div class="card">
            <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-group">
                        <label for="customer_id" class="form-label">Customer *</label>
                        <select id="customer_id" name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_date" class="form-label">Sale Date *</label>
                        <input type="date" id="sale_date" name="sale_date" class="form-input"
                            value="{{ old('sale_date', date('Y-m-d')) }}" required>
                        @error('sale_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sale Items</h3>
                        <button type="button" onclick="addItem()" class="btn btn-primary btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div id="items-container">
                        <!-- Initial item row -->
                        <div class="item-row p-4 bg-gray-50 rounded-lg mb-3 relative">
                            <button type="button" onclick="removeItem(this)"
                                class="absolute top-2 right-2 text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                <div class="md:col-span-5">
                                    <label class="form-label text-sm">Product *</label>
                                    <select name="items[0][product_id]" class="form-select product-select" required
                                        onchange="updatePrice(this)">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                data-stock="{{ $product->stock }}">
                                                {{ $product->name }} (Stock: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="form-label text-sm">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" class="form-input quantity-input"
                                        value="1" min="1" required onchange="updateTotal(this)">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="form-label text-sm">Unit Price ($)</label>
                                    <input type="number" name="items[0][unit_price]" class="form-input price-input"
                                        step="0.01" readonly>
                                </div>

                                <div class="md:col-span-3">
                                    <label class="form-label text-sm">Subtotal ($)</label>
                                    <input type="text" class="form-input subtotal-display bg-gray-100" readonly
                                        value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mb-6">
                    <div class="w-full md:w-1/2 space-y-2">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span id="subtotal" class="font-semibold">$0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Tax (10%):</span>
                            <span id="tax" class="font-semibold">$0.00</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                            <span>Total:</span>
                            <span id="total" class="text-primary-600">$0.00</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total_amount" id="total_amount" value="0">

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Complete Sale
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemCount = 1;

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = container.firstElementChild.cloneNode(true);

            // Update input names with new index
            newItem.querySelectorAll('select, input').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${itemCount}]`);
                }
                if (input.classList.contains('product-select')) {
                    input.value = '';
                }
                if (input.classList.contains('quantity-input')) {
                    input.value = '1';
                }
                if (input.classList.contains('price-input') || input.classList.contains('subtotal-display')) {
                    input.value = '0.00';
                }
            });

            container.appendChild(newItem);
            itemCount++;
            calculateTotal();
        }

        function removeItem(button) {
            const container = document.getElementById('items-container');
            if (container.children.length > 1) {
                button.closest('.item-row').remove();
                calculateTotal();
            } else {
                alert('At least one item is required');
            }
        }

        function updatePrice(select) {
            const row = select.closest('.item-row');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.dataset.price || 0;
            const priceInput = row.querySelector('.price-input');
            priceInput.value = parseFloat(price).toFixed(2);
            updateTotal(select);
        }

        function updateTotal(input) {
            const row = input.closest('.item-row');
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = quantity * price;
            row.querySelector('.subtotal-display').value = subtotal.toFixed(2);
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.subtotal-display').forEach(display => {
                subtotal += parseFloat(display.value) || 0;
            });

            const tax = subtotal * 0.10;
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        // Initial calculation
        calculateTotal();
    </script>
</x-layout>
