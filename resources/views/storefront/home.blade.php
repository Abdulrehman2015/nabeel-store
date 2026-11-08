@extends('layouts.storefront')

@section('content')
<div class="space-y-12 sm:space-y-16 pb-20">

    {{-- 1. Modern Hero Section --}}
    @if($banners->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2 sm:pt-4">
            <div 
                x-data="{
                    activeSlide: 0,
                    totalSlides: {{ $banners->count() }},
                    interval: null,
                    next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                    start() {
                        this.interval = setInterval(() => { this.next(); }, {{ setting('slider_autoplay_speed', 6000) }});
                    },
                    stop() { clearInterval(this.interval); }
                }" 
                x-init="start()" 
                @mouseenter="stop()" 
                @mouseleave="start()"
                class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-950 border border-slate-800 text-white min-h-[360px] sm:min-h-[460px] lg:min-h-[500px] flex items-center group select-none"
            >
                @foreach($banners as $index => $banner)
                    @php
                        $rawPath = ltrim($banner->desktop_image ?? '', '/');
                        if (str_starts_with($rawPath, 'storage/')) {
                            $imageSrc = asset($rawPath);
                        } elseif (str_starts_with($rawPath, 'images/')) {
                            $imageSrc = asset($rawPath);
                        } else {
                            $imageSrc = asset('storage/' . $rawPath);
                        }
                        $publicFallback = asset('images/' . basename($rawPath));
                    @endphp
                    <div 
                        x-show="activeSlide === {{ $index }}" 
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-102"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-98"
                        class="absolute inset-0 w-full h-full"
                    >
                        {{-- Background Image with Cinematic Gradient --}}
                        <div class="relative w-full h-full overflow-hidden">
                            @if($banner->desktop_image)
                                <img 
                                    src="{{ $imageSrc }}" 
                                    onerror="this.onerror=null; this.src='{{ $publicFallback }}'" 
                                    alt="{{ $banner->title }}" 
                                    class="w-full h-full object-cover object-center transform group-hover:scale-104 transition-transform duration-1000 ease-out"
                                >
                                {{-- Multi-stop dynamic gradient: Deep dark on left for text legibility, subtle fade to right --}}
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/85 to-slate-950/30 sm:to-transparent"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent sm:hidden"></div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-950 via-slate-900 to-sky-950"></div>
                            @endif

                            {{-- Hero Content Layout --}}
                            <div class="absolute inset-0 flex items-center px-6 sm:px-12 lg:px-16 z-10">
                                <div class="max-w-xl lg:max-w-2xl space-y-4 sm:space-y-5 text-left">
                                    
                                    {{-- Badge / Tagline --}}
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-sky-500/20 text-sky-300 border border-sky-400/30 backdrop-blur-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-ping"></span>
                                            {{ $banner->badge ?: 'Fresh Groceries & Essentials' }}
                                        </span>
                                        <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-bold text-slate-300 bg-white/10 px-2.5 py-1 rounded-full backdrop-blur-xs">
                                            <span>🏢 Floor 0 & 1 Free</span>
                                        </span>
                                    </div>

                                    {{-- Main Title --}}
                                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight drop-shadow-md">
                                        {{ $banner->title }}
                                    </h1>

                                    {{-- Subtitle --}}
                                    @if($banner->subtitle)
                                        <p class="text-xs sm:text-base text-slate-200 font-medium line-clamp-3 sm:line-clamp-2 max-w-lg leading-relaxed drop-shadow-sm">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif

                                    {{-- Micro-Pill Feature Highlights --}}
                                    <div class="hidden sm:flex flex-wrap items-center gap-2 pt-1 text-[11px] text-slate-300">
                                        <span class="inline-flex items-center gap-1.5 bg-slate-900/60 px-3 py-1 rounded-lg border border-slate-700/60 backdrop-blur-sm">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            100% Fresh & Authentic
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 bg-slate-900/60 px-3 py-1 rounded-lg border border-slate-700/60 backdrop-blur-sm">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Free Delivery Radius ({{ setting('free_delivery_radius', 2) }}km)
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 bg-slate-900/60 px-3 py-1 rounded-lg border border-slate-700/60 backdrop-blur-sm">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Everyday Low Mart Prices
                                        </span>
                                    </div>

                                    {{-- CTA Action Buttons --}}
                                    <div class="pt-2 sm:pt-3 flex flex-wrap items-center gap-3">
                                        <a 
                                            href="{{ $banner->link ?: route('category.index') }}" 
                                            class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3.5 rounded-xl bg-sky-500 hover:bg-sky-400 active:scale-95 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-sky-500/30 transition-all border border-sky-300"
                                        >
                                            <span>{{ $banner->button_text ?: 'Shop Groceries Now' }}</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>

                                        <a 
                                            href="{{ route('category.index') }}" 
                                            class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2.5 sm:py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all"
                                        >
                                            <span>Explore Aisles</span>
                                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Left & Right Arrows --}}
                <button 
                    type="button" 
                    @click="prev()" 
                    aria-label="Previous slide" 
                    class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white backdrop-blur-md border border-white/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-xl cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button 
                    type="button" 
                    @click="next()" 
                    aria-label="Next slide" 
                    class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white backdrop-blur-md border border-white/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-xl cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Slider Dots --}}
                <div class="absolute bottom-4 left-0 right-0 z-20 flex justify-center gap-2">
                    @foreach($banners as $index => $banner)
                        <button 
                            type="button" 
                            @click="activeSlide = {{ $index }}" 
                            aria-label="Slide {{ $index + 1 }}"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-sky-400 shadow-md shadow-sky-400/50' : 'w-2 bg-white/40 hover:bg-white/70'"
                        ></button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 2. Store Value Props (Refined Modern Trust Strip) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs">
            
            {{-- Prop 1: Delivery --}}
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">Free Local Delivery</h4>
                    <p class="text-[11px] text-slate-500">Within {{ setting('free_delivery_radius', 2) }} km radius</p>
                </div>
            </div>

            {{-- Prop 2: Floor --}}
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">Doorstep & Floor Delivery</h4>
                    <p class="text-[11px] text-slate-500">Floor 0 & 1 zero extra charges</p>
                </div>
            </div>

            {{-- Prop 3: 100% Genuine --}}
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">100% Fresh & Authentic</h4>
                    <p class="text-[11px] text-slate-500">Direct from trusted brand distributors</p>
                </div>
            </div>

            {{-- Prop 4: Easy Payment --}}
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">Fast QR & Online Pay</h4>
                    <p class="text-[11px] text-slate-500">Instant DuitNow & Bank Transfer</p>
                </div>
            </div>

        </div>
    </section>

    {{-- 3. Curated Mart Categories Grid --}}
    @if(!isset($sections['categories']) || $sections['categories']->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-5 sm:mb-6 gap-2">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-sky-600"></span>
                        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Mart Aisles</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mt-0.5">
                        {{ $sections['categories']->title ?? 'Shop by Category' }}
                    </h2>
                </div>
                <a href="{{ route('category.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 hover:underline">
                    <span>View All ({{ $categories->count() }})</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($categories as $category)
                    <a 
                        href="{{ route('category.show', $category->slug) }}" 
                        class="group bg-white rounded-2xl p-4 border border-slate-200/90 hover:border-sky-500 hover:shadow-lg hover:shadow-sky-500/10 transition-all duration-300 text-center flex flex-col items-center justify-between"
                    >
                        {{-- Visual Icon / Thumbnail --}}
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden mb-3 bg-slate-50 border border-slate-100 flex items-center justify-center p-2 group-hover:scale-108 transition-transform duration-300">
                            {!! $category->renderIcon('w-full h-full object-contain rounded-xl') !!}
                        </div>
                        
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-sky-600 transition leading-snug line-clamp-2">
                                {{ $category->name }}
                            </h3>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 block">
                                {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 4. Featured Store Specials (Fresh, Clean, High-End Grocery Deals Showcase) --}}
    @if($promoProducts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-rose-50/80 via-orange-50/40 to-white border border-rose-200/80 p-5 sm:p-8 shadow-xs">
                
                {{-- Decorative background glow --}}
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-rose-200/30 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -top-20 w-64 h-64 bg-amber-200/30 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white shadow-xs">
                                🔥 Limited Store Deals
                            </span>
                            <span class="text-xs font-bold text-rose-700">Special Price Drops</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-slate-900 mt-1">
                            {{ $sections['promo']->title ?? 'Featured Store Specials' }}
                        </h2>
                        <p class="text-xs text-slate-600 mt-1 max-w-xl">
                            {{ $sections['promo']->subtitle ?? 'Exclusive discounts on popular groceries, drinks, and daily household essentials.' }}
                        </p>
                    </div>

                    <a 
                        href="{{ route('category.index') }}" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-700 font-bold text-xs border border-rose-200 shadow-2xs hover:shadow-xs transition self-start sm:self-center"
                    >
                        <span>See All Deals</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="relative z-10 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($promoProducts->take(4) as $product)
                        @include('storefront.partials.product_card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 5. Interactive Merchandise Showcase (Tabs) --}}
    <section 
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" 
        x-data="{
            currentTab: 'featured',
            switchTab(tab) { this.currentTab = tab; }
        }"
    >
        {{-- Showcase Header & Filter Tabs --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between pb-4 mb-6 border-b border-slate-200/80 gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Live Store Catalog</span>
                </div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mt-0.5">
                    Groceries, Drinks & Daily Essentials
                </h2>
                <p class="text-xs text-slate-500 mt-1">Fresh groceries, snacks, household essentials and drinks ready for fast delivery.</p>
            </div>

            {{-- Modern Switcher Tabs --}}
            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 self-start md:self-end text-xs font-bold">
                <button 
                    type="button" 
                    @click="switchTab('featured')" 
                    :class="currentTab === 'featured' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" 
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer"
                >
                    <span>⭐</span>
                    <span>Featured</span>
                </button>
                @if($newArrivalProducts->count() > 0)
                    <button 
                        type="button" 
                        @click="switchTab('new')" 
                        :class="currentTab === 'new' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" 
                        class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                        <span>✨</span>
                        <span>New Arrivals</span>
                    </button>
                @endif
                @if($bestSellers->count() > 0)
                    <button 
                        type="button" 
                        @click="switchTab('bestsellers')" 
                        :class="currentTab === 'bestsellers' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'" 
                        class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer"
                    >
                        <span>🏆</span>
                        <span>Best Sellers</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Tab 1: Featured Products --}}
        <div x-show="currentTab === 'featured'" x-cloak>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($featuredProducts as $product)
                    @include('storefront.partials.product_card', ['product' => $product])
                @endforeach
            </div>
        </div>

        {{-- Tab 2: New Arrivals --}}
        @if($newArrivalProducts->count() > 0)
            <div x-show="currentTab === 'new'" x-cloak>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($newArrivalProducts as $product)
                        @include('storefront.partials.product_card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tab 3: Best Sellers --}}
        @if($bestSellers->count() > 0)
            <div x-show="currentTab === 'bestsellers'" x-cloak>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($bestSellers as $product)
                        @include('storefront.partials.product_card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('category.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs sm:text-sm rounded-xl border border-slate-200 shadow-2xs hover:border-sky-300 transition-all">
                <span>View All Products & Prices</span>
                <span>&rarr;</span>
            </a>
        </div>
    </section>

    {{-- 6. Mart Departments Showcase --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-8">
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Everything for Your Home & Family</span>
            <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                Your Complete Neighbourhood Mart
            </h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                From fresh pantry staples to household cleaning goods, we deliver straight to your door.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            {{-- Department 1 --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:border-sky-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-xl mb-3">
                        🛒
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Daily Pantry Staples</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Cooking oils, premium rice, instant noodles, flour, and daily cooking essentials at everyday low prices.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('category.index') }}" class="text-[11px] font-bold text-sky-600 hover:underline">Fresh daily stock &rarr;</a>
                </div>
            </div>

            {{-- Department 2 --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:border-amber-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-xl mb-3">
                        🥤
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Chilled & Ambient Drinks</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Soft drinks, 100 Plus, Milo, UHT milk, mineral water, tea, and coffee sachets ready for your kitchen.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('category.index') }}" class="text-[11px] font-bold text-amber-600 hover:underline">Great family packs &rarr;</a>
                </div>
            </div>

            {{-- Department 3 --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:border-emerald-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-xl mb-3">
                        🧼
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Laundry & Home Cleaning</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Detergent liquids, fabric softeners, dishwashing refills, floor cleaners, and disinfectants.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('category.index') }}" class="text-[11px] font-bold text-emerald-600 hover:underline">Floor 0 & 1 Free delivery &rarr;</a>
                </div>
            </div>

            {{-- Department 4 --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:border-purple-400 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-xl mb-3">
                        🍪
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Biscuits & Family Snacks</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Crispy crackers, sandwich cookies, wafer rolls, and kid-favorite snacks for tea-time and celebrations.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('category.index') }}" class="text-[11px] font-bold text-purple-600 hover:underline">Snack favorites &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. Customer Inquiries & Orders Callout Banner --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-slate-900 via-slate-950 to-sky-950 border border-slate-800 p-6 sm:p-10 text-white shadow-xl">
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="max-w-2xl text-center lg:text-left space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-sky-500/20 text-sky-300 border border-sky-400/30">
                        💬 Customer Support Desk
                    </span>
                    <h3 class="text-xl sm:text-3xl font-black tracking-tight text-white">
                        Have Questions or Need Help with Your Order?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Need help finding an item, checking delivery areas, or asking about stock? Contact our friendly mart team anytime.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                    @php
                        $adminPhone = setting('admin_phone', setting('store_phone', '+60128899900'));
                        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
                    @endphp
                    @if($cleanPhone)
                        <a 
                            href="https://wa.me/{{ $cleanPhone }}?text=Hello%20Store%20Team,%20I%20have%20an%20inquiry%20regarding%20products%20and%20orders." 
                            target="_blank" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-extrabold text-xs transition shadow-lg shadow-emerald-500/30 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>WhatsApp Customer Care</span>
                        </a>
                    @endif

                    <a 
                        href="{{ route('page.contact') }}" 
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs border border-slate-700 transition"
                    >
                        <span>Contact Information</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. How Ordering Works (Illustrated Process Timeline) --}}
    @if(!isset($sections['how_it_works']) || $sections['how_it_works']->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-2xs">
                
                <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                    <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Simple & Convenient</span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                        {{ $sections['how_it_works']->title ?? 'How Online Ordering Works' }}
                    </h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">
                        {{ $sections['how_it_works']->subtitle ?? 'From browsing your groceries straight to your door or floor in 4 simple steps.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                    @foreach($steps as $step)
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80 hover:border-sky-300 transition-colors relative flex flex-col justify-between">
                            <div>
                                <div class="w-9 h-9 rounded-xl bg-sky-600 text-white font-black text-xs flex items-center justify-center mb-3 shadow-xs">
                                    0{{ $step->step_number }}
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 mb-1.5">{{ $step->title }}</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $step->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- 9. Why Shop at N&J Mini Mart? (The Mart Advantage) --}}
    @if(!isset($sections['benefits']) || $sections['benefits']->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">The Mart Advantage</span>
                <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    {{ $sections['benefits']->title ?? ('Why Shop at ' . store_name() . '?') }}
                </h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    {{ $sections['benefits']->subtitle ?? 'Genuine products, reliable local delivery, and transparent pricing.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($benefits as $benefit)
                    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-sky-300 transition-all text-center flex flex-col items-center">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center mb-3.5">
                            @switch($benefit->icon)
                                @case('tag')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    @break
                                @case('truck')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    @break
                                @case('building')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    @break
                                @case('shield-check')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    @break
                                @default
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @endswitch
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1.5">{{ $benefit->title }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $benefit->description }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 10. Frequently Asked Questions (Clean Accordion) --}}
    @if(!isset($sections['faqs']) || $sections['faqs']->is_active)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Help & Clarifications</span>
                <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    {{ $sections['faqs']->title ?? 'Frequently Asked Questions' }}
                </h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    {{ $sections['faqs']->subtitle ?? 'Common questions about delivery, payments, and shopping at our store.' }}
                </p>
            </div>

            <div class="space-y-2.5" x-data="{ activeFaq: 0 }">
                @foreach($faqs as $index => $faq)
                    <div class="bg-white rounded-xl border border-slate-200/90 overflow-hidden shadow-2xs">
                        <button 
                            type="button" 
                            @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-4 sm:px-5 py-3.5 text-left flex justify-between items-center text-xs sm:text-sm font-bold text-slate-900 hover:text-sky-600 transition cursor-pointer"
                        >
                            <span>{{ $faq->question }}</span>
                            <svg 
                                class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2"
                                :class="activeFaq === {{ $index }} ? 'transform rotate-180 text-sky-600' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="activeFaq === {{ $index }}" 
                            x-collapse 
                            class="px-4 sm:px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3"
                        >
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('page.faqs') }}" class="text-xs font-bold text-sky-600 hover:underline">
                    View More Questions & Delivery Details &rarr;
                </a>
            </div>
        </section>
    @endif

</div>
@endsection
