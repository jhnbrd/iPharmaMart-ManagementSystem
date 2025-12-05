<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - iPharma Mart</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/ipharma-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/ipharma-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[var(--color-bg-secondary)]">
    {{ $slot }}

    <script>
        // Global fallback to disable autocomplete/auto-fill for auth pages
        document.addEventListener('DOMContentLoaded', function() {
            try {
                document.querySelectorAll('form').forEach(function(f) {
                    f.setAttribute('autocomplete', 'off');
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

                document.querySelectorAll('input,textarea,select').forEach(function(el) {
                    var type = (el.getAttribute('type') || '').toLowerCase();
                    if (type === 'password') {
                        el.setAttribute('autocomplete', 'new-password');
                    } else if (!el.hasAttribute('autocomplete') || el.getAttribute('autocomplete') ===
                        'on') {
                        el.setAttribute('autocomplete', 'off');
                    }
                });
            } catch (e) {
                console.log('Auth autocomplete script error:', e);
            }
        });
    </script>
</body>

</html>
