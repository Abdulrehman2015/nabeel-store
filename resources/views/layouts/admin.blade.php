<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') | {{ store_name() }} Admin</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0284c7">

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

    @stack('styles')

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
<body class="h-full flex antialiased text-slate-800 bg-[#f8fafc]" x-data="adminApp()" x-init="initAdmin()">

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
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('admin.pos.index') }}" class="flex items-center gap-2.5">
                    <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png') }}" alt="{{ store_name() }}" class="h-8 w-auto object-contain" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                    <span class="text-[10px] font-bold {{ auth()->user()->isAdmin() ? 'text-sky-400 bg-sky-950/70 border-sky-800/60' : 'text-emerald-400 bg-emerald-950/70 border-emerald-800/60' }} border px-1.5 py-0.5 rounded uppercase tracking-wider">
                        {{ auth()->user()->role === 'cashier' ? 'Cashier' : (auth()->user()->isAdmin() ? 'Admin' : 'Staff') }}
                    </span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Navigation Menu --}}
            <nav class="p-3.5 space-y-1 text-xs font-semibold">
                
                {{-- Point of Sale (POS) Counter (Always top for quick counter operation) --}}
                <a href="{{ route('admin.pos.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.pos.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.pos.*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>POS Register</span>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                        Counter
                    </span>
                </a>

                @if(auth()->user()->isAdmin())
                    {{-- Dashboard (Admin Only) --}}
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        <span>Dashboard</span>
                    </a>
                @endif

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

                @if(auth()->user()->isAdmin())
                    {{-- Reports & Analytics Module (Admin Only) --}}
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Reports & Analytics</span>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-sky-500/20 text-sky-400 border border-sky-500/30">
                            New
                        </span>
                    </a>
                @endif

                {{-- Daily Notifications & Alert Feed --}}
                <a href="{{ route('admin.notifications.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.notifications.index') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.notifications.index') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>Notifications</span>
                    </div>
                    <span x-show="bellCount > 0" x-text="bellCount" class="bg-rose-500 text-white font-black text-[10px] px-2 py-0.5 rounded-full"></span>
                </a>

                {{-- Store Expenses & Internal Consumption --}}
                <a href="{{ route('admin.expenses.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.expenses.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.expenses.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Store Expenses</span>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">
                        Staff Use
                    </span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Catalog</div>

                {{-- Products --}}
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Products</span>
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

                @if(auth()->user()->isAdmin())
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

                    <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Settings & Administration</div>

                    {{-- Staff & Cashiers Management (NEW) --}}
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.staff.*') ? 'bg-sky-600 text-white font-bold shadow-sm shadow-sky-600/30' : 'hover:bg-slate-900 text-slate-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.staff.*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Staff & Cashiers</span>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            Roles
                        </span>
                    </a>

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

                    {{-- Store Data Reset --}}
                    <a href="{{ route('admin.data-reset') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.data-reset*') ? 'bg-rose-600 text-white font-bold shadow-sm shadow-rose-600/30' : 'hover:bg-slate-900 text-rose-300/80 hover:text-rose-300' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.data-reset*') ? 'text-white' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Store Data Reset</span>
                    </a>
                @endif

                {{-- Windows Desktop POS App Download Card --}}
                <div class="pt-3 pb-1">
                    <a 
                        href="{{ asset('downloads/N_I_POS_Setup_v1.0.exe') }}" 
                        download="N_I_POS_Setup_v1.0.exe"
                        class="flex items-center justify-between px-3 py-2 rounded-xl bg-gradient-to-r from-sky-600/20 to-blue-600/20 hover:from-sky-600/30 hover:to-blue-600/30 border border-sky-500/30 text-sky-300 hover:text-white transition group"
                        title="Download Standalone Windows Desktop POS App (.exe)"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-sky-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-[11px] font-bold">Windows POS App</span>
                        </div>
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-sky-500 text-white shadow-2xs">
                            .EXE
                        </span>
                    </a>
                </div>

            </nav>
        </div>

        {{-- Sidebar Footer --}}
        <div class="p-3 bg-slate-950/80 border-t border-slate-900 flex items-center justify-between text-xs">
            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 truncate group hover:opacity-90 transition" title="Manage Profile & Password">
                <div class="w-8 h-8 rounded-lg bg-sky-600/20 text-sky-400 group-hover:bg-sky-600 group-hover:text-white border border-sky-500/30 font-bold flex items-center justify-center text-xs shrink-0 transition">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="truncate">
                    <span class="block font-bold text-white group-hover:text-sky-400 transition truncate text-[11px] leading-tight">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] text-slate-400 capitalize block leading-tight">
                        {{ auth()->user()->role === 'cashier' ? 'Cashier (Counter)' : (auth()->user()->isAdmin() ? 'Administrator' : ucfirst(auth()->user()->role ?? 'Staff')) }}
                    </span>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-900 transition cursor-pointer" title="Log Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Admin Content Column --}}
    <div class="flex-1 lg:pl-64 flex flex-col {{ request()->routeIs('admin.pos.*') ? 'h-screen max-h-screen overflow-hidden' : 'min-h-screen' }}">
        
        {{-- Admin Top Bar --}}
        <header class="shrink-0 h-16 bg-white border-b border-slate-200/90 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs">
            
            {{-- Left Section: Mobile Toggle & Store Status --}}
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition cursor-pointer" title="Open Navigation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <a 
                    href="{{ url('/') }}" 
                    target="_blank" 
                    class="text-xs font-bold text-slate-700 hover:text-sky-700 flex items-center gap-1.5 bg-slate-100/80 hover:bg-sky-50 px-3 py-1.5 rounded-xl border border-slate-200/90 hover:border-sky-300 transition shadow-2xs group"
                >
                    <svg class="w-3.5 h-3.5 text-sky-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Storefront</span>
                </a>

                {{-- Live Store Online Pill --}}
                <div class="hidden xl:flex items-center gap-2 px-2.5 py-1 bg-emerald-50/80 border border-emerald-200/80 rounded-xl text-emerald-800 text-[11px] font-bold shadow-2xs">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Store Live</span>
                    <span class="text-emerald-300 font-normal">&bull;</span>
                    <span class="text-[10px] text-emerald-600 font-semibold">Puchong Hub</span>
                </div>
            </div>

            {{-- Center Section: Global Quick Search Bar --}}
            <form action="{{ route('admin.orders.index') }}" method="GET" class="hidden md:flex items-center flex-1 max-w-sm lg:max-w-md mx-4 lg:mx-8">
                <div class="relative w-full group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="search" 
                        name="search" 
                        id="adminGlobalSearch"
                        value="{{ request('search') }}"
                        placeholder="Search orders by #ORD, customer, phone..." 
                        class="w-full pl-9 pr-14 py-2 bg-slate-100/70 hover:bg-slate-100 focus:bg-white text-xs rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-slate-800 placeholder-slate-400 font-medium transition"
                    >
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                        <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 text-[10px] font-mono font-semibold text-slate-400 bg-white border border-slate-200/90 rounded-md shadow-2xs">Ctrl K</kbd>
                    </div>
                </div>
            </form>

            {{-- Right Section: Quick Action, Alerts & User Profile --}}
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                
                {{-- Shopify-Style Loud Order Bell Audio Pill --}}
                <div class="hidden md:flex items-center gap-1 bg-slate-100/90 hover:bg-slate-200/80 border border-slate-200/90 rounded-xl p-1 transition shadow-2xs">
                    <button 
                        type="button" 
                        @click="toggleSound()" 
                        class="flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-bold transition cursor-pointer"
                        :class="soundAlertsEnabled ? 'bg-white text-emerald-700 shadow-2xs' : 'text-slate-400 hover:text-slate-600'"
                        :title="soundAlertsEnabled ? 'Shopify Order Bell is Active. Click to Mute' : 'Order Bell Muted. Click to Turn ON'"
                    >
                        <span x-text="soundAlertsEnabled ? '🔔' : '🔕'"></span>
                        <span class="text-[11px]" x-text="soundAlertsEnabled ? 'Order Bell: ON' : 'Bell Muted'"></span>
                        <span x-show="soundAlertsEnabled" class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    </button>

                    <button 
                        type="button" 
                        @click="playShopifyOrderBell()" 
                        class="px-2 py-1 bg-white hover:bg-sky-50 text-sky-700 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer border border-slate-200/80 shadow-2xs"
                        title="Test Loud Order Bell Sound"
                    >
                        Test Bell
                    </button>
                </div>

                {{-- Quick POS Register CTA (Shown on non-POS pages) --}}
                @unless(request()->routeIs('admin.pos.*'))
                    <a 
                        href="{{ route('admin.pos.index') }}" 
                        class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-xs shadow-emerald-600/20 transition cursor-pointer"
                        title="Launch Point of Sale Counter Register"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>POS Register</span>
                    </a>
                @endunless

                {{-- Quick Add Product CTA --}}
                <a 
                    href="{{ route('admin.products.create') }}" 
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-xs shadow-sky-600/20 transition cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Product</span>
                </a>

                {{-- In-App Notification Bell & Popover --}}
                <div class="relative" @click.outside="notificationsOpen = false">
                    <button 
                        type="button" 
                        @click="notificationsOpen = !notificationsOpen" 
                        class="relative p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition cursor-pointer" 
                        title="Store Notifications & Payment Alerts"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span 
                            x-show="bellCount > 0" 
                            x-text="bellCount" 
                            class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-amber-500 text-slate-950 font-black text-[10px] flex items-center justify-center shadow-xs ring-2 ring-white"
                        ></span>
                    </button>

                    {{-- Notifications Dropdown Menu --}}
                    <div 
                        x-show="notificationsOpen" 
                        x-cloak 
                        x-transition:enter="transition ease-out duration-150" 
                        x-transition:enter-start="opacity-0 scale-95" 
                        x-transition:enter-end="opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-100" 
                        x-transition:leave-start="opacity-100 scale-100" 
                        x-transition:leave-end="opacity-0 scale-95" 
                        class="absolute right-0 mt-2 w-80 sm:w-92 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 overflow-hidden"
                    >
                        {{-- Shopify Audio Alert Controls inside Dropdown --}}
                        <div class="px-4 py-2.5 bg-gradient-to-r from-slate-900 to-slate-950 text-white flex items-center justify-between border-b border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <span class="text-base">🔔</span>
                                <div>
                                    <div class="text-xs font-black text-white leading-tight">Shopify-Style Order Alert</div>
                                    <div class="text-[10px] text-emerald-400 font-semibold" x-text="soundAlertsEnabled ? 'Loud Bell Active (Even Minimized)' : 'Bell Muted'"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click="toggleSound()" 
                                    class="px-2 py-0.5 rounded-lg text-[10px] font-bold border transition cursor-pointer"
                                    :class="soundAlertsEnabled ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-slate-800 text-slate-300 border-slate-700'"
                                    x-text="soundAlertsEnabled ? 'ON' : 'OFF'"
                                ></button>

                                <button 
                                    type="button" 
                                    @click="playShopifyOrderBell()" 
                                    class="px-2 py-0.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-[10px] font-bold border border-slate-700 transition cursor-pointer"
                                    title="Play loud test chime"
                                >
                                    Test
                                </button>
                            </div>
                        </div>

                        {{-- Desktop Windows Notification Banner Permission CTA --}}
                        <template x-if="desktopNotifPermission !== 'granted'">
                            <div class="p-2.5 bg-amber-50 border-b border-amber-200/80 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 text-amber-900">
                                    <span class="text-sm">🪟</span>
                                    <span class="text-[11px] font-semibold">Enable Windows Desktop Banners</span>
                                </div>
                                <button 
                                    type="button" 
                                    @click="requestDesktopPermission()" 
                                    class="px-2.5 py-1 bg-amber-600 hover:bg-amber-500 text-white text-[10px] font-bold rounded-lg transition cursor-pointer shadow-2xs"
                                >
                                    Enable
                                </button>
                            </div>
                        </template>

                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-xs text-slate-900">Notifications & Alerts</span>
                                <span 
                                    x-show="bellCount > 0" 
                                    x-text="bellCount + ' pending'" 
                                    class="text-[10px] font-bold px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full"
                                ></span>
                            </div>
                            <a href="{{ route('admin.notifications.index') }}" class="text-[10px] font-bold text-sky-600 hover:text-sky-800 hover:underline flex items-center gap-0.5">
                                <span>Daily Feed</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs">
                            {{-- 1. Pending Payments Alert --}}
                            <template x-if="pendingPaymentsCount > 0">
                                <a 
                                    href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" 
                                    class="p-3.5 hover:bg-slate-50 flex items-start gap-3 transition block"
                                >
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 text-sm">
                                        💳
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-slate-900 flex items-center justify-between">
                                            <span>Payment Proof Awaiting Review</span>
                                            <span class="text-[10px] text-amber-600 font-bold" x-text="pendingPaymentsCount + ' pending'"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            <span x-text="pendingPaymentsCount"></span> customer order(s) submitted with payment receipts requiring admin sign-off.
                                        </p>
                                        <span class="text-[11px] text-sky-600 font-bold mt-1.5 inline-flex items-center gap-1">
                                            <span>Verify Receipts Now</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </div>
                                </a>
                            </template>

                            {{-- 2. Low Stock Inventory Alert --}}
                            <template x-if="lowStockCount > 0">
                                <div class="p-3.5 hover:bg-amber-50/40 bg-amber-50/20 transition block">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 text-sm">
                                            ⚠️
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-slate-900 flex items-center justify-between">
                                                <span>Low Stock Inventory Alert</span>
                                                <span class="text-[10px] text-rose-700 font-extrabold bg-rose-100 px-1.5 py-0.5 rounded-full" x-text="lowStockCount + ' alert' + (lowStockCount > 1 ? 's' : '')"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                Item(s) dropped to or below their alert quantity:
                                            </p>
                                            <div class="mt-2 space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                                <template x-for="item in lowStockItems" :key="item.id">
                                                    <a :href="item.url" class="flex items-center justify-between p-1.5 rounded-lg bg-white border border-slate-200 hover:border-amber-400 hover:bg-amber-50/50 text-[11px] transition group">
                                                        <span class="font-semibold text-slate-800 truncate mr-2 group-hover:text-amber-900" x-text="item.name"></span>
                                                        <span class="shrink-0 font-bold text-rose-700 font-mono text-[10px]" x-text="item.stock_quantity + ' left (limit: ' + item.low_stock_threshold + ')'"></span>
                                                    </a>
                                                </template>
                                            </div>
                                            <a href="{{ route('admin.products.index', ['stock_status' => 'low']) }}" class="text-[11px] text-sky-600 hover:text-sky-800 font-bold mt-2 inline-flex items-center gap-1">
                                                <span>Manage All Low Stock Items &rarr;</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- 3. All Clear Placeholder --}}
                            <template x-if="bellCount === 0">
                                <div class="p-6 text-center text-slate-400">
                                    <svg class="w-8 h-8 mx-auto text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="font-bold text-slate-700 text-xs">All Clear & Caught Up</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">No pending payment receipts or low stock alerts.</p>
                                </div>
                            </template>
                        </div>

                        <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50/80 flex items-center justify-between text-[11px]">
                            <a href="{{ route('admin.notifications.index') }}" class="font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span>Open Notification Center &rarr;</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="text-slate-500 hover:text-slate-800 font-semibold">Orders</a>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-slate-200"></div>

                {{-- User Profile Pill with Interactive Dropdown Menu --}}
                <div class="relative" @click.outside="userMenuOpen = false">
                    <button 
                        type="button" 
                        @click="userMenuOpen = !userMenuOpen" 
                        class="flex items-center gap-2 p-1 sm:px-2.5 sm:py-1.5 rounded-xl hover:bg-slate-100/90 border border-transparent hover:border-slate-200 transition cursor-pointer"
                    >
                        <div class="w-8 h-8 rounded-xl bg-sky-500/10 text-sky-700 border border-sky-200/80 font-black flex items-center justify-center text-xs shadow-2xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-xs text-left hidden sm:block">
                            <span class="block font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-slate-400 capitalize block leading-tight font-medium">{{ auth()->user()->role ?? 'Admin' }}</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block transition-transform duration-200" :class="userMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- User Dropdown Menu --}}
                    <div 
                        x-show="userMenuOpen" 
                        x-cloak 
                        x-transition:enter="transition ease-out duration-150" 
                        x-transition:enter-start="opacity-0 scale-95" 
                        x-transition:enter-end="opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-100" 
                        x-transition:leave-start="opacity-100 scale-100" 
                        x-transition:leave-end="opacity-0 scale-95" 
                        class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50"
                    >
                        {{-- User Header Details --}}
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <span class="block font-bold text-xs text-slate-900 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] text-slate-500 block truncate leading-tight mt-0.5">{{ auth()->user()->email }}</span>
                            <span class="inline-block mt-1.5 px-2 py-0.5 bg-sky-50 text-sky-700 border border-sky-200/60 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                {{ auth()->user()->role ?? 'Store Admin' }}
                            </span>
                        </div>

                        {{-- Nav Links --}}
                        <div class="py-1 text-xs">
                            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 transition font-bold {{ request()->routeIs('admin.profile.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-800 hover:text-sky-600' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.profile.*') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Profile & Password</span>
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.pos.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-emerald-700 hover:bg-emerald-50 hover:text-emerald-800 transition font-bold">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>POS Register</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Customer Orders</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span>Product Catalog</span>
                            </a>
                            <a href="{{ route('admin.settings.delivery') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span>Delivery & Zones</span>
                            </a>
                            <a href="{{ route('admin.settings.master-seo') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <span>Master SEO Tools</span>
                            </a>
                        </div>

                        {{-- Log Out CTA --}}
                        <div class="pt-1 border-t border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </div>
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

        {{-- Live Floating Order Arrival Banner (Shopify Style) --}}
        <div 
            x-show="latestNewOrderToast" 
            x-cloak 
            x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
            x-transition:leave="transition ease-in duration-200 transform" 
            x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95" 
            class="fixed top-20 right-4 sm:right-6 z-50 max-w-sm w-full bg-slate-950 text-white rounded-2xl shadow-2xl border-2 border-emerald-500 p-4"
        >
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center text-xl shrink-0 animate-bounce">
                    🔔
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400">New Store Order!</span>
                        <span class="text-[10px] font-mono text-slate-400">Just Now</span>
                    </div>
                    <h4 class="text-sm font-extrabold text-white truncate mt-0.5" x-text="latestNewOrderToast?.customer_name"></h4>
                    <div class="flex items-center gap-2 mt-1 text-xs">
                        <span class="font-black text-lime-400">RM <span x-text="latestNewOrderToast?.grand_total"></span></span>
                        <span class="text-slate-400">&bull;</span>
                        <span class="text-slate-300 font-mono text-[11px]" x-text="'#' + latestNewOrderToast?.order_number"></span>
                        <span class="text-slate-400">&bull;</span>
                        <span class="text-slate-400 text-[10px]" x-text="latestNewOrderToast?.items_count + ' items'"></span>
                    </div>
                    <div class="mt-2.5 flex items-center gap-2">
                        <a 
                            :href="latestNewOrderToast?.url" 
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1 shadow-xs"
                        >
                            <span>Open Order</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <button 
                            type="button" 
                            @click="latestNewOrderToast = null" 
                            class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl transition cursor-pointer"
                        >
                            Dismiss
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Admin Page View --}}
        <main class="{{ request()->routeIs('admin.pos.*') ? 'flex-1 min-h-0 p-2 sm:p-2.5 max-w-[1800px] w-full mx-auto flex flex-col overflow-hidden' : 'flex-1 p-4 sm:p-5 lg:p-6 max-w-[1600px] w-full mx-auto' }}">
            @yield('content')
        </main>

        {{-- Admin Footer (Suppressed on POS for True Viewport-Fit Register Experience) --}}
        @unless(request()->routeIs('admin.pos.*'))
            <footer class="px-6 py-3.5 bg-white border-t border-slate-200/80 text-[11px] text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>{{ store_name() }} &bull; Mart Operations Platform</span>
                <span>Version 2.4 &bull; {{ store_name() }} Management</span>
            </footer>
        @endunless
    </div>

    <script>
        // Global Hardware Barcode Scanner Listener for Orders
        // When admin scans an order slip barcode (e.g. POS-2026-01014), immediately open it in a new tab
        (function() {
            let scanBuffer = '';
            let scanLastTime = 0;
            let scanTimeout = null;

            function triggerOrderOpen(rawCode) {
                const code = rawCode.trim();
                if (!code) return;
                const targetUrl = '{{ url('/admin/orders/barcode') }}/' + encodeURIComponent(code);

                const link = document.createElement('a');
                link.href = targetUrl;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                document.body.appendChild(link);
                link.click();
                setTimeout(() => link.remove(), 200);
            }

            window.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.altKey || e.metaKey) return;

                const now = Date.now();
                const timeDiff = now - scanLastTime;
                scanLastTime = now;

                if (e.key === 'Enter' || e.key === 'Tab') {
                    const code = scanBuffer.trim();
                    if (code.length >= 6 && (code.startsWith('POS-') || code.startsWith('ORD-') || code.startsWith('BS-') || /^POS\d+/i.test(code))) {
                        e.preventDefault();
                        e.stopPropagation();
                        scanBuffer = '';
                        triggerOrderOpen(code);
                        return;
                    }
                    scanBuffer = '';
                } else if (e.key && e.key.length === 1) {
                    if (timeDiff < 75 || scanBuffer.length === 0) {
                        scanBuffer += e.key;
                    } else {
                        scanBuffer = e.key;
                    }

                    if (scanTimeout) clearTimeout(scanTimeout);
                    scanTimeout = setTimeout(function() {
                        const code = scanBuffer.trim();
                        if (code.length >= 9 && (code.startsWith('POS-') || code.startsWith('ORD-') || code.startsWith('BS-'))) {
                            scanBuffer = '';
                            triggerOrderOpen(code);
                        } else {
                            scanBuffer = '';
                        }
                    }, 110);
                }
            }, true);
        })();

        // Global Ctrl+K / Cmd+K shortcut to focus Quick Search
        window.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                e.preventDefault();
                const searchEl = document.getElementById('adminGlobalSearch');
                if (searchEl) {
                    searchEl.focus();
                    searchEl.select();
                }
            }
        });

        // Shopify-Style Order Audio Bell & Notification Engine
        function adminApp() {
            return {
                sidebarOpen: false,
                pendingPaymentsCount: {{ (int) \App\Models\Order::where('order_status', 'pending_payment_verification')->where('delivery_zone', '!=', 'pos_counter')->where('order_number', 'NOT LIKE', 'POS-%')->count() }},
                lowStockCount: {{ (int) \App\Models\Product::where('is_active', true)->whereRaw(\Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold') ? 'stock_quantity <= COALESCE(low_stock_threshold, 10)' : 'stock_quantity <= 10')->count() }},
                lowStockItems: [],
                get bellCount() {
                    return (this.pendingPaymentsCount || 0) + (this.lowStockCount || 0);
                },
                notificationsOpen: false,
                userMenuOpen: false,
                soundAlertsEnabled: true,
                desktopNotifPermission: (typeof Notification !== 'undefined') ? Notification.permission : 'denied',
                lastOrderId: {{ (int) (\App\Models\Order::where('delivery_zone', '!=', 'pos_counter')->where('order_number', 'NOT LIKE', 'POS-%')->max('id') ?? 0) }},
                latestNewOrderToast: null,
                toastTimer: null,
                titleFlashInterval: null,
                originalTitle: document.title,
                audioUnlocked: false,
                shopifyAudioUrl: '{{ asset('sounds/shopify-order.mp3') }}',
                shopifyAudioBuffer: null,
                htmlAudioEl: null,

                initAdmin() {
                    // Preload authentic Shopify audio element immediately
                    try {
                        this.htmlAudioEl = new Audio(this.shopifyAudioUrl);
                        this.htmlAudioEl.preload = 'auto';
                        this.htmlAudioEl.volume = 1.0;
                    } catch(e) {}

                    // Pre-fetch & decode authentic Shopify MP3 into Web Audio buffer
                    this.loadShopifyAudioBuffer();

                    // Auto-unlock Web Audio on first user interaction anywhere
                    const unlockAudio = () => {
                        this.ensureAudioUnlocked();
                        window.removeEventListener('click', unlockAudio);
                        window.removeEventListener('keydown', unlockAudio);
                        window.removeEventListener('touchstart', unlockAudio);
                    };
                    window.addEventListener('click', unlockAudio, { once: true });
                    window.addEventListener('keydown', unlockAudio, { once: true });
                    window.addEventListener('touchstart', unlockAudio, { once: true });

                    // Restore sound preference from localStorage
                    try {
                        const saved = localStorage.getItem('nj_admin_sound_enabled');
                        if (saved !== null) {
                            this.soundAlertsEnabled = saved === 'true';
                        }
                    } catch(e) {}

                    // Track notification permissions
                    if ('Notification' in window) {
                        this.desktopNotifPermission = Notification.permission;
                    }

                    // High-frequency polling (every 6 seconds for instantaneous background alerts)
                    setInterval(() => this.pollNotifications(), 6000);
                    // Initial silent sync
                    this.pollNotifications();
                },

                toggleSound() {
                    this.soundAlertsEnabled = !this.soundAlertsEnabled;
                    try {
                        localStorage.setItem('nj_admin_sound_enabled', this.soundAlertsEnabled ? 'true' : 'false');
                    } catch(e) {}
                    if (this.soundAlertsEnabled) {
                        this.playShopifyOrderBell();
                    }
                },

                requestDesktopPermission() {
                    if (!('Notification' in window)) {
                        alert('Your browser does not support desktop notifications.');
                        return;
                    }
                    Notification.requestPermission().then(permission => {
                        this.desktopNotifPermission = permission;
                        if (permission === 'granted') {
                            try {
                                new Notification("✅ Order Alerts Active", {
                                    body: "You will now hear loud chimes & see desktop banners for every new order!",
                                    icon: "{{ asset('images/logo.png') }}"
                                });
                            } catch(e) {}
                        }
                    });
                },

                loadShopifyAudioBuffer() {
                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;
                        if (!AudioCtx) return;
                        if (!window.__njAudioContext) {
                            window.__njAudioContext = new AudioCtx();
                        }
                        fetch(this.shopifyAudioUrl)
                            .then(res => {
                                if (!res.ok) throw new Error('Network error');
                                return res.arrayBuffer();
                            })
                            .then(buffer => window.__njAudioContext.decodeAudioData(buffer))
                            .then(decoded => {
                                this.shopifyAudioBuffer = decoded;
                            })
                            .catch(() => {});
                    } catch(e) {}
                },

                ensureAudioUnlocked() {
                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;
                        if (!AudioCtx) return;
                        if (!window.__njAudioContext) {
                            window.__njAudioContext = new AudioCtx();
                        }
                        if (window.__njAudioContext.state === 'suspended') {
                            window.__njAudioContext.resume();
                        }
                        if (!this.shopifyAudioBuffer) {
                            this.loadShopifyAudioBuffer();
                        }
                        this.audioUnlocked = true;
                    } catch(e) {}
                },

                // Authentic Shopify Order "Cha-Ching!" Notification Bell with Studio Loudness
                playShopifyOrderBell() {
                    if (!this.soundAlertsEnabled) return;

                    let playedSuccess = false;

                    // 1. Primary Engine: Web Audio Buffer Source with 2.2x High-Volume Gain & Limiter
                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;
                        if (AudioCtx) {
                            if (!window.__njAudioContext) {
                                window.__njAudioContext = new AudioCtx();
                            }
                            const ctx = window.__njAudioContext;
                            if (ctx.state === 'suspended') {
                                ctx.resume();
                            }

                            if (this.shopifyAudioBuffer) {
                                const source = ctx.createBufferSource();
                                source.buffer = this.shopifyAudioBuffer;

                                // Limiting Compressor prevents clipping while maximizing room loudness
                                const compressor = ctx.createDynamicsCompressor();
                                compressor.threshold.setValueAtTime(-10, ctx.currentTime);
                                compressor.knee.setValueAtTime(4, ctx.currentTime);
                                compressor.ratio.setValueAtTime(12, ctx.currentTime);
                                compressor.attack.setValueAtTime(0.002, ctx.currentTime);
                                compressor.release.setValueAtTime(0.2, ctx.currentTime);
                                compressor.connect(ctx.destination);

                                // Master Gain boosted to 2.2 for maximum loudness across rooms/minimized windows
                                const gainNode = ctx.createGain();
                                gainNode.gain.setValueAtTime(2.2, ctx.currentTime);

                                source.connect(gainNode);
                                gainNode.connect(compressor);
                                source.start(0);
                                playedSuccess = true;
                            }
                        }
                    } catch (e) {
                        console.warn('WebAudio Shopify bell error:', e);
                    }

                    // 2. HTML5 Audio Element Playback at 100% volume
                    try {
                        if (!this.htmlAudioEl) {
                            this.htmlAudioEl = new Audio(this.shopifyAudioUrl);
                            this.htmlAudioEl.preload = 'auto';
                        }
                        this.htmlAudioEl.currentTime = 0;
                        this.htmlAudioEl.volume = 1.0;
                        const playPromise = this.htmlAudioEl.play();
                        if (playPromise) {
                            playPromise.then(() => {
                                playedSuccess = true;
                            }).catch(() => {
                                if (!playedSuccess) {
                                    this.playSynthesizedOrderBell();
                                }
                            });
                        }
                    } catch (e) {
                        if (!playedSuccess) {
                            this.playSynthesizedOrderBell();
                        }
                    }
                },

                // Fallback Engine: Multi-harmonic Brass Cash Register Synthesizer
                playSynthesizedOrderBell() {
                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;
                        if (!AudioCtx) return;
                        if (!window.__njAudioContext) {
                            window.__njAudioContext = new AudioCtx();
                        }
                        const ctx = window.__njAudioContext;
                        if (ctx.state === 'suspended') {
                            ctx.resume();
                        }

                        const now = ctx.currentTime;
                        const compressor = ctx.createDynamicsCompressor();
                        compressor.threshold.setValueAtTime(-14, now);
                        compressor.knee.setValueAtTime(4, now);
                        compressor.ratio.setValueAtTime(12, now);
                        compressor.attack.setValueAtTime(0.002, now);
                        compressor.release.setValueAtTime(0.2, now);
                        compressor.connect(ctx.destination);

                        const masterGain = ctx.createGain();
                        masterGain.gain.setValueAtTime(1.8, now);
                        masterGain.connect(compressor);

                        // Mechanical Click / Drawer Slide
                        const bufferSize = Math.floor(ctx.sampleRate * 0.05);
                        const noiseBuffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
                        const output = noiseBuffer.getChannelData(0);
                        for (let i = 0; i < bufferSize; i++) {
                            output[i] = Math.random() * 2 - 1;
                        }
                        const whiteNoise = ctx.createBufferSource();
                        whiteNoise.buffer = noiseBuffer;

                        const noiseFilter = ctx.createBiquadFilter();
                        noiseFilter.type = 'bandpass';
                        noiseFilter.frequency.setValueAtTime(3200, now);
                        noiseFilter.Q.setValueAtTime(4, now);

                        const noiseGain = ctx.createGain();
                        noiseGain.gain.setValueAtTime(0.5, now);
                        noiseGain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);

                        whiteNoise.connect(noiseFilter);
                        noiseFilter.connect(noiseGain);
                        noiseGain.connect(masterGain);
                        whiteNoise.start(now);
                        whiteNoise.stop(now + 0.05);

                        // Brass Bell Strikes ("Cha-Ching!")
                        const t2 = now + 0.08;
                        const harmonics = [
                            { f: 2093.00, type: 'sine', vol: 0.8 },
                            { f: 2637.02, type: 'sine', vol: 0.7 },
                            { f: 3135.96, type: 'sine', vol: 0.6 },
                            { f: 4186.01, type: 'triangle', vol: 0.4 },
                            { f: 6271.93, type: 'sine', vol: 0.3 }
                        ];

                        harmonics.forEach(note => {
                            const osc = ctx.createOscillator();
                            const g = ctx.createGain();
                            osc.type = note.type;
                            osc.frequency.setValueAtTime(note.f, t2);
                            g.gain.setValueAtTime(note.vol, t2);
                            g.gain.exponentialRampToValueAtTime(0.0001, t2 + 1.8);
                            osc.connect(g);
                            g.connect(masterGain);
                            osc.start(t2);
                            osc.stop(t2 + 1.85);
                        });
                    } catch(e) {}
                },

                triggerNewOrderAlert(order) {
                    // 1. Play Loud Bell (Works even in background tab / minimized)
                    this.playShopifyOrderBell();

                    // 2. Desktop Windows Notification (Persistent banner when minimized!)
                    if ('Notification' in window && Notification.permission === 'granted') {
                        try {
                            const notif = new Notification('🔔 New Order Received! #' + order.order_number, {
                                body: order.customer_name + ' • RM ' + order.grand_total + ' (' + order.items_count + ' items)\nPayment: ' + (order.payment_method || 'Online'),
                                icon: '{{ asset('images/logo.png') }}',
                                requireInteraction: true,
                                tag: 'order-' + order.id,
                            });
                            notif.onclick = () => {
                                window.focus();
                                window.location.href = order.url;
                            };
                        } catch(e) {}
                    }

                    // 3. Flashing Tab Title
                    this.startTabTitleFlash(order);

                    // 4. In-App Floating Toast
                    this.latestNewOrderToast = order;
                    if (this.toastTimer) clearTimeout(this.toastTimer);
                    this.toastTimer = setTimeout(() => {
                        this.latestNewOrderToast = null;
                    }, 12000);
                },

                startTabTitleFlash(order) {
                    if (this.titleFlashInterval) clearInterval(this.titleFlashInterval);
                    let state = false;
                    this.titleFlashInterval = setInterval(() => {
                        document.title = state ? '🔔 (1) NEW ORDER! RM ' + order.grand_total : this.originalTitle;
                        state = !state;
                    }, 1000);

                    const stopFlashing = () => {
                        if (this.titleFlashInterval) {
                            clearInterval(this.titleFlashInterval);
                            this.titleFlashInterval = null;
                        }
                        document.title = this.originalTitle;
                        window.removeEventListener('focus', stopFlashing);
                        window.removeEventListener('click', stopFlashing);
                    };
                    window.addEventListener('focus', stopFlashing);
                    window.addEventListener('click', stopFlashing);
                },

                async pollNotifications() {
                    try {
                        let url = '{{ route("admin.notifications.poll") }}?last_order_id=' + this.lastOrderId;
                        let res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return;
                        let data = await res.json();

                        this.pendingPaymentsCount = data.pending_payments || 0;
                        this.lowStockCount = data.low_stock_count || 0;
                        this.lowStockItems = data.low_stock_items || [];

                        if (data.has_new_orders && data.new_orders && data.new_orders.length > 0) {
                            data.new_orders.forEach(ord => {
                                // Strictly skip in-store walk-in POS counter orders (no chime/bell, no popups)
                                if (ord.order_number && (ord.order_number.startsWith('POS-') || ord.order_number.includes('POS'))) {
                                    return;
                                }
                                this.triggerNewOrderAlert(ord);
                            });
                        }

                        if (data.latest_order_id) {
                            this.lastOrderId = data.latest_order_id;
                        }
                    } catch(e) {}
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
