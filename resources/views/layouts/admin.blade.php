<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') | {{ setting('site_name', '99 Bulk Sales') }} Admin</title>

    {{-- Google Fonts: Plus Jakarta Sans & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Leaflet for Store Location Picker --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Chart.js for Sales Trends --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            font-family: var(--font-family);
        }
    </style>
</head>
<body class="h-full flex antialiased text-slate-800 bg-[#f8fafc]" x-data="{
    sidebarOpen: false,
    bellCount: {{ \App\Models\Order::where('order_status', 'pending_payment_verification')->count() }},
    async pollNotifications() {
        try {
            let res = await fetch('{{ route("admin.notifications.poll") }}');
            let data = await res.json();
            this.bellCount = data.pending_payments;
        } catch(e) {}
    }
}" x-init="setInterval(() => pollNotifications(), 30000)">

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden" @click="sidebarOpen = false"></div>

    {{-- Admin Sidebar Layout --}}
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-950 text-slate-300 flex flex-col justify-between transition-transform duration-300 transform lg:translate-x-0 border-r border-slate-900 shadow-xl"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <div class="flex-1 overflow-y-auto no-scrollbar">
            {{-- Admin Brand Header --}}
            <div class="h-16 flex items-center justify-between px-5 bg-slate-950 border-b border-slate-900 sticky top-0 z-10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ setting('site_name', 'N&J mini mart') }}" class="h-8 w-auto object-contain">
                    <span class="text-[10px] font-bold text-sky-400 bg-sky-950/70 border border-sky-800/60 px-1.5 py-0.5 rounded uppercase tracking-wider">Admin</span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Navigation Menu --}}
            <nav class="p-3.5 space-y-1 text-xs font-semibold">
                
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    <span>Dashboard</span>
                </a>

                {{-- Orders (with pending badge) --}}
                @php
                    $pendingCount = \App\Models\Order::where('order_status', 'pending_payment_verification')->count();
                @endphp
                <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Orders</span>
                    </div>
                    @if($pendingCount > 0)
                        <span class="bg-amber-400 text-slate-950 font-black text-[10px] px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Catalog & Bulk</div>

                {{-- Products --}}
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Products (Cartons)</span>
                </a>

                {{-- Categories --}}
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span>Categories</span>
                </a>

                {{-- Brands --}}
                <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.brands.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.brands.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>Brands</span>
                </a>

                {{-- Customers --}}
                <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.customers.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Customers</span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Storefront CMS</div>

                {{-- Homepage Settings --}}
                <a href="{{ route('admin.settings.homepage') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.homepage') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.homepage') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Homepage Settings</span>
                </a>

                {{-- Banners / Slider --}}
                <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.banners.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.banners.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Hero Banners</span>
                </a>

                {{-- Home Sections Manager --}}
                <a href="{{ route('admin.home-sections.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.home-sections.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.home-sections.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Homepage Sections</span>
                </a>

                {{-- Legal & Policy Pages --}}
                <a href="{{ route('admin.policies.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.policies.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.policies.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Legal & Policies</span>
                </a>

                {{-- Contact Messages --}}
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.contact-messages.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.contact-messages.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Inquiries</span>
                </a>

                {{-- Data Requests (GDPR / PDPA) --}}
                <a href="{{ route('admin.data-requests.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.data-requests.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.data-requests.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Data Privacy</span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Settings & Engines</div>

                {{-- Delivery Settings --}}
                <a href="{{ route('admin.settings.delivery') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.delivery') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.delivery') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <span>Delivery & Zones</span>
                </a>

                {{-- Payment Settings --}}
                <a href="{{ route('admin.settings.payment') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.payment') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.payment') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Payment & QR</span>
                </a>

                {{-- Notification Settings --}}
                <a href="{{ route('admin.settings.notifications') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.notifications') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.notifications') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>Notifications</span>
                </a>

                {{-- Appearance & Theme --}}
                <a href="{{ route('admin.settings.appearance') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.appearance') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.appearance') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4 4 0 014-4c.48 0 .937.085 1.36.241M15.5 8.5l3.5-3.5a2.121 2.121 0 013 3L18.5 11.5M10.5 13.5l7-7"/></svg>
                    <span>Theme / Appearance</span>
                </a>

                {{-- Master SEO --}}
                <a href="{{ route('admin.settings.master-seo') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.master-seo') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.master-seo') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <span>Master SEO</span>
                </a>

                {{-- Page SEO --}}
                <a href="{{ route('admin.settings.page-seo') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.page-seo') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.page-seo') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Page SEO</span>
                </a>

                {{-- Audit Logs --}}
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.activity-logs.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Audit Trail Log</span>
                </a>

            </nav>
        </div>

        {{-- Sidebar Footer --}}
        <div class="p-3 bg-slate-950/80 border-t border-slate-900 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2.5 truncate">
                <div class="w-8 h-8 rounded-lg bg-sky-600/20 text-sky-400 border border-sky-500/30 font-bold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="truncate">
                    <span class="block font-bold text-white truncate text-[11px] leading-tight">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] text-slate-400 capitalize block leading-tight">{{ auth()->user()->role }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-900 transition" title="Log Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Admin Content Column --}}
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        
        {{-- Admin Top Bar --}}
        <header class="h-14 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <a href="{{ url('/') }}" target="_blank" class="text-xs font-semibold text-slate-700 hover:text-sky-600 flex items-center gap-1.5 bg-slate-50 hover:bg-sky-50 px-2.5 py-1.5 rounded-lg border border-slate-200 hover:border-sky-200 transition">
                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Storefront</span>
                </a>

                <div class="hidden md:flex items-center gap-1.5 text-[11px] font-medium text-slate-400 pl-2 border-l border-slate-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Store Online</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- In-app notification bell with pending advance payments --}}
                <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="Pending Payment Verifications">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span 
                        x-show="bellCount > 0" 
                        x-text="bellCount" 
                        class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-amber-500 text-slate-950 font-extrabold text-[9px] flex items-center justify-center shadow-xs"
                    ></span>
                </a>

                {{-- User Mini Profile --}}
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="w-7 h-7 rounded-lg bg-sky-500/10 text-sky-600 border border-sky-200 font-bold flex items-center justify-center text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-xs text-left hidden sm:block">
                        <span class="block font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-slate-400 capitalize block leading-tight">{{ auth()->user()->role }}</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="bg-emerald-600 text-white px-6 py-2.5 text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-600 text-white px-6 py-2.5 text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Main Admin Page View --}}
        <main class="flex-1 p-4 sm:p-5 lg:p-6 max-w-[1600px] w-full mx-auto">
            @yield('content')
        </main>

        {{-- Admin Footer --}}
        <footer class="px-6 py-3.5 bg-white border-t border-slate-200/80 text-[11px] text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>{{ setting('site_name', 'N&J Mini Mart') }} &bull; Mart Operations Platform</span>
            <span>Version 2.4 &bull; Fast Carton Fulfilment</span>
        </footer>
    </div>

</body>
</html>
