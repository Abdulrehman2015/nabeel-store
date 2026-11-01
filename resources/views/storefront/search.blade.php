@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Search Results Header --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Wholesale Search</span>
            <h1 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Results for "<span class="text-sky-600">{{ $search }}</span>"
            </h1>
            <p class="text-xs text-slate-500 mt-1">Found {{ $products->total() }} matching carton products</p>
        </div>

        {{-- Sort Dropdown --}}
        <div class="flex items-center gap-2">
            <label for="sort" class="text-xs text-slate-500 font-semibold">Sort by:</label>
            <select 
                id="sort" 
                onchange="location.href = this.value" 
                class="text-xs py-1.5 pl-3 pr-8 border border-slate-300 rounded-xl focus:ring-sky-500 bg-slate-50"
            >
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>
    </div>

    {{-- Product Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                @include('storefront.partials.product_card', ['product' => $product])
            @endforeach
        </div>

        <div class="pt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-2xl mb-3">
                🔍
            </div>
            <h3 class="text-base font-bold text-slate-900">No matching wholesale cartons</h3>
            <p class="text-xs text-slate-500 mt-1">
                We couldn't find any products matching "{{ $search }}". Try searching for general terms like "Fanta", "Milo", or "Cooking Oil".
            </p>
            <div class="mt-4">
                <a href="{{ route('category.index') }}" class="inline-block px-4 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-700 transition">
                    Browse All Categories
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

