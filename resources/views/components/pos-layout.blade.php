<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'iPharma Mart' }} - iPharma Mart</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/ipharma-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/ipharma-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Fullscreen POS Styles */
        .pos-fullscreen {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background: var(--color-bg-secondary);
        }

        .pos-container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .pos-header {
            background: var(--color-brand-green-dark);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .pos-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .fullscreen-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Hide scrollbar but keep functionality */
        .pos-body::-webkit-scrollbar {
            width: 8px;
        }

        .pos-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .pos-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .pos-body::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* Fullscreen Required Modal */
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
            background: linear-gradient(135deg, #2c6356 0%, #3a7d6f 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(44, 99, 86, 0.3);
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
            background: linear-gradient(135deg, #2c6356 0%, #3a7d6f 100%);
            color: white;
            font-weight: 600;
            font-size: 1.125rem;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(44, 99, 86, 0.3);
            width: 100%;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(44, 99, 86, 0.4);
        }

        .modal-btn:active {
            transform: translateY(0);
        }

        .modal-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.75rem;
            color: #6b7280;
            font-size: 0.875rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .modal-back-btn:hover {
            color: #374151;
        }

        .pos-content {
            filter: blur(5px);
            pointer-events: none;
        }

        .pos-content.active {
            filter: none;
            pointer-events: auto;
        }

        /* Fullscreen banner */
        #fullscreenBanner.hidden {
            display: none;
        }

        #fullscreenBanner {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 99998;
            pointer-events: none;
        }

        #fullscreenBanner .banner-content {
            pointer-events: auto;
            background: rgba(44, 99, 86, 0.95);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(6px);
            opacity: 0.95;
            cursor: pointer;
            user-select: none;
            font-size: 0.95rem;
        }
    </style>
</head>

<body class="pos-fullscreen">
    <!-- Fullscreen modal removed per UI revision (POS will attempt fullscreen without modal) -->

    <div class="pos-container pos-content" id="posContent">
        <!-- POS Header -->
        <div class="pos-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[var(--color-accent-orange)] rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold">iPharma Mart - Point of Sale</h1>
                    <p class="text-xs text-white/60">{{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="toggleFullscreen()" class="fullscreen-btn" id="fullscreenBtn"
                    title="Toggle Fullscreen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                </button>

                <a href="{{ route('dashboard') }}" class="fullscreen-btn" title="Back to Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline" id="posLogoutForm">
                    @csrf
                    <button type="button" onclick="showPosLogoutModal()" class="fullscreen-btn" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- POS Body -->
        <div class="pos-body">
            {{ $slot }}
        </div>
    </div>

    <!-- Unobtrusive fullscreen banner (shows when POS should attempt fullscreen) -->
    <div id="fullscreenBanner" class="hidden" aria-hidden="true" style="">
        <div class="banner-content">Click anywhere to enter fullscreen</div>
    </div>

    <script>
        // Fullscreen management (modal removed)
        const posContent = document.getElementById('posContent');

        // Function to enter fullscreen from modal or button. Returns a Promise<boolean>.
        async function enterFullscreen() {
            if (!document.fullscreenEnabled || !document.documentElement.requestFullscreen) {
                return false;
            }

            try {
                await document.documentElement.requestFullscreen();
                posContent.classList.add('active');
                try {
                    localStorage.setItem('posFullscreen', 'true');
                } catch (e) {}
                return true;
            } catch (err) {
                // Keep modal visible; advise manual fallback
                console.warn('Fullscreen request failed:', err);
                return false;
            }
        }

        // Fullscreen toggle for header button
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                enterFullscreen().then(success => {
                    if (!success) {
                        alert(
                            'Fullscreen could not be entered automatically. Please click the "Enter Fullscreen Mode" button.'
                        );
                    }
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Monitor fullscreen changes
        document.addEventListener('fullscreenchange', () => {
            const btn = document.getElementById('fullscreenBtn');

            if (document.fullscreenElement) {
                // In fullscreen mode
                btn.title = 'Exit Fullscreen';
                posContent.classList.add('active');
            } else {
                // Exited fullscreen mode - keep pos content interactive but re-enter fullscreen on Esc
                btn.title = 'Toggle Fullscreen';
                posContent.classList.remove('active');
            }
        });

        // Check fullscreen status on page load - bind a one-time user gesture to enter fullscreen
        window.addEventListener('DOMContentLoaded', () => {
            // If already in fullscreen, ensure posContent is active
            if (document.fullscreenElement) {
                posContent.classList.add('active');
                try {
                    localStorage.setItem('posFullscreen', 'true');
                } catch (e) {}
            }

            // Bind a persistent click/touch handler so clicking anywhere will attempt fullscreen
            function tryEnterOnUserGesture(e) {
                // If already in fullscreen, do nothing
                if (document.fullscreenElement) return;

                // Ignore explicit dashboard navigation clicks
                if (e && e.type === 'click') {
                    const dash = e.target.closest && e.target.closest('a[href*="dashboard"]');
                    if (dash) return;
                }

                // Attempt to enter fullscreen as a side-effect of user gesture
                enterFullscreen().catch(() => {});
            }

            if (!window._posFullscreenHandlerBound) {
                document.addEventListener('click', tryEnterOnUserGesture, true);
                document.addEventListener('touchstart', tryEnterOnUserGesture, true);
                window._posFullscreenHandlerBound = true;
            }

            // No modal: rely on server-side flag or persistent localStorage flag to require fullscreen
            const shouldAttempt = @json(session('enter_fullscreen', false)) || (function() {
                try {
                    return !!localStorage.getItem('posRequireFullscreen');
                } catch (e) {
                    return false;
                }
            })();

            if (shouldAttempt) {
                // Bind a one-time gesture to enter fullscreen (must be user gesture)
                // Persistent click/gesture handler that will always try to re-enter fullscreen
                function tryEnterOnUserGesture(e) {
                    // If already in fullscreen, do nothing
                    if (document.fullscreenElement) return;

                    // Ignore dashboard navigation clicks
                    if (e && e.type === 'click') {
                        const dash = e.target.closest && e.target.closest('a[href*="dashboard"]');
                        if (dash) return;
                    }

                    // Do not block the event; just attempt fullscreen as a side-effect
                    enterFullscreen().catch(() => {});
                }

                // Bind persistently so banner and clicks keep working after exit
                if (!window._posFullscreenHandlerBound) {
                    document.addEventListener('click', tryEnterOnUserGesture, true);
                    document.addEventListener('keydown', tryEnterOnUserGesture, true);
                    document.addEventListener('touchstart', tryEnterOnUserGesture, true);
                    window._posFullscreenHandlerBound = true;
                }

                // Show unobtrusive banner prompting user to click
                try {
                    const banner = document.getElementById('fullscreenBanner');
                    if (banner && !document.fullscreenElement) {
                        banner.classList.remove('hidden');

                        function onBannerClick(e) {
                            const dash = e.target.closest && e.target.closest('a[href*="dashboard"]');
                            if (dash) return;
                            enterFullscreen().then(() => {
                                try {
                                    banner.classList.add('hidden');
                                } catch (e) {}
                            });
                        }

                        banner.addEventListener('click', onBannerClick, true);

                        // keep persistent handler; just remove banner click listener
                        document.addEventListener('fullscreenchange', function _hideBanner() {
                            if (document.fullscreenElement) {
                                try {
                                    banner.classList.add('hidden');
                                } catch (e) {}
                                // keep persistent handler; just remove banner click listener
                                banner.removeEventListener('click', onBannerClick, true);
                            } else {
                                // If we exited fullscreen, show the banner again (if requirement is set)
                                try {
                                    banner.classList.remove('hidden');
                                } catch (e) {}
                            }
                        });
                    }
                } catch (e) {}
            }

            // Clear flag when leaving POS
            const dashboardLink = document.querySelector('a[href*="dashboard"]');
            const logoutForm = document.querySelector('form[action*="logout"]');

            if (dashboardLink) {
                dashboardLink.addEventListener('click', () => {
                    try {
                        localStorage.removeItem('posFullscreen');
                    } catch (e) {}
                });
            }

            if (logoutForm) {
                logoutForm.addEventListener('submit', () => {
                    try {
                        localStorage.removeItem('posFullscreen');
                    } catch (e) {}
                });
            }
        });

        // Show a minimal inline prompt to re-enter fullscreen
        function showQuickFullscreenPrompt() {
            const prompt = document.createElement('div');
            prompt.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: linear-gradient(135deg, #2c6356 0%, #3a7d6f 100%);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 99999;
                display: flex;
                align-items: center;
                gap: 1rem;
                animation: slideDown 0.3s ease;
                cursor: pointer;
                font-weight: 600;
            `;
            prompt.innerHTML = `
                <svg style="width: 24px; height: 24px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <span>Click here to return to fullscreen mode</span>
            `;

            // Add animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideDown {
                    from { transform: translateX(-50%) translateY(-100px); opacity: 0; }
                    to { transform: translateX(-50%) translateY(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);

            prompt.onclick = () => {
                document.documentElement.requestFullscreen()
                    .then(() => {
                        prompt.remove();
                        // modal removed; ensure posContent active
                        posContent.classList.add('active');
                    });
            };

            document.body.appendChild(prompt);

            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (prompt.parentNode) {
                    prompt.style.animation = 'slideDown 0.3s ease reverse';
                    setTimeout(() => prompt.remove(), 300);
                }
            }, 10000);
        }

        // Handle F11 key - enhance browser fullscreen and prevent exiting via Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'F11') {
                // Let browser handle F11 naturally; ensure our UI state updates
                setTimeout(() => {
                    if (document.fullscreenElement) {
                        posContent.classList.add('active');
                    }
                }, 100);
            }

            // Intercept Escape to prevent leaving fullscreen for POS users
            if (e.key === 'Escape') {
                // Prevent default behavior and re-enter fullscreen
                e.preventDefault();
                e.stopPropagation();
                enterFullscreen().catch(() => {});
            }
        });

        // Store fullscreen state in localStorage (persists across page loads)
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                localStorage.setItem('posFullscreen', 'true');
            } else {
                // Only remove if user manually exited (not from navigation)
                if (!window.navigating) {
                    localStorage.removeItem('posFullscreen');
                }
            }
        });

        // Track navigation state
        window.navigating = false;

        // Save fullscreen state before any navigation
        window.addEventListener('beforeunload', () => {
            window.navigating = true;
            if (document.fullscreenElement) {
                localStorage.setItem('posFullscreen', 'true');
            }
        });

        // Intercept all form submissions to save fullscreen state
        document.addEventListener('submit', (e) => {
            window.navigating = true;
            if (document.fullscreenElement) {
                localStorage.setItem('posFullscreen', 'true');
            }
        });

        // Intercept all link clicks to save fullscreen state
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (link && document.fullscreenElement) {
                window.navigating = true;
                localStorage.setItem('posFullscreen', 'true');
            }
        });

        // Initial state save if currently in fullscreen
        if (document.fullscreenElement) {
            localStorage.setItem('posFullscreen', 'true');
        }

        function showPosLogoutModal() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('posFullscreen');
                document.getElementById('posLogoutForm').submit();
            }
        }
    </script>

    <script>
        // Aggressively disable browser autocomplete/auto-fill for security
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Ensure forms declare autocomplete off
                document.querySelectorAll('form').forEach(function(f) {
                    f.setAttribute('autocomplete', 'off');
                });

                // Apply to all inputs, textareas and selects
                document.querySelectorAll('input,textarea,select').forEach(function(el) {
                    var tag = el.tagName.toLowerCase();
                    var type = (el.getAttribute('type') || '').toLowerCase();

                    // Set password fields to new-password to avoid stored password suggestions
                    if (type === 'password') {
                        el.setAttribute('autocomplete', 'new-password');
                    } else if (!el.hasAttribute('autocomplete') || el.getAttribute('autocomplete') ===
                        'on') {
                        el.setAttribute('autocomplete', 'off');
                    }
                });

                // Add an invisible dummy input at the start of each form to discourage autofill
                document.querySelectorAll('form').forEach(function(f) {
                    if (!f.querySelector('input[name="__no_autofill"]')) {
                        var dummy = document.createElement('input');
                        dummy.type = 'text';
                        dummy.name = '__no_autofill';
                        dummy.autocomplete = 'off';
                        dummy.style.cssText =
                            'position:absolute;left:-9999px;top:-9999px;opacity:0;height:0;width:0;border:0;padding:0;margin:0;pointer-events:none;';
                        f.prepend(dummy);
                    }
                });
            } catch (e) {
                console.log('Auto-complete disable script error:', e);
            }
        });
    </script>
</body>

</html>
