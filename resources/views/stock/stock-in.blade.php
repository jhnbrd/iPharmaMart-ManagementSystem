<x-layout title="Stock In">
    <div class="max-w-2xl">
        <div class="page-header">
            <h1 class="page-title">Stock In</h1>
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <form action="{{ route('stock.in.process') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div class="form-group">
                        <label for="product_id" class="form-label">Product *</label>
                        <select id="product_id" name="product_id" class="form-select" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-shelf="{{ $product->shelf_stock }}"
                                    data-back="{{ $product->back_stock }}" data-unit="{{ $product->unit }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ $product->category->name }}
                                    (Shelf: {{ $product->shelf_stock }}, Back: {{ $product->back_stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4" id="product-info"
                        style="display: none;">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-blue-900">Shelf Stock</p>
                                <p class="text-2xl font-bold text-blue-700">
                                    <span id="shelf-stock">0</span>
                                    <span id="stock-unit-1" class="text-lg">pcs</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-900">Back Stock</p>
                                <p class="text-2xl font-bold text-green-700">
                                    <span id="back-stock">0</span>
                                    <span id="stock-unit-2" class="text-lg">pcs</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity to Add *</label>
                        <input type="number" id="quantity" name="quantity" class="form-input"
                            value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Stock Location *</label>
                        <select id="location" name="location" class="form-select" required>
                            <option value="shelf" {{ old('location') == 'shelf' ? 'selected' : '' }}>On Shelf
                                (Display)</option>
                            <option value="back" {{ old('location', 'back') == 'back' ? 'selected' : '' }}>Back Stock
                                (Storage)</option>
                        </select>
                        @error('location')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-input"
                            value="{{ old('expiry_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">Optional - for tracking batch expiry
                        </p>
                        @error('expiry_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="manufacture_date" class="form-label">Manufacture Date</label>
                        <input type="date" id="manufacture_date" name="manufacture_date" class="form-input"
                            value="{{ old('manufacture_date') }}" max="{{ date('Y-m-d') }}">
                        @error('manufacture_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" id="reference_number" name="reference_number" class="form-input"
                            value="{{ old('reference_number') }}" placeholder="e.g., PO#12345, Invoice #INV-001">
                        @error('reference_number')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reason" class="form-label">Reason/Notes</label>
                        <textarea id="reason" name="reason" class="form-textarea" rows="3"
                            placeholder="Reason for stock in (e.g., Supplier delivery, Returns)">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="btn btn-success">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Add Stock
                    </button>
                    <a href="{{ route('stock.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productInfo = document.getElementById('product-info');

            if (this.value) {
                const shelf = selectedOption.getAttribute('data-shelf');
                const back = selectedOption.getAttribute('data-back');
                const unit = selectedOption.getAttribute('data-unit');

                document.getElementById('shelf-stock').textContent = shelf;
                document.getElementById('back-stock').textContent = back;
                document.getElementById('stock-unit-1').textContent = unit;
                document.getElementById('stock-unit-2').textContent = unit;
                productInfo.style.display = 'block';
            } else {
                productInfo.style.display = 'none';
            }
        });
    </script>
</x-layout>
