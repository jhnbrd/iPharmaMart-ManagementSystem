<x-pos-layout title="Point of Sale">
    <style>
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
    </style>

    <div class="pos-grid">
        <!-- Left Panel: Products -->
        <div class="left-panel">
            <!-- Search Bar -->
            <div class="product-search">
                <form id="filterForm" class="flex gap-3" action="javascript:void(0);">
                    <div class="relative" style="flex: 2;">
                        <input type="text" name="search" id="searchProduct"
                            placeholder="Search products by name or ID..." class="form-input pl-10 w-full text-base"
                            value="{{ request('search') }}">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <select name="product_type" id="productTypeFilter" class="form-select"
                        style="flex: 1; min-width: 140px;" onchange="applyFilters()">
                        <option value="">All Types</option>
                        <option value="pharmacy" {{ request('product_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy
                        </option>
                        <option value="mini_mart" {{ request('product_type') == 'mini_mart' ? 'selected' : '' }}>Mini
                            Mart</option>
                    </select>
                    <select name="category" id="categoryFilter" class="form-select" style="flex: 1; min-width: 150px;"
                        onchange="applyFilters()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" onclick="applyFilters(1)" class="btn btn-primary" style="min-width: 80px;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Products List -->
            <div class="products-container">
                <div id="productsGrid" class="product-list">
                    @foreach ($products as $product)
                        @php
                            $stockStatus = 'stock-good';
                            $stockText = 'In Stock';
                            if ($product->stock <= 0) {
                                $stockStatus = 'stock-out';
                                $stockText = 'Out of Stock';
                            } elseif ($product->stock <= 10) {
                                $stockStatus = 'stock-low';
                                $stockText = 'Low Stock';
                            }
                        @endphp
                        <div class="product-item" data-category="{{ $product->category_id }}"
                            data-name="{{ strtolower($product->name) }}" data-id="{{ $product->id }}"
                            data-type="{{ $product->product_type }}"
                            onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, '{{ addslashes($product->category->name) }}')">
                            <div class="product-info">
                                <div class="product-name" title="{{ $product->name }}">{{ $product->name }}</div>
                                <div class="product-category">
                                    {{ $product->category->name }}
                                    <span class="text-xs text-gray-400">•
                                        {{ ucfirst(str_replace('_', ' ', $product->product_type)) }}</span>
                                </div>
                            </div>
                            <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                            <div class="product-stock {{ $stockStatus }}">
                                {{ $stockText }}: {{ $product->stock }}
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
            <!-- Customer Section -->
            <div class="customer-section">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-semibold text-gray-700">Customer</label>
                    <button onclick="openCustomerModal()"
                        class="text-[var(--color-brand-green)] hover:text-[var(--color-brand-green-dark)] text-sm font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New
                    </button>
                </div>
                <div id="customerDisplay" class="text-sm text-gray-600 italic">Walk-in Customer</div>
                <input type="hidden" id="customerId" value="">
            </div>

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
                        <button onclick="clearCart()" class="btn btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Clear
                        </button>
                        <button onclick="processCheckout()" id="checkoutBtn" class="btn btn-primary" disabled>
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
                        <input type="text" id="customerName" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Phone Number *</label>
                        <input type="text" id="customerPhone" class="form-input" placeholder="+63 9XX XXX XXXX"
                            required>
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
                                    <select id="existingCustomerSelect" class="form-select mt-1 text-xs" disabled
                                        onchange="updateSelectedCustomer()" onclick="event.stopPropagation()">
                                        <option value="">Choose a customer...</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                data-phone="{{ $customer->phone }}">
                                                {{ $customer->name }} - {{ $customer->phone }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                            placeholder="Customer Name" disabled onclick="event.stopPropagation()">
                                        <input type="text" id="newCustomerPhone" class="form-input text-xs"
                                            placeholder="Phone Number" disabled onclick="event.stopPropagation()">
                                        <input type="text" id="newCustomerAddress" class="form-input text-xs"
                                            placeholder="Address (optional)" disabled
                                            onclick="event.stopPropagation()">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="form-label text-xs">Payment Method *</label>
                        <select id="paymentMethod" class="form-select text-sm" onchange="toggleReferenceNumber()">
                            <option value="cash">💵 Cash</option>
                            <option value="gcash">📱 GCash</option>
                            <option value="card">💳 Card</option>
                        </select>
                    </div>

                    <!-- Reference Number (for GCash/Card) -->
                    <div id="referenceNumberDiv" style="display: none;">
                        <label class="form-label text-xs">Reference Number *</label>
                        <input type="text" id="referenceNumber" class="form-input text-sm"
                            placeholder="Enter transaction reference">
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
                            autocomplete="off">
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
    </div>

    <script>
        let cart = [];
        let currentCustomer = null;

        function addToCart(productId, productName, price, stock, category) {
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    alert('Not enough stock available!');
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
            }

            updateCart();
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                const newQuantity = item.quantity + change;
                if (newQuantity > 0 && newQuantity <= item.stock) {
                    item.quantity = newQuantity;
                } else if (newQuantity > item.stock) {
                    alert('Not enough stock available!');
                    return;
                }
                updateCart();
            }
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Void Item Functions
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
                        // Remove item from cart
                        removeFromCart(productId);
                        closeVoidItemModal();

                        // Show success message
                        alert(`Item voided successfully by ${data.admin_name}`);
                    } else {
                        // Show error
                        const errorDiv = document.getElementById('voidAuthError');
                        errorDiv.textContent = data.message || 'Invalid admin credentials';
                        errorDiv.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Void verification error:', error);
                    const errorDiv = document.getElementById('voidAuthError');
                    errorDiv.textContent = 'Error verifying credentials. Please try again.';
                    errorDiv.classList.remove('hidden');
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
                    <tr>
                        <td>
                            <div class="font-medium text-sm">${item.name}</div>
                            <div class="text-xs text-gray-500">${item.category}</div>
                        </td>
                        <td>
                            <div class="qty-controls">
                                <button onclick="updateQuantity(${item.id}, -1)" class="qty-btn">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span class="font-medium px-2">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, 1)" class="qty-btn">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="text-right">₱${item.price.toFixed(2)}</td>
                        <td class="text-right font-semibold">₱${subtotal.toFixed(2)}</td>
                        <td>
                            <button onclick="openVoidItemModal(${item.id}, '${item.name.replace(/'/g, "\\'")}')" class="text-red-600 hover:text-red-800" title="Void Item">
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
            const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.12; // 12% VAT
            const total = subtotal + tax;

            document.getElementById('itemCount').textContent = itemCount;
            document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('total').textContent = `₱${total.toFixed(2)}`;

            // Enable checkout button if cart has items
            document.getElementById('checkoutBtn').disabled = cart.length === 0;
        }

        function clearCart() {
            if (cart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
                cart = [];
                updateCart();
            }
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
            const existingSelect = document.getElementById('existingCustomerSelect');
            const newCustomerForm = document.getElementById('newCustomerForm');
            const newCustomerInputs = newCustomerForm.querySelectorAll('input');

            // Reset all options to default state
            existingSelect.disabled = true;
            existingSelect.value = '';
            existingSelect.style.pointerEvents = 'none';
            newCustomerForm.style.display = 'none';
            newCustomerInputs.forEach(input => {
                input.disabled = true;
                input.value = '';
                input.style.pointerEvents = 'none';
            });

            // Enable based on selection
            if (option === 'existing') {
                existingSelect.disabled = false;
                existingSelect.style.pointerEvents = 'auto';
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

        function updateSelectedCustomer() {
            const select = document.getElementById('existingCustomerSelect');
            if (select.value) {
                const option = select.options[select.selectedIndex];
                currentCustomer = {
                    id: select.value,
                    name: option.getAttribute('data-name'),
                    phone: option.getAttribute('data-phone')
                };
            }
        }

        // Payment Modal Functions
        function openPaymentModal() {
            if (cart.length === 0) {
                alert('Cart is empty! Please add items before checkout.');
                return;
            }

            // Update modal summary
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.12;
            const total = subtotal + tax;

            document.getElementById('modalSubtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('modalTax').textContent = `₱${tax.toFixed(2)}`;
            document.getElementById('modalTotal').textContent = `₱${total.toFixed(2)}`;

            // Reset payment inputs
            document.getElementById('paymentMethod').value = 'cash';
            document.getElementById('paidAmount').value = '';
            document.getElementById('referenceNumber').value = '';
            document.getElementById('changeAmount').textContent = '₱0.00';
            document.getElementById('referenceNumberDiv').style.display = 'none';

            // Reset customer selection
            document.querySelector('input[name="customerOption"][value="walkin"]').checked = true;
            selectCustomerOption('walkin');
            currentCustomer = null;

            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        function toggleReferenceNumber() {
            const method = document.getElementById('paymentMethod').value;
            const refDiv = document.getElementById('referenceNumberDiv');
            refDiv.style.display = (method === 'gcash' || method === 'card') ? 'block' : 'none';
        }

        function calculateChange() {
            const total = parseFloat(document.getElementById('modalTotal').textContent.replace('₱', '').replace(',',
                '')) || 0;
            const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
            const change = Math.max(0, paid - total);

            document.getElementById('changeAmount').textContent = `₱${change.toFixed(2)}`;

            // Enable/disable confirm button
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            confirmBtn.disabled = paid < total;
        }

        async function confirmPayment() {
            try {
                // Validate inputs
                const paymentMethod = document.getElementById('paymentMethod').value;
                const paidAmount = parseFloat(document.getElementById('paidAmount').value);
                const total = parseFloat(document.getElementById('modalTotal').textContent.replace('₱', '').replace(',',
                    ''));

                if (!paidAmount || paidAmount < total) {
                    alert('Please enter an amount greater than or equal to the total.');
                    return;
                }

                if ((paymentMethod === 'gcash' || paymentMethod === 'card') && !document.getElementById(
                        'referenceNumber').value.trim()) {
                    alert('Please enter a reference number for ' + paymentMethod.toUpperCase() + ' payment.');
                    return;
                }

                // Handle customer selection
                const customerOption = document.querySelector('input[name="customerOption"]:checked').value;
                let customerData = null;

                if (customerOption === 'existing') {
                    const existingSelect = document.getElementById('existingCustomerSelect');
                    if (!existingSelect.value) {
                        alert('Please select a customer from the list.');
                        return;
                    }
                    customerData = {
                        type: 'existing',
                        id: existingSelect.value
                    };
                } else if (customerOption === 'new') {
                    const name = document.getElementById('newCustomerName').value.trim();
                    const phone = document.getElementById('newCustomerPhone').value.trim();
                    const address = document.getElementById('newCustomerAddress').value.trim();

                    if (!name || !phone) {
                        alert('Please fill in customer name and phone number.');
                        return;
                    }

                    customerData = {
                        type: 'new',
                        name: name,
                        phone: phone,
                        address: address
                    };
                }

                // Prepare form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('payment_method', paymentMethod);
                formData.append('paid_amount', paidAmount);
                formData.append('change_amount', paidAmount - total);

                if (paymentMethod === 'gcash' || paymentMethod === 'card') {
                    formData.append('reference_number', document.getElementById('referenceNumber').value.trim());
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

                cart.forEach((item, index) => {
                    formData.append(`items[${index}][product_id]`, item.id);
                    formData.append(`items[${index}][quantity]`, item.quantity);
                });

                // Disable button during processing
                const confirmBtn = document.getElementById('confirmPaymentBtn');
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = 'Processing...';

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
                } else {
                    throw new Error(data.message || 'Failed to process sale');
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
                // Re-enable button
                const confirmBtn = document.getElementById('confirmPaymentBtn');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Confirm & Print Receipt';
            }
        }

        function processCheckout() {
            openPaymentModal();
        }

        // Receipt Modal Functions
        function showReceipt(receiptData) {
            const receiptContent = document.getElementById('receiptContent');

            const customerInfo = receiptData.customer ?
                `<div class="text-sm mb-3">
                    <div><strong>Customer:</strong> ${receiptData.customer.name}</div>
                    <div><strong>Phone:</strong> ${receiptData.customer.phone}</div>
                    ${receiptData.customer.address ? `<div><strong>Address:</strong> ${receiptData.customer.address}</div>` : ''}
                </div>` :
                `<div class="text-sm mb-3 text-gray-600 italic">Walk-in Customer</div>`;

            const referenceInfo = receiptData.reference_number ?
                `<div class="text-sm">
                    <strong>Reference #:</strong> ${receiptData.reference_number}
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

                <div class="border-t-2 border-gray-300 pt-2 mb-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Subtotal:</span>
                        <span>₱${parseFloat(receiptData.subtotal).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>VAT (12%):</span>
                        <span>₱${parseFloat(receiptData.tax).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2 mb-3">
                        <span>TOTAL:</span>
                        <span>₱${parseFloat(receiptData.total).toFixed(2)}</span>
                    </div>
                </div>

                <div class="border-t-2 border-gray-300 pt-2 mb-3 text-sm">
                    <div class="flex justify-between mb-1">
                        <span><strong>Payment Method:</strong></span>
                        <span class="uppercase">${receiptData.payment_method}</span>
                    </div>
                    ${referenceInfo}
                    <div class="flex justify-between mb-1">
                        <span><strong>Amount Paid:</strong></span>
                        <span>₱${parseFloat(receiptData.paid_amount).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between font-bold text-base">
                        <span>Change:</span>
                        <span>₱${parseFloat(receiptData.change_amount).toFixed(2)}</span>
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

            // Clear cart and reset
            cart = [];
            currentCustomer = null;
            updateCart();

            // Reset customer display
            document.getElementById('customerId').value = '';
            document.getElementById('customerDisplay').innerHTML =
                '<div class="text-sm text-gray-600 italic">Walk-in Customer</div>';

            // Show success message
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.innerHTML = '✓ Ready for new transaction';
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 2000);
        }

        // Search form auto-submit with debounce
        let searchTimeout;
        document.getElementById('searchProduct').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });

        // Close modals when clicking outside
        document.getElementById('customerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCustomerModal();
            }
        });

        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        // AJAX Filter and Pagination (keeps fullscreen mode)
        let filterTimeout;
        let currentPage = {{ $products->currentPage() }};

        // Apply filters with AJAX
        function applyFilters(page = 1) {
            const search = document.getElementById('searchProduct').value;
            const productType = document.getElementById('productTypeFilter').value;
            const category = document.getElementById('categoryFilter').value;

            // Build query string
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (productType) params.append('product_type', productType);
            if (category) params.append('category', category);
            if (page > 1) params.append('page', page);

            // Show loading state
            const productsGrid = document.getElementById('productsGrid');
            productsGrid.style.opacity = '0.5';
            productsGrid.style.pointerEvents = 'none';

            // Fetch filtered products
            fetch(`{{ route('pos.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Update products grid
                    const newProductsGrid = doc.getElementById('productsGrid');
                    if (newProductsGrid) {
                        productsGrid.innerHTML = newProductsGrid.innerHTML;
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
                    productsGrid.style.opacity = '1';
                    productsGrid.style.pointerEvents = 'auto';

                    currentPage = page;
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    productsGrid.style.opacity = '1';
                    productsGrid.style.pointerEvents = 'auto';
                    alert('Error loading products. Please try again.');
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

        // Search input with debounce
        document.getElementById('searchProduct').addEventListener('input', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                applyFilters(1);
            }, 500);
        });

        // Intercept form submission (Enter key or button click)
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters(1);
        });

        // Initial pagination link interception
        document.addEventListener('DOMContentLoaded', function() {
            interceptPaginationLinks();
        });
    </script>
</x-pos-layout>
