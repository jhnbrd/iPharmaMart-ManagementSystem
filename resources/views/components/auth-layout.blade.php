<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - iPharma Mart</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ipharma-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/ipharma-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[var(--color-bg-secondary)]">
    {{ $slot }}
</body>

</html>
