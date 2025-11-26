<x-layout title="Restock Shelf">
    <div class="max-w-2xl">
        <div class="page-header">
            <h1 class="page-title">Restock Shelf</h1>
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <form action="{{ route('stock.restock.process') }}" method="POST">
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
                                    (Back: {{ $product->back_stock }} {{ $product->unit }})
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
                        <label for="quantity" class="form-label">Quantity to Move to Shelf *</label>
                        <input type="number" id="quantity" name="quantity" class="form-input"
                            value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-textarea" rows="3"
                            placeholder="Optional notes about this shelf restocking">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Move to Shelf
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

                // Set max quantity to back stock
                document.getElementById('quantity').setAttribute('max', back);
            } else {
                productInfo.style.display = 'none';
            }
        });
    </script>
</x-layout>
