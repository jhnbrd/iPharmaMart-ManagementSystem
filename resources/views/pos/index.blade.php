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
            overflow-y: auto;
            flex: 1;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .product-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            border-radius: 0.75rem;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .product-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 99, 86, 0.2);
            border-color: var(--color-brand-green);
        }

        .product-item.selected {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: var(--color-brand-green);
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
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
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

        .cart-items-container {
            flex: 1;
            overflow-y: auto;
            max-height: 300px;
        }

        .total-section {
            background: var(--color-brand-green-dark);
            color: white;
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
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
            margin-top: 1rem;
        }
    </style>

    <div class="pos-grid">
        <!-- Left Panel: Products -->
        <div class="left-panel">
            <!-- Search Bar -->
            <div class="product-search">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <input type="text" id="searchProduct" placeholder="Search products by name or ID..."
                            class="form-input pl-10 w-full" onkeyup="filterProducts()">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <select id="categoryFilter" class="form-select w-48" onchange="filterProducts()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-container">
                <div id="productsGrid" class="product-grid">
                    @foreach ($products as $product)
                        <div class="product-item" data-category="{{ $product->category_id }}"
                            data-name="{{ strtolower($product->name) }}" data-id="{{ $product->id }}"
                            onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, '{{ addslashes($product->category->name) }}')">
                            <div class="mb-2">
                                <svg class="w-10 h-10 mx-auto text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-xs mb-1 line-clamp-2 min-h-[2rem]">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-600 mb-1">{{ $product->category->name }}</p>
                            <p class="text-base font-bold text-[var(--color-brand-green)]">
                                ₱{{ number_format($product->price, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Stock: {{ $product->stock }}</p>
                        </div>
                    @endforeach
                </div>
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
                <h3 class="font-semibold text-gray-900 mb-3">Order Items</h3>

                <div class="cart-items-container">
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

                <div class="total-section">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Tax (10%):</span>
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

                <div class="action-buttons">
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
                            <button onclick="removeFromCart(${item.id})" class="text-red-600 hover:text-red-800">
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
            const tax = subtotal * 0.10;
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

        function processCheckout() {
            if (cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            // If no customer, create as walk-in with temp ID
            let customerId = document.getElementById('customerId').value || null;

            // Prepare data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            // If new customer was added, send customer data
            if (currentCustomer && !customerId) {
                formData.append('customer_name', currentCustomer.name);
                formData.append('customer_phone', currentCustomer.phone);
                formData.append('customer_address', currentCustomer.address || '');
            } else if (customerId) {
                formData.append('customer_id', customerId);
            }

            formData.append('total_amount', document.getElementById('total').textContent.replace('₱', '').replace(',', ''));

            cart.forEach((item, index) => {
                formData.append(`items[${index}][product_id]`, item.id);
                formData.append(`items[${index}][quantity]`, item.quantity);
            });

            // Submit
            fetch('{{ route('sales.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Sale completed successfully!');
                        cart = [];
                        currentCustomer = null;
                        updateCart();
                        document.getElementById('customerId').value = '';
                        document.getElementById('customerDisplay').innerHTML =
                            '<div class="text-sm text-gray-600 italic">Walk-in Customer</div>';

                        if (data.sale_id) {
                            window.location.href = `/sales/${data.sale_id}`;
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Failed to process sale'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the sale');
                });
        }

        function filterProducts() {
            const searchText = document.getElementById('searchProduct').value.toLowerCase();
            const categoryId = document.getElementById('categoryFilter').value;
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                const name = product.getAttribute('data-name');
                const id = product.getAttribute('data-id');
                const category = product.getAttribute('data-category');

                const matchesSearch = name.includes(searchText) || id.includes(searchText);
                const matchesCategory = !categoryId || category === categoryId;

                if (matchesSearch && matchesCategory) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('customerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCustomerModal();
            }
        });
    </script>
</x-pos-layout>
