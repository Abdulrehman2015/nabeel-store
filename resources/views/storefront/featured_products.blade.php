@extends('layouts.storefront')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

    {{-- Breadcrumbs Navigation --}}
    <nav class="flex text-xs text-slate-500 font-medium overflow-x-auto no-scrollbar py-1" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 flex-nowrap font-['Quicksand'] font-semibold">
            <li>
                <a href="{{ url('/') }}" class="hover:text-[#3BB77E] transition flex items-center gap-1 text-[#7E7E7E]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Home</span>
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li class="text-[#253D4E] font-bold" aria-current="page">
                Featured Products
            </li>
        </ol>
    </nav>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-[#DEF9EC]/60 via-[#F2FCE4]/40 to-white rounded-3xl p-6 sm:p-8 border border-[#BCE3C9]/40 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#3BB77E] text-white font-['Quicksand']">
                    ★ Handpicked Collection
                </span>
                <span class="text-xs text-[#7E7E7E] font-['Quicksand']">
                    Showing {{ $products->total() }} Products
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                Featured Products
            </h1>
            <p class="text-xs sm:text-sm text-[#7E7E7E] mt-1 font-['Lato']">
                Explore our full catalog of hand-picked featured groceries, beverages, snacks, and daily household essentials.
            </p>
        </div>

        {{-- Filters & Sort --}}
        <div class="flex flex-wrap items-center gap-3" x-data="{ catOpen: false, sortOpen: false }">
            
            {{-- Category Filter Dropdown --}}
            <div class="relative" @click.outside="catOpen = false">
                <button 
                    type="button" 
                    @click="catOpen = !catOpen; sortOpen = false"
                    class="flex items-center gap-2.5 px-4 py-2.5 bg-white border border-[#ececec] hover:border-[#3BB77E] rounded-2xl text-xs font-bold text-[#253D4E] font-['Quicksand'] shadow-2xs hover:shadow-sm transition-all cursor-pointer group"
                >
                    <div class="w-5 h-5 rounded-md bg-[#DEF9EC] text-[#3BB77E] flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                    <span class="text-[#7E7E7E] font-medium">Category:</span>
                    <span class="font-extrabold text-[#253D4E] max-w-[150px] truncate">
                        {{ $categories->firstWhere('slug', request('category'))?->name ?? 'All Categories' }}
                    </span>
                    <svg class="w-3.5 h-3.5 text-[#7E7E7E] group-hover:text-[#3BB77E] transition-transform duration-200 shrink-0" :class="catOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                {{-- Dropdown Menu --}}
                <div 
                    x-show="catOpen" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    class="absolute right-0 sm:left-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-xl border border-[#ececec] p-2 z-50 max-h-72 overflow-y-auto divide-y divide-slate-50"
                    style="display: none;"
                >
                    <a 
                        href="{{ route('products.featured', ['sort' => request('sort')]) }}" 
                        class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ !request('category') ? 'bg-[#DEF9EC] text-[#3BB77E]' : 'text-[#253D4E] hover:bg-slate-50' }}"
                    >
                        <span>All Categories</span>
                        @if(!request('category'))
                            <svg class="w-4 h-4 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </a>
                    @foreach($categories as $cat)
                        <a 
                            href="{{ route('products.featured', ['category' => $cat->slug, 'sort' => request('sort')]) }}" 
                            class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === $cat->slug ? 'bg-[#DEF9EC] text-[#3BB77E] font-bold' : 'text-[#253D4E] hover:bg-slate-50' }}"
                        >
                            <span class="truncate">{{ $cat->name }}</span>
                            @if(request('category') === $cat->slug)
                                <svg class="w-4 h-4 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Sort By Dropdown --}}
            <div class="relative" @click.outside="sortOpen = false">
                <button 
                    type="button" 
                    @click="sortOpen = !sortOpen; catOpen = false"
                    class="flex items-center gap-2.5 px-4 py-2.5 bg-white border border-[#ececec] hover:border-[#3BB77E] rounded-2xl text-xs font-bold text-[#253D4E] font-['Quicksand'] shadow-2xs hover:shadow-sm transition-all cursor-pointer group"
                >
                    <div class="w-5 h-5 rounded-md bg-[#F2FCE4] text-[#3BB77E] flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                    </div>
                    <span class="text-[#7E7E7E] font-medium">Sort by:</span>
                    <span class="font-extrabold text-[#253D4E]">
                        @if($sort === 'price_asc') Price: Low to High
                        @elseif($sort === 'price_desc') Price: High to Low
                        @elseif($sort === 'name_asc') Name: A to Z
                        @else Newest
                        @endif
                    </span>
                    <svg class="w-3.5 h-3.5 text-[#7E7E7E] group-hover:text-[#3BB77E] transition-transform duration-200 shrink-0" :class="sortOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                {{-- Dropdown Menu --}}
                <div 
                    x-show="sortOpen" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-[#ececec] p-2 z-50 divide-y divide-slate-50"
                    style="display: none;"
                >
                    @php
                        $sortOptions = [
                            'newest' => 'Newest',
                            'price_asc' => 'Price: Low to High',
                            'price_desc' => 'Price: High to Low',
                            'name_asc' => 'Name: A to Z',
                        ];
                    @endphp
                    @foreach($sortOptions as $key => $label)
                        <a 
                            href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}" 
                            class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ $sort === $key ? 'bg-[#DEF9EC] text-[#3BB77E] font-bold' : 'text-[#253D4E] hover:bg-slate-50' }}"
                        >
                            <span>{{ $label }}</span>
                            @if($sort === $key)
                                <svg class="w-4 h-4 text-[#3BB77E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- Product Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            @foreach($products as $product)
                @include('storefront.partials.product_card', ['product' => $product])
            @endforeach
        </div>

        <div class="pt-8">
            {{ $products->links('vendor.pagination.storefront') }}
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-[#ececec] max-w-lg mx-auto shadow-xs">
            <div class="w-16 h-16 bg-[#DEF9EC] rounded-full flex items-center justify-center mx-auto text-2xl mb-3 text-[#3BB77E]">
                🛍️
            </div>
            <h3 class="text-base font-bold text-[#253D4E] font-['Quicksand']">No featured products found</h3>
            <p class="text-xs text-[#7E7E7E] mt-1 font-['Lato']">
                Try selecting a different category or browse all available products in our store.
            </p>
            <div class="mt-4">
                <a href="{{ route('category.index') }}"
                    class="inline-block px-5 py-2.5 bg-[#3BB77E] text-white rounded-xl text-xs font-bold hover:bg-[#29A56C] transition font-['Quicksand']">
                    Browse All Categories
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
