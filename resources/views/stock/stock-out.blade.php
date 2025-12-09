<x-layout title="Stock Out" subtitle="Record stock removed or damaged inventory">
    <div class="max-w-4xl">
        <div class="page-header">
            @if (isset($selectedBatch))
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-amber-900">Expiring Product Alert</p>
                            <p class="text-xs text-amber-700 mt-1">
                                <strong>{{ $selectedBatch->product->name }}</strong> - Batch
                                {{ $selectedBatch->batch_number }}
                                expires on {{ $selectedBatch->expiry_date->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <form action="{{ route('stock.out.process') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div class="form-group">
                        <label for="product_id" class="form-label">Product *</label>
                        <select id="product_id" name="product_id" class="form-select" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                @php
                                    $isSelected = isset($prefilledData)
                                        ? $prefilledData['product_id'] == $product->id
                                        : old('product_id') == $product->id;
                                @endphp
                                <option value="{{ $product->id }}" data-shelf="{{ $product->shelf_stock }}"
                                    data-back="{{ $product->back_stock }}" data-unit="{{ $product->unit }}"
                                    data-danger="{{ $product->stock_danger_level }}"
                                    data-low="{{ $product->low_stock_threshold }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ $product->category->name }}
                                    (Shelf: {{ $product->shelf_stock }}, Back: {{ $product->back_stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4" id="product-info"
                        style="display: none;">
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm font-medium text-red-900">Shelf Stock</p>
                                <p class="text-2xl font-bold text-red-700">
                                    <span id="shelf-stock">0</span>
                                    <span id="stock-unit-1" class="text-lg">pcs</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-orange-900">Back Stock</p>
                                <p class="text-2xl font-bold text-orange-700">
                                    <span id="back-stock">0</span>
                                    <span id="stock-unit-2" class="text-lg">pcs</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 text-xs border-t border-red-200 pt-2">
                            <div>
                                <span class="text-[var(--color-text-secondary)]">Low threshold:</span>
                                <span class="font-medium" id="low-threshold">0</span>
                            </div>
                            <div>
                                <span class="text-[var(--color-text-secondary)]">Critical:</span>
                                <span class="font-medium" id="danger-threshold">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity to Remove *</label>
                        <input type="number" id="quantity" name="quantity" class="form-input"
                            value="{{ isset($prefilledData) ? $prefilledData['quantity'] : old('quantity') }}"
                            min="1" max="" required>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">
                            Maximum: <span id="max-quantity">0</span>
                        </p>
                        @error('quantity')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Remove From *</label>
                        <select id="location" name="location" class="form-select" required>
                            <option value="shelf" {{ old('location', 'shelf') == 'shelf' ? 'selected' : '' }}>Shelf
                                Stock</option>
                            <option value="back" {{ old('location') == 'back' ? 'selected' : '' }}>Back Stock
                            </option>
                        </select>
                        @error('location')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" id="reference_number" name="reference_number" class="form-input"
                            value="{{ old('reference_number') }}" placeholder="e.g., DR#12345, Transfer #TRF-001">
                        @error('reference_number')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reason" class="form-label">Reason *</label>
                        <textarea id="reason" name="reason" class="form-textarea" rows="3"
                            placeholder="Reason for stock removal (e.g., damaged, expired, transferred)" required>{{ isset($prefilledData) ? $prefilledData['reason'] : old('reason') }}</textarea>
                        @error('reason')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="btn btn-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Remove Stock
                    </button>
                    <a href="{{ route('stock.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const locationSelect = document.getElementById('location');

        // Trigger product info display on page load if product is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            if (productSelect.value) {
                productSelect.dispatchEvent(new Event('change'));
            }
        });

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productInfo = document.getElementById('product-info');

            if (this.value) {
                const shelf = parseInt(selectedOption.getAttribute('data-shelf'));
                const back = parseInt(selectedOption.getAttribute('data-back'));
                const unit = selectedOption.getAttribute('data-unit');
                const danger = selectedOption.getAttribute('data-danger');
                const low = selectedOption.getAttribute('data-low');
                const totalStock = shelf + back;

                document.getElementById('shelf-stock').textContent = shelf;
                document.getElementById('back-stock').textContent = back;
                document.getElementById('stock-unit-1').textContent = unit;
                document.getElementById('stock-unit-2').textContent = unit;
                document.getElementById('danger-threshold').textContent = danger;
                document.getElementById('low-threshold').textContent = low;

                // Set max based on location
                updateMaxQuantity();

                productInfo.style.display = 'block';
            } else {
                productInfo.style.display = 'none';
            }
        });

        locationSelect.addEventListener('change', updateMaxQuantity);

        function updateMaxQuantity() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (productSelect.value) {
                const shelf = parseInt(selectedOption.getAttribute('data-shelf'));
                const back = parseInt(selectedOption.getAttribute('data-back'));
                const maxQty = locationSelect.value === 'shelf' ? shelf : back;

                document.getElementById('max-quantity').textContent = maxQty;
                quantityInput.max = maxQty;
            }
        }
    </script>
</x-layout>
