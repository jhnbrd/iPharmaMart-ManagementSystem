<x-layout title="Edit Product">
    <div class="max-w-3xl">
        <div class="page-header">
            <h1 class="page-title">Edit Product</h1>
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <form action="{{ route('inventory.update', $inventory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group md:col-span-2">
                        <label for="product_type" class="form-label">Product Type *</label>
                        <select id="product_type" name="product_type" class="form-select" required
                            onchange="toggleProductFields()">
                            <option value="">Select Product Type</option>
                            <option value="pharmacy"
                                {{ old('product_type', $inventory->product_type) == 'pharmacy' ? 'selected' : '' }}>
                                Pharmacy</option>
                            <option value="mini_mart"
                                {{ old('product_type', $inventory->product_type) == 'mini_mart' ? 'selected' : '' }}>
                                Mini
                                Mart</option>
                        </select>
                        @error('product_type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name', $inventory->name) }}" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="brand_name" class="form-label">Brand Name *</label>
                        <input type="text" id="brand_name" name="brand_name" class="form-input"
                            value="{{ old('brand_name', $inventory->brand_name) }}" required>
                        @error('brand_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2" id="generic_name_field">
                        <label for="generic_name" class="form-label">Generic Name <span
                                id="generic_required">*</span></label>
                        <input type="text" id="generic_name" name="generic_name" class="form-input"
                            value="{{ old('generic_name', $inventory->generic_name) }}">
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">Required for pharmacy products</p>
                        @error('generic_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" id="barcode" name="barcode" class="form-input"
                            value="{{ old('barcode', $inventory->barcode) }}" placeholder="Optional">
                        @error('barcode')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="form-label">Category *</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="supplier_id" class="form-label">Supplier *</label>
                        <select id="supplier_id" name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $inventory->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit" class="form-label">Unit *</label>
                        <select id="unit" name="unit" class="form-select" required>
                            <option value="pcs" {{ old('unit', $inventory->unit) == 'pcs' ? 'selected' : '' }}>
                                Pieces
                                (pcs)</option>
                            <option value="box" {{ old('unit', $inventory->unit) == 'box' ? 'selected' : '' }}>Box
                            </option>
                            <option value="bottle" {{ old('unit', $inventory->unit) == 'bottle' ? 'selected' : '' }}>
                                Bottle</option>
                            <option value="pack" {{ old('unit', $inventory->unit) == 'pack' ? 'selected' : '' }}>Pack
                            </option>
                            <option value="kg" {{ old('unit', $inventory->unit) == 'kg' ? 'selected' : '' }}>
                                Kilogram
                                (kg)</option>
                            <option value="liter" {{ old('unit', $inventory->unit) == 'liter' ? 'selected' : '' }}>
                                Liter
                            </option>
                        </select>
                        @error('unit')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit_quantity" class="form-label">Unit Quantity *</label>
                        <input type="number" id="unit_quantity" name="unit_quantity" class="form-input"
                            value="{{ old('unit_quantity', $inventory->unit_quantity) }}" min="1" required>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">Quantity per unit (e.g., 12 for a
                            dozen)</p>
                        @error('unit_quantity')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Current Stock Quantity</label>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="form-input bg-blue-50 cursor-not-allowed text-center">
                                <div class="text-xs text-[var(--color-text-secondary)]">Shelf</div>
                                <div class="font-semibold text-lg text-blue-700">{{ $inventory->shelf_stock }}</div>
                            </div>
                            <div class="form-input bg-green-50 cursor-not-allowed text-center">
                                <div class="text-xs text-[var(--color-text-secondary)]">Back</div>
                                <div class="font-semibold text-lg text-green-700">{{ $inventory->back_stock }}</div>
                            </div>
                            <div class="form-input bg-gray-100 cursor-not-allowed text-center">
                                <div class="text-xs text-[var(--color-text-secondary)]">Total</div>
                                <div class="font-semibold text-lg">{{ $inventory->total_stock }}
                                    {{ $inventory->unit }}</div>
                            </div>
                        </div>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">
                            Stock can only be modified through the Stock In/Out module
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="low_stock_threshold" class="form-label">Low Stock Alert Level *</label>
                        <input type="number" id="low_stock_threshold" name="low_stock_threshold" class="form-input"
                            value="{{ old('low_stock_threshold', $inventory->low_stock_threshold) }}" min="0"
                            required>
                        @error('low_stock_threshold')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock_danger_level" class="form-label">Critical Stock Level *</label>
                        <input type="number" id="stock_danger_level" name="stock_danger_level" class="form-input"
                            value="{{ old('stock_danger_level', $inventory->stock_danger_level) }}" min="0"
                            required>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">Critical threshold (below low stock)
                        </p>
                        @error('stock_danger_level')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price" class="form-label">Price (₱) *</label>
                        <input type="number" id="price" name="price" class="form-input"
                            value="{{ old('price', $inventory->price) }}" step="0.01" min="0" required>
                        @error('price')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-input"
                            value="{{ old('expiry_date', $inventory->expiry_date?->format('Y-m-d')) }}">
                        @error('expiry_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea">{{ old('description', $inventory->description) }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Update Product
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleProductFields() {
            const productType = document.getElementById('product_type').value;
            const genericNameField = document.getElementById('generic_name_field');
            const genericNameInput = document.getElementById('generic_name');

            if (productType === 'pharmacy') {
                genericNameField.style.display = 'block';
                genericNameInput.required = true;
            } else {
                genericNameField.style.display = 'none';
                genericNameInput.required = false;
                if (!genericNameInput.value) {
                    genericNameInput.value = '';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleProductFields();
        });
    </script>
</x-layout>
