@extends('layouts.storefront')

@section('content')
<div class="space-y-10 sm:space-y-14 pb-16 font-['Lato']">

    {{-- 1. Nest Theme Home-3 Split Hero Layout (Wide Container Matching Screenshot) --}}
    <section class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="flex flex-col lg:flex-row gap-6 items-stretch">
            
            {{-- Left Column: Category Sidebar (Matching Banner Height with Full Scrollable List) --}}
            @if($categories->count() > 0)
                <div class="w-full lg:w-[260px] xl:w-[280px] shrink-0 flex flex-col">
                    <div class="bg-white rounded-[15px] border border-[#ececec] p-4 sm:p-5 shadow-2xs h-full flex flex-col justify-between">
                        <div class="flex-1 flex flex-col min-h-0">
                            <div class="pb-2.5 mb-2 border-b border-[#ececec] flex items-center justify-between shrink-0">
                                <h2 class="text-lg font-bold text-[#253D4E] font-['Quicksand']">
                                    Category
                                </h2>
                                <span class="text-xs text-[#7E7E7E]">({{ $categories->count() }})</span>
                            </div>

                            {{-- Scrollable List Showing ALL Categories --}}
                            <div class="space-y-0.5 overflow-y-auto flex-1 pr-1.5 max-h-[440px] [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-[#BCE3C9] [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-transparent">
                                @foreach($categories as $cat)
                                    <a 
                                        href="{{ route('category.show', $cat->slug) }}" 
                                        class="flex items-center justify-between py-2 px-2 rounded-lg hover:bg-[#DEF9EC]/60 hover:text-[#3BB77E] transition group"
                                    >
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-5 h-5 rounded flex items-center justify-center shrink-0">
                                                {!! $cat->renderIcon('w-4 h-4 object-contain') !!}
                                            </div>
                                            <span class="text-xs sm:text-[13px] font-bold text-[#253D4E] group-hover:text-[#3BB77E] truncate font-['Quicksand']">
                                                {{ $cat->name }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] font-bold text-[#253D4E] bg-[#BCE3C9]/40 group-hover:bg-[#3BB77E] group-hover:text-white px-2 py-0.5 rounded-full transition font-['Quicksand'] shrink-0">
                                            {{ $cat->products_count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2.5 mt-2 border-t border-[#ececec] text-center shrink-0">
                            <a href="{{ route('category.index') }}" class="text-xs font-bold text-[#3BB77E] hover:underline font-['Quicksand'] flex items-center justify-center gap-1">
                                <span>View All Categories</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Right Column: Main Hero Mint Banner (Matching Screenshot Layout) --}}
            <div class="flex-1 min-w-0 w-full flex flex-col">
                @if($banners->count() > 0)
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
                        class="relative rounded-[25px] overflow-hidden bg-[#C5EAD9] min-h-[400px] sm:min-h-[470px] lg:min-h-[500px] h-full flex items-center select-none shadow-sm"
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
                                class="absolute inset-0 w-full h-full flex items-center"
                            >
                                {{-- Background Mint Design with Right Decorative Groceries Graphic --}}
                                <div class="absolute inset-0 bg-[#C5EAD9] overflow-hidden">
                                    {{-- Banner image if provided --}}
                                    @if($banner->desktop_image)
                                        <img 
                                            src="{{ $imageSrc }}" 
                                            onerror="this.onerror=null; this.src='{{ $publicFallback }}'" 
                                            alt="{{ $banner->title }}" 
                                            class="w-full h-full object-cover object-right-bottom mix-blend-multiply opacity-85"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-r from-[#C5EAD9] via-[#C5EAD9]/85 to-transparent"></div>
                                    @endif

                                    {{-- Decorative Right Fresh Groceries Graphic (Matching Screenshot) --}}
                                    <div class="absolute right-0 bottom-0 top-0 w-1/2 pointer-events-none hidden md:flex items-end justify-end p-6 pr-12 opacity-90">
                                        <div class="relative w-full h-full flex items-center justify-end">
                                            <div class="w-72 sm:w-96 lg:w-[460px] aspect-square rounded-full bg-white/20 blur-2xl absolute -right-10 -bottom-10"></div>
                                            @if(!$banner->desktop_image)
                                                <img 
                                                    src="{{ asset('images/categories/rice-noodles-grains.png') }}" 
                                                    alt="Fresh groceries" 
                                                    class="max-h-[85%] max-w-[85%] object-contain drop-shadow-xl"
                                                >
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Hero Content (Left-aligned as in the screenshot) --}}
                                <div class="relative z-10 p-6 sm:p-12 lg:p-16 max-w-xl">
                                    {{-- Badge / Tag --}}
                                    @if($banner->badge)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#DEF9EC] text-[#3BB77E] border border-[#bcebd3] font-['Quicksand'] mb-3">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#3BB77E]"></span>
                                            {{ $banner->badge }}
                                        </span>
                                    @endif

                                    {{-- Main Title (Quicksand Bold Big Font) --}}
                                    <h1 class="text-2xl sm:text-4xl lg:text-[46px] font-black text-[#253D4E] leading-[1.15] tracking-tight font-['Quicksand']">
                                        {{ $banner->title }}
                                    </h1>

                                    {{-- Subtitle (Lato Font) --}}
                                    @if($banner->subtitle)
                                        <p class="text-sm sm:text-base lg:text-lg text-[#7E7E7E] mt-3 mb-6 font-normal max-w-md leading-relaxed font-['Lato']">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @else
                                        <p class="text-sm sm:text-base text-[#7E7E7E] mt-3 mb-6 font-normal font-['Lato']">
                                            Save up to 50% off on your first grocery & household order.
                                        </p>
                                    @endif

                                    {{-- Pill Search / CTA Bar (Matching Screenshot) --}}
                                    <form action="{{ route('search') }}" method="GET" class="relative max-w-md">
                                        <div class="bg-white rounded-full p-1.5 pl-5 sm:pl-6 flex items-center shadow-md">
                                            <svg class="w-4 h-4 text-[#7E7E7E] mr-2 shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            <input 
                                                type="text" 
                                                name="q" 
                                                placeholder="Your email address or search products..." 
                                                class="w-full bg-transparent border-0 focus:ring-0 text-xs sm:text-sm text-[#253D4E] placeholder-[#7E7E7E] font-['Lato'] p-0"
                                            >
                                            <button 
                                                type="submit" 
                                                class="bg-[#3BB77E] hover:bg-[#29A56C] active:scale-95 text-white font-bold text-xs sm:text-sm px-6 sm:px-7 py-3 rounded-full transition-all font-['Quicksand'] shrink-0 cursor-pointer shadow-xs"
                                            >
                                                {{ $banner->button_text ?: 'Subscribe' }}
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        @endforeach

                        {{-- Carousel Dots (Matching Screenshot: two green dots) --}}
                        @if($banners->count() > 1)
                            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 sm:left-14 sm:translate-x-0 z-20 flex items-center gap-2">
                                @foreach($banners as $index => $banner)
                                    <button 
                                        type="button" 
                                        @click="activeSlide = {{ $index }}" 
                                        class="h-2.5 rounded-full transition-all cursor-pointer"
                                        :class="activeSlide === {{ $index }} ? 'w-6 bg-[#3BB77E]' : 'w-2.5 bg-white/80 hover:bg-white'"
                                        aria-label="Slide {{ $index + 1 }}"
                                    ></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Default Mint Welcome Banner if no banners exist --}}
                    <div class="rounded-[25px] overflow-hidden bg-[#C5EAD9] p-8 sm:p-14 min-h-[440px] flex items-center relative shadow-sm">
                        <div class="max-w-xl">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#DEF9EC] text-[#3BB77E] border border-[#bcebd3] font-['Quicksand'] mb-3">
                                Fresh & Fast
                            </span>
                            <h1 class="text-3xl sm:text-5xl font-black text-[#253D4E] leading-tight font-['Quicksand']">
                                Fresh Groceries Big discount
                            </h1>
                            <p class="text-sm sm:text-base text-[#7E7E7E] mt-3 mb-6 font-['Lato']">
                                Save up to 50% off on your first order. Free delivery within {{ setting('free_delivery_radius', 5) }} km radius!
                            </p>
                            <a 
                                href="{{ route('category.index') }}" 
                                class="inline-flex items-center gap-2 bg-[#3BB77E] hover:bg-[#29A56C] text-white font-bold text-sm px-8 py-3.5 rounded-full font-['Quicksand'] transition-all shadow-md"
                            >
                                <span>Browse Catalog</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>

    {{-- 2. Featured Products Section (Wide Container) --}}
    @if(isset($allProducts) && $allProducts->count() > 0)
        @php
            $categoriesWithProducts = $allProducts->pluck('category_id')->unique()->filter()->values()->all();
        @endphp
        <section 
            class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8" 
            x-data="{
                activeTab: 'all',
                filterCategory: null,
                selectedCategoryName: 'All Products',
                selectedCategorySlug: '',
                dropdownOpen: false,
                setTab(tab, catId = null, name = 'All Products', slug = '') {
                    this.activeTab = tab;
                    this.filterCategory = catId;
                    this.selectedCategoryName = name;
                    this.selectedCategorySlug = slug;
                    this.dropdownOpen = false;
                },
                getViewAllUrl() {
                    if (this.activeTab !== 'all' && this.selectedCategorySlug) {
                        return '/category/' + this.selectedCategorySlug;
                    }
                    return '{{ route('products.featured') }}';
                }
            }"
        >
            {{-- Header Row: Left title + Right category dropdown & View All button --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-6 border-b border-[#ececec] gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-[32px] font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                        Featured Products
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Category Dropdown (All Products selected by default) --}}
                    <div class="relative" @click.outside="dropdownOpen = false">
                        <button 
                            type="button" 
                            @click="dropdownOpen = !dropdownOpen" 
                            class="inline-flex items-center gap-2.5 px-4 py-2 rounded-xl border border-[#ececec] bg-white text-sm font-bold font-['Quicksand'] text-[#253D4E] hover:border-[#3BB77E] transition-all cursor-pointer shadow-xs min-w-[170px] justify-between"
                        >
                            <div class="flex items-center gap-2 truncate">
                                <span class="w-2 h-2 rounded-full bg-[#3BB77E]"></span>
                                <span class="text-[#253D4E] font-bold truncate" x-text="selectedCategoryName">All Products</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 text-[#7E7E7E] shrink-0" :class="dropdownOpen ? 'rotate-180 text-[#3BB77E]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div 
                            x-show="dropdownOpen" 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-64 max-h-72 overflow-y-auto rounded-xl bg-white shadow-xl ring-1 ring-black/10 z-50 p-1.5 focus:outline-none divide-y divide-gray-50"
                            style="display: none;"
                        >
                            {{-- All Products Option --}}
                            <button 
                                type="button"
                                @click="setTab('all', null, 'All Products', '')" 
                                :class="activeTab === 'all' ? 'bg-[#DEF9EC] text-[#3BB77E] font-bold' : 'text-[#253D4E] hover:bg-[#F2FCE4] hover:text-[#3BB77E]'" 
                                class="w-full text-left px-3.5 py-2.5 text-sm rounded-lg transition-colors flex items-center justify-between cursor-pointer font-['Quicksand']"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="activeTab === 'all' ? 'bg-[#3BB77E]' : 'bg-transparent'"></span>
                                    All Products
                                </span>
                                <span class="text-[11px] text-[#7E7E7E]">({{ $allProducts->count() > 10 ? 10 : $allProducts->count() }})</span>
                            </button>

                            {{-- Category Options --}}
                            <div class="pt-1 space-y-0.5">
                                @foreach($categories as $filterCat)
                                    <button 
                                        type="button"
                                        @click="setTab('cat_{{ $filterCat->id }}', {{ $filterCat->id }}, '{{ addslashes($filterCat->name) }}', '{{ $filterCat->slug }}')" 
                                        :class="activeTab === 'cat_{{ $filterCat->id }}' ? 'bg-[#DEF9EC] text-[#3BB77E] font-bold' : 'text-[#253D4E] hover:bg-[#F2FCE4] hover:text-[#3BB77E]'" 
                                        class="w-full text-left px-3.5 py-2 text-xs sm:text-sm rounded-lg transition-colors flex items-center justify-between cursor-pointer font-['Quicksand']"
                                    >
                                        <span class="truncate flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="activeTab === 'cat_{{ $filterCat->id }}' ? 'bg-[#3BB77E]' : 'bg-transparent'"></span>
                                            <span class="truncate">{{ $filterCat->name }}</span>
                                        </span>
                                        @if(isset($filterCat->products_count) && $filterCat->products_count > 0)
                                            <span class="text-[11px] text-[#7E7E7E] ml-2 font-normal">({{ $filterCat->products_count }})</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- View All Button in Header (Dynamically links to selected category or all) --}}
                    <a 
                        :href="getViewAllUrl()" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#DEF9EC]/60 hover:bg-[#3BB77E] text-[#3BB77E] hover:text-white border border-[#3BB77E]/30 text-xs sm:text-sm font-bold font-['Quicksand'] transition-all shadow-2xs whitespace-nowrap group"
                    >
                        <span x-text="selectedCategorySlug ? 'View All ' + selectedCategoryName : 'View All'">View All</span>
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Products Grid: 5 Columns Desktop Spanning Wide Screen (10 Products) --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                @php
                    $catCounts = [];
                @endphp
                @foreach($allProducts as $product)
                    @php 
                        $cid = $product->category_id;
                        $catCounts[$cid] = ($catCounts[$cid] ?? 0) + 1;
                        $isCategoryTen = ($catCounts[$cid] <= 10);
                        $isDefaultTen = ($loop->index < 10);
                    @endphp
                    <div 
                        x-show="(activeTab === 'all' && {{ $isDefaultTen ? 'true' : 'false' }}) || (activeTab !== 'all' && filterCategory === {{ $product->category_id }} && {{ $isCategoryTen ? 'true' : 'false' }})" 
                        x-transition
                    >
                        @include('storefront.partials.product_card', ['product' => $product])
                    </div>
                @endforeach

                {{-- Empty State if Category Has No Products in Current Batch --}}
                <div 
                    x-show="activeTab !== 'all' && !{{ json_encode($categoriesWithProducts) }}.includes(filterCategory)" 
                    class="col-span-full py-12 text-center"
                    style="display: none;"
                >
                    <p class="text-[#7E7E7E] text-sm font-['Quicksand'] font-semibold">No products found in this category.</p>
                </div>
            </div>

            {{-- Bottom View All Button (Dynamically links to selected category or all) --}}
            <div class="mt-8 text-center">
                <a 
                    :href="getViewAllUrl()" 
                    class="inline-flex items-center gap-2 px-8 py-3 rounded-full border-2 border-[#3BB77E] text-[#3BB77E] hover:bg-[#3BB77E] hover:text-white font-bold text-sm font-['Quicksand'] transition-all shadow-xs hover:shadow-md group"
                >
                    <span x-text="selectedCategorySlug ? 'View All ' + selectedCategoryName + ' Products' : 'View All Featured Products'">View All Products</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>
    @endif

    {{-- 3. Promo Deals Showcase (100% Dynamic from Admin Specials) --}}
    @if((!isset($sections['promo']) || $sections['promo']->is_active) && $promoProducts->count() > 0)
        <section class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 pb-2 border-b border-[#ececec] gap-2">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#fde8ef] text-[#f74b81] font-['Quicksand']">
                            Hot Deals
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-[28px] font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                        {{ $sections['promo']->title ?? 'Deals of the Day' }}
                    </h2>
                    @if(isset($sections['promo']->subtitle) && $sections['promo']->subtitle)
                        <p class="text-xs sm:text-sm text-[#7E7E7E] mt-0.5">{{ $sections['promo']->subtitle }}</p>
                    @endif
                </div>

                <a 
                    href="{{ route('category.index') }}" 
                    class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#3BB77E] hover:text-[#29A56C] font-['Quicksand']"
                >
                    <span>View All Deals</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                @foreach($promoProducts as $product)
                    @include('storefront.partials.product_card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- 4. Dynamic Store Benefits / Trust Badges (100% Dynamic from Admin Benefit Model) --}}
    @if((!isset($sections['benefits']) || $sections['benefits']->is_active) && $benefits->count() > 0)
        @php
            $benefitColors = [
                ['bg' => 'bg-[#F2FCE4]', 'icon_bg' => 'bg-[#DEF9EC]', 'icon_text' => 'text-[#3BB77E]'],
                ['bg' => 'bg-[#FFFCEB]', 'icon_bg' => 'bg-[#FFF3EB]', 'icon_text' => 'text-[#F59758]'],
                ['bg' => 'bg-[#ECFFEC]', 'icon_bg' => 'bg-[#DEF9EC]', 'icon_text' => 'text-[#29A56C]'],
                ['bg' => 'bg-[#FEEFEA]', 'icon_bg' => 'bg-[#fde8ef]', 'icon_text' => 'text-[#f74b81]'],
                ['bg' => 'bg-[#FFF3FF]', 'icon_bg' => 'bg-[#f6d7f6]', 'icon_text' => 'text-[#9c599c]'],
            ];
        @endphp
        <section class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                @foreach($benefits as $index => $benefit)
                    @php
                        $preset = $benefitColors[$index % count($benefitColors)];
                    @endphp
                    <div class="bg-[#F4F6FA] hover:bg-white rounded-[15px] p-5 border border-[#ececec] hover:border-[#BCE3C9] hover:shadow-[0_8px_20px_rgba(0,0,0,0.06)] transition-all flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl {{ $preset['icon_bg'] }} {{ $preset['icon_text'] }} flex items-center justify-center shrink-0">
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
                        <div>
                            <h4 class="text-sm font-bold text-[#253D4E] font-['Quicksand'] leading-snug">{{ $benefit->title }}</h4>
                            <p class="text-xs text-[#7E7E7E] mt-0.5 leading-relaxed">{{ $benefit->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 5. How Online Ordering Works (100% Dynamic from Admin HowItWorksStep Model) --}}
    @if((!isset($sections['how_it_works']) || $sections['how_it_works']->is_active) && $steps->count() > 0)
        <section class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[20px] p-6 sm:p-10 border border-[#ececec] shadow-2xs">
                
                <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                    <span class="text-xs font-bold text-[#3BB77E] uppercase tracking-wider font-['Quicksand']">Easy Process</span>
                    <h2 class="text-xl sm:text-3xl font-bold text-[#253D4E] tracking-tight mt-1 font-['Quicksand']">
                        {{ $sections['how_it_works']->title ?? 'How Online Ordering Works' }}
                    </h2>
                    @if(isset($sections['how_it_works']->subtitle) && $sections['how_it_works']->subtitle)
                        <p class="text-[#7E7E7E] text-xs sm:text-sm mt-1">
                            {{ $sections['how_it_works']->subtitle }}
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                    @foreach($steps as $step)
                        <div class="bg-[#F4F6FA] hover:bg-white rounded-2xl p-5 border border-[#ececec] hover:border-[#3BB77E] hover:shadow-md transition-all relative flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-xl bg-[#DEF9EC] text-[#3BB77E] font-bold text-sm flex items-center justify-center mb-3 font-['Quicksand']">
                                    0{{ $step->step_number }}
                                </div>
                                <h3 class="text-sm font-bold text-[#253D4E] mb-1.5 font-['Quicksand']">{{ $step->title }}</h3>
                                <p class="text-xs text-[#7E7E7E] leading-relaxed">{{ $step->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- 6. Frequently Asked Questions (100% Dynamic from Admin Faq Model) --}}
    @if((!isset($sections['faqs']) || $sections['faqs']->is_active) && $faqs->count() > 0)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <span class="text-xs font-bold text-[#3BB77E] uppercase tracking-wider font-['Quicksand']">Help & FAQ</span>
                <h2 class="text-xl sm:text-3xl font-bold text-[#253D4E] tracking-tight mt-1 font-['Quicksand']">
                    {{ $sections['faqs']->title ?? 'Frequently Asked Questions' }}
                </h2>
                @if(isset($sections['faqs']->subtitle) && $sections['faqs']->subtitle)
                    <p class="text-[#7E7E7E] text-xs sm:text-sm mt-1">
                        {{ $sections['faqs']->subtitle }}
                    </p>
                @endif
            </div>

            <div class="space-y-3" x-data="{ activeFaq: 0 }">
                @foreach($faqs as $index => $faq)
                    <div class="bg-white rounded-2xl border border-[#ececec] overflow-hidden hover:border-[#BCE3C9] transition-colors shadow-2xs">
                        <button 
                            type="button" 
                            @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-5 py-4 text-left flex justify-between items-center text-xs sm:text-sm font-bold text-[#253D4E] hover:text-[#3BB77E] transition cursor-pointer font-['Quicksand']"
                        >
                            <span>{{ $faq->question }}</span>
                            <svg 
                                class="w-4 h-4 text-[#7E7E7E] transition-transform duration-200 shrink-0 ml-2"
                                :class="activeFaq === {{ $index }} ? 'transform rotate-180 text-[#3BB77E]' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="activeFaq === {{ $index }}" 
                            x-collapse 
                            class="px-5 pb-4 text-xs text-[#7E7E7E] leading-relaxed border-t border-[#ececec] pt-3 font-['Lato']"
                        >
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('page.faqs') }}" class="text-xs font-bold text-[#3BB77E] hover:underline font-['Quicksand']">
                    View More Questions & Delivery Details &rarr;
                </a>
            </div>
        </section>
    @endif

</div>
@endsection
