@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Breadcrumbs --}}
    <nav class="flex text-xs text-slate-500 font-medium" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2">
            <li><a href="{{ url('/') }}" class="hover:text-sky-600">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('category.index') }}" class="hover:text-sky-600">Categories</a></li>
            <li>/</li>
            <li class="text-slate-900 font-bold" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Category Header Banner --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-md">
        <div class="max-w-2xl">
            <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Wholesale Category</span>
            <h1 class="text-2xl sm:text-4xl font-black mt-1">{{ $category->name }}</h1>
            <p class="text-slate-300 text-xs sm:text-sm mt-2 leading-relaxed">
                {{ $category->description ?? 'Browse our wholesale cartons and commercial pack sizes with door-to-door delivery.' }}
            </p>
        </div>
    </div>

    {{-- Content Layout: Sidebar Filters + Products Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- Left Sidebar: Filters --}}
        <aside class="space-y-6">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Filters</h3>
                    @if(request()->hasAny(['brand', 'min_price', 'max_price', 'in_stock']))
                        <a href="{{ route('category.show', $category->slug) }}" class="text-xs text-rose-600 hover:underline font-semibold">
                            Reset All
                        </a>
                    @endif
                </div>

                <form action="{{ route('category.show', $category->slug) }}" method="GET" class="space-y-6">
                    <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                    {{-- In Stock Only Toggle --}}
                    <div>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="in_stock" 
                                value="1" 
                                {{ request('in_stock') ? 'checked' : '' }} 
                                onchange="this.form.submit()" 
                                class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4"
                            >
                            <span class="text-xs font-bold text-slate-800">In-Stock Only</span>
                        </label>
                    </div>

                    {{-- Brands Filter --}}
                    @if($availableBrands->count() > 0)
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2.5">Brand</h4>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @foreach($availableBrands as $brand)
                                    <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer hover:text-sky-600 py-1">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="radio" 
                                                name="brand" 
                                                value="{{ $brand->slug }}" 
                                                {{ request('brand') === $brand->slug ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="text-sky-600 focus:ring-sky-500 w-3.5 h-3.5"
                                            >
                                            <span>{{ $brand->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Price Range Filter --}}
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2.5">Price Range (RM)</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Min</label>
                                <input 
                                    type="number" 
                                    name="min_price" 
                                    value="{{ request('min_price') }}" 
                                    placeholder="0" 
                                    class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                >
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Max</label>
                                <input 
                                    type="number" 
                                    name="max_price" 
                                    value="{{ request('max_price') }}" 
                                    placeholder="{{ round($maxPrice) }}" 
                                    class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg"
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-lg transition">
                            Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Right Side: Products Grid --}}
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Top Sort Bar --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <span class="font-bold text-slate-900">{{ $products->total() }}</span> wholesale items in <strong class="text-slate-900">{{ $category->name }}</strong>
                </div>

                <div class="flex items-center gap-2">
                    <label for="sort" class="text-xs text-slate-500 font-semibold">Sort by:</label>
                    <select 
                        id="sort" 
                        onchange="location.href = this.value" 
                        class="text-xs py-1.5 pl-3 pr-8 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500 bg-slate-50"
                    >
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ $sort === 'popular' ? 'selected' : '' }}>Most In Stock</option>
                    </select>
                </div>
            </div>

            {{-- Products Grid --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('storefront.partials.product_card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-2xl mb-3">
                        🔍
                    </div>
                    <h3 class="text-base font-bold text-slate-900">No matching cartons found</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Try adjusting your filters or search criteria to discover wholesale products.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('category.show', $category->slug) }}" class="inline-block px-4 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-700 transition">
                            Clear Filters
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
