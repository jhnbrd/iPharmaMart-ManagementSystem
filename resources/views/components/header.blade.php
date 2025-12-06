@props(['title' => 'Dashboard', 'subtitle' => ''])

<header class="main-header">
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleMobileMenu()" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg"
            aria-label="Toggle menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <h1 class="text-2xl font-bold">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-[var(--color-text-secondary)] mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <div
                    class="w-8 h-8 bg-[var(--color-brand-green)] text-white rounded-full flex items-center justify-center">
                    <span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-transition
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile Settings</span>
                    </div>
                </a>
                <hr class="my-1 border-gray-200">
                <form method="POST" action="{{ route('logout') }}" id="headerLogoutForm">
                    @csrf
                    <button type="button" onclick="showHeaderLogoutModal()"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Logout Confirmation Modal -->
<div id="headerLogoutModal" class="fullscreen-modal hidden">
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
        <button onclick="confirmHeaderLogout()" class="modal-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Yes, Logout
        </button>
        <button onclick="closeHeaderLogoutModal()" class="modal-back-btn">
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
    function showHeaderLogoutModal() {
        document.getElementById('headerLogoutModal').classList.remove('hidden');
    }

    function closeHeaderLogoutModal() {
        document.getElementById('headerLogoutModal').classList.add('hidden');
    }

    function confirmHeaderLogout() {
        document.getElementById('headerLogoutForm').submit();
    }

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeHeaderLogoutModal();
        }
    });

    // Alpine.js is required for dropdown functionality
    if (typeof Alpine === 'undefined') {
        document.addEventListener('DOMContentLoaded', function() {
            // Fallback dropdown without Alpine
            document.querySelectorAll('[x-data]').forEach(el => {
                const button = el.querySelector('button');
                const menu = el.querySelector('[x-show]');
                if (button && menu) {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation();
                        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                    });
                    document.addEventListener('click', () => {
                        menu.style.display = 'none';
                    });
                }
            });
        });
    }
</script>
