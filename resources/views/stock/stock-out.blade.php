<x-layout title="Stock Out">
    <div class="max-w-2xl">
        <div class="page-header">
            <h1 class="page-title">Stock Out</h1>
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
                                <option value="{{ $product->id }}" data-stock="{{ $product->stock }}"
                                    data-unit="{{ $product->unit }}" data-danger="{{ $product->stock_danger_level }}"
                                    data-low="{{ $product->low_stock_threshold }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ $product->category->name }}
                                    (Stock: {{ $product->stock }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4" id="product-info"
                        style="display: none;">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-red-900">Current Stock</p>
                                    <p class="text-2xl font-bold text-red-700">
                                        <span id="current-stock">0</span>
                                        <span id="stock-unit" class="text-lg">pcs</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-4 text-xs">
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
                    </div>

                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity to Remove *</label>
                        <input type="number" id="quantity" name="quantity" class="form-input"
                            value="{{ old('quantity') }}" min="1" max="" required>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">
                            Maximum: <span id="max-quantity">0</span>
                        </p>
                        @error('quantity')
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
                        <label for="remarks" class="form-label">Remarks *</label>
                        <textarea id="remarks" name="remarks" class="form-textarea" rows="3"
                            placeholder="Reason for stock removal (e.g., damaged, expired, transferred)" required>{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="btn btn-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productInfo = document.getElementById('product-info');

            if (this.value) {
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                const unit = selectedOption.getAttribute('data-unit');
                const danger = selectedOption.getAttribute('data-danger');
                const low = selectedOption.getAttribute('data-low');

                document.getElementById('current-stock').textContent = stock;
                document.getElementById('stock-unit').textContent = unit;
                document.getElementById('danger-threshold').textContent = danger;
                document.getElementById('low-threshold').textContent = low;
                document.getElementById('max-quantity').textContent = stock;

                quantityInput.max = stock;
                productInfo.style.display = 'block';
            } else {
                productInfo.style.display = 'none';
            }
        });
    </script>
</x-layout>
