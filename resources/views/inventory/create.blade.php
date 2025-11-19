<x-layout title="Add New Product">
    <div class="max-w-3xl">
        <div class="page-header">
            <h1 class="page-title">Add New Product</h1>
        </div>

        <div class="bg-white p-8 shadow-sm border border-[var(--color-border-light)]">
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group md:col-span-2">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="product_type" class="form-label">Product Type *</label>
                        <select id="product_type" name="product_type" class="form-select" required>
                            <option value="">Select Product Type</option>
                            <option value="pharmacy" {{ old('product_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy
                            </option>
                            <option value="mini_mart" {{ old('product_type') == 'mini_mart' ? 'selected' : '' }}>Mini
                                Mart</option>
                        </select>
                        @error('product_type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" id="barcode" name="barcode" class="form-input"
                            value="{{ old('barcode') }}" placeholder="Optional">
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
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                            <option value="pcs" {{ old('unit', 'pcs') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)
                            </option>
                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="bottle" {{ old('unit') == 'bottle' ? 'selected' : '' }}>Bottle</option>
                            <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                        </select>
                        @error('unit')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit_quantity" class="form-label">Unit Quantity *</label>
                        <input type="number" id="unit_quantity" name="unit_quantity" class="form-input"
                            value="{{ old('unit_quantity', 1) }}" min="1" required>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">Quantity per unit (e.g., 12 for a
                            dozen)</p>
                        @error('unit_quantity')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock" class="form-label">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" class="form-input"
                            value="{{ old('stock', 0) }}" min="0" required>
                        @error('stock')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="low_stock_threshold" class="form-label">Low Stock Alert Level *</label>
                        <input type="number" id="low_stock_threshold" name="low_stock_threshold" class="form-input"
                            value="{{ old('low_stock_threshold', 50) }}" min="0" required>
                        @error('low_stock_threshold')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock_danger_level" class="form-label">Critical Stock Level *</label>
                        <input type="number" id="stock_danger_level" name="stock_danger_level" class="form-input"
                            value="{{ old('stock_danger_level', 10) }}" min="0" required>
                        <p class="text-xs text-[var(--color-text-secondary)] mt-1">Critical threshold (below low stock)
                        </p>
                        @error('stock_danger_level')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price" class="form-label">Price (₱) *</label>
                        <input type="number" id="price" name="price" class="form-input"
                            value="{{ old('price') }}" step="0.01" min="0" required>
                        @error('price')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-input"
                            value="{{ old('expiry_date') }}">
                        @error('expiry_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea">{{ old('description') }}</textarea>
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
                        Save Product
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
