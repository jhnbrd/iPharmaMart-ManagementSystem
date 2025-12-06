<x-layout title="New Shelf Movement" subtitle="Move products between shelf and back stock">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between w-full">
            <div>
            </div>
            <a href="{{ route('inventory.shelf-movements.index') }}" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Movements
            </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white border border-[var(--color-border-light)]">
            <div class="px-6 py-4 border-b border-[var(--color-border-light)]">
                <h3 class="text-lg font-semibold">Movement Details</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('inventory.shelf-movements.store') }}" method="POST">
                    @csrf

                    <!-- Product Selection -->
                    <div class="form-group">
                        <label for="product_id" class="form-label required">Product</label>
                        <select id="product_id" name="product_id"
                            class="form-select @error('product_id') border-red-500 @enderror" required>
                            <option value="">Select a product...</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-shelf-stock="{{ $product->shelf_stock ?? 0 }}"
                                    data-back-stock="{{ $product->back_stock ?? 0 }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Stock Levels Display -->
                    <div id="stock-levels" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-3">Current Stock Levels</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-100 rounded">
                                <div class="text-sm text-blue-600">Shelf Stock</div>
                                <div id="current-shelf-stock" class="text-xl font-bold text-blue-800">0</div>
                            </div>
                            <div class="text-center p-3 bg-gray-100 rounded">
                                <div class="text-sm text-gray-600">Back Stock</div>
                                <div id="current-back-stock" class="text-xl font-bold text-gray-800">0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Batch Selection (Optional) -->
                    <div class="form-group">
                        <label for="batch_id" class="form-label">Batch (Optional)</label>
                        <select id="batch_id" name="batch_id" class="form-select">
                            <option value="">No specific batch</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Select a specific batch if applicable</p>
                    </div>

                    <!-- Movement Type -->
                    <div class="form-group">
                        <label class="form-label required">Movement Type</label>
                        <div class="space-y-3">
                            <label
                                class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="movement_type" value="restock" class="form-radio"
                                    {{ old('movement_type') == 'restock' ? 'checked' : '' }} required>
                                <div class="ml-3">
                                    <div class="font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Restock (Back to Shelf)
                                    </div>
                                    <div class="text-sm text-gray-500">Move products from back stock to shelf</div>
                                </div>
                            </label>
                            <label
                                class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="movement_type" value="destock" class="form-radio"
                                    {{ old('movement_type') == 'destock' ? 'checked' : '' }} required>
                                <div class="ml-3">
                                    <div class="font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Destock (Shelf to Back)
                                    </div>
                                    <div class="text-sm text-gray-500">Move products from shelf to back stock</div>
                                </div>
                            </label>
                        </div>
                        @error('movement_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity" class="form-label required">Quantity</label>
                        <input type="number" id="quantity" name="quantity"
                            class="form-input @error('quantity') border-red-500 @enderror"
                            value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Number of units to move</p>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="3" class="form-input @error('remarks') border-red-500 @enderror"
                            placeholder="Optional notes about this movement...">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Record Movement
                        </button>
                        <a href="{{ route('inventory.shelf-movements.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('product_id');
        const stockLevels = document.getElementById('stock-levels');
        const shelfStockDisplay = document.getElementById('current-shelf-stock');
        const backStockDisplay = document.getElementById('current-back-stock');
        const batchSelect = document.getElementById('batch_id');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                // Show stock levels
                const shelfStock = selectedOption.dataset.shelfStock || 0;
                const backStock = selectedOption.dataset.backStock || 0;

                shelfStockDisplay.textContent = parseInt(shelfStock).toLocaleString();
                backStockDisplay.textContent = parseInt(backStock).toLocaleString();
                stockLevels.classList.remove('hidden');

                // Load batches for selected product
                loadProductBatches(selectedOption.value);
            } else {
                stockLevels.classList.add('hidden');
                clearBatches();
            }
        });

        function loadProductBatches(productId) {
            // You can implement AJAX call here to load batches for the selected product
            // For now, we'll clear the batches
            clearBatches();
        }

        function clearBatches() {
            batchSelect.innerHTML = '<option value="">No specific batch</option>';
        }

        // Validate quantity based on movement type and available stock
        const quantityInput = document.getElementById('quantity');
        const movementTypeInputs = document.querySelectorAll('input[name="movement_type"]');

        function validateQuantity() {
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            const quantity = parseInt(quantityInput.value) || 0;
            const selectedMovementType = document.querySelector('input[name="movement_type"]:checked')?.value;

            if (selectedProduct.value && selectedMovementType && quantity > 0) {
                const shelfStock = parseInt(selectedProduct.dataset.shelfStock) || 0;
                const backStock = parseInt(selectedProduct.dataset.backStock) || 0;

                if (selectedMovementType === 'restock' && quantity > backStock) {
                    alert(`Cannot restock ${quantity} units. Only ${backStock} units available in back stock.`);
                    quantityInput.value = Math.min(quantity, backStock);
                } else if (selectedMovementType === 'destock' && quantity > shelfStock) {
                    alert(`Cannot destock ${quantity} units. Only ${shelfStock} units available on shelf.`);
                    quantityInput.value = Math.min(quantity, shelfStock);
                }
            }
        }

        quantityInput.addEventListener('change', validateQuantity);
        movementTypeInputs.forEach(input => {
            input.addEventListener('change', validateQuantity);
        });
    </script>
</x-layout>
