@props(['title' => 'Dashboard'])

<header class="main-header">
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleMobileMenu()" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg"
            aria-label="Toggle menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-2xl font-bold">{{ $title }}</h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Search - Hidden on mobile -->
        <div class="relative hidden md:block">
        </div>
    </div>
</header>
