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
    <link rel="icon" type="image/png" href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts: Quicksand & Lato (Nest Theme Official Typography) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,400&family=Quicksand:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Phase 6: Dynamic CSS Variables & Tailwind Theme Injection (Nest Theme Harmonized) --}}
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --color-primary: {{ setting('color_primary', '#3BB77E') }};
            --color-primary-hover: {{ setting('color_primary_hover', '#29A56C') }};
            --color-secondary: {{ setting('color_secondary', '#253D4E') }};
            --color-accent: {{ setting('color_accent', '#FDC040') }};
            --color-bg: {{ setting('color_bg', '#ffffff') }};
            --color-text: {{ setting('color_text', '#253D4E') }};
            --color-header-bg: {{ setting('color_header_bg', '#ffffff') }};
            --color-footer-bg: {{ setting('color_footer_bg', '#253D4E') }};
            --color-button-text: {{ setting('color_button_text', '#ffffff') }};
            --font-family: 'Lato', system-ui, -apple-system, sans-serif;
            --font-heading: 'Quicksand', system-ui, -apple-system, sans-serif;
            --border-radius: {{ setting('border_radius', '0.75rem') }};
            --nest-green: #3BB77E;
            --nest-green-hover: #29A56C;
            --nest-dark: #253D4E;
            --nest-muted: #7E7E7E;
            --nest-border: #ececec;
            --nest-light-bg: #F4F6FA;
        }
        body {
            font-family: var(--font-family);
            background-color: var(--color-bg);
            color: var(--color-text);
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .nest-heading {
            font-family: var(--font-heading) !important;
        }
        .btn-primary {
            background-color: var(--nest-green);
            color: var(--color-button-text);
            border-radius: var(--border-radius);
            font-family: var(--font-heading);
            font-weight: 700;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: var(--nest-green-hover);
        }
        .btn-accent {
            background-color: var(--color-accent);
            color: #253D4E;
            border-radius: var(--border-radius);
            font-family: var(--font-heading);
            font-weight: 700;
        }
        .bg-primary { background-color: var(--nest-green); }
        .text-primary { color: var(--nest-green); }
        .border-primary { border-color: var(--nest-green); }
    </style>

    {{-- Vite Scripts (Tailwind + Alpine.js + Axios) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

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
@php
    $cartSummary = app(\App\Services\CartService::class)->getSummary();
    $cachedNavCategories = \Illuminate\Support\Facades\Cache::remember('storefront_nav_categories', 300, function () {
        return \App\Models\Category::active()->withCount('products')->orderBy('sort_order')->take(12)->get();
    });
    $cachedFooterPages = \Illuminate\Support\Facades\Cache::remember('storefront_footer_pages', 300, function () {
        return \App\Models\Page::footer()->get();
    });
@endphp
<body class="flex flex-col min-h-screen" x-data="{
    mobileMenuOpen: false,
    cartOpen: false,
    liveSearchQuery: '',
    liveSearchResults: [],
    searchLoading: false,
    cartCount: {{ (int) ($cartSummary['item_count'] ?? 0) }},
    cartSubtotal: '{{ $cartSummary['subtotal_formatted'] ?? format_currency(0) }}',
    cartItems: @js($cartSummary['items'] ?? []),
    minOrderValue: '{{ $cartSummary['minimum_order_formatted'] ?? format_currency(0) }}',
    isMinMet: {{ !empty($cartSummary['is_minimum_met']) ? 'true' : 'false' }},
    deficitFormatted: '{{ $cartSummary['deficit_formatted'] ?? format_currency(0) }}',
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
}">

    {{-- Top Announcement Bar (Compact single-line on mobile) --}}
    <div class="bg-slate-900 text-slate-200 text-[11px] sm:text-xs py-1.5 sm:py-2 px-3 sm:px-4 border-b border-slate-800">
        <div class="max-w-[1600px] mx-auto flex justify-between items-center gap-2">
            <div class="flex items-center gap-1.5 sm:gap-3 truncate">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-semibold shrink-0">
                    ⚡ Free Delivery
                </span>
                <span class="truncate">Within {{ setting('free_delivery_radius', 5) }} km &amp; 1st Floor!</span>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-slate-300 text-xs shrink-0">
                <span>📞 Hotline: {{ setting('store_phone', '+60 12-889 9900') }}</span>
                <span class="hidden md:inline">🕒 {{ setting('working_hours', 'Mon-Sat 8am-7pm') }}</span>
            </div>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('store_phone', '+60 12-889 9900')) }}" class="sm:hidden text-emerald-300 font-semibold shrink-0">📞 Call</a>
        </div>
    </div>

    {{-- Main Header --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-2xs">
        <div class="max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-4">
                
                {{-- Mobile menu button --}}
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 active:scale-95 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                {{-- Brand Logo --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="bg-black p-1.5 sm:px-3 sm:py-2 rounded-xl sm:rounded-2xl shadow-md border border-neutral-800 flex items-center transition-all group-hover:border-lime-500/50">
                            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png') }}" alt="{{ store_name() }}" class="h-8 sm:h-11 w-auto object-contain" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                        </div>
                    </a>
                </div>

                {{-- Live Search Bar (Center - Nest Style) --}}
                <div class="flex-1 max-w-xl mx-2 hidden sm:block relative">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <input 
                                type="text" 
                                name="q" 
                                x-model="liveSearchQuery" 
                                @input.debounce.300ms="fetchLiveSearch()" 
                                @focus="fetchLiveSearch()" 
                                placeholder="Search for products, fresh groceries..." 
                                class="w-full pl-11 pr-24 py-2.5 bg-white border-2 border-[#BCE3C9] rounded-lg text-sm text-[#253D4E] focus:bg-white focus:border-[#3BB77E] focus:ring-2 focus:ring-[#DEF9EC] transition font-['Lato']"
                                autocomplete="off"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#7E7E7E]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <button type="submit" class="absolute inset-y-1 right-1 px-5 bg-[#3BB77E] hover:bg-[#29A56C] text-white text-xs font-bold rounded-md transition flex items-center font-['Quicksand'] cursor-pointer">
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
                            <div class="py-1">
                                @foreach($cachedNavCategories->take(10) as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg overflow-hidden flex items-center justify-center p-0.5 shrink-0 border border-slate-200 bg-slate-100 shadow-2xs">
                                                {!! $cat->renderIcon('w-full h-full object-cover rounded-md') !!}
                                            </div>
                                            <span class="font-medium text-xs">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $cat->products_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="pt-2 text-center">
                                <a href="{{ route('category.index') }}" class="text-xs font-bold text-sky-600 hover:underline">View All Categories &rarr;</a>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Account Menu (Desktop Header - moved to Bottom Bar on Mobile) --}}
                    @auth
                        <div class="relative hidden lg:block" x-data="{ userMenuOpen: false }">
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

                    {{-- Mini-Cart Trigger Button (Nest Style) --}}
                    <button 
                        type="button" 
                        @click="cartOpen = true; refreshMiniCart()" 
                        class="relative flex items-center gap-2.5 bg-[#DEF9EC] hover:bg-[#3BB77E] text-[#253D4E] hover:text-white border border-[#BCE3C9] px-4 py-2.5 rounded-lg font-bold text-sm shadow-2xs transition-all font-['Quicksand'] cursor-pointer"
                    >
                        <div class="relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span 
                                x-show="cartCount > 0" 
                                x-text="cartCount" 
                                class="absolute -top-2.5 -right-3 bg-[#3BB77E] text-white text-[11px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-xs"
                            ></span>
                        </div>
                        <span class="hidden md:inline font-bold" x-text="cartSubtotal"></span>
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

        {{-- Nest Style Sub-Navigation Bar (Home-3 Reference) --}}
        <div class="hidden lg:block border-t border-[#ececec] bg-white">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                {{-- Left: Browse All Categories Button --}}
                <div class="relative" x-data="{ browseOpen: false }">
                    <button 
                        @click="browseOpen = !browseOpen" 
                        @click.away="browseOpen = false"
                        class="bg-[#3BB77E] hover:bg-[#29A56C] text-white font-bold text-sm px-5 py-3 rounded-t-lg flex items-center gap-2.5 transition-colors font-['Quicksand'] cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span>Browse All Categories</span>
                        <svg class="w-4 h-4 ml-1 transition-transform" :class="browseOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- Dropdown Menu for Browse Categories --}}
                    <div 
                        x-show="browseOpen" 
                        x-transition 
                        class="absolute left-0 top-full mt-0 w-72 bg-white rounded-b-xl shadow-2xl border border-[#ececec] border-t-0 p-3 z-50 divide-y divide-[#ececec]/60"
                        style="display: none;"
                    >
                        <div class="py-1">
                            @foreach($cachedNavCategories as $navCat)
                                <a href="{{ route('category.show', $navCat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-[#253D4E] hover:bg-[#DEF9EC] hover:text-[#3BB77E] transition">
                                    <span class="truncate">{{ $navCat->name }}</span>
                                    <span class="text-[10px] text-[#253D4E] bg-[#DEF9EC] font-bold px-2 py-0.5 rounded-full">{{ $navCat->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Center: Navigation Links --}}
                <nav class="flex items-center gap-7 text-sm font-bold text-[#253D4E] font-['Quicksand']">
                    <a href="{{ route('home') }}" class="hover:text-[#3BB77E] transition {{ request()->routeIs('home') ? 'text-[#3BB77E]' : '' }}">
                        <span>Home</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="hover:text-[#3BB77E] transition {{ request()->routeIs('products.index') ? 'text-[#3BB77E]' : '' }}">
                        <span>All Products</span>
                    </a>
                    <a href="{{ route('page.faqs') }}" class="hover:text-[#3BB77E] transition {{ request()->routeIs('page.faqs') ? 'text-[#3BB77E]' : '' }}">
                        <span>FAQs</span>
                    </a>
                    <a href="{{ route('page.contact') }}" class="hover:text-[#3BB77E] transition {{ request()->routeIs('page.contact') ? 'text-[#3BB77E]' : '' }}">
                        <span>Contact</span>
                    </a>
                </nav>

                {{-- Right: Support Phone Hotline --}}
                <div class="flex items-center gap-2.5 text-[#253D4E]">
                    <div class="w-8 h-8 rounded-full bg-[#DEF9EC] text-[#3BB77E] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-[#3BB77E] font-['Quicksand'] leading-tight">{{ setting('store_phone', setting('admin_phone', '1900888123')) }}</span>
                        <span class="block text-[10px] text-[#7E7E7E] font-medium leading-tight">24/7 Support Center</span>
                    </div>
                </div>
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
                    <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">All Products</a>
                    <a href="{{ route('category.index') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">All Categories</a>
                    <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-sm font-semibold text-slate-800 rounded-lg hover:bg-slate-50">View Cart</a>
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
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                
                {{-- Col 1: Brand Info --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-black p-2.5 rounded-2xl border border-neutral-800 shadow-md">
                            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png') }}" alt="{{ store_name() }}" class="h-11 sm:h-13 w-auto object-contain" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
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
                        @foreach($cachedNavCategories->take(6) as $cat)
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
                        @foreach($cachedFooterPages as $p)
                            <li>
                                <a href="{{ route('page.show', $p->slug) }}" class="hover:text-white transition">{{ $p->title }}</a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('page.data-request') }}" class="hover:text-white transition">Data Export / Deletion</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom Credit & Payment Badges --}}
            <div class="mt-10 sm:mt-12 pt-6 sm:pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs pb-20 lg:pb-0">
                <p>&copy; {{ date('Y') }} {{ store_name() }}. All rights reserved.</p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <span class="text-slate-500 text-[11px] uppercase tracking-wider font-semibold">Payment Methods:</span>
                    <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 font-bold">DuitNow QR</span>
                    <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 font-bold">Maybank / Instant Transfer</span>
                    <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 font-bold">Balance on Delivery</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile App-Style Sticky Bottom Navigation Bar --}}
    <div x-data="{ mobileAccountOpen: false }" class="lg:hidden">
        {{-- Mobile Account Menu Sheet (Opens when tapping Account in bottom bar) --}}
        <div 
            x-show="mobileAccountOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            @click.away="mobileAccountOpen = false"
            x-cloak
            class="fixed bottom-16 right-3 left-3 sm:left-auto sm:w-72 z-50 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden"
        >
            @auth
                <div class="px-4 py-3 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-[#3BB77E] text-white flex items-center justify-center text-sm font-black shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-300 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <button type="button" @click="mobileAccountOpen = false" class="p-1 text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="py-1.5 text-sm divide-y divide-slate-100">
                    @if(auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 font-bold text-sky-600 hover:bg-sky-50">
                            <span>⚙️</span>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-medium">
                        <span>📦</span>
                        <span>My Orders</span>
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-medium">
                        <span>📍</span>
                        <span>Saved Addresses</span>
                    </a>
                    <a href="{{ route('account.export-data') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-medium">
                        <span>📥</span>
                        <span>Download My Data</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 text-left px-4 py-2.5 text-rose-600 hover:bg-rose-50 font-semibold">
                            <span>🚪</span>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="p-4 space-y-2.5">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-bold text-slate-800">Welcome to {{ store_name() }}</p>
                        <button type="button" @click="mobileAccountOpen = false" class="p-1 text-slate-400 hover:text-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <a href="{{ route('login') }}" class="block w-full text-center py-2.5 rounded-xl bg-[#3BB77E] hover:bg-[#29A56C] text-white font-bold text-sm transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-sm transition">
                        Create Account (Sign Up)
                    </a>
                </div>
            @endauth
        </div>

        <nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 py-1.5" aria-label="Mobile Navigation">
            <div class="grid grid-cols-5 items-center text-center">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('home') ? 'text-[#3BB77E] font-bold' : 'text-slate-500 hover:text-[#3BB77E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-[10px] leading-none font-['Quicksand']">Home</span>
                </a>

                <a href="{{ route('category.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('category.*') ? 'text-[#3BB77E] font-bold' : 'text-slate-500 hover:text-[#3BB77E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="text-[10px] leading-none font-['Quicksand']">Categories</span>
                </a>

                <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center py-1 rounded-xl transition {{ request()->routeIs('products.*') ? 'text-[#3BB77E] font-bold' : 'text-slate-500 hover:text-[#3BB77E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="text-[10px] leading-none font-['Quicksand']">Shop</span>
                </a>

                <button type="button" @click="cartOpen = true; mobileAccountOpen = false" class="relative flex flex-col items-center justify-center py-1 rounded-xl text-slate-500 hover:text-[#3BB77E] transition cursor-pointer">
                    <div class="relative">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span x-show="cartCount > 0" x-text="cartCount" x-cloak class="absolute -top-1.5 -right-2.5 min-w-[16px] h-4 px-1 bg-[#3BB77E] text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-xs"></span>
                    </div>
                    <span class="text-[10px] leading-none font-['Quicksand']">Cart</span>
                </button>

                <button type="button" @click="mobileAccountOpen = !mobileAccountOpen" class="flex flex-col items-center justify-center py-1 rounded-xl transition cursor-pointer" :class="mobileAccountOpen || {{ request()->routeIs('account.*') ? 'true' : 'false' }} ? 'text-[#3BB77E] font-bold' : 'text-slate-500 hover:text-[#3BB77E]'">
                    @auth
                        <div class="w-5 h-5 mb-0.5 rounded-full bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-[10px] leading-none font-['Quicksand']">Account</span>
                    @else
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="text-[10px] leading-none font-['Quicksand']">Account</span>
                    @endauth
                </button>
            </div>
        </nav>
    </div>

    {{-- Floating WhatsApp Chat Button --}}
    @php
        $waRawPhone = setting('store_whatsapp') ?: (setting('admin_phone') ?: setting('store_phone', '+60128899900'));
        $waNumber = preg_replace('/[^0-9]/', '', $waRawPhone);
        $waText = rawurlencode('Hello ' . store_name() . ', I have an inquiry regarding your products.');
    @endphp
    <a 
        href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" 
        target="_blank" 
        rel="noopener noreferrer"
        aria-label="Chat on WhatsApp"
        title="Chat with us on WhatsApp"
        class="fixed bottom-20 right-4 lg:bottom-6 lg:right-6 z-40 group flex items-center gap-2 bg-[#25D366] hover:bg-[#1EBE5D] text-white p-3.5 sm:px-4 sm:py-3.5 rounded-full shadow-lg hover:shadow-xl active:scale-95 transition-all duration-200"
    >
        <svg class="w-6 h-6 fill-current shrink-0" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12.031 2c-5.516 0-9.969 4.453-9.969 9.969 0 1.766.469 3.484 1.344 5l-1.406 5.156 5.281-1.375c1.469.797 3.109 1.219 4.75 1.219 5.516 0 9.969-4.453 9.969-9.969S17.547 2 12.031 2zm5.813 14.125c-.25.703-1.438 1.344-2 1.422-.516.078-1.188.109-1.922-.125-.453-.141-1.031-.328-1.766-.656-3.094-1.344-5.109-4.469-5.266-4.672-.156-.203-1.266-1.688-1.266-3.219s.797-2.281 1.078-2.594c.281-.313.609-.391.813-.391.203 0 .406.016.578.016.188 0 .438-.078.688.516.25.609.859 2.094.938 2.25.078.156.125.344.031.547-.094.203-.141.328-.297.5-.156.172-.313.391-.453.516-.156.141-.313.297-.141.594.172.297.766 1.266 1.641 2.047 1.125 1 2.078 1.313 2.375 1.469.297.156.469.125.641-.078.172-.203.75-.875.953-1.172.203-.297.406-.25.688-.156.281.094 1.781.844 2.094 1 .313.156.516.234.594.359.078.125.078.734-.172 1.438z"/>
        </svg>
        <span class="hidden sm:inline text-xs font-bold font-['Quicksand'] tracking-wide pr-0.5">WhatsApp</span>
    </a>

    {{-- Floating Store-Only AI Assistant Chat Widget --}}
    <div 
        x-data="{
            aiOpen: false,
            aiInput: '',
            aiLoading: false,
            aiMessages: [
                {
                    role: 'bot',
                    text: 'Hi! I am the {{ addslashes(store_name()) }} AI Assistant. Ask me anything about our products, prices, categories, delivery, or orders!',
                    links: []
                }
            ],
            async sendAiMessage(customText = null) {
                const msg = (customText ?? this.aiInput).trim();
                if (!msg || this.aiLoading) return;
                if (!customText) this.aiInput = '';

                this.aiMessages.push({ role: 'user', text: msg, links: [] });
                this.aiLoading = true;
                this.$nextTick(() => {
                    const box = this.$refs.aiScroll;
                    if (box) box.scrollTop = box.scrollHeight;
                });

                try {
                    const res = await fetch('{{ route("ai.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: msg })
                    });
                    const data = await res.json();
                    this.aiMessages.push({
                        role: 'bot',
                        text: data.reply || 'Sorry, I could not process that request. Please ask about our store products or orders.',
                        links: data.links || []
                    });
                } catch (e) {
                    this.aiMessages.push({
                        role: 'bot',
                        text: 'Connection error. Please try again in a moment.',
                        links: []
                    });
                } finally {
                    this.aiLoading = false;
                    this.$nextTick(() => {
                        const box = this.$refs.aiScroll;
                        if (box) box.scrollTop = box.scrollHeight;
                    });
                }
            }
        }"
        class="fixed bottom-20 left-4 lg:bottom-6 lg:left-6 z-40"
    >
        {{-- Chat Window --}}
        <div 
            x-show="aiOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            x-cloak
            class="mb-3 w-[calc(100vw-2rem)] sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[70vh]"
        >
            {{-- Header --}}
            <div class="bg-slate-900 text-white px-4 py-3.5 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-2xl bg-[#3BB77E] flex items-center justify-center text-lg shadow-sm">
                        🤖
                    </div>
                    <div>
                        <h3 class="text-sm font-bold leading-tight font-['Quicksand']">{{ store_name() }} AI</h3>
                        <p class="text-[10px] text-emerald-300 font-medium">Store &amp; Products Assistant Only</p>
                    </div>
                </div>
                <button type="button" @click="aiOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer" aria-label="Close AI Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Messages Feed --}}
            <div x-ref="aiScroll" class="flex-1 overflow-y-auto p-3.5 space-y-3 bg-slate-50/70">
                <template x-for="(m, idx) in aiMessages" :key="idx">
                    <div :class="m.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div 
                            :class="m.role === 'user' ? 'bg-[#3BB77E] text-white rounded-2xl rounded-br-xs' : 'bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-bl-xs shadow-2xs'"
                            class="max-w-[85%] px-3.5 py-2.5 text-xs leading-relaxed"
                        >
                            <p x-text="m.text" class="whitespace-pre-line"></p>
                            <template x-if="m.links && m.links.length > 0">
                                <div class="mt-2 pt-2 border-t border-slate-100 space-y-1.5">
                                    <template x-for="(lnk, lIdx) in m.links" :key="lIdx">
                                        <a :href="lnk.url" class="block px-2.5 py-1.5 rounded-xl bg-[#DEF9EC]/60 hover:bg-[#DEF9EC] text-[#253D4E] font-bold text-[11px] transition truncate" x-text="lnk.label"></a>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="aiLoading" class="flex justify-start">
                    <div class="bg-white border border-slate-200 rounded-2xl rounded-bl-xs px-3.5 py-2 text-xs text-slate-400 font-medium">
                        Thinking...
                    </div>
                </div>
            </div>

            {{-- Quick Starter Chips --}}
            <div class="px-3 py-2 bg-white border-t border-slate-100 flex items-center gap-1.5 overflow-x-auto no-scrollbar shrink-0">
                <button type="button" @click="sendAiMessage('Popular products')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-[#DEF9EC] text-[11px] font-semibold text-slate-700 whitespace-nowrap transition cursor-pointer">🛍️ Popular Products</button>
                <button type="button" @click="sendAiMessage('Delivery charges & radius')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-[#DEF9EC] text-[11px] font-semibold text-slate-700 whitespace-nowrap transition cursor-pointer">🚚 Delivery Info</button>
                <button type="button" @click="sendAiMessage('Track my order')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-[#DEF9EC] text-[11px] font-semibold text-slate-700 whitespace-nowrap transition cursor-pointer">📦 Track Order</button>
                <button type="button" @click="sendAiMessage('Contact & store hours')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-[#DEF9EC] text-[11px] font-semibold text-slate-700 whitespace-nowrap transition cursor-pointer">📞 Contact</button>
            </div>

            {{-- Input Form --}}
            <form @submit.prevent="sendAiMessage()" class="p-2.5 bg-white border-t border-slate-200 flex items-center gap-2 shrink-0">
                <input 
                    type="text" 
                    x-model="aiInput" 
                    placeholder="Ask about products, prices, delivery..." 
                    maxlength="400"
                    class="flex-1 text-xs px-3.5 py-2.5 rounded-xl bg-slate-100 border-0 focus:ring-2 focus:ring-[#3BB77E] text-slate-800"
                >
                <button 
                    type="submit" 
                    :disabled="aiLoading || !aiInput.trim()"
                    class="px-3.5 py-2.5 rounded-xl bg-[#3BB77E] hover:bg-[#29A56C] disabled:opacity-50 text-white text-xs font-bold transition cursor-pointer shrink-0"
                >
                    Send
                </button>
            </form>
        </div>

        {{-- Floating Toggle Button --}}
        <button 
            type="button" 
            @click="aiOpen = !aiOpen"
            aria-label="Open Store AI Assistant"
            title="Ask {{ store_name() }} AI Assistant"
            class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white p-3.5 sm:px-4 sm:py-3.5 rounded-full shadow-lg hover:shadow-xl active:scale-95 transition-all duration-200 border border-slate-700 cursor-pointer"
        >
            <span class="text-lg leading-none">🤖</span>
            <span class="hidden sm:inline text-xs font-bold font-['Quicksand'] tracking-wide pr-0.5">AI Help</span>
        </button>
    </div>

    {{-- Phase 5.2: Custom Footer Scripts --}}
    {!! setting('custom_footer_scripts') !!}
</body>
</html>

