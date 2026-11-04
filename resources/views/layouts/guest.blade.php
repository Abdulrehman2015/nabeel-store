<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Customer Account' }} | {{ config('app.name', 'N&J Mini Mart') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="auth-page min-h-full antialiased flex flex-col">
    <header class="auth-header">
        <div class="auth-header-inner">
            <a href="{{ route('home') }}" aria-label="{{ config('app.name', 'N&J Mini Mart') }} home" class="auth-header-brand">
                <span class="auth-header-mark">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'N&J Mini Mart') }}" class="auth-header-logo">
                </span>
                <span class="auth-header-context">
                    <strong>DIRECT GROCERY</strong>
                    <span>Everyday essentials & delivery</span>
                </span>
            </a>
            <div class="flex items-center gap-5 sm:gap-7">
                <a href="{{ route('home') }}" class="auth-header-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back to store</span>
                </a>
                <a href="https://wa.me/60128899900" target="_blank" rel="noopener" class="auth-support">Support +60 12-889 9900</a>
            </div>
        </div>
    </header>

    <main class="auth-main">
        {{ $slot }}
    </main>

    <footer class="auth-footer">© {{ date('Y') }} N&J Mini Mart · Secure customer account access</footer>
</body>
</html>
