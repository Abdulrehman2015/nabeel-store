<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') | {{ setting('site_name', '99 Bulk Sales') }} Admin</title>

    {{-- Leaflet for Store Location Picker --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Chart.js for Sales Trends --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex antialiased text-slate-800" x-data="{
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
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="sidebarOpen = false"></div>

    {{-- Admin Sidebar Layout --}}
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between transition-transform duration-300 transform lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <div class="flex-1 overflow-y-auto">
            {{-- Admin Brand Header --}}
            <div class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-600 flex items-center justify-center text-white font-black text-sm">
                        99
                    </div>
                    <span class="font-black text-white text-base tracking-tight">Bulk Admin</span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-slate-400 p-1">
                    ✕
                </button>
            </div>

            {{-- Navigation Menu --}}
            <nav class="p-4 space-y-1 text-xs font-semibold">
                
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">📊</span>
                    <span>Dashboard</span>
                </a>

                {{-- Orders (with pending badge) --}}
                @php
                    $pendingCount = \App\Models\Order::where('order_status', 'pending_payment_verification')->count();
                @endphp
                <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">📦</span>
                        <span>Orders</span>
                    </div>
                    @if($pendingCount > 0)
                        <span class="bg-amber-400 text-slate-950 font-black text-[10px] px-2 py-0.5 rounded-full animate-pulse">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <div class="pt-4 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Catalog & Bulk</div>

                {{-- Products --}}
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🏷️</span>
                    <span>Products (Cartons)</span>
                </a>

                {{-- Categories --}}
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🗂️</span>
                    <span>Categories</span>
                </a>

                {{-- Brands --}}
                <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.brands.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">✨</span>
                    <span>Brands</span>
                </a>

                {{-- Customers --}}
                <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.customers.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">👥</span>
                    <span>Customers</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Storefront CMS</div>

                {{-- Banners / Slider --}}
                <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.banners.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🖼️</span>
                    <span>Hero Banners</span>
                </a>

                {{-- Home Sections Manager --}}
                <a href="{{ route('admin.home-sections.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.home-sections.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">📑</span>
                    <span>Homepage Sections</span>
                </a>

                {{-- Legal & Policy Pages (Phase 8) --}}
                <a href="{{ route('admin.policies.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.policies.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">⚖️</span>
                    <span>Legal & Policies</span>
                </a>

                {{-- Contact Messages --}}
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.contact-messages.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">✉️</span>
                    <span>Inquiries</span>
                </a>

                {{-- Data Requests (GDPR / PDPA) --}}
                <a href="{{ route('admin.data-requests.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.data-requests.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🛡️</span>
                    <span>Data Privacy</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Settings & Engines</div>

                {{-- Delivery Settings (Phase 3) --}}
                <a href="{{ route('admin.settings.delivery') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.delivery') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🚚</span>
                    <span>Delivery & Zones</span>
                </a>

                {{-- Payment Settings (Phase 3) --}}
                <a href="{{ route('admin.settings.payment') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.payment') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">💳</span>
                    <span>Payment & QR</span>
                </a>

                {{-- Notification Settings (Phase 4) --}}
                <a href="{{ route('admin.settings.notifications') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.notifications') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🔔</span>
                    <span>Notifications</span>
                </a>

                {{-- Appearance & Theme (Phase 6) --}}
                <a href="{{ route('admin.settings.appearance') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.appearance') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🎨</span>
                    <span>Theme / Appearance</span>
                </a>

                {{-- Master SEO (Phase 5) --}}
                <a href="{{ route('admin.settings.master-seo') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.master-seo') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🌐</span>
                    <span>Master SEO</span>
                </a>

                {{-- Page SEO (Phase 5) --}}
                <a href="{{ route('admin.settings.page-seo') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.page-seo') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">🔍</span>
                    <span>Page SEO</span>
                </a>

                {{-- Audit Logs (Phase 7.1) --}}
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-sky-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span class="text-base">📜</span>
                    <span>Audit Trail Log</span>
                </a>

            </nav>
        </div>

        {{-- Sidebar Footer --}}
        <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between text-xs">
            <div class="truncate">
                <span class="block font-bold text-white truncate">{{ auth()->user()->name }}</span>
                <span class="text-[11px] text-slate-400 capitalize">{{ auth()->user()->role }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-rose-400 p-1" title="Log Out">
                    🚪
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Admin Content Column --}}
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        
        {{-- Admin Top Bar --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden text-slate-600 p-2 rounded-lg hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <a href="{{ url('/') }}" target="_blank" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1.5 bg-sky-50 px-3 py-1.5 rounded-xl border border-sky-100">
                    <span>🛍️</span>
                    <span>Visit Live Store</span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                {{-- In-app notification bell with pending advance payments (Phase 4.4) --}}
                <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" class="relative p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition" title="Pending Payment Verifications">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span 
                        x-show="bellCount > 0" 
                        x-text="bellCount" 
                        class="absolute top-1 right-1 w-4 h-4 rounded-full bg-amber-500 text-slate-900 font-black text-[10px] flex items-center justify-center"
                    ></span>
                </a>

                <div class="text-xs text-right hidden sm:block">
                    <span class="block font-bold text-slate-800">{{ auth()->user()->name }}</span>
                    <span class="text-slate-400 capitalize">{{ auth()->user()->role }}</span>
                </div>
            </div>
        </header>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="bg-emerald-600 text-white px-6 py-3 text-xs font-bold flex items-center gap-2 shadow">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-600 text-white px-6 py-3 text-xs font-bold flex items-center gap-2 shadow">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Main Admin Page View --}}
        <main class="flex-1 p-6 sm:p-8">
            @yield('content')
        </main>

        {{-- Admin Footer --}}
        <footer class="p-6 bg-white border-t border-slate-200 text-xs text-slate-400 text-center">
            {{ setting('site_name', '99 Bulk Sales') }} &bull; Wholesale Carton Operations Management Panel
        </footer>
    </div>

</body>
</html>

