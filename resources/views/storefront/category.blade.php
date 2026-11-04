@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6" x-data="{ mobileFiltersOpen: false }">

    {{-- Breadcrumbs Navigation --}}
    <nav class="flex text-xs text-slate-500 font-medium overflow-x-auto no-scrollbar py-1" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 flex-nowrap">
            <li>
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition flex items-center gap-1 text-slate-500 hover:text-slate-900">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Home</span>
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li>
                <a href="{{ route('category.index') }}" class="hover:text-sky-600 transition text-slate-500 hover:text-slate-900">
                    Categories
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li class="text-slate-900 font-bold truncate max-w-[200px] sm:max-w-none" aria-current="page">
                {{ $category->name }}
            </li>
        </ol>
    </nav>

    {{-- Modern Mart Department Hero Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 text-white shadow-xl border border-slate-800/80 p-6 sm:p-8">
        {{-- Ambient decorative background glows --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6">
                {{-- Category Icon / Visual Card --}}
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-2xl bg-white/10 backdrop-blur-md p-2 flex items-center justify-center flex-shrink-0 shadow-2xl border border-white/20 ring-1 ring-white/10 group">
                    <div class="w-full h-full rounded-xl overflow-hidden flex items-center justify-center bg-slate-900/60 p-1.5 transition-transform duration-300 group-hover:scale-105">
                        {!! $category->renderIcon('w-full h-full object-contain drop-shadow-md rounded-lg') !!}
                    </div>
                </div>

                {{-- Banner Details --}}
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-[11px] font-bold uppercase tracking-wider border border-sky-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                            Mart Department
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/10 text-slate-300 text-[11px] font-medium border border-white/10">
                            🛒 Groceries & Essentials
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                        {{ $category->name }}
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                        {{ $category->description ?? 'Fresh groceries, beverages, daily snacks, and essential household supplies with direct local delivery.' }}
                    </p>
                </div>
            </div>

            {{-- Quick Home Delivery Guarantee Badges with 3D Delivery Character --}}
            <div class="w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-slate-800/80 flex items-center justify-between sm:justify-end gap-4">
                <div class="flex flex-col gap-2 text-xs text-slate-300">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/60 shadow-2xs">
                        <span class="text-lime-400">⚡</span>
                        <span>Free Delivery <strong class="text-white">&le; 2 km</strong></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/60 shadow-2xs">
                        <span class="text-amber-400">🏢</span>
                        <span>Free Up to <strong class="text-white">1st Floor</strong></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/60 shadow-2xs">
                        <span class="text-sky-400">🚚</span>
                        <span>Direct <strong class="text-white">Home Delivery</strong></span>
                    </div>
                </div>

                {{-- Integrated 3D Delivery Character in Hero Banner --}}
                <div class="relative w-20 h-24 sm:w-24 sm:h-28 md:w-28 md:h-32 flex-shrink-0 flex items-center justify-center">
                    <img 
                        src="{{ asset('images/delivery-3d.png') }}" 
                        alt="3D Delivery Character" 
                        class="w-full h-full object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-300"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid: Desktop Sidebar + Products Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        {{-- ========================================================
             LEFT SIDEBAR: Categories with Icons + Refined Filters + 3D Delivery Trust Card
             (Visible on Desktop, Sticky for Smooth Browsing)
             ======================================================== --}}
        <aside class="hidden lg:block lg:col-span-1 space-y-6 sticky top-24">
            
            {{-- Card 1: All Categories List with Icons --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">All Categories</h3>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                        {{ $allCategories->count() }}
                    </span>
                </div>

                {{-- Categories List with Icons --}}
                <div class="space-y-1 max-h-[380px] overflow-y-auto pr-1 no-scrollbar">
                    @foreach($allCategories as $cat)
                        @php $isActive = $cat->id === $category->id; @endphp
                        <a 
                            href="{{ route('category.show', $cat->slug) }}" 
                            class="group flex items-center justify-between p-2 rounded-xl text-xs transition-all duration-200 border {{ $isActive ? 'bg-sky-50/90 border-sky-200 text-sky-950 font-bold shadow-2xs' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:text-sky-600' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 p-1 border {{ $isActive ? 'bg-white border-sky-200 shadow-2xs' : 'bg-slate-100 border-slate-200/80 group-hover:bg-white group-hover:border-sky-200' }} transition">
                                    {!! $cat->renderIcon('w-full h-full object-contain rounded') !!}
                                </div>
                                <span class="truncate leading-tight">{{ $cat->name }}</span>
                            </div>

                            <div class="flex items-center gap-1.5 flex-shrink-0 ml-2">
                                <span class="text-[11px] px-2 py-0.5 rounded-full font-bold {{ $isActive ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-100 group-hover:text-sky-700' }} transition">
                                    {{ $cat->products_count }}
                                </span>
                                @if($isActive)
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-600"></span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="pt-2 border-t border-slate-100 text-center">
                    <a href="{{ route('category.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-semibold inline-flex items-center gap-1 hover:underline">
                        <span>Browse All Categories</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            {{-- Card 2: Filters (Availability, Brand, Price) --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm space-y-6">
                
                {{-- Header with Active Filters Count & Reset --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Filters</h3>
                    </div>

                    @if($activeFiltersCount > 0)
                        <a href="{{ route('category.show', $category->slug) }}" class="text-xs text-rose-600 hover:text-rose-700 hover:underline font-bold inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Reset ({{ $activeFiltersCount }})</span>
                        </a>
                    @endif
                </div>

                {{-- Active Filter Badges / Chips --}}
                @if($activeFiltersCount > 0)
                    <div class="flex flex-wrap gap-1.5 pb-3 border-b border-slate-100">
                        @if(request('in_stock'))
                            <a href="{{ request()->fullUrlWithoutQuery(['in_stock']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-semibold border border-emerald-200 hover:bg-emerald-100 transition group" title="Remove In-Stock filter">
                                <span>In-Stock</span>
                                <span class="text-emerald-500 group-hover:text-emerald-700">&times;</span>
                            </a>
                        @endif

                        @if(request('brand'))
                            @php $activeBrand = $availableBrands->firstWhere('slug', request('brand')); @endphp
                            <a href="{{ request()->fullUrlWithoutQuery(['brand']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-sky-50 text-sky-800 text-[11px] font-semibold border border-sky-200 hover:bg-sky-100 transition group" title="Remove Brand filter">
                                <span>Brand: {{ $activeBrand ? $activeBrand->name : request('brand') }}</span>
                                <span class="text-sky-500 group-hover:text-sky-700">&times;</span>
                            </a>
                        @endif

                        @if(request('min_price') || request('max_price'))
                            <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 text-[11px] font-semibold border border-slate-300 hover:bg-slate-200 transition group" title="Remove Price filter">
                                <span>Price: RM {{ request('min_price', '0') }} - {{ request('max_price', round($maxPrice)) }}</span>
                                <span class="text-slate-500 group-hover:text-slate-700">&times;</span>
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Filter Form --}}
                <form id="desktopFilterForm" action="{{ route('category.show', $category->slug) }}" method="GET" class="space-y-6">
                    <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                    {{-- In Stock Only Toggle --}}
                    <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-200/80">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ request('in_stock') ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block leading-tight">In-Stock Only</span>
                                    <span class="text-[10px] text-slate-500">Ready for dispatch</span>
                                </div>
                            </div>
                            <input 
                                type="checkbox" 
                                name="in_stock" 
                                value="1" 
                                {{ request('in_stock') ? 'checked' : '' }} 
                                onchange="this.form.submit()" 
                                class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4 cursor-pointer"
                            >
                        </label>
                    </div>

                    {{-- Brands Filter --}}
                    @if($availableBrands->count() > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2.5">
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Brand</h4>
                                @if(request('brand'))
                                    <a href="{{ request()->fullUrlWithoutQuery(['brand']) }}" class="text-[10px] text-slate-400 hover:text-slate-600 font-semibold">
                                        Clear
                                    </a>
                                @endif
                            </div>

                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 no-scrollbar">
                                {{-- All Brands option --}}
                                <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer hover:text-sky-600 py-1 px-2 rounded-lg hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-2.5">
                                        <input 
                                            type="radio" 
                                            name="brand" 
                                            value="" 
                                            {{ !request('brand') ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            class="text-sky-600 focus:ring-sky-500 w-3.5 h-3.5 cursor-pointer"
                                        >
                                        <span class="{{ !request('brand') ? 'font-bold text-slate-900' : '' }}">All Brands</span>
                                    </div>
                                </label>

                                @foreach($availableBrands as $brand)
                                    <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer hover:text-sky-600 py-1 px-2 rounded-lg hover:bg-slate-50 transition">
                                        <div class="flex items-center gap-2.5 truncate">
                                            <input 
                                                type="radio" 
                                                name="brand" 
                                                value="{{ $brand->slug }}" 
                                                {{ request('brand') === $brand->slug ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="text-sky-600 focus:ring-sky-500 w-3.5 h-3.5 cursor-pointer"
                                            >
                                            <span class="truncate {{ request('brand') === $brand->slug ? 'font-bold text-sky-700' : '' }}">{{ $brand->name }}</span>
                                        </div>
                                        @if(isset($brand->products_count))
                                            <span class="text-[10px] text-slate-400 font-medium ml-1">({{ $brand->products_count }})</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Price Range Filter --}}
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Price Range (RM)</h4>
                            @if(request('min_price') || request('max_price'))
                                <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}" class="text-[10px] text-slate-400 hover:text-slate-600 font-semibold">
                                    Reset
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="text-[10px] text-slate-500 block mb-1 font-semibold">Min (RM)</label>
                                <div class="relative rounded-lg shadow-2xs">
                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-xs text-slate-400 font-bold">RM</span>
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        id="desktop_min_price"
                                        value="{{ request('min_price') }}" 
                                        placeholder="0" 
                                        min="0"
                                        step="1"
                                        class="w-full text-xs pl-8 pr-2.5 py-1.5 border border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                                    >
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 block mb-1 font-semibold">Max (RM)</label>
                                <div class="relative rounded-lg shadow-2xs">
                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-xs text-slate-400 font-bold">RM</span>
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        id="desktop_max_price"
                                        value="{{ request('max_price') }}" 
                                        placeholder="{{ round($maxPrice) }}" 
                                        min="0"
                                        step="1"
                                        class="w-full text-xs pl-8 pr-2.5 py-1.5 border border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Quick Price Presets --}}
                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <button 
                                type="button" 
                                onclick="document.getElementById('desktop_min_price').value='0'; document.getElementById('desktop_max_price').value='35'; document.getElementById('desktopFilterForm').submit();"
                                class="px-2 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-sky-50 hover:text-sky-700 transition"
                            >
                                &le; RM 35
                            </button>
                            <button 
                                type="button" 
                                onclick="document.getElementById('desktop_min_price').value='35'; document.getElementById('desktop_max_price').value='40'; document.getElementById('desktopFilterForm').submit();"
                                class="px-2 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-sky-50 hover:text-sky-700 transition"
                            >
                                RM 35 - 40
                            </button>
                            <button 
                                type="button" 
                                onclick="document.getElementById('desktop_min_price').value='40'; document.getElementById('desktop_max_price').value=''; document.getElementById('desktopFilterForm').submit();"
                                class="px-2 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-sky-50 hover:text-sky-700 transition"
                            >
                                &ge; RM 40
                            </button>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full mt-3 py-2 bg-slate-900 hover:bg-slate-800 active:scale-[0.98] text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Apply Price Filter</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Card 3: 3D Delivery Trust Card in Sidebar --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 via-sky-500/10 to-slate-100 p-4 border border-amber-300/40 shadow-xs space-y-3">
                <div class="flex items-center gap-3">
                    <img 
                        src="{{ asset('images/delivery-character-carrier.png') }}" 
                        alt="Doorstep Delivery Courier" 
                        class="w-14 h-14 object-contain flex-shrink-0 drop-shadow-md"
                        loading="lazy"
                    >
                    <div>
                        <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Direct Delivery</span>
                        <h4 class="text-xs font-extrabold text-slate-900 leading-tight">Home & Store Delivery</h4>
                    </div>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed">
                    We deliver groceries, snacks, and daily essentials straight to your home or office.
                </p>
                <div class="pt-1 border-t border-slate-200/80 flex items-center justify-between text-[11px] font-bold">
                    <span class="text-emerald-700">⚡ &le; 2 km Free</span>
                    <a href="{{ route('order.track') }}" class="text-sky-600 hover:underline">Track Order &rarr;</a>
                </div>
            </div>

        </aside>

        {{-- ========================================================
             RIGHT MAIN AREA: Mobile Filter Bar + Sorting + Products
             ======================================================== --}}
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Mobile & Tablet Filter & Sort Bar (<lg screens) --}}
            <div class="lg:hidden bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between gap-3">
                {{-- Filter Drawer Trigger Button --}}
                <button 
                    type="button" 
                    @click="mobileFiltersOpen = true" 
                    class="flex items-center gap-2 px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-slate-800 transition active:scale-95"
                >
                    <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <span>Filters</span>
                    @if($activeFiltersCount > 0)
                        <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center">
                            {{ $activeFiltersCount }}
                        </span>
                    @endif
                </button>

                {{-- Mobile Sort Dropdown --}}
                <div class="flex items-center gap-1.5 flex-1 justify-end">
                    <label for="mobile_sort" class="text-xs text-slate-500 font-semibold hidden sm:inline">Sort:</label>
                    <select 
                        id="mobile_sort" 
                        onchange="location.href = this.value" 
                        class="text-xs py-2 pl-3 pr-8 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50 font-medium"
                    >
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ $sort === 'popular' ? 'selected' : '' }}>Most In Stock</option>
                    </select>
                </div>
            </div>

            {{-- Desktop Sort Bar (lg+ screens) --}}
            <div class="hidden lg:flex bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm items-center justify-between gap-4">
                <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                    <span>Showing <strong class="font-bold text-slate-900">{{ $products->total() }}</strong> items in <strong class="text-slate-900">{{ $category->name }}</strong></span>
                    @if($activeFiltersCount > 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 text-[11px] font-bold border border-amber-200">
                            {{ $activeFiltersCount }} filter(s) active
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <label for="desktop_sort" class="text-xs text-slate-500 font-bold uppercase tracking-wider">Sort by:</label>
                    <select 
                        id="desktop_sort" 
                        onchange="location.href = this.value" 
                        class="text-xs py-1.5 pl-3 pr-8 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50 font-medium"
                    >
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ $sort === 'popular' ? 'selected' : '' }}>Most In Stock</option>
                    </select>
                </div>
            </div>

            {{-- Products Grid: Mobile (2-col), Tablet (2-col), Desktop (3-col) --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                    @foreach($products as $product)
                        @include('storefront.partials.product_card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-3xl p-10 sm:p-14 text-center border border-slate-200/90 shadow-sm">
                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto text-3xl mb-4 border border-slate-200">
                        📦
                    </div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">No matching items found</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                        We couldn't find any products in <strong>{{ $category->name }}</strong> matching your selected filter criteria. Try adjusting or clearing your filters.
                    </p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('category.show', $category->slug) }}" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-700 shadow-sm transition">
                            Clear Active Filters
                        </a>
                        <a href="{{ route('category.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                            Explore All Categories
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ========================================================
         MOBILE & TABLET FILTER DRAWER (Alpine Slide-Over)
         ======================================================== --}}
    <div 
        x-cloak 
        x-show="mobileFiltersOpen" 
        class="fixed inset-0 z-50 overflow-hidden lg:hidden"
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="mobileFiltersOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition-opacity ease-linear duration-300" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            @click="mobileFiltersOpen = false" 
            class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs"
        ></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div 
                x-show="mobileFiltersOpen" 
                x-transition:enter="transition ease-in-out duration-300 transform" 
                x-transition:enter-start="translate-x-full" 
                x-transition:enter-end="translate-x-0" 
                x-transition:leave="transition ease-in-out duration-300 transform" 
                x-transition:leave-start="translate-x-0" 
                x-transition:leave-end="translate-x-full" 
                class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between"
            >
                {{-- Drawer Header --}}
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Filters & Departments</h2>
                            <p class="text-[11px] text-slate-500">Refine store catalog items</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        @click="mobileFiltersOpen = false" 
                        class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Drawer Body: Scrollable Categories & Filters --}}
                <div class="p-5 overflow-y-auto space-y-6 flex-1">
                    
                    {{-- 1. All Categories List with Icons --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Browse Departments</h3>
                            <span class="text-[10px] text-slate-400 font-semibold">{{ $allCategories->count() }} Categories</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 max-h-56 overflow-y-auto pr-1 no-scrollbar">
                            @foreach($allCategories as $cat)
                                @php $isActive = $cat->id === $category->id; @endphp
                                <a 
                                    href="{{ route('category.show', $cat->slug) }}" 
                                    class="flex items-center gap-2 p-2 rounded-xl text-xs transition border {{ $isActive ? 'bg-sky-50 border-sky-300 text-sky-950 font-bold' : 'border-slate-100 bg-slate-50/50 text-slate-700 hover:bg-sky-50/50 hover:text-sky-700' }}"
                                >
                                    <div class="w-7 h-7 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0 bg-white border border-slate-200/80 p-0.5">
                                        {!! $cat->renderIcon('w-full h-full object-contain rounded') !!}
                                    </div>
                                    <span class="truncate flex-1">{{ $cat->name }}</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold {{ $isActive ? 'bg-sky-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $cat->products_count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Form for mobile filters --}}
                    <form id="mobileFilterForm" action="{{ route('category.show', $category->slug) }}" method="GET" class="space-y-6 pt-3 border-t border-slate-100">
                        <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                        {{-- 2. In Stock Only --}}
                        <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/80">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">In-Stock Only</span>
                                    <span class="text-[10px] text-slate-500">Only show in-stock products</span>
                                </div>
                                <input 
                                    type="checkbox" 
                                    name="in_stock" 
                                    value="1" 
                                    {{ request('in_stock') ? 'checked' : '' }} 
                                    class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4 cursor-pointer"
                                >
                            </label>
                        </div>

                        {{-- 3. Brands --}}
                        @if($availableBrands->count() > 0)
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2.5">Brand</h3>
                                <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1 no-scrollbar">
                                    <label class="flex items-center justify-between text-xs text-slate-700 py-1.5 px-2 rounded-lg hover:bg-slate-50">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="radio" 
                                                name="brand" 
                                                value="" 
                                                {{ !request('brand') ? 'checked' : '' }}
                                                class="text-sky-600 focus:ring-sky-500 w-3.5 h-3.5"
                                            >
                                            <span class="{{ !request('brand') ? 'font-bold' : '' }}">All Brands</span>
                                        </div>
                                    </label>
                                    @foreach($availableBrands as $brand)
                                        <label class="flex items-center justify-between text-xs text-slate-700 py-1.5 px-2 rounded-lg hover:bg-slate-50">
                                            <div class="flex items-center gap-2 truncate">
                                                <input 
                                                    type="radio" 
                                                    name="brand" 
                                                    value="{{ $brand->slug }}" 
                                                    {{ request('brand') === $brand->slug ? 'checked' : '' }}
                                                    class="text-sky-600 focus:ring-sky-500 w-3.5 h-3.5"
                                                >
                                                <span class="truncate {{ request('brand') === $brand->slug ? 'font-bold text-sky-700' : '' }}">{{ $brand->name }}</span>
                                            </div>
                                            @if(isset($brand->products_count))
                                                <span class="text-[10px] text-slate-400">({{ $brand->products_count }})</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 4. Price Range --}}
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2.5">Price Range (RM)</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-slate-500 block mb-1 font-semibold">Min (RM)</label>
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        value="{{ request('min_price') }}" 
                                        placeholder="0" 
                                        class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                                    >
                                </div>
                                <div>
                                    <label class="text-[10px] text-slate-500 block mb-1 font-semibold">Max (RM)</label>
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        value="{{ request('max_price') }}" 
                                        placeholder="{{ round($maxPrice) }}" 
                                        class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-sky-500 focus:border-sky-500"
                                    >
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- 5. Mobile 3D Delivery Callout --}}
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-amber-50 to-sky-50 rounded-2xl border border-amber-200">
                        <img 
                            src="{{ asset('images/delivery-character-carrier.png') }}" 
                            alt="3D Delivery" 
                            class="w-12 h-12 object-contain"
                        >
                        <div class="text-[11px]">
                            <strong class="text-slate-900 block font-bold">Direct Home Delivery</strong>
                            <span class="text-slate-600">Free within 2km &amp; free to 1st floor</span>
                        </div>
                    </div>

                </div>

                {{-- Drawer Footer: Reset & Apply CTA --}}
                <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center gap-3">
                    <a 
                        href="{{ route('category.show', $category->slug) }}" 
                        class="px-4 py-2.5 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-100 transition text-center"
                    >
                        Reset All
                    </a>
                    <button 
                        type="button" 
                        onclick="document.getElementById('mobileFilterForm').submit();" 
                        class="flex-1 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm transition text-center"
                    >
                        Apply Filters
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
