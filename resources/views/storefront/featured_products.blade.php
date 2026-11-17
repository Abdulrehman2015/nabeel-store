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
        <div class="flex flex-wrap items-center gap-3">
            {{-- Category Filter --}}
            <div class="flex items-center gap-2">
                <label for="category_filter" class="text-xs text-[#7E7E7E] font-bold font-['Quicksand']">Category:</label>
                <select 
                    id="category_filter" 
                    onchange="location.href = this.value"
                    class="text-xs py-2 pl-3 pr-8 border border-[#ececec] rounded-xl focus:ring-[#3BB77E] focus:border-[#3BB77E] bg-white font-bold text-[#253D4E] font-['Quicksand'] cursor-pointer shadow-xs"
                >
                    <option value="{{ route('products.featured') }}" {{ !request('category') ? 'selected' : '' }}>All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ route('products.featured', ['category' => $cat->slug, 'sort' => request('sort')]) }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort Dropdown --}}
            <div class="flex items-center gap-2">
                <label for="sort" class="text-xs text-[#7E7E7E] font-bold font-['Quicksand']">Sort by:</label>
                <select 
                    id="sort" 
                    onchange="location.href = this.value"
                    class="text-xs py-2 pl-3 pr-8 border border-[#ececec] rounded-xl focus:ring-[#3BB77E] focus:border-[#3BB77E] bg-white font-bold text-[#253D4E] font-['Quicksand'] cursor-pointer shadow-xs"
                >
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
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
            {{ $products->links() }}
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
