<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic SEO Metadata (Phase 5) --}}
    <title>{{ $seo['title'] ?? (store_name() . ' - Online Mart & Groceries') }}</title>
    <meta name="description" content="{{ $seo['description'] ?? setting('seo_default_description') }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? setting('seo_default_keywords') }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:site_name" content="{{ store_name() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? setting('site_name')) }}">
    <meta property="og:description" content="{{ $seo['og_description'] ?? ($seo['description'] ?? '') }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:image" content="{{ $seo['og_image'] ?? asset('images/product-placeholder.png') }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $seo['twitter_card'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? '') }}">
    <meta name="twitter:description" content="{{ $seo['og_description'] ?? ($seo['description'] ?? '') }}">
    <meta name="twitter:image" content="{{ $seo['og_image'] ?? asset('images/product-placeholder.png') }}">

    {{-- Webmaster Verification (Phase 5) --}}
    @if(setting('google_search_console_tag'))
        <meta name="google-site-verification" content="{{ setting('google_search_console_tag') }}">
    @endif
    @if(setting('bing_verification_tag'))
        <meta name="msvalidate.01" content="{{ setting('bing_verification_tag') }}">
    @endif

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Phase 6: Dynamic CSS Variables & Tailwind Theme Injection --}}
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --color-primary: {{ setting('color_primary', '#0284c7') }};
            --color-primary-hover: {{ setting('color_primary_hover', '#0369a1') }};
            --color-secondary: {{ setting('color_secondary', '#0f172a') }};
            --color-accent: {{ setting('color_accent', '#f59e0b') }};
            --color-bg: {{ setting('color_bg', '#f8fafc') }};
            --color-text: {{ setting('color_text', '#1e293b') }};
            --color-header-bg: {{ setting('color_header_bg', '#ffffff') }};
            --color-footer-bg: {{ setting('color_footer_bg', '#0f172a') }};
            --color-button-text: {{ setting('color_button_text', '#ffffff') }};
            --font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --border-radius: {{ setting('border_radius', '0.75rem') }};
        }
        body {
            font-family: var(--font-family);
            background-color: var(--color-bg);
            color: var(--color-text);
        }
        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-button-text);
            border-radius: var(--border-radius);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
        }
        .btn-accent {
            background-color: var(--color-accent);
            color: #000;
            border-radius: var(--border-radius);
            font-weight: 600;
        }
        .bg-primary { background-color: var(--color-primary); }
        .text-primary { color: var(--color-primary); }
        .border-primary { border-color: var(--color-primary); }
    </style>

    {{-- Leaflet Map CSS for Map Picker --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- Vite Scripts (Tailwind + Alpine.js + Axios) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Structured Data JSON-LD Schemas (Phase 5) --}}
    <script type="application/ld+json">{!! app(\App\Services\SeoService::class)->generateOrganizationJsonLd() !!}</script>
    @isset($breadcrumbsJsonLd)
        <script type="application/ld+json">{!! $breadcrumbsJsonLd !!}</script>
    @endisset
    @isset($productJsonLd)
        <script type="application/ld+json">{!! $productJsonLd !!}</script>
    @endisset

    {{-- Phase 5.2: Custom Header Scripts --}}
    {!! setting('custom_header_scripts') !!}

    {{-- Conditional Analytics (loaded only after cookie consent) --}}
    <script>
        function checkAndLoadAnalytics() {
            var consent = localStorage.getItem('bulk_cookie_consent');
            if (consent === 'accepted') {
                @if(setting('google_analytics_id'))
                    var gaScript = document.createElement('script');
                    gaScript.async = true;
                    gaScript.src = "https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}";
                    document.head.appendChild(gaScript);

                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', '{{ setting("google_analytics_id") }}');
                @endif

                @if(setting('facebook_pixel_id'))
                    !function(f,b,e,v,n,t,s)
                    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                    n.queue=[];t=b.createElement(e);t.async=!0;
                    t.src=v;s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', '{{ setting("facebook_pixel_id") }}');
                    fbq('track', 'PageView');
                @endif
            }
        }
        document.addEventListener('DOMContentLoaded', checkAndLoadAnalytics);
    </script>
</head>
<body class="flex flex-col min-h-screen" x-data="{
    mobileMenuOpen: false,
    cartOpen: false,
    liveSearchQuery: '',
    liveSearchResults: [],
    searchLoading: false,
    cartCount: {{ app(\App\Services\CartService::class)->getItemCount() }},
    cartSubtotal: '{{ format_currency(app(\App\Services\CartService::class)->getSubtotal()) }}',
    cartItems: [],
    minOrderValue: '{{ format_currency(app(\App\Services\CartService::class)->getMinimumOrderValue()) }}',
    isMinMet: {{ app(\App\Services\CartService::class)->isMinimumMet() ? 'true' : 'false' }},
    deficitFormatted: '{{ format_currency(app(\App\Services\CartService::class)->getSummary()['deficit']) }}',
    async fetchLiveSearch() {
        if (this.liveSearchQuery.length < 2) {
            this.liveSearchResults = [];
            return;
        }
        this.searchLoading = true;
        try {
            let res = await fetch('{{ route("product.live-search") }}?q=' + encodeURIComponent(this.liveSearchQuery));
            let data = await res.json();
            this.liveSearchResults = data.results || [];
        } catch (e) {
            console.error(e);
        } finally {
            this.searchLoading = false;
        }
    },
    async refreshMiniCart() {
        try {
            let res = await fetch('{{ route("cart.summary") }}');
            let data = await res.json();
            this.cartCount = data.item_count;
            this.cartSubtotal = data.subtotal_formatted;
            this.cartItems = data.items;
            this.isMinMet = data.is_minimum_met;
            this.deficitFormatted = data.deficit_formatted;
        } catch (e) {}
    },
    async addToCart(productId, quantity = 1) {
        try {
            let formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            formData.append('_token', '{{ csrf_token() }}');

            let res = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
            let data = await res.json();
            if (data.success) {
                this.cartCount = data.summary.item_count;
                this.cartSubtotal = data.summary.subtotal_formatted;
                this.cartItems = data.summary.items;
                this.isMinMet = data.summary.is_minimum_met;
                this.deficitFormatted = data.summary.deficit_formatted;
                this.cartOpen = true;
            }
        } catch(e) {
            console.error(e);
        }
    },
    async updateCartQty(productId, qty) {
        try {
            let formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', qty);
            formData.append('_token', '{{ csrf_token() }}');

            let res = await fetch('{{ route("cart.update") }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
            let data = await res.json();
            if (data.success) {
                this.cartCount = data.summary.item_count;
                this.cartSubtotal = data.summary.subtotal_formatted;
                this.cartItems = data.summary.items;
                this.isMinMet = data.summary.is_minimum_met;
                this.deficitFormatted = data.summary.deficit_formatted;
            }
        } catch(e) {}
    }
}" x-init="refreshMiniCart()">

    {{-- Top Announcement Bar --}}
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-medium">
                    ⚡ Free Delivery
                </span>
                <span>Within {{ setting('free_delivery_radius', 5) }} km radius & Free up to 1st Floor!</span>
            </div>
            <div class="flex items-center gap-4 text-slate-300 text-xs">
                <span>📞 Hotline: {{ setting('store_phone', '+60 12-889 9900') }}</span>
                <span class="hidden md:inline">🕒 {{ setting('working_hours', 'Mon-Sat 8am-7pm') }}</span>
                <a href="{{ route('order.track') }}" class="hover:text-amber-400 underline transition">Track Order</a>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                {{-- Mobile menu button --}}
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                {{-- Brand Logo --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="bg-slate-950 p-2 sm:px-3 sm:py-2 rounded-2xl shadow-md border border-slate-800 flex items-center transition-all group-hover:border-lime-500/50 group-hover:shadow-lime-500/10">
                            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png') }}" alt="{{ store_name() }}" class="h-9 sm:h-11 w-auto object-contain" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                        </div>
                        <div class="hidden xl:block">
                            <span class="block text-xs font-black text-slate-900 uppercase tracking-tight">Mini Mart</span>
                            <span class="block text-[10px] font-semibold text-lime-600">Daily Groceries & Essentials</span>
                        </div>
                    </a>
                </div>

                {{-- Live Search Bar (Center) --}}
                <div class="flex-1 max-w-xl mx-2 hidden sm:block relative">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="q" 
                                x-model="liveSearchQuery" 
                                @input.debounce.300ms="fetchLiveSearch()" 
                                @focus="fetchLiveSearch()" 
                                placeholder="Search products by brand, name or SKU (e.g. Fanta, Milo, Maggi)..." 
                                class="w-full pl-11 pr-24 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                autocomplete="off"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <button type="submit" class="absolute inset-y-1 right-1 px-4 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg transition flex items-center">
                                Search
                            </button>
                        </div>
                    </form>

                    {{-- Live Autocomplete Dropdown --}}
                    <div 
                        x-show="liveSearchQuery.length >= 2" 
                        @click.away="liveSearchQuery = ''" 
                        x-transition 
                        class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden z-50 divide-y divide-slate-100"
                        style="display: none;"
                    >
                        <div x-show="searchLoading" class="p-4 text-center text-sm text-slate-500">
                            Searching store catalog...
                        </div>
                        <template x-for="item in liveSearchResults" :key="item.id">
                            <a :href="item.url" class="flex items-center gap-3 p-3 hover:bg-slate-50 transition group">
                                <img :src="item.image" class="w-12 h-12 object-contain rounded bg-slate-100 p-1 border border-slate-200">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-slate-900 group-hover:text-sky-600 truncate" x-text="item.name"></div>
                                    <div class="text-xs text-slate-500 flex items-center gap-2">
                                        <span x-text="item.pack_size"></span>
                                        <span>&bull;</span>
                                        <span class="text-slate-400" x-text="item.sku"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-sky-600" x-text="item.price"></div>
                                    <template x-if="item.original_price">
                                        <div class="text-xs text-slate-400 line-through" x-text="item.original_price"></div>
                                    </template>
                                </div>
                            </a>
                        </template>
                        <div x-show="!searchLoading && liveSearchResults.length === 0" class="p-4 text-center text-sm text-slate-500">
                            No products matching "<span x-text="liveSearchQuery"></span>"
                        </div>
                    </div>
                </div>

                {{-- Right Actions: Categories Mega-Menu, Account, Mini-Cart --}}
                <div class="flex items-center gap-3 sm:gap-4">
                    
                    {{-- Categories Quick Mega-Menu --}}
                    <div class="relative hidden lg:block" x-data="{ catMenuOpen: false }">
                        <button 
                            @click="catMenuOpen = !catMenuOpen" 
                            @click.away="catMenuOpen = false" 
                            class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-slate-700 hover:text-sky-600 rounded-lg hover:bg-slate-50 transition"
                        >
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path></svg>
                            <span>Categories</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div 
                            x-show="catMenuOpen" 
                            x-transition 
                            class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-slate-200 p-2 z-50 divide-y divide-slate-100"
                            style="display: none;"
                        >
                            @php
                                $headerCategories = \App\Models\Category::active()->orderBy('sort_order')->take(10)->get();
                            @endphp
                            <div class="py-1">
                                @foreach($headerCategories as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg overflow-hidden flex items-center justify-center p-0.5 shrink-0 border border-slate-200 bg-slate-100 shadow-2xs">
                                                {!! $cat->renderIcon('w-full h-full object-cover rounded-md') !!}
                                            </div>
                                            <span class="font-medium text-xs">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $cat->products()->count() }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="pt-2 text-center">
                                <a href="{{ route('category.index') }}" class="text-xs font-bold text-sky-600 hover:underline">View All Categories &rarr;</a>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Account Menu --}}
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center gap-2 p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition">
                                <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:inline text-sm font-semibold text-slate-800">{{ Str::limit(auth()->user()->name, 12) }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50 text-sm" style="display: none;">
                                @if(auth()->user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 font-bold text-sky-600 hover:bg-sky-50">
                                        ⚙️ Admin Panel
                                    </a>
                                    <hr class="my-1 border-slate-100">
                                @endif
                                <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">My Orders</a>
                                <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Saved Addresses</a>
                                <a href="{{ route('account.export-data') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Download My Data</a>
                                <hr class="my-1 border-slate-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-medium">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:inline-flex items-center text-sm font-semibold text-slate-700 hover:text-sky-600 py-2 px-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                            <svg class="w-4 h-4 text-slate-400 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <a href="{{ route('login') }}" class="hover:text-sky-600 transition">Sign In</a>
                            <span class="mx-1 text-slate-300 font-normal">/</span>
                            <a href="{{ route('register') }}" class="hover:text-sky-600 transition">Sign Up</a>
                        </div>
                    @endauth

                    {{-- Mini-Cart Trigger Button --}}
                    <button 
                        type="button" 
                        @click="cartOpen = true; refreshMiniCart()" 
                        class="relative flex items-center gap-2.5 bg-sky-600 hover:bg-sky-700 text-white px-3.5 py-2.5 rounded-xl font-semibold text-sm shadow-md transition"
                    >
                        <div class="relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span 
                                x-show="cartCount > 0" 
                                x-text="cartCount" 
                                class="absolute -top-2 -right-2.5 bg-amber-400 text-slate-900 text-[11px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-sm"
                            ></span>
                        </div>
                        <span class="hidden md:inline" x-text="cartSubtotal"></span>
                    </button>
                </div>
            </div>

            {{-- Mobile Search Bar --}}
            <div class="py-2 pb-3 sm:hidden">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search products, drinks, pantry..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>
        </div>
    </header>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="bg-emerald-600 text-white px-4 py-3 text-center text-sm font-semibold flex items-center justify-center gap-2 shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-600 text-white px-4 py-3 text-center text-sm font-semibold flex items-center justify-center gap-2 shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Slide-Out Mini-Cart Drawer --}}
    <div 
        x-show="cartOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-hidden" 
        style="display: none;"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="cartOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-md bg-white shadow-2xl flex flex-col">
                {{-- Drawer Header --}}
                <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <h2 class="text-lg font-bold text-slate-900">Shopping Cart (<span x-text="cartCount"></span>)</h2>
                    </div>
                    <button type="button" @click="cartOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Minimum Order Notice Banner --}}
                <div class="p-4 bg-amber-50 border-b border-amber-200 text-xs">
                    <template x-if="isMinMet">
                        <div class="text-emerald-800 font-semibold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Minimum bulk order reached! Ready for checkout.</span>
                        </div>
                    </template>
                    <template x-if="!isMinMet">
                        <div>
                            <span class="font-bold text-amber-900">Minimum Order: <span x-text="minOrderValue"></span></span>
                            <p class="text-amber-800 mt-0.5">Add <strong x-text="deficitFormatted"></strong> more to qualify for delivery.</p>
                        </div>
                    </template>
                </div>

                {{-- Drawer Cart Items --}}
                <div class="flex-1 overflow-y-auto p-5 divide-y divide-slate-100">
                    <template x-if="cartItems.length === 0">
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-slate-600 font-semibold">Your shopping cart is empty</p>
                            <a href="{{ route('category.index') }}" @click="cartOpen = false" class="inline-block mt-4 px-4 py-2 bg-sky-600 text-white text-xs font-bold rounded-lg hover:bg-sky-700 transition">
                                Start Shopping
                            </a>
                        </div>
                    </template>

                    <template x-for="item in cartItems" :key="item.product_id">
                        <div class="py-4 flex gap-4 items-center">
                            <img :src="item.image" class="w-16 h-16 object-contain rounded-lg border border-slate-200 bg-slate-50 p-1 flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate" x-text="item.name"></h4>
                                <p class="text-xs text-slate-500" x-text="item.pack_size"></p>
                                <div class="text-xs font-bold text-sky-600 mt-1" x-text="'RM ' + Number(item.unit_price).toFixed(2) + ''"></div>
                                
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex items-center border border-slate-300 rounded-lg">
                                        <button type="button" @click="updateCartQty(item.product_id, item.quantity - 1)" class="px-2 py-0.5 text-slate-600 hover:bg-slate-100 rounded-l">-</button>
                                        <span class="px-2.5 text-xs font-bold text-slate-800" x-text="item.quantity"></span>
                                        <button type="button" @click="updateCartQty(item.product_id, item.quantity + 1)" class="px-2 py-0.5 text-slate-600 hover:bg-slate-100 rounded-r">+</button>
                                    </div>
                                    <button type="button" @click="updateCartQty(item.product_id, 0)" class="text-xs text-rose-500 hover:underline">Remove</button>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-extrabold text-slate-900" x-text="'RM ' + Number(item.line_total).toFixed(2)"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Drawer Footer --}}
                <div class="p-5 border-t border-slate-200 bg-slate-50 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Subtotal:</span>
                        <span class="text-lg font-black text-slate-900" x-text="cartSubtotal"></span>
                    </div>
                    <p class="text-xs text-slate-500">Delivery and floor charges calculated at checkout based on your exact pin.</p>
                    
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('cart.index') }}" class="w-full text-center py-2.5 border border-slate-300 rounded-xl text-slate-700 text-sm font-bold hover:bg-slate-100 transition">
                            View Full Cart
                        </a>
                        <template x-if="isMinMet">
                            <a href="{{ route('checkout.index') }}" class="w-full text-center py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-bold shadow-md transition">
                                Checkout &rarr;
                            </a>
                        </template>
                        <template x-if="!isMinMet">
                            <button disabled class="w-full py-2.5 bg-slate-300 text-slate-500 rounded-xl text-sm font-bold cursor-not-allowed">
                                Min Not Met
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation Drawer --}}
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60" @click="mobileMenuOpen = false"></div>
        <div class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <span class="text-lg font-black text-slate-900">{{ store_name() }}</span>
                    <button type="button" @click="mobileMenuOpen = false" class="text-slate-400 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <nav class="mt-4 space-y-1">
                    <a href="{{ url('/') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">Home</a>
                    <a href="{{ route('category.index') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">All Categories</a>
                    <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">View Cart</a>
                    <a href="{{ route('order.track') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">Track Order</a>
                    <a href="{{ route('page.faqs') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">FAQs</a>
                    <a href="{{ route('page.contact') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">Contact Us</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-100">
                @auth
                    <div class="text-xs text-slate-500 mb-2">Signed in as {{ auth()->user()->name }}</div>
                    <a href="{{ route('account.orders') }}" class="block py-2 text-sm font-bold text-sky-600">My Account &bull; Orders</a>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="py-2.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-sm font-bold transition">Sign In</a>
                        <a href="{{ route('register') }}" class="py-2.5 text-center bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-bold transition">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- Cookie Consent Banner (Phase 8 Compliance) --}}
    @if(setting('cookie_consent_enabled', true))
        <div 
            x-data="{
                showBanner: false,
                init() {
                    if (!localStorage.getItem('bulk_cookie_consent')) {
                        this.showBanner = true;
                    }
                },
                acceptAll() {
                    localStorage.setItem('bulk_cookie_consent', 'accepted');
                    this.showBanner = false;
                    checkAndLoadAnalytics();
                },
                rejectNonEssential() {
                    localStorage.setItem('bulk_cookie_consent', 'rejected');
                    this.showBanner = false;
                }
            }"
            x-show="showBanner"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="fixed bottom-4 left-4 right-4 md:left-auto md:right-6 md:max-w-md z-50 bg-slate-900 text-white p-5 rounded-2xl shadow-2xl border border-slate-800"
            style="display: none;"
        >
            <div class="flex items-start gap-3">
                <span class="text-2xl">🍪</span>
                <div class="flex-1 text-xs">
                    <h4 class="font-bold text-sm text-white">{{ setting('cookie_consent_title', 'Cookie Preferences') }}</h4>
                    <p class="text-slate-300 mt-1">
                        {{ setting('cookie_consent_message', 'We use essential cookies to make our store work. Analytics cookies are loaded only with your approval.') }}
                        <a href="{{ route('page.show', 'cookie-policy') }}" class="underline hover:text-sky-400">Cookie Policy</a>.
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <button type="button" @click="acceptAll()" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-lg transition">
                            Accept All
                        </button>
                        <button type="button" @click="rejectNonEssential()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-lg transition">
                            Reject Non-Essential
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Dynamic Footer --}}
    <footer class="bg-slate-900 text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                
                {{-- Col 1: Brand Info --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-950 p-2.5 rounded-2xl border border-slate-800 shadow-md">
                            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png') }}" alt="{{ store_name() }}" class="h-10 sm:h-12 w-auto object-contain" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        {{ setting('site_tagline', 'Your neighbourhood mart for groceries, beverages, daily food essentials, and household supplies.') }}
                    </p>
                    <div class="text-xs space-y-1.5 text-slate-300 pt-2">
                        <p class="flex items-start gap-2">
                            <span class="text-sky-500">📍</span>
                            <span>{{ setting('store_address', 'Puchong, Selangor') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-sky-500">📞</span>
                            <span>{{ setting('store_phone', '+60 12-889 9900') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-sky-500">✉️</span>
                            <span>{{ setting('store_email', 'orders@99bulksales.my') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-sky-500">🏢</span>
                            <span>SSM Reg: {{ setting('company_registration_no', '202401019988') }}</span>
                        </p>
                    </div>
                </div>

                {{-- Col 2: Categories --}}
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Bulk Categories</h3>
                    <ul class="space-y-2 text-xs">
                        @php
                            $footerCats = \App\Models\Category::active()->orderBy('sort_order')->take(6)->get();
                        @endphp
                        @foreach($footerCats as $cat)
                            <li>
                                <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-white transition">{{ $cat->name }}</a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('category.index') }}" class="text-sky-400 font-semibold hover:underline">All Categories &rarr;</a>
                        </li>
                    </ul>
                </div>

                {{-- Col 3: Customer Care --}}
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Customer Care</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('order.track') }}" class="hover:text-white transition">Track Your Order</a></li>
                        <li><a href="{{ route('page.faqs') }}" class="hover:text-white transition">FAQs</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-white transition">Contact Us & Support</a></li>
                        <li><a href="{{ route('page.show', 'shipping-and-delivery-policy') }}" class="hover:text-white transition">Shipping & Floor Rates</a></li>
                        <li><a href="{{ route('page.show', 'payment-policy') }}" class="hover:text-white transition">Payment Policy (QR)</a></li>
                        <li><a href="{{ route('page.show', 'refund-and-return-policy') }}" class="hover:text-white transition">Refunds & Returns</a></li>
                    </ul>
                </div>

                {{-- Col 4: Legal & Policies (Phase 8) --}}
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Legal & Privacy</h3>
                    <ul class="space-y-2 text-xs">
                        @php
                            $footerPages = \App\Models\Page::footer()->get();
                        @endphp
                        @foreach($footerPages as $p)
                            <li>
                                <a href="{{ route('page.show', $p->slug) }}" class="hover:text-white transition">{{ $p->title }}</a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('page.data-request') }}" class="hover:text-white transition">Data Export / Deletion</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom Credit & Payment Badges --}}
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
                <p>&copy; {{ date('Y') }} {{ store_name() }}. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <span class="text-slate-500 text-[11px] uppercase tracking-wider font-semibold">Payment Methods:</span>
                    <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 font-bold">DuitNow QR</span>
                    <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 font-bold">Maybank / Instant Transfer</span>
                    <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 font-bold">Balance on Delivery</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Phase 5.2: Custom Footer Scripts --}}
    {!! setting('custom_footer_scripts') !!}
</body>
</html>

