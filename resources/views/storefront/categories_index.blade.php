@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- Header --}}
    <div class="text-center max-w-xl mx-auto space-y-2">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Wholesale Department Directory</span>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">All Wholesale Categories</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Browse our full carton inventory across food essentials, beverages, cooking oil, and cleaning supplies.
        </p>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="group bg-white rounded-3xl p-6 border border-slate-200 hover:border-sky-500 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 text-3xl flex items-center justify-center mb-4 group-hover:bg-sky-600 group-hover:text-white transition transform group-hover:scale-105">
                        @switch($category->icon)
                            @case('drink') 🥤 @break
                            @case('milk') 🥛 @break
                            @case('oil') 🛢️ @break
                            @case('noodle') 🍜 @break
                            @case('can') 🥫 @break
                            @case('snack') 🍪 @break
                            @case('coffee') ☕ @break
                            @case('laundry') 🧺 @break
                            @case('paper') 🧻 @break
                            @case('cleaning') 🧼 @break
                            @default 📦
                        @endswitch
                    </div>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-sky-600 transition">
                        {{ $category->name }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                        {{ $category->description }}
                    </p>
                </div>
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs mt-4">
                    <span class="font-bold text-slate-400">{{ $category->products_count }} {{ Str::plural('Carton', $category->products_count) }}</span>
                    <span class="text-sky-600 font-bold group-hover:translate-x-1 transition-transform">Browse &rarr;</span>
                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection
