<x-pos-layout title="Point of Sale">
    <style>
        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9998;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 400px;
        }

        .toast {
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
            min-width: 300px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast.hiding {
            animation: slideOut 0.3s ease-out;
        }

        .toast-error {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #991b1b;
        }

        .toast-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        .toast-warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .toast-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }

        .toast-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
        }

        .toast-content {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .toast-close {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .toast-close:hover {
            opacity: 1;
        }

        .pos-grid {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 1.5rem;
            height: calc(100vh - 140px);
        }

        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product-search {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .products-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .pagination-wrapper {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .product-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 1rem;
            align-items: center;
        }

        .product-item:hover {
            background: #f9fafb;
            border-color: var(--color-brand-green);
            box-shadow: 0 2px 8px rgba(44, 99, 86, 0.15);
        }

        .product-item.selected {
            background: #ecfdf5;
            border-color: var(--color-brand-green);
            box-shadow: 0 0 0 2px rgba(44, 99, 86, 0.1);
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
            min-width: 0;
        }

        .product-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-category {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .product-price {
            font-weight: 700;
            font-size: 1rem;
            color: var(--color-brand-green);
            white-space: nowrap;
        }

        .product-stock {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            white-space: nowrap;
            font-weight: 500;
        }

        .stock-good {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-low {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-out {
            background: #fee2e2;
            color: #991b1b;
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .customer-section {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-section {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .cart-header {
            padding: 1rem 1rem 0.5rem;
            flex-shrink: 0;
        }

        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 0 1rem;
        }

        .cart-footer {
            flex-shrink: 0;
            border-top: 2px solid #e5e7eb;
            background: white;
        }

        .cart-table {
            width: 100%;
            font-size: 0.875rem;
        }

        .cart-table thead {
            background: var(--color-brand-green-dark);
            color: white;
        }

        .cart-table th {
            padding: 0.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .cart-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .total-section {
            background: var(--color-brand-green-dark);
            color: white;
            padding: 1.25rem;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #cbd5e0;
            border-radius: 0.25rem;
            padding: 0.25rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .qty-input:focus {
            outline: none;
            border-color: var(--color-brand-green);
            box-shadow: 0 0 0 2px rgba(44, 99, 86, 0.1);
        }

        .qty-btn {
            width: 24px;
            height: 24px;
            border: 1px solid #cbd5e0;
            background: white;
            border-radius: 0.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background: var(--color-brand-green);
            color: white;
            border-color: var(--color-brand-green);
        }

        .action-buttons {
            display: block;
        }
    </style>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="toast-container"></div>

    <div class="pos-grid">
        <!-- Left Panel: Products -->
        <div class="left-panel">
            <!-- Search Bar -->
            <div class="product-search">
                <form id="filterForm" class="flex gap-3" action="javascript:void(0);">
                    <div class="relative" style="flex: 2;">
                        <input type="text" name="search" id="searchProduct"
                            placeholder="Search products by name or ID..." class="form-input pl-10 w-full text-base"
                            value="{{ request('search') }}" autocomplete="off">
                        {{-- <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg> --}}
                    </div>
                    <select name="product_type" id="productTypeFilter" class="form-select"
                        style="flex: 1; min-width: 140px;">
                        <option value="">All Types</option>
                        <option value="pharmacy" {{ request('product_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy
                        </option>
                        <option value="mini_mart" {{ request('product_type') == 'mini_mart' ? 'selected' : '' }}>Mini
                            Mart</option>
                    </select>
                    <select name="category" id="categoryFilter" class="form-select" style="flex: 1; min-width: 150px;">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Search button removed: realtime filtering handles input and selects -->
                </form>
            </div>

            <!-- Products List -->
            <div class="products-container">
                <div id="productsGrid" class="product-list">
                    @foreach ($products as $product)
                        @php
                            $stockStatus = 'stock-good';
                            $stockText = 'In Stock';
                            if ($product->total_stock <= 0) {
                                $stockStatus = 'stock-out';
                                $stockText = 'Out of Stock';
                            } elseif ($product->total_stock <= 10) {
                                $stockStatus = 'stock-low';
                                $stockText = 'Low Stock';
                            }
                            $productFullName =
                                ($product->brand_name ? $product->brand_name . ' ' : '') . $product->name;
                            $categoryName = $product->category->name;
                        @endphp
                        <div class="product-item" data-category="{{ $product->category_id }}"
                            data-name="{{ strtolower($productFullName) }}" data-id="{{ $product->id }}"
                            data-type="{{ $product->product_type }}" data-product-name="{{ $productFullName }}"
                            data-price="{{ $product->price }}" data-stock="{{ $product->total_stock }}"
                            data-category-name="{{ $categoryName }}">
                            <div class="product-info">
                                <div class="product-name"
                                    title="{{ ($product->brand_name ? $product->brand_name . ' ' : '') . $product->name }}">
                                    @if ($product->brand_name)
                                        <span class="font-bold">{{ $product->brand_name }}</span>
                                    @endif
                                    {{ $product->name }}
                                </div>
                                <div class="product-category">
                                    @if ($product->product_type === 'pharmacy' && $product->generic_name)
                                        <span class="text-xs text-blue-600 italic">{{ $product->generic_name }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                    @endif
                                    {{ $product->category->name }}
                                    <span class="text-xs text-gray-400">•
                                        {{ ucfirst(str_replace('_', ' ', $product->product_type)) }}</span>
                                </div>
                                @if ($product->batches && $product->batches->count() > 0)
                                    @php
                                        $earliestBatch = $product->batches->first();
                                        $daysUntilExpiry = now()->diffInDays($earliestBatch->expiry_date, false);
                                    @endphp
                                    <div class="text-xs mt-1">
                                        <span class="font-semibold"
                                            style="color: {{ $daysUntilExpiry <= 30 ? '#dc2626' : '#6b7280' }}">
                                            📦 Batch: {{ $earliestBatch->batch_number }}
                                        </span>
                                        <span class="text-gray-400">•</span>
                                        <span style="color: {{ $daysUntilExpiry <= 30 ? '#dc2626' : '#6b7280' }}">
                                            Exp: {{ $earliestBatch->expiry_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                            <div class="product-stock {{ $stockStatus }}">
                                {{ $stockText }}: {{ $product->total_stock }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="pagination-wrapper" id="paginationWrapper">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Panel: Cart & Customer -->
        <div class="right-panel">

            <!-- Cart Section -->
            <div class="cart-section">
                <div class="cart-header">
                    <h3 class="font-semibold text-gray-900">Order Items</h3>
                </div>

                <div class="cart-body">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="width: 100px;">Qty</th>
                                <th style="width: 80px; text-align: right;">Price</th>
                                <th style="width: 80px; text-align: right;">Total</th>
                                <th style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-8">No items added</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="cart-footer">
                    <div class="total-section">
                        <div class="flex justify-between text-sm mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span>VATable (12%):</span>
                            <span id="tax">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-2xl font-bold border-t border-white/20 pt-2 mt-2">
                            <span>TOTAL:</span>
                            <span id="total">₱0.00</span>
                        </div>
                        <div class="text-right text-sm mt-1 opacity-80">
                            Items: <span id="itemCount">0</span>
                        </div>
                    </div>

                    <div class="action-buttons" style="padding: 1rem; margin-top: 0;">
                        <button onclick="processCheckout()" id="checkoutBtn" class="btn btn-primary w-full" disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Modal -->
    <div id="customerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 m-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Add Customer</h3>
                <button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="customerForm" onsubmit="saveCustomer(event)">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Name *</label>
                        <input type="text" id="customerName" class="form-input" required autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Phone Number *</label>
                        <input type="text" id="customerPhone" class="form-input" placeholder="+63 9XX XXX XXXX"
                            required autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Address (Optional)</label>
                        <textarea id="customerAddress" class="form-input" rows="3"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeCustomerModal()"
                        class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1">Save Customer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-5 m-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Complete Payment</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Left Column -->
                <div class="space-y-3">
                    <!-- Discount Section (moved above customer selection) -->
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <label class="form-label text-xs mb-2">Discount (Optional)</label>
                        <div class="space-y-2">
                            <label
                                class="flex items-center p-2 bg-white rounded border cursor-pointer hover:border-blue-500 transition">
                                <input type="checkbox" id="seniorDiscount" class="mr-2"
                                    onchange="handleDiscountChange()">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm">Senior Citizen (20%)</div>
                                    <input type="text" id="seniorIdNumber" class="form-input text-xs mt-1"
                                        placeholder="ID Number" disabled onclick="event.stopPropagation()"
                                        autocomplete="off" oninput="searchDiscountId('senior')">
                                    <div id="seniorIdDropdown"
                                        class="hidden bg-white border border-gray-200 rounded mt-1 max-h-40 overflow-y-auto text-sm">
                                    </div>
                                </div>
                            </label>
                            <label
                                class="flex items-center p-2 bg-white rounded border cursor-pointer hover:border-blue-500 transition">
                                <input type="checkbox" id="pwdDiscount" class="mr-2"
                                    onchange="handleDiscountChange()">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm">PWD (20%)</div>
                                    <input type="text" id="pwdIdNumber" class="form-input text-xs mt-1"
                                        placeholder="PWD ID Number" disabled onclick="event.stopPropagation()"
                                        autocomplete="off" oninput="searchDiscountId('pwd')">
                                    <div id="pwdIdDropdown"
                                        class="hidden bg-white border border-gray-200 rounded mt-1 max-h-40 overflow-y-auto text-sm">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Customer Selection Section -->
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <label class="form-label text-xs mb-2">Customer Information</label>
                        <div class="space-y-2">
                            <!-- Walk-in Option -->
                            <label
                                class="flex items-start p-2 bg-white rounded border-2 cursor-pointer hover:border-green-500 transition">
                                <input type="radio" name="customerOption" value="walkin" class="mt-1 mr-2" checked
                                    onchange="selectCustomerOption('walkin')">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-sm">Walk-in Customer</div>
                                    <div class="text-xs text-gray-500">No customer information</div>
                                </div>
                            </label>

                            <!-- Existing Customer Option -->
                            <label
                                class="flex items-start p-2 bg-white rounded border-2 cursor-pointer hover:border-green-500 transition">
                                <input type="radio" name="customerOption" value="existing" class="mt-1 mr-2"
                                    onchange="selectCustomerOption('existing')">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-sm">Existing Customer</div>
                                    <div class="mt-1 relative">
                                        <input type="text" id="customerSearchInput" class="form-input text-xs"
                                            placeholder="Search customer by name..." disabled
                                            oninput="filterCustomers()" onclick="event.stopPropagation()"
                                            autocomplete="off">
                                        <div id="customerDropdown"
                                            class="hidden absolute z-10 w-full bg-white border border-gray-300 rounded mt-1 max-h-48 overflow-y-auto shadow-lg">
                                            @foreach ($customers as $customer)
                                                <div class="customer-item px-3 py-2 hover:bg-green-50 cursor-pointer text-xs border-b"
                                                    data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                    data-phone="{{ $customer->phone }}"
                                                    data-is-senior="{{ $customer->is_senior_citizen ? '1' : '0' }}"
                                                    data-is-pwd="{{ $customer->is_pwd ? '1' : '0' }}"
                                                    data-senior-id="{{ $customer->senior_citizen_id ?? '' }}"
                                                    data-pwd-id="{{ $customer->pwd_id ?? '' }}"
                                                    onclick="selectExistingCustomer(this)">
                                                    <div class="font-semibold">{{ $customer->name }}</div>
                                                    <div class="text-gray-500">{{ $customer->phone }}</div>
                                                    @if ($customer->is_senior_citizen)
                                                        <div class="text-blue-600 text-xs">Senior Citizen:
                                                            {{ $customer->senior_citizen_id }}</div>
                                                    @endif
                                                    @if ($customer->is_pwd)
                                                        <div class="text-blue-600 text-xs">PWD:
                                                            {{ $customer->pwd_id }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- New Customer Option -->
                            <label
                                class="flex items-start p-2 bg-white rounded border-2 cursor-pointer hover:border-green-500 transition">
                                <input type="radio" name="customerOption" value="new" class="mt-1 mr-2"
                                    onchange="selectCustomerOption('new')">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-sm">New Customer</div>
                                    <div id="newCustomerForm" class="mt-2 space-y-1.5" style="display: none;">
                                        <input type="text" id="newCustomerName" class="form-input text-xs"
                                            placeholder="Customer Name" disabled onclick="event.stopPropagation()"
                                            autocomplete="off">
                                        <input type="text" id="newCustomerPhone" class="form-input text-xs"
                                            placeholder="Phone Number" disabled onclick="event.stopPropagation()"
                                            autocomplete="off">
                                        <input type="text" id="newCustomerAddress" class="form-input text-xs"
                                            placeholder="Address (optional)" disabled
                                            onclick="event.stopPropagation()" autocomplete="off">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="form-label text-xs">Payment Method *</label>
                        <select id="paymentMethod" class="form-select text-sm"
                            onchange="handlePaymentMethodChange()">
                            <option value="cash">💵 Cash</option>
                            <option value="gcash">📱 GCash</option>
                            <option value="card">💳 Card</option>
                        </select>
                    </div>

                    <!-- Reference Number (for GCash/Card) -->
                    <div id="referenceNumberDiv" style="display: none;">
                        <label class="form-label text-xs">Reference Number *</label>
                        <input type="text" id="referenceNumber" class="form-input text-sm"
                            placeholder="Enter transaction reference" autocomplete="off">
                    </div>

                    <!-- Card Payment Details (for Card only) -->
                    <div id="cardDetailsDiv" style="display: none;" class="space-y-2">
                        <div>
                            <label class="form-label text-xs">Bank Name</label>
                            <input type="text" id="cardBankName" class="form-input text-sm"
                                placeholder="e.g., BDO, BPI, Metrobank" autocomplete="off">
                        </div>
                        <div>
                            <label class="form-label text-xs">Cardholder Name</label>
                            <input type="text" id="cardHolderName" class="form-input text-sm"
                                placeholder="Name on card" autocomplete="off">
                        </div>
                        <div>
                            <label class="form-label text-xs">Last 4 Digits</label>
                            <input type="text" id="cardLastFour" class="form-input text-sm" placeholder="XXXX"
                                maxlength="4" pattern="[0-9]{4}" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-3">
                    <!-- Order Summary -->
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between text-xs mb-1.5">
                            <span>Subtotal:</span>
                            <span id="modalSubtotal" class="font-semibold">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-xs mb-1.5">
                            <span>VAT (12%):</span>
                            <span id="modalTax" class="font-semibold">₱0.00</span>
                        </div>
                        <div id="discountRow" class="flex justify-between text-xs mb-1.5 text-blue-600"
                            style="display: none;">
                            <span id="discountLabel">Discount:</span>
                            <span id="modalDiscount" class="font-semibold">-₱0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2 mt-2">
                            <span>TOTAL:</span>
                            <span id="modalTotal" class="text-green-600">₱0.00</span>
                        </div>
                    </div>

                    <!-- Amount Paid -->
                    <div>
                        <label class="form-label text-xs">Amount Paid *</label>
                        <input type="number" id="paidAmount" class="form-input text-base font-semibold"
                            placeholder="0.00" step="0.01" min="0" oninput="calculateChange()">
                    </div>

                    <!-- Change -->
                    <div class="p-3 bg-green-50 rounded-lg border-2 border-green-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700">Change:</span>
                            <span id="changeAmount" class="text-2xl font-bold text-green-600">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closePaymentModal()" class="btn btn-secondary flex-1">
                    Cancel
                </button>
                <button type="button" onclick="confirmPayment()" id="confirmPaymentBtn"
                    class="btn btn-primary flex-1">
                    Confirm & Print Receipt
                </button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 m-4">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-green-600 mb-2">✓ Transaction Complete!</h2>
                <p class="text-gray-600">Receipt generated successfully</p>
            </div>

            <!-- Receipt Content -->
            <div id="receiptContent" class="bg-white p-6 border-2 border-gray-200 rounded-lg mb-6">
                <!-- Receipt will be dynamically inserted here -->
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="printReceipt()" class="btn btn-primary flex-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Receipt
                </button>
                <button type="button" onclick="newTransaction()" class="btn btn-success flex-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Order
                </button>
            </div>
        </div>
    </div>

    <!-- Void Item Authorization Modal -->
    <div id="voidItemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 m-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Admin Authorization Required</h3>
                <button onclick="closeVoidItemModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">
                    You are about to remove the item: <span id="voidItemName" class="font-bold text-gray-900"></span>
                </p>
                <p class="text-sm text-red-600">
                    ⚠️ This action requires admin authorization.
                </p>
            </div>
            <form id="voidItemForm" onsubmit="processVoidItem(event)">
                <input type="hidden" id="voidItemId">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Admin Username *</label>
                        <input type="text" id="voidAdminUsername" class="form-input" required autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Admin Password *</label>
                        <input type="password" id="voidAdminPassword" class="form-input" required
                            autocomplete="new-password">
                    </div>
                    <div>
                        <label class="form-label">Reason for Void *</label>
                        <textarea id="voidReason" class="form-input" rows="2" required
                            placeholder="Enter reason for removing this item..."></textarea>
                    </div>
                    <div id="voidAuthError"
                        class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeVoidItemModal()" class="btn btn-secondary flex-1">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger flex-1">
                        Void Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Void Sale Authorization Modal -->
    <div id="voidSaleModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 m-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-red-700">⚠️ Void Entire Sale</h3>
                <button onclick="closeVoidSaleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4 bg-red-50 border-l-4 border-red-600 p-3 rounded">
                <p class="text-sm text-red-800 font-semibold mb-2">
                    You are about to void the entire sale transaction!
                </p>
                <p class="text-xs text-red-700">
                    This will clear all items from the cart. This action requires admin authorization.
                </p>
            </div>
            <form id="voidSaleForm" onsubmit="processVoidSale(event)">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Admin Username *</label>
                        <input type="text" id="voidSaleAdminUsername" class="form-input" required
                            autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Admin Password *</label>
                        <input type="password" id="voidSaleAdminPassword" class="form-input" required
                            autocomplete="new-password">
                    </div>
                    <div>
                        <label class="form-label">Reason for Voiding Sale *</label>
                        <textarea id="voidSaleReason" class="form-input" rows="3" required
                            placeholder="Enter reason for voiding entire sale..."></textarea>
                    </div>
                    <div id="voidSaleAuthError"
                        class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:18px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <!-- Require explicit confirmation before enabling the destructive action -->
                        <label style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:#6b7280;">
                            <input type="checkbox" id="confirmVoidSaleCheck"
                                onchange="document.getElementById('voidSaleSubmitBtn').disabled = !this.checked;">
                            <span>I understand this will void the entire sale</span>
                        </label>
                    </div>

                    <div style="display:flex;gap:8px;align-items:center;">
                        <button type="button" onclick="closeVoidSaleModal()" class="btn btn-secondary">
                            Cancel
                        </button>

                        <button type="submit" id="voidSaleSubmitBtn" class="" disabled
                            style="display:inline-flex;align-items:center;gap:8px;justify-content:center;min-width:130px;padding:10px 14px;border-radius:8px;background:transparent;border:1px solid #dc2626;color:#dc2626;cursor:pointer;font-weight:700;font-size:0.98rem;">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="#dc2626" viewBox="0 0 24 24"
                                style="width:18px;height:18px;vertical-align:middle;flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Void Sale
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let cart = [];
        let currentCustomer = null;

        // Toast Notification System
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            const icons = {
                error: '<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
                success: '<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                warning: '<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
                info: '<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
            };

            toast.innerHTML = `
                ${icons[type]}
                <div class="toast-content">${message}</div>
                <svg class="toast-close" fill="currentColor" viewBox="0 0 20 20" onclick="this.parentElement.remove()">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            `;

            container.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Helper function to add to cart from data attributes (avoids quote escaping issues)
        window.addToCartFromData = function(element) {
            const productId = parseInt(element.getAttribute('data-id'));
            const productName = element.getAttribute('data-product-name');
            const price = parseFloat(element.getAttribute('data-price'));
            const stock = parseInt(element.getAttribute('data-stock'));
            const category = element.getAttribute('data-category-name');

            window.addToCart(productId, productName, price, stock, category);
        };

        window.addToCart = function(productId, productName, price, stock, category) {
            // Validate stock availability
            if (stock <= 0) {
                showToast(`Cannot add "${productName}" - Out of stock!`, 'error');
                return;
            }

            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                    showToast(`Updated quantity for "${productName}" (${existingItem.quantity})`, 'success');
                } else {
                    showToast(`Cannot add more "${productName}" - Only ${stock} available in stock!`, 'error');
                    return;
                }
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: price,
                    quantity: 1,
                    stock: stock,
                    category: category
                });
                showToast(`Added "${productName}" to cart`, 'success');
            }

            updateCart();
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                const newQuantity = item.quantity + change;
                if (newQuantity <= 0) {
                    showToast('Quantity cannot be less than 1. Use the remove button to delete items.', 'warning');
                    return;
                }
                if (newQuantity > item.stock) {
                    showToast(`Cannot exceed available stock (${item.stock} units)`, 'error');
                    return;
                }
                item.quantity = newQuantity;
                updateCart();
            }
        }

        function setQuantity(productId, value, maxStock) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                let newQuantity = parseInt(value) || 1;

                if (newQuantity < 1) {
                    newQuantity = 1;
                    showToast('Quantity cannot be less than 1', 'warning');
                }

                if (newQuantity > maxStock) {
                    newQuantity = maxStock;
                    showToast(`Cannot exceed available stock (${maxStock} units)`, 'error');
                }

                item.quantity = newQuantity;
                updateCart();
            }
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Void Item Functions
        function openVoidItemModalFromRow(button) {
            const row = button.closest('tr');
            const productId = parseInt(row.getAttribute('data-item-id'));
            const productName = row.getAttribute('data-item-name');
            openVoidItemModal(productId, productName);
        }

        function openVoidItemModal(productId, productName) {
            document.getElementById('voidItemId').value = productId;
            document.getElementById('voidItemName').textContent = productName;
            document.getElementById('voidItemForm').reset();
            document.getElementById('voidItemId').value = productId; // Reset clears this, so set again
            document.getElementById('voidAuthError').classList.add('hidden');
            document.getElementById('voidItemModal').style.display = 'flex';
        }

        function closeVoidItemModal() {
            document.getElementById('voidItemModal').style.display = 'none';
        }

        // Void Sale Functions
        function openVoidSaleModal() {
            if (cart.length === 0) {
                showToast('Cart is empty - Nothing to void', 'info');
                return;
            }
            document.getElementById('voidSaleForm').reset();
            document.getElementById('voidSaleAuthError').classList.add('hidden');
            document.getElementById('voidSaleModal').style.display = 'flex';
        }

        function closeVoidSaleModal() {
            document.getElementById('voidSaleModal').style.display = 'none';
        }

        function processVoidSale(event) {
            event.preventDefault();

            const username = document.getElementById('voidSaleAdminUsername').value;
            const password = document.getElementById('voidSaleAdminPassword').value;
            const reason = document.getElementById('voidSaleReason').value;

            // Verify admin credentials via AJAX
            fetch('{{ route('pos.verify-admin') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password,
                        action: 'void_entire_sale',
                        item_id: null,
                        reason: reason,
                        items: cart.map(i => ({
                            product_id: i.id,
                            quantity: i.quantity
                        }))
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear the entire cart
                        const itemCount = cart.length;
                        cart = [];
                        updateCart();
                        closeVoidSaleModal();

                        // Show success message
                        showToast(`Sale voided successfully by ${data.admin_name} - ${itemCount} item(s) cleared`,
                            'success');

                        // If there is a pending navigation (triggered before void), execute it now
                        if (window.pendingNavigation && typeof window.pendingNavigation === 'function') {
                            try {
                                const nav = window.pendingNavigation;
                                window.pendingNavigation = null;
                                // Slight delay to allow modal close animation
                                setTimeout(() => {
                                    nav();
                                }, 200);
                            } catch (err) {
                                console.error('Error executing pending navigation after void:', err);
                            }
                        }
                    } else {
                        // Show error
                        const errorDiv = document.getElementById('voidSaleAuthError');
                        errorDiv.textContent = data.message || 'Invalid admin credentials';
                        errorDiv.classList.remove('hidden');
                        showToast('Authorization failed - ' + (data.message || 'Invalid credentials'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Void verification error:', error);
                    const errorDiv = document.getElementById('voidSaleAuthError');
                    errorDiv.textContent = 'Error verifying credentials. Please try again.';
                    errorDiv.classList.remove('hidden');
                    showToast('Error verifying admin credentials', 'error');
                });
        }

        function processVoidItem(event) {
            event.preventDefault();

            const productId = parseInt(document.getElementById('voidItemId').value);
            const username = document.getElementById('voidAdminUsername').value;
            const password = document.getElementById('voidAdminPassword').value;
            const reason = document.getElementById('voidReason').value;

            // Verify admin credentials via AJAX
            fetch('{{ route('pos.verify-admin') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password,
                        action: 'void_item',
                        item_id: productId,
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mark the item in the cart as voided so it appears on receipt
                        const item = cart.find(i => i.id === productId);
                        const itemName = item ? item.name : 'Item';
                        if (item) {
                            item.is_voided = true;
                            item.void_reason = reason;
                            item.voided_by = data.admin_name || null;
                        }
                        updateCart();
                        closeVoidItemModal();

                        // Show success message
                        showToast(`"${itemName}" marked VOIDED by ${data.admin_name}`, 'success');
                    } else {
                        // Show error
                        const errorDiv = document.getElementById('voidAuthError');
                        errorDiv.textContent = data.message || 'Invalid admin credentials';
                        errorDiv.classList.remove('hidden');
                        showToast('Authorization failed - ' + (data.message || 'Invalid credentials'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Void verification error:', error);
                    const errorDiv = document.getElementById('voidAuthError');
                    errorDiv.textContent = 'Error verifying credentials. Please try again.';
                    errorDiv.classList.remove('hidden');
                    showToast('Error verifying admin credentials', 'error');
                });
        }

        function updateCart() {
            const cartTableBody = document.getElementById('cartTableBody');

            if (cart.length === 0) {
                cartTableBody.innerHTML =
                    '<tr><td colspan="5" class="text-center text-gray-500 py-8">No items added</td></tr>';
                updateSummary();
                return;
            }

            let html = '';
            cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                html += `
                    <tr data-item-id="${item.id}" data-item-name="${item.name}">
                        <td>
                            <div class="font-medium text-sm">${item.name}</div>
                            <div class="text-xs text-gray-500">${item.category}${item.is_voided ? ' • (VOIDED)' : ''}</div>
                        </td>
                        <td>
                            <div class="qty-controls">
                                ${item.is_voided ? `
                                                                                                                                                                            <div class="text-xs text-red-600 font-semibold">VOIDED</div>
                                                                                                                                                                        ` : `
                                                                                                                                                                            <button onclick="updateQuantity(${item.id}, -1)" class="qty-btn">
                                                                                                                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                                                                                                                                </svg>
                                                                                                                                                                            </button>
                                                                                                                                                                            <input type="number" class="qty-input" value="${item.quantity}" min="1" max="${item.stock}" 
                                                                                                                                                                                onchange="setQuantity(${item.id}, this.value, ${item.stock})" 
                                                                                                                                                                                onblur="if(!this.value || this.value < 1) this.value = 1; setQuantity(${item.id}, this.value, ${item.stock})"
                                                                                                                                                                                autocomplete="off">
                                                                                                                                                                            <button onclick="updateQuantity(${item.id}, 1)" class="qty-btn">
                                                                                                                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                                                                                                                                                                </svg>
                                                                                                                                                                            </button>
                                                                                                                                                                        `}
                            </div>
                        </td>
                        <td class="text-right">₱${item.price.toFixed(2)}</td>
                        <td class="text-right font-semibold">₱${(item.is_voided ? 0 : subtotal).toFixed(2)}</td>
                        <td>
                            <button onclick="openVoidItemModalFromRow(this)" class="text-red-600 hover:text-red-800" title="Void Item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
            });

            cartTableBody.innerHTML = html;
            updateSummary();
        }

        function updateSummary() {
            // Only count non-voided items towards totals
            const nonVoidedItemCount = cart.reduce((sum, item) => sum + (item.is_voided ? 0 : item.quantity), 0);
            // Prices are VAT-inclusive. Compute VAT portion and keep total equal to subtotal.
            const subtotal = cart.reduce((sum, item) => sum + (item.is_voided ? 0 : (item.price * item.quantity)), 0);
            const tax = subtotal * (0.12 / 1.12); // VAT portion included in prices (12% of the net)
            const total = subtotal; // since prices already include VAT

            document.getElementById('itemCount').textContent = nonVoidedItemCount;
            document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('total').textContent = `₱${total.toFixed(2)}`;

            // Enable checkout button only if there are non-voided items
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) checkoutBtn.disabled = nonVoidedItemCount === 0;
        }

        function openCustomerModal() {
            document.getElementById('customerModal').style.display = 'flex';
            document.getElementById('customerForm').reset();
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').style.display = 'none';
        }

        function saveCustomer(event) {
            event.preventDefault();

            const name = document.getElementById('customerName').value;
            const phone = document.getElementById('customerPhone').value;
            const address = document.getElementById('customerAddress').value;

            // Save customer info temporarily
            currentCustomer = {
                name: name,
                phone: phone,
                address: address
            };

            // Update display
            document.getElementById('customerDisplay').innerHTML = `
                <div class="font-semibold text-gray-900">${name}</div>
                <div class="text-xs text-gray-600">${phone}</div>
                ${address ? `<div class="text-xs text-gray-600">${address}</div>` : ''}
            `;

            closeCustomerModal();
        }

        // Customer Selection Functions
        function selectCustomerOption(option) {
            const customerSearchInput = document.getElementById('customerSearchInput');
            const customerDropdown = document.getElementById('customerDropdown');
            const newCustomerForm = document.getElementById('newCustomerForm');
            const newCustomerInputs = newCustomerForm.querySelectorAll('input');

            // Reset all options to default state
            if (customerSearchInput) {
                customerSearchInput.disabled = true;
                customerSearchInput.value = '';
                customerSearchInput.style.pointerEvents = 'none';
            }
            if (customerDropdown) {
                customerDropdown.classList.add('hidden');
            }
            newCustomerForm.style.display = 'none';
            newCustomerInputs.forEach(input => {
                input.disabled = true;
                input.value = '';
                input.style.pointerEvents = 'none';
            });

            // Enable based on selection
            if (option === 'existing') {
                if (customerSearchInput) {
                    customerSearchInput.disabled = false;
                    customerSearchInput.style.pointerEvents = 'auto';
                    customerSearchInput.focus();
                }
            } else if (option === 'new') {
                newCustomerForm.style.display = 'block';
                newCustomerInputs.forEach(input => {
                    input.disabled = false;
                    input.style.pointerEvents = 'auto';
                });
            }

            // Reset currentCustomer
            currentCustomer = null;
        }

        function filterCustomers() {
            const searchInput = document.getElementById('customerSearchInput');
            const dropdown = document.getElementById('customerDropdown');
            const searchTerm = searchInput.value.toLowerCase();
            const seniorChecked = document.getElementById('seniorDiscount').checked;
            const pwdChecked = document.getElementById('pwdDiscount').checked;

            const items = dropdown.querySelectorAll('.customer-item');
            let hasVisible = false;

            items.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                const phone = item.getAttribute('data-phone').toLowerCase();
                const isSenior = item.getAttribute('data-is-senior') === '1';
                const isPwd = item.getAttribute('data-is-pwd') === '1';

                // Check search match
                const matchesSearch = name.includes(searchTerm) || phone.includes(searchTerm);

                // Check discount filter
                let matchesDiscount = true;
                if (seniorChecked && !isSenior) matchesDiscount = false;
                if (pwdChecked && !isPwd) matchesDiscount = false;

                if (matchesSearch && matchesDiscount) {
                    item.style.display = 'block';
                    hasVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });

            dropdown.classList.toggle('hidden', !hasVisible || searchTerm.length === 0);
        }

        function selectExistingCustomer(element) {
            const searchInput = document.getElementById('customerSearchInput');
            const dropdown = document.getElementById('customerDropdown');

            currentCustomer = {
                id: element.getAttribute('data-id'),
                name: element.getAttribute('data-name'),
                phone: element.getAttribute('data-phone'),
                is_senior: element.getAttribute('data-is-senior') === '1',
                is_pwd: element.getAttribute('data-is-pwd') === '1',
                senior_id: element.getAttribute('data-senior-id'),
                pwd_id: element.getAttribute('data-pwd-id')
            };

            searchInput.value = currentCustomer.name;
            dropdown.classList.add('hidden');

            // Auto-fill discount ID if applicable
            const seniorChecked = document.getElementById('seniorDiscount').checked;
            const pwdChecked = document.getElementById('pwdDiscount').checked;

            if (seniorChecked && currentCustomer.is_senior && currentCustomer.senior_id) {
                document.getElementById('seniorIdNumber').value = currentCustomer.senior_id;
            }

            if (pwdChecked && currentCustomer.is_pwd && currentCustomer.pwd_id) {
                document.getElementById('pwdIdNumber').value = currentCustomer.pwd_id;
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('customerDropdown');
            const searchInput = document.getElementById('customerSearchInput');
            if (dropdown && searchInput && !searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Payment Modal Functions
        function openPaymentModal() {
            if (cart.length === 0) {
                showToast('Cannot checkout - Please add items to cart first!', 'error');
                return;
            }

            // Reset discount checkboxes and ID fields
            document.getElementById('seniorDiscount').checked = false;
            document.getElementById('pwdDiscount').checked = false;
            document.getElementById('seniorIdNumber').value = '';
            document.getElementById('pwdIdNumber').value = '';
            document.getElementById('seniorIdNumber').disabled = true;
            document.getElementById('pwdIdNumber').disabled = true;
            document.getElementById('discountRow').style.display = 'none';

            // Update modal summary
            updatePaymentSummary();

            // Reset payment inputs
            document.getElementById('paymentMethod').value = 'cash';
            document.getElementById('paidAmount').value = '';
            document.getElementById('paidAmount').disabled = false;
            document.getElementById('referenceNumber').value = '';
            document.getElementById('changeAmount').textContent = '₱0.00';
            document.getElementById('referenceNumberDiv').style.display = 'none';

            // Reset card details
            document.getElementById('cardBankName').value = '';
            document.getElementById('cardHolderName').value = '';
            document.getElementById('cardLastFour').value = '';
            document.getElementById('cardDetailsDiv').style.display = 'none';

            // Reset customer selection
            document.querySelector('input[name="customerOption"][value="walkin"]').checked = true;
            selectCustomerOption('walkin');
            currentCustomer = null;

            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        function handleDiscountChange() {
            const seniorChecked = document.getElementById('seniorDiscount').checked;
            const pwdChecked = document.getElementById('pwdDiscount').checked;

            // Enable/disable senior ID field
            document.getElementById('seniorIdNumber').disabled = !seniorChecked;
            if (!seniorChecked) {
                document.getElementById('seniorIdNumber').value = '';
            }

            // Enable/disable PWD ID field
            document.getElementById('pwdIdNumber').disabled = !pwdChecked;
            if (!pwdChecked) {
                document.getElementById('pwdIdNumber').value = '';
            }

            // Only allow one discount at a time
            if (seniorChecked && pwdChecked) {
                showToast('Only one discount can be applied per transaction', 'error');
                document.getElementById('pwdDiscount').checked = false;
                document.getElementById('pwdIdNumber').disabled = true;
                document.getElementById('pwdIdNumber').value = '';
            }

            // Filter customers in dropdown if discount is selected
            const searchInput = document.getElementById('customerSearchInput');
            if (searchInput && !searchInput.disabled) {
                filterCustomers();
            }

            // Disable walk-in option while any discount is checked; if walk-in was selected, switch to existing
            const walkinRadio = document.querySelector('input[name="customerOption"][value="walkin"]');
            if (walkinRadio) {
                if (seniorChecked || pwdChecked) {
                    if (walkinRadio.checked) {
                        const existingRadio = document.querySelector('input[name="customerOption"][value="existing"]');
                        if (existingRadio) {
                            existingRadio.checked = true;
                            selectCustomerOption('existing');
                        }
                    }
                    walkinRadio.disabled = true;
                } else {
                    walkinRadio.disabled = false;
                }
            }

            // Auto-fill discount ID if customer is already selected
            if (currentCustomer) {
                if (seniorChecked && currentCustomer.is_senior && currentCustomer.senior_id) {
                    document.getElementById('seniorIdNumber').value = currentCustomer.senior_id;
                }

                if (pwdChecked && currentCustomer.is_pwd && currentCustomer.pwd_id) {
                    document.getElementById('pwdIdNumber').value = currentCustomer.pwd_id;
                }
            }

            updatePaymentSummary();
        }

        // Calculate change and enable/disable confirm button
        function calculateChange() {
            const totalEl = document.getElementById('modalTotal');
            const paidInput = document.getElementById('paidAmount');
            const changeEl = document.getElementById('changeAmount');
            const confirmBtn = document.getElementById('confirmPaymentBtn');

            const total = totalEl ? parseFloat((totalEl.textContent || '').replace('₱', '').replace(/,/g, '')) : 0;
            const paid = paidInput ? parseFloat(paidInput.value) || 0 : 0;
            const change = Math.max(0, paid - total);

            if (changeEl) changeEl.textContent = `₱${change.toFixed(2)}`;
            if (confirmBtn) confirmBtn.disabled = paid < total;
        }

        // Safe helper to get input value or empty string
        function getInputValue(id) {
            const el = document.getElementById(id);
            if (!el) return '';
            return (el.value || '').toString();
        }

        function updatePaymentSummary() {
            // Compute subtotal from non-voided items (prices are VAT-inclusive)
            const subtotal = cart.reduce((sum, item) => sum + (item.is_voided ? 0 : (item.price * item.quantity)), 0);
            // VAT portion included in prices: VAT = subtotal * (0.12/1.12)
            const tax = subtotal * (0.12 / 1.12);

            // Calculate discount (20% on subtotal, matching server behavior)
            let discount = 0;
            let discountLabel = '';
            const seniorChecked = document.getElementById('seniorDiscount').checked;
            const pwdChecked = document.getElementById('pwdDiscount').checked;

            if (seniorChecked) {
                discount = subtotal * 0.20; // 20% discount on subtotal
                discountLabel = 'Senior Discount (20%)';
            } else if (pwdChecked) {
                discount = subtotal * 0.20; // 20% discount on subtotal
                discountLabel = 'PWD Discount (20%)';
            }

            // Total is subtotal minus discount (subtotal already includes VAT)
            const total = subtotal - discount;

            document.getElementById('modalSubtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('modalTax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('modalTotal').textContent = `₱${total.toFixed(2)}`;

            // Show/hide discount row
            if (discount > 0) {
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('discountLabel').textContent = discountLabel + ':';
                document.getElementById('modalDiscount').textContent = `-₱${discount.toFixed(2)}`;
            } else {
                document.getElementById('discountRow').style.display = 'none';
            }

            // Update paid amount if using GCash/Card
            const paymentMethod = document.getElementById('paymentMethod').value;
            if (paymentMethod === 'gcash' || paymentMethod === 'card') {
                document.getElementById('paidAmount').value = total.toFixed(2);
            }

            calculateChange();
        }

        // Embed customers for client-side ID lookups
        window._customers = {!! $customers->map->only(['id', 'name', 'phone', 'is_senior_citizen', 'is_pwd', 'senior_citizen_id', 'pwd_id'])->values()->toJson() !!};

        // Search discount ID (senior/pwd) and show dropdown of matches
        function searchDiscountId(type) {
            const inputId = type === 'senior' ? 'seniorIdNumber' : 'pwdIdNumber';
            const dropdownId = type === 'senior' ? 'seniorIdDropdown' : 'pwdIdDropdown';
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            const val = (input.value || '').trim();

            // If empty, hide dropdown and re-enable walk-in
            if (!val) {
                dropdown.classList.add('hidden');
                enableCustomerRadios();
                return;
            }

            // Disable walk-in option while an ID is being entered
            disableWalkinOption();

            const matches = window._customers.filter(c => {
                if (type === 'senior') {
                    return c.is_senior_citizen && c.senior_citizen_id && c.senior_citizen_id.toLowerCase().includes(
                        val.toLowerCase());
                }
                return c.is_pwd && c.pwd_id && c.pwd_id.toLowerCase().includes(val.toLowerCase());
            });

            let html = '';
            if (matches.length === 0) {
                html =
                    `<div class="px-3 py-2 text-sm text-gray-600 cursor-pointer hover:bg-gray-50" data-action="add">No existing customer, add details?</div>`;
            } else {
                matches.forEach(c => {
                    const idVal = type === 'senior' ? c.senior_citizen_id : c.pwd_id;
                    html +=
                        `<div class="px-3 py-2 border-b last:border-b-0 cursor-pointer hover:bg-gray-50" data-id="${c.id}" data-name="${escapeHtml(c.name)}">${escapeHtml(c.name)} — ${escapeHtml(idVal)}</div>`;
                });
            }

            dropdown.innerHTML = html;
            dropdown.classList.remove('hidden');
        }

        // Utility to escape HTML for insertion
        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"']/g, function(s) {
                switch (s) {
                    case '&':
                        return '&amp;';
                    case '<':
                        return '&lt;';
                    case '>':
                        return '&gt;';
                    case '"':
                        return '&quot;';
                    case "'":
                        return '&#39;';
                    default:
                        return s;
                }
            });
        }

        // Disable only the walk-in radio while ID is present
        function disableWalkinOption() {
            const walkin = document.querySelector('input[name="customerOption"][value="walkin"]');
            if (walkin) walkin.disabled = true;
        }

        function enableCustomerRadios() {
            document.querySelectorAll('input[name="customerOption"]').forEach(r => r.disabled = false);
        }

        // Click handlers for the ID dropdowns (event delegation)
        document.addEventListener('click', function(e) {
            // senior dropdown
            const sDrop = document.getElementById('seniorIdDropdown');
            if (sDrop && sDrop.contains(e.target)) {
                e.preventDefault();
                e.stopPropagation();
                const el = e.target.closest('div[data-id], div[data-action]');
                if (!el) return;
                if (el.dataset.action === 'add') {
                    // switch to new customer and mark as senior
                    document.querySelector('input[name="customerOption"][value="new"]').checked = true;
                    selectCustomerOption('new');
                    // disable other radios to prevent changing
                    document.querySelectorAll('input[name="customerOption"]').forEach(r => {
                        if (r.value !== 'new') r.disabled = true;
                    });
                    // ensure new customer form is visible and mark as senior on submit
                    document.getElementById('newCustomerName').disabled = false;
                    document.getElementById('newCustomerPhone').disabled = false;
                    document.getElementById('newCustomerAddress').disabled = false;
                    // remember to flag server via discount checkbox (seniorDiscount already checked)
                } else if (el.dataset.id) {
                    // existing customer selected
                    const id = el.dataset.id;
                    const name = el.dataset.name;

                    // find the corresponding existing customer element first
                    const matching = document.querySelector(`.customer-item[data-id="${id}"]`);

                    // If we found the matching customer element, populate the ID input first
                    if (matching) {
                        try {
                            const seniorCheckbox = document.getElementById('seniorDiscount');
                            const seniorInput = document.getElementById('seniorIdNumber');
                            const seniorId = matching.getAttribute('data-senior-id') || '';
                            if (seniorCheckbox && seniorInput) {
                                if (seniorId) {
                                    seniorCheckbox.checked = true;
                                    seniorInput.disabled = false;
                                    seniorInput.value = seniorId;
                                } else {
                                    if (!seniorCheckbox.checked) {
                                        seniorInput.value = '';
                                        seniorInput.disabled = true;
                                    }
                                }
                            }
                        } catch (e) {
                            console.error('Error pre-populating Senior id before selection', e);
                        }
                    }

                    // Now set existing option and lock radios, then trigger existing selection
                    const existingRadio = document.querySelector('input[name="customerOption"][value="existing"]');
                    if (existingRadio) {
                        existingRadio.checked = true;
                        selectCustomerOption('existing');
                    }
                    document.querySelectorAll('input[name="customerOption"]').forEach(r => {
                        if (r.value !== 'existing') r.disabled = true;
                    });

                    if (matching) {
                        selectExistingCustomer(matching);
                    }
                }
                sDrop.classList.add('hidden');
            }

            // pwd dropdown
            const pDrop = document.getElementById('pwdIdDropdown');
            if (pDrop && pDrop.contains(e.target)) {
                e.preventDefault();
                e.stopPropagation();
                const el = e.target.closest('div[data-id], div[data-action]');
                if (!el) return;
                if (el.dataset.action === 'add') {
                    document.querySelector('input[name="customerOption"][value="new"]').checked = true;
                    selectCustomerOption('new');
                    document.querySelectorAll('input[name="customerOption"]').forEach(r => {
                        if (r.value !== 'new') r.disabled = true;
                    });
                    document.getElementById('newCustomerName').disabled = false;
                    document.getElementById('newCustomerPhone').disabled = false;
                    document.getElementById('newCustomerAddress').disabled = false;
                } else if (el.dataset.id) {
                    const id = el.dataset.id;
                    // set existing option and lock radios BEFORE selecting to avoid clearing currentCustomer
                    const existingRadioP = document.querySelector('input[name="customerOption"][value="existing"]');
                    if (existingRadioP) {
                        existingRadioP.checked = true;
                        selectCustomerOption('existing');
                    }
                    document.querySelectorAll('input[name="customerOption"]').forEach(r => {
                        if (r.value !== 'existing') r.disabled = true;
                    });
                    const matching = document.querySelector(`.customer-item[data-id="${id}"]`);
                    if (matching) {
                        // Pre-populate PWD id and checkbox before running selectExistingCustomer
                        try {
                            const pwdCheckbox = document.getElementById('pwdDiscount');
                            const pwdInput = document.getElementById('pwdIdNumber');
                            const pwdId = matching.getAttribute('data-pwd-id') || '';
                            if (pwdCheckbox && pwdInput) {
                                if (pwdId) {
                                    pwdCheckbox.checked = true;
                                    pwdInput.disabled = false;
                                    pwdInput.value = pwdId;
                                } else {
                                    if (!pwdCheckbox.checked) {
                                        pwdInput.value = '';
                                        pwdInput.disabled = true;
                                    }
                                }
                            }
                        } catch (e) {
                            console.error('Error pre-populating PWD id before selection', e);
                        }

                        // Now set existing option and run selection
                        const existingRadioP = document.querySelector(
                            'input[name="customerOption"][value="existing"]');
                        if (existingRadioP) {
                            existingRadioP.checked = true;
                            selectCustomerOption('existing');
                        }
                        document.querySelectorAll('input[name="customerOption"]').forEach(r => {
                            if (r.value !== 'existing') r.disabled = true;
                        });
                        selectExistingCustomer(matching);
                    }
                }
                pDrop.classList.add('hidden');
            }
        });

        function handlePaymentMethodChange() {
            const method = document.getElementById('paymentMethod').value;
            const refDiv = document.getElementById('referenceNumberDiv');
            const cardDetailsDiv = document.getElementById('cardDetailsDiv');
            const paidAmountInput = document.getElementById('paidAmount');
            const total = parseFloat(document.getElementById('modalTotal').textContent.replace('₱', '').replace(',', '')) ||
                0;
            // Show/hide reference number field
            if (refDiv) refDiv.style.display = (method === 'gcash' || method === 'card') ? 'block' : 'none';

            // Show/hide card details fields (only for card)
            if (cardDetailsDiv) cardDetailsDiv.style.display = (method === 'card') ? 'block' : 'none';

            // Auto-fill and disable amount for GCash/Card
            if (paidAmountInput) {
                if (method === 'gcash' || method === 'card') {
                    paidAmountInput.value = total.toFixed(2);
                    paidAmountInput.disabled = true;
                } else {
                    paidAmountInput.value = '';
                    paidAmountInput.disabled = false;
                }
            }

            calculateChange();
        }

        async function confirmPayment() {
            try {
                // Validate inputs
                const paymentMethod = getInputValue('paymentMethod');
                const paidAmount = parseFloat(getInputValue('paidAmount'));
                const total = parseFloat((document.getElementById('modalTotal') && document.getElementById('modalTotal')
                    .textContent ? document.getElementById('modalTotal').textContent.replace('₱', '').replace(
                        /,/g, '') : 0));

                const paidInput = document.getElementById('paidAmount');
                if (!paidAmount || isNaN(paidAmount) || paidAmount <= 0) {
                    showToast('Please enter a valid payment amount', 'error');
                    if (paidInput) paidInput.focus();
                    return;
                }

                if (paidAmount < total) {
                    showToast(`Insufficient payment - Need ₱${total.toFixed(2)}, received ₱${paidAmount.toFixed(2)}`,
                        'error');
                    if (paidInput) paidInput.focus();
                    return;
                }

                if ((paymentMethod === 'gcash' || paymentMethod === 'card') && !getInputValue('referenceNumber')
                .trim()) {
                    showToast('Reference number is required for ' + paymentMethod.toUpperCase() + ' payment', 'error');
                    return;
                }

                // Validate discount ID numbers
                const seniorChecked = document.getElementById('seniorDiscount').checked;
                const pwdChecked = document.getElementById('pwdDiscount').checked;

                if (seniorChecked && !getInputValue('seniorIdNumber').trim()) {
                    showToast('Senior Citizen ID number is required', 'error');
                    return;
                }

                if (pwdChecked && !getInputValue('pwdIdNumber').trim()) {
                    showToast('PWD ID number is required', 'error');
                    return;
                }

                // Handle customer selection
                const customerOption = document.querySelector('input[name="customerOption"]:checked').value;
                let customerData = null;

                if (customerOption === 'existing') {
                    if (!currentCustomer || !currentCustomer.id) {
                        showToast('Please select a customer from the list', 'error');
                        return;
                    }
                    customerData = {
                        type: 'existing',
                        id: currentCustomer.id
                    };
                } else if (customerOption === 'new') {
                    const name = getInputValue('newCustomerName').trim();
                    const phone = getInputValue('newCustomerPhone').trim();
                    const address = getInputValue('newCustomerAddress').trim();

                    if (!name || !phone) {
                        showToast('Customer name and phone number are required', 'error');
                        return;
                    }

                    customerData = {
                        type: 'new',
                        name: name,
                        phone: phone,
                        address: address,
                        is_senior_citizen: seniorChecked ? 1 : 0,
                        senior_citizen_id: seniorChecked ? document.getElementById('seniorIdNumber').value.trim() :
                            null,
                        is_pwd: pwdChecked ? 1 : 0,
                        pwd_id: pwdChecked ? document.getElementById('pwdIdNumber').value.trim() : null
                    };
                }

                // Prepare form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('payment_method', paymentMethod);
                formData.append('paid_amount', paidAmount);
                formData.append('change_amount', paidAmount - total);

                if (paymentMethod === 'gcash' || paymentMethod === 'card') {
                    formData.append('reference_number', getInputValue('referenceNumber').trim());
                }

                // Add card payment details if card payment
                if (paymentMethod === 'card') {
                    const bankName = getInputValue('cardBankName').trim();
                    const holderName = getInputValue('cardHolderName').trim();
                    const lastFour = getInputValue('cardLastFour').trim();

                    if (bankName) formData.append('card_bank_name', bankName);
                    if (holderName) formData.append('card_holder_name', holderName);
                    if (lastFour) formData.append('card_last_four', lastFour);
                }

                if (customerData) {
                    if (customerData.type === 'existing') {
                        formData.append('customer_id', customerData.id);
                    } else {
                        formData.append('customer_name', customerData.name);
                        formData.append('customer_phone', customerData.phone);
                        formData.append('customer_address', customerData.address);
                    }
                }

                // Add discount information
                if (seniorChecked) {
                    formData.append('discount_type', 'senior_citizen');
                    formData.append('discount_id_number', getInputValue('seniorIdNumber').trim());
                    formData.append('discount_name', (document.getElementById('seniorName') ? getInputValue(
                        'seniorName').trim() : (currentCustomer && currentCustomer.name ? currentCustomer
                        .name : '')));
                    formData.append('discount_percentage', 20);
                } else if (pwdChecked) {
                    formData.append('discount_type', 'pwd');
                    formData.append('discount_id_number', getInputValue('pwdIdNumber').trim());
                    formData.append('discount_name', (document.getElementById('pwdName') ? getInputValue('pwdName')
                        .trim() : (currentCustomer && currentCustomer.name ? currentCustomer.name : '')));
                    formData.append('discount_percentage', 20);
                }

                cart.forEach((item, index) => {
                    formData.append(`items[${index}][product_id]`, item.id);
                    formData.append(`items[${index}][quantity]`, item.quantity);
                    formData.append(`items[${index}][is_voided]`, item.is_voided ? 1 : 0);
                });

                // Disable button during processing
                const confirmBtn = document.getElementById('confirmPaymentBtn');
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = 'Confirm & Print Receipt';

                // Submit sale
                const response = await fetch('{{ route('sales.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    closePaymentModal();
                    showReceipt(data.receipt);
                    showToast('Transaction completed successfully!', 'success');
                } else {
                    // Handle validation errors or general errors
                    if (data.errors) {
                        // Show first validation error only
                        const firstError = Object.values(data.errors)[0][0];
                        showToast(firstError, 'error');
                    } else {
                        showToast(data.message || 'Failed to process sale', 'error');
                    }
                    // Re-enable button
                    const confirmBtn = document.getElementById('confirmPaymentBtn');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = 'Confirm & Print Receipt';
                }

            } catch (error) {
                console.error('Error:', error);
                // Only show toast if not already shown
                if (!error.message.includes('Failed to process sale')) {
                    showToast('Transaction failed: ' + error.message, 'error');
                }
                // Re-enable button
                const confirmBtn = document.getElementById('confirmPaymentBtn');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Confirm & Print Receipt';
            }
        }

        function processCheckout() {
            // Double check cart is not empty
            if (cart.length === 0) {
                showToast('Cannot proceed to checkout - Cart is empty!', 'error');
                return;
            }
            openPaymentModal();
        }

        // Receipt Modal Functions
        function showReceipt(receiptData) {
            const receiptContent = document.getElementById('receiptContent');

            const customerInfo = receiptData.customer ?
                `<div class="text-sm mb-3 border-b border-gray-200 pb-3">
                    <div><strong>Customer:</strong> ${receiptData.customer.name}</div>
                    <div><strong>Phone:</strong> ${receiptData.customer.phone}</div>
                    ${receiptData.customer.address ? `<div><strong>Address:</strong> ${receiptData.customer.address}</div>` : ''}
                </div>` :
                `<div class="text-sm mb-3 text-gray-600 italic border-b border-gray-200 pb-3">Walk-in Customer</div>`;

            const discountInfo = receiptData.discount ?
                `<div class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-3 text-sm">
                    <div class="font-semibold text-yellow-800 mb-1">🎫 Discount Applied</div>
                    <div><strong>Type:</strong> ${receiptData.discount.type}</div>
                    <div><strong>ID Number:</strong> ${receiptData.discount.id_number || 'N/A'}</div>
                    <div><strong>Discount:</strong> ${receiptData.discount.percentage}% (₱${parseFloat(receiptData.discount.amount).toFixed(2)})</div>
                </div>` : '';

            let itemsHtml = '';
            receiptData.items.forEach(item => {
                itemsHtml += `
                <tr>
                    <td class="py-1">${item.name}</td>
                    <td class="text-center">${item.quantity}</td>
                    <td class="text-right">₱${parseFloat(item.price).toFixed(2)}</td>
                    <td class="text-right">₱${parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>
            `;
            });

            receiptContent.innerHTML = `
            <style>
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    #receiptContent, #receiptContent * {
                        visibility: visible;
                    }
                    #receiptContent {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }
                    button {
                        display: none !important;
                    }
                    .no-print {
                        display: none !important;
                    }
                }
            </style>
            <div class="receipt-print">
                <div class="text-center mb-4 border-b-2 border-gray-300 pb-3">
                    <h1 class="text-xl font-bold text-gray-800">{{ config('business.name') }}</h1>
                    <p class="text-xs text-gray-600">{{ config('business.address') }}</p>
                    <p class="text-xs text-gray-600">Tel: {{ config('business.phone') }} | Email: {{ config('business.email') }}</p>
                    <p class="text-xs text-gray-600 mt-1 font-semibold">{{ config('business.tagline') }}</p>
                    <h2 class="text-base font-bold text-gray-700 mt-2">Official Receipt</h2>
                </div>

                <div class="mb-3 text-sm border-b border-gray-200 pb-3">
                    <div><strong>Receipt #:</strong> ${receiptData.receipt_number}</div>
                    <div><strong>Date:</strong> ${receiptData.date}</div>
                    <div><strong>Time:</strong> ${receiptData.time}</div>
                    <div><strong>Cashier:</strong> ${receiptData.cashier}</div>
                </div>

                ${customerInfo}

                ${discountInfo}

                <table class="w-full text-sm mb-3">
                    <thead class="border-y-2 border-gray-300">
                        <tr>
                            <th class="text-left py-2">Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>

                <div class="border-t-2 border-gray-300 pt-3 mb-3">
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-gray-700">Subtotal:</span>
                        <span class="font-medium">₱${parseFloat(receiptData.subtotal).toFixed(2)}</span>
                    </div>
                    ${receiptData.discount ? 
                        `<div class="flex justify-between text-sm mb-1.5 text-yellow-700">
                                                                                                                                                                                                                                                                                                                                                                                        <span>Discount (${receiptData.discount.percentage}%):</span>
                                                                                                                                                                                                                                                                                                                                                                                        <span class="font-medium">- ₱${parseFloat(receiptData.discount.amount).toFixed(2)}</span>
                                                                                                                                                                                                                                                                                                                                                                                    </div>` : ''}
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-gray-700">VAT (12%):</span>
                        <span class="font-medium">₱${parseFloat(receiptData.tax).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold border-t-2 border-gray-400 pt-2 mt-2">
                        <span>TOTAL:</span>
                        <span class="text-green-700">₱${parseFloat(receiptData.total).toFixed(2)}</span>
                    </div>
                </div>

                <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-3 mb-3">
                    <div class="text-center font-bold text-gray-800 mb-2 text-sm uppercase tracking-wide">Payment Details</div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-semibold text-gray-700">Payment Method:</span>
                        <span class="uppercase font-medium">${receiptData.payment_method}</span>
                    </div>
                    ${receiptData.reference_number ? 
                        `<div class="flex justify-between text-sm mb-1.5">
                                                                                                                                                                                                                                                                                                                                                                                        <span class="font-semibold text-gray-700">Reference #:</span>
                                                                                                                                                                                                                                                                                                                                                                                        <span class="font-mono">${receiptData.reference_number}</span>
                                                                                                                                                                                                                                                                                                                                                                                    </div>` : ''}
                    <div class="flex justify-between text-sm mb-1.5 border-t border-gray-300 pt-2 mt-2">
                        <span class="font-semibold text-gray-700">Amount Paid:</span>
                        <span class="font-semibold text-green-600">₱${parseFloat(receiptData.paid_amount).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold border-t-2 border-gray-400 pt-2">
                        <span class="text-gray-800">Change:</span>
                        <span class="text-green-700">₱${parseFloat(receiptData.change_amount).toFixed(2)}</span>
                    </div>
                </div>

                <div class="text-center text-xs text-gray-600 border-t border-gray-300 pt-3">
                    <p class="font-semibold mb-1">Thank you for your purchase!</p>
                    <p>This serves as your official receipt</p>
                    <p class="mt-2">VAT Reg. TIN: {{ config('business.tin') }}</p>
                    <p>Business Permit No.: {{ config('business.permit_number') }}</p>
                    @if (config('business.fda_license'))
                        <p>FDA License No.: {{ config('business.fda_license') }}</p>
                    @endif
                    <p class="mt-2 text-gray-500">{{ config('business.tagline') }}</p>
                </div>
            </div>
        `;

            document.getElementById('receiptModal').style.display = 'flex';
        }

        function printReceipt() {
            window.print();
        }

        function newTransaction() {
            // Close receipt modal
            document.getElementById('receiptModal').style.display = 'none';

            // Capture sold items (include name) for optimistic UI update, then clear cart
            const soldItems = cart.map(i => ({
                id: i.id,
                quantity: i.quantity,
                name: i.name
            }));

            // Clear cart and reset state
            cart = [];
            updateCart();

            // Reset customer selection safely (guard missing elements)
            try {
                currentCustomer = null;
                const existingSelect = document.getElementById('existingCustomerSelect');
                if (existingSelect) existingSelect.value = '';
                const walkinRadio = document.querySelector('input[name="customerOption"][value="walkin"]');
                if (walkinRadio) {
                    walkinRadio.checked = true;
                    selectCustomerOption('walkin');
                }
                const customerDisplayEl = document.getElementById('customerDisplay');
                if (customerDisplayEl) customerDisplayEl.innerHTML =
                    '<div class="text-sm text-gray-600 italic">Walk-in Customer</div>';

                // Reset discount fields safely
                const seniorCheckbox = document.getElementById('seniorDiscount');
                const pwdCheckbox = document.getElementById('pwdDiscount');
                const seniorInput = document.getElementById('seniorIdNumber');
                const pwdInput = document.getElementById('pwdIdNumber');

                if (seniorCheckbox) seniorCheckbox.checked = false;
                if (pwdCheckbox) pwdCheckbox.checked = false;
                if (seniorInput) {
                    seniorInput.value = '';
                    seniorInput.disabled = true;
                }
                if (pwdInput) {
                    pwdInput.value = '';
                    pwdInput.disabled = true;
                }
            } catch (e) {
                console.warn('newTransaction: safe reset encountered an issue', e);
            }

            // Reset payment method
            document.getElementById('paymentMethod').value = 'cash';
            handlePaymentMethodChange();

            // Optimistically decrement product cards to reflect just-completed transaction
            optimisticDecrementStock(soldItems);

            // Attempt a targeted AJAX refresh of the products grid first, keep fullscreen flags if fallback reload required
            try {
                refreshProductsGrid(typeof currentPage !== 'undefined' ? currentPage : 1).catch(err => {
                    // Persist fullscreen requirement so reload can attempt to re-enter fullscreen/user gesture
                    try {
                        localStorage.setItem('posRequireFullscreen', 'true');
                        localStorage.setItem('posFullscreen', 'true');
                    } catch (e) {}
                    // Fallback to full reload if AJAX refresh fails
                    setTimeout(() => {
                        location.reload();
                    }, 800);
                });
            } catch (err) {
                try {
                    localStorage.setItem('posRequireFullscreen', 'true');
                    localStorage.setItem('posFullscreen', 'true');
                } catch (e) {}
                setTimeout(() => {
                    location.reload();
                }, 800);
            }
        }

        // Optimistically decrement product card stock counts for sold items
        function optimisticDecrementStock(soldItems) {
            if (!Array.isArray(soldItems) || soldItems.length === 0) return;

            soldItems.forEach(si => {
                try {
                    // Try to find card by id first
                    let card = null;
                    if (si.id !== undefined && si.id !== null) {
                        card = document.querySelector(`.product-item[data-id="${si.id}"]`);
                    }

                    // If not found by id, try matching by product-name or .product-name text
                    if (!card) {
                        const nameToMatch = (si.name || '').toLowerCase().trim();
                        const all = document.querySelectorAll('.product-item');
                        for (let i = 0; i < all.length; i++) {
                            const c = all[i];
                            const productNameAttr = (c.getAttribute('data-product-name') || c.getAttribute(
                                'data-name') || '').toLowerCase();
                            const titleEl = c.querySelector('.product-name');
                            const titleText = titleEl ? titleEl.textContent.toLowerCase() : '';
                            if (nameToMatch && (productNameAttr.includes(nameToMatch) || titleText.includes(
                                    nameToMatch))) {
                                card = c;
                                break;
                            }
                        }
                    }

                    if (!card) {
                        console.debug('optimisticDecrementStock: no matching product card for', si.id, si.name);
                        return;
                    }

                    const stockEl = card.querySelector('.product-stock');
                    if (!stockEl) return;

                    // read current data-stock attribute if present, otherwise parse from text
                    let currentStock = parseInt(card.getAttribute('data-stock'));
                    if (isNaN(currentStock)) {
                        const txt = stockEl.textContent || '';
                        const m = txt.match(/(\d+)/);
                        currentStock = m ? parseInt(m[1]) : 0;
                    }

                    let newStock = currentStock - (si.quantity || 0);
                    if (newStock < 0) newStock = 0;

                    // Update data attribute and display
                    card.setAttribute('data-stock', String(newStock));
                    stockEl.textContent =
                        `${newStock <= 0 ? 'Out of Stock' : (newStock <= 10 ? 'Low Stock' : 'In Stock')}: ${newStock}`;
                    stockEl.classList.remove('stock-good', 'stock-low', 'stock-out');
                    if (newStock <= 0) stockEl.classList.add('stock-out');
                    else if (newStock <= 10) stockEl.classList.add('stock-low');
                    else stockEl.classList.add('stock-good');

                    // Flash highlight so the change is obvious
                    try {
                        const prev = card.style.transition || '';
                        card.style.transition = 'background-color 0.45s ease';
                        const originalBg = card.style.backgroundColor;
                        card.style.backgroundColor = '#fef3c7';
                        setTimeout(() => {
                            card.style.backgroundColor = originalBg || '';
                            // restore transition after animation
                            setTimeout(() => {
                                card.style.transition = prev;
                            }, 300);
                        }, 450);
                    } catch (e) {
                        // ignore
                    }
                } catch (err) {
                    console.error('optimisticDecrementStock error for item', si, err);
                }
            });
        }
        const _customerDisplay = document.getElementById('customerDisplay');
        if (_customerDisplay) {
            _customerDisplay.innerHTML = '<div class="text-sm text-gray-600 italic">Walk-in Customer</div>';
        }

        // Show success message
        const notification = document.createElement('div');
        notification.className =
            'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.innerHTML = '✓ Ready for new transaction';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 2000);

        // Close modals when clicking outside (guard elements)
        const _customerModal = document.getElementById('customerModal');
        if (_customerModal) {
            _customerModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCustomerModal();
                }
            });
        }

        const _paymentModal = document.getElementById('paymentModal');
        if (_paymentModal) {
            _paymentModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePaymentModal();
                }
            });
        }

        // AJAX Filter and Pagination (keeps fullscreen mode)
        let filterTimeout;
        var currentPage = {{ $products->currentPage() }};

        // Apply filters with AJAX
        function applyFilters(page = 1) {
            const searchEl = document.getElementById('searchProduct');
            const productTypeEl = document.getElementById('productTypeFilter');
            const categoryEl = document.getElementById('categoryFilter');

            const search = searchEl ? searchEl.value : '';
            const productType = productTypeEl ? productTypeEl.value : '';
            const category = categoryEl ? categoryEl.value : '';

            // Build query string
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (productType) params.append('product_type', productType);
            if (category) params.append('category', category);
            if (page > 1) params.append('page', page);

            // Show loading state
            const productsGrid = document.getElementById('productsGrid');
            if (productsGrid) {
                productsGrid.style.opacity = '0.5';
                productsGrid.style.pointerEvents = 'none';
            }

            const url = `{{ route('pos.index') }}?${params.toString()}`;
            console.debug('Applying filters, fetching:', url);

            // Fetch filtered products (with extra debug logging)
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => {
                    console.debug('Filter response status:', response.status, response.statusText);
                    const ct = response.headers.get('content-type') || '';
                    console.debug('Filter response content-type:', ct);
                    if (!response.ok) throw new Error('Server responded with status ' + response.status + ' ' + response
                        .statusText);
                    return response.text().then(text => ({
                        text,
                        status: response.status,
                        contentType: ct
                    }));
                })
                .then(({
                    text: html,
                    status,
                    contentType
                }) => {
                    try {
                        // If the response doesn't include the products grid, dump a preview for debugging
                        if (typeof html === 'string' && html.indexOf('id="productsGrid"') === -1) {
                            console.warn('AJAX response does not contain #productsGrid. Response preview:', html
                                .substring(0, 1200));
                        }

                        // Parse the response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Detect if server returned a login page (session expired / unauthorized)
                        const loginForm = doc.querySelector('form[action*=login], form#loginForm');
                        if (loginForm) {
                            console.warn(
                                'AJAX product fetch returned a login page — possible session/authorization issue.');
                            if (productsGrid) {
                                productsGrid.style.opacity = '1';
                                productsGrid.style.pointerEvents = 'auto';
                            }
                            showToast('Session expired or not authorized. Please reload and sign in again.', 'error');
                            return;
                        }

                        // Update products grid
                        const newProductsGrid = doc.getElementById('productsGrid');
                        if (newProductsGrid && productsGrid) {
                            productsGrid.innerHTML = newProductsGrid.innerHTML;
                        } else {
                            console.debug('No productsGrid found in AJAX response.');
                        }

                        // Update pagination
                        const newPagination = doc.getElementById('paginationWrapper');
                        const pagination = document.getElementById('paginationWrapper');
                        if (newPagination && pagination) {
                            pagination.innerHTML = newPagination.innerHTML;
                            interceptPaginationLinks();
                        } else if (pagination && !newPagination) {
                            pagination.innerHTML = '';
                        }

                        // Restore state
                        if (productsGrid) {
                            productsGrid.style.opacity = '1';
                            productsGrid.style.pointerEvents = 'auto';
                        }

                        currentPage = page;
                    } catch (e) {
                        console.error('Error parsing filter response:', e);
                        if (productsGrid) {
                            productsGrid.style.opacity = '1';
                            productsGrid.style.pointerEvents = 'auto';
                        }
                        showToast('Error loading products. Please try again.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    if (productsGrid) {
                        productsGrid.style.opacity = '1';
                        productsGrid.style.pointerEvents = 'auto';
                    }
                    showToast('Error loading products. ' + (error.message || 'Please try again.'), 'error');
                });
        }

        // Refresh products grid via AJAX (partial fetch) and preserve fullscreen
        function refreshProductsGrid(page = typeof currentPage !== 'undefined' ? currentPage : 1) {
            const productsGrid = document.getElementById('productsGrid');
            const pagination = document.getElementById('paginationWrapper');
            if (!productsGrid) return Promise.resolve();

            // Build same query params used by applyFilters
            const searchEl = document.getElementById('searchProduct');
            const productTypeEl = document.getElementById('productTypeFilter');
            const categoryEl = document.getElementById('categoryFilter');

            const params = new URLSearchParams();
            if (searchEl && searchEl.value) params.append('search', searchEl.value);
            if (productTypeEl && productTypeEl.value) params.append('product_type', productTypeEl.value);
            if (categoryEl && categoryEl.value) params.append('category', categoryEl.value);
            if (page > 1) params.append('page', page);

            const url = `{{ route('pos.index') }}?${params.toString()}`;

            productsGrid.style.opacity = '0.5';
            productsGrid.style.pointerEvents = 'none';

            return fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            }).then(resp => {
                if (!resp.ok) throw new Error('Server responded ' + resp.status);
                return resp.text();
            }).then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.getElementById('productsGrid');
                const newPagination = doc.getElementById('paginationWrapper');
                if (newGrid) productsGrid.innerHTML = newGrid.innerHTML;
                if (pagination) {
                    if (newPagination) pagination.innerHTML = newPagination.innerHTML;
                    else pagination.innerHTML = '';
                }
                interceptPaginationLinks();
                productsGrid.style.opacity = '1';
                productsGrid.style.pointerEvents = 'auto';
                showToast('Product list refreshed', 'success');
            }).catch(err => {
                console.error('refreshProductsGrid error:', err);
                productsGrid.style.opacity = '1';
                productsGrid.style.pointerEvents = 'auto';
                // Let caller handle fallback
                throw err;
            });
        }

        // Intercept pagination link clicks
        function interceptPaginationLinks() {
            const paginationLinks = document.querySelectorAll('#paginationWrapper a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page') || 1;
                    applyFilters(parseInt(page));
                });
            });
        }

        // Initialize POS interactions. Run immediately if DOM already loaded.
        function initPosFilters() {
            // Delegate product clicks to the products grid (works after AJAX updates)
            const productsGrid = document.getElementById('productsGrid');
            if (productsGrid) {
                productsGrid.addEventListener('click', function(e) {
                    const item = e.target.closest('.product-item');
                    if (item) {
                        // Use the data-* attributes to add to cart
                        window.addToCartFromData(item);
                    }
                });
            }

            // Bind search input with debounce
            const searchInput = document.getElementById('searchProduct');
            if (searchInput) {
                let localFilterTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(localFilterTimeout);
                    localFilterTimeout = setTimeout(() => {
                        applyFilters(1);
                    }, 300);
                });
            }

            // Handle product type changes: fetch relevant categories and apply filters
            const productTypeSelect = document.getElementById('productTypeFilter');
            const categorySelect = document.getElementById('categoryFilter');

            function populateCategoryOptions(categories, selectedId = '') {
                if (!categorySelect) return;
                categorySelect.innerHTML = '';
                const allOpt = document.createElement('option');
                allOpt.value = '';
                allOpt.text = 'All Categories';
                categorySelect.appendChild(allOpt);

                categories.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.text = cat.name;
                    if (String(cat.id) === String(selectedId)) opt.selected = true;
                    categorySelect.appendChild(opt);
                });
            }

            if (productTypeSelect) {
                productTypeSelect.addEventListener('change', function() {
                    const val = this.value;
                    // Fetch categories for selected product type
                    const categoriesUrl = `{{ route('pos.categories') }}?product_type=${encodeURIComponent(val)}`;
                    console.debug('Fetching categories from', categoriesUrl);
                    fetch(categoriesUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(resp => {
                            if (!resp.ok) throw new Error('Categories request failed: ' + resp.status + ' ' +
                                resp.statusText);
                            return resp.json();
                        })
                        .then(data => {
                            populateCategoryOptions(data);
                            applyFilters(1);
                        })
                        .catch(err => {
                            console.error('Error fetching categories:', err);
                            // Even on error, still apply filters
                            applyFilters(1);
                        });
                });
            }

            // If there is an initial product type selected on page load, fetch matching categories
            if (productTypeSelect && productTypeSelect.value) {
                const ev = new Event('change');
                // Slight delay to allow other DOM init to complete
                setTimeout(() => productTypeSelect.dispatchEvent(ev), 50);
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    applyFilters(1);
                });
            }

            // Intercept form submission (Enter key or button click)
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    applyFilters(1);
                });
            }

            // Initial pagination link interception
            interceptPaginationLinks();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPosFilters);
        } else {
            initPosFilters();
        }

        // Navigation guards: prevent navigating away (dashboard/logout) when cart has items
        // pendingNavigation will be executed after a successful admin-void when set
        window.pendingNavigation = null;

        function showUnsavedCartModal(onProceed) {
            // If modal already exists reuse
            let modal = document.getElementById('unsavedCartModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'unsavedCartModal';
                modal.style.position = 'fixed';
                modal.style.left = '0';
                modal.style.top = '0';
                modal.style.width = '100vw';
                modal.style.height = '100vh';
                modal.style.background = 'rgba(0,0,0,0.6)';
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.style.zIndex = '999999';

                modal.innerHTML = `
                    <div style="background:#fff;border-radius:12px;padding:20px;max-width:520px;width:94%;box-shadow:0 10px 30px rgba(0,0,0,0.3);text-align:left;">
                        <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:#111">Cart has items</h3>
                        <p style="margin:0 0 12px;color:#444">You currently have items in the cart. You must either complete the order or void the entire sale (admin authorization required) before navigating away.</p>
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <!-- Void button intentionally de-emphasized: outline, small, separated from primary actions -->
                                <button id="unsavedVoidBtn" title="Admin only — use only when necessary" style="padding:6px 10px;border-radius:8px;background:transparent;border:1px solid #dc2626;color:#dc2626;cursor:pointer;font-weight:600;font-size:0.875rem;">Void Entire Sale</button>
                            </div>

                            <div style="display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap;">
                                <button id="unsavedCancelBtn" style="padding:8px 12px;border-radius:8px;background:#f3f4f6;border:1px solid #d1d5db;color:#111;cursor:pointer">Cancel</button>
                                <button id="unsavedCompleteBtn" style="padding:8px 12px;border-radius:8px;background:#047857;border:none;color:#fff;cursor:pointer">Complete Order</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                document.getElementById('unsavedCancelBtn').addEventListener('click', function() {
                    modal.style.display = 'none';
                });

                document.getElementById('unsavedCompleteBtn').addEventListener('click', function() {
                    modal.style.display = 'none';
                    // Open payment modal for completing the order
                    if (typeof openPaymentModal === 'function') {
                        openPaymentModal();
                    } else {
                        // Fallback: just show a toast
                        showToast('Open payment modal to complete order', 'info');
                    }
                });

                document.getElementById('unsavedVoidBtn').addEventListener('click', function() {
                    modal.style.display = 'none';
                    // Set pending navigation and open void sale modal for admin authorization
                    if (typeof onProceed === 'function') {
                        window.pendingNavigation = onProceed;
                    }
                    if (typeof openVoidSaleModal === 'function') {
                        openVoidSaleModal();
                    } else {
                        showToast('Open void sale dialog to authorize discard', 'info');
                    }
                });
            } else {
                modal.style.display = 'flex';
            }
        }

        function addNavigationGuards() {
            try {
                const dashboardUrl = '{{ route('dashboard') }}';

                // Guard dashboard links
                const dashboardLinks = Array.from(document.querySelectorAll(`a[href]`)).filter(a => a.href ===
                    dashboardUrl || a.getAttribute('href') === dashboardUrl);
                dashboardLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (cart && cart.length > 0) {
                            e.preventDefault();
                            // Pass a navigation callback that will execute AFTER a successful admin void
                            showUnsavedCartModal(() => {
                                window.location.href = link.href;
                            });
                        }
                    });
                });

                // Guard logout triggers (elements that call showLogoutModal)
                const logoutTriggers = Array.from(document.querySelectorAll('[onclick]')).filter(el => el.getAttribute(
                    'onclick') && el.getAttribute('onclick').includes('showLogoutModal'));
                logoutTriggers.forEach(el => {
                    // Remove inline handler and replace with safe handler
                    el.removeAttribute('onclick');
                    el.addEventListener('click', function(e) {
                        if (cart && cart.length > 0) {
                            e.preventDefault();
                            // Pass a callback that will show the logout modal after void
                            showUnsavedCartModal(() => {
                                if (typeof showLogoutModal === 'function') showLogoutModal();
                            });
                        } else {
                            if (typeof showLogoutModal === 'function') showLogoutModal();
                        }
                    });
                });
            } catch (err) {
                console.error('Error adding navigation guards:', err);
            }
        }

        // Call guards after initializing POS interactions
        try {
            addNavigationGuards();
        } catch (e) {
            console.error('Failed to initialize navigation guards:', e);
        }
    </script>
</x-pos-layout>
