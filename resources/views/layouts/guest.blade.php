<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Wholesale Portal' }} | {{ config('app.name', '99 Bulk Sales') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 flex flex-col justify-between">
    <!-- Top Brand Navbar -->
    <header class="w-full bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="bg-slate-950 p-2 sm:px-3 sm:py-2 rounded-2xl shadow-md border border-slate-800 flex items-center group-hover:border-lime-500/50 transition-all">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'N&J mini mart') }}" class="h-8 sm:h-10 w-auto object-contain">
                </div>
            </a>

            <!-- Quick Links -->
            <div class="flex items-center gap-4 text-sm font-medium">
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-600 flex items-center gap-1.5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Storefront</span>
                </a>
                <span class="text-slate-300">|</span>
                <a href="https://wa.me/60128899900" target="_blank" rel="noopener" class="text-slate-600 hover:text-emerald-600 flex items-center gap-1.5 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Support: +60 12-889 9900</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Auth Content Area -->
    <main class="grow flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white border-t border-slate-200/80 py-5 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                &copy; {{ date('Y') }} 99 Bulk Sales (Nabeel Store). Direct Factory Carton Wholesale. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('page.show', 'privacy-policy') }}" class="hover:text-emerald-600 transition-colors">Privacy Policy</a>
                <a href="{{ route('page.show', 'terms-and-conditions') }}" class="hover:text-emerald-600 transition-colors">Terms of Service</a>
                <a href="{{ route('page.show', 'security-policy') }}" class="hover:text-emerald-600 transition-colors">Security Policy</a>
                <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-medium">
                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                    </svg>
                    256-Bit SSL Protected
                </span>
            </div>
        </div>
    </footer>
</body>
</html>
