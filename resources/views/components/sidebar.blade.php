@php
    $currentRoute = request()->route()->getName();
@endphp

<aside class="sidebar">
    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center p-1">
                <img src="{{ asset('images/logo/ipharma-logo.png') }}" alt="iPharma Mart"
                    class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-lg font-bold">iPharma Mart</h1>
                <p class="text-xs text-white/60">Management System</p>
            </div>
        </div>

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

            @if (auth()->user()->role === 'cashier')
                <!-- SALES SECTION - Cashier Only -->
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-white/50 uppercase tracking-wider">Sales</h3>
                </div>

                <a href="{{ route('pos.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'pos.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Point of Sale</span>
                </a>

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
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Stock In/Out</span>
                </a>

                <a href="{{ route('suppliers.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'suppliers.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Suppliers</span>
                </a>

                <a href="{{ route('customers.index') }}"
                    class="sidebar-nav-item {{ str_starts_with($currentRoute, 'customers.') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Customers</span>
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

    <!-- Fixed Footer: User Profile & Logout -->
    <div class="px-4 py-4 border-t border-white/10 bg-[var(--color-primary)]">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-[var(--color-accent-orange)] rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-white/60 text-xs truncate">{{ '@' . auth()->user()->username }}</p>
                    <p class="text-white/50 text-xs truncate">
                        @if (auth()->user()->role === 'superadmin')
                            ⚡ Super Admin
                        @elseif(auth()->user()->role === 'admin')
                            👑 Admin
                        @elseif(auth()->user()->role === 'cashier')
                            💰 Cashier
                        @else
                            📦 Inventory Manager
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full btn btn-secondary flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
