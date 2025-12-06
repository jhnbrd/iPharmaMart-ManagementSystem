@php
    $currentRoute = request()->route()->getName();
@endphp

<aside class="sidebar">
    <!-- Sticky Logo -->
    <div class="sidebar-logo sticky top-0 bg-[var(--color-brand-green)] z-10">
        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center p-1">
            <img src="{{ asset('images/logo/ipharma-logo.png') }}" alt="iPharma Mart" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="text-lg font-bold">iPharma Mart</h1>
            <p class="text-xs text-white/60">Management System</p>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto scrollbar-hide">

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="sidebar-nav-item {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            @if (in_array(auth()->user()->role, ['admin', 'cashier']))
                <!-- SALES SECTION -->
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-white/50 uppercase tracking-wider">Sales</h3>
                </div>

                @if (auth()->user()->role === 'cashier')
                    <a href="{{ route('pos.index') }}"
                        class="sidebar-nav-item {{ str_starts_with($currentRoute, 'pos.') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Point of Sale</span>
                    </a>
                @endif

                <a href="{{ route('sales.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'sales.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Sales History</span>
                </a>

                <a href="{{ route('customers.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'customers.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Customers</span>
                </a>

                <a href="{{ route('discounts.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'discounts.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Discounts</span>
                </a>
            @endif

            @if (in_array(auth()->user()->role, ['admin', 'inventory_manager']))
                <!-- INVENTORY SECTION -->
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-white/50 uppercase tracking-wider">Inventory</h3>
                </div>

                <a href="{{ route('inventory.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'inventory.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Products</span>
                </a>

                <a href="{{ route('stock.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'stock.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Stock Movements</span>
                </a>

                <a href="{{ route('inventory.shelf-movements.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'inventory.shelf-movements') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Shelf Movements</span>
                </a>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('suppliers.index') }}"
                        class="sidebar-nav-item {{ str_starts_with($currentRoute, 'suppliers.') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Suppliers</span>
                    </a>
                @endif
            @endif

            @if (auth()->user()->role === 'admin')
                <!-- REPORTS SECTION -->
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-white/50 uppercase tracking-wider">Reports</h3>
                </div>

                <a href="{{ route('reports.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'reports.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Reports</span>
                </a>
            @endif

            @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
                <!-- SETTINGS SECTION -->
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-white/50 uppercase tracking-wider">System</h3>
                </div>

                <a href="{{ route('settings.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'settings.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>General Settings</span>
                </a>
            @endif

            @if (auth()->user()->role === 'superadmin')
                <!-- ADMINISTRATION SECTION -->
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-white/50 uppercase tracking-wider">Administration</h3>
                </div>

                <a href="{{ route('users.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'users.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Users</span>
                </a>

                <a href="{{ route('audit-logs.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'audit-logs.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Audit Logs</span>
                </a>
            @endif
        </nav>
    </div>
</aside>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fullscreen-modal hidden">
    <div class="modal-content">
        <div class="modal-icon">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </div>
        <h2 class="modal-title">Confirm Logout</h2>
        <p class="modal-text">
            Are you sure you want to logout? You will need to sign in again to access your account.
        </p>
        <button onclick="confirmLogout()" class="modal-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Yes, Logout
        </button>
        <button onclick="closeLogoutModal()" class="modal-back-btn">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Cancel
        </button>
    </div>
</div>

<style>
    /* Modal styles matching POS layout */
    .fullscreen-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        backdrop-filter: blur(10px);
    }

    .fullscreen-modal.hidden {
        display: none;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        padding: 3rem;
        max-width: 500px;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
    }

    .modal-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .modal-text {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .modal-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.3);
        width: 100%;
        margin-bottom: 1rem;
    }

    .modal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px rgba(220, 38, 38, 0.4);
    }

    .modal-btn:active {
        transform: translateY(0);
    }

    .modal-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        color: #6b7280;
        font-size: 0.875rem;
        background: none;
        border: none;
        cursor: pointer;
        transition: color 0.2s;
    }

    .modal-back-btn:hover {
        color: #374151;
    }
</style>

<script>
    function showLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }

    function confirmLogout() {
        document.getElementById('logoutForm').submit();
    }

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>
