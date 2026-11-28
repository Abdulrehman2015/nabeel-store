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
                Categories
            </li>
        </ol>
    </nav>

    {{-- Header Banner (Matching Shop Page Style) --}}
    <div class="bg-gradient-to-r from-[#DEF9EC]/60 via-[#F2FCE4]/40 to-white rounded-3xl p-5 sm:p-8 border border-[#BCE3C9]/40 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#3BB77E] text-white font-['Quicksand']">
                    🗂️ Category Directory
                </span>
                <span class="text-xs text-[#7E7E7E] font-['Quicksand']">
                    {{ $categories->count() }} {{ Str::plural('Category', $categories->count()) }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                All Categories
            </h1>
            <p class="text-xs sm:text-sm text-[#7E7E7E] mt-1 font-['Lato']">
                Browse our complete catalog of food essentials, beverages, snacks, and cleaning supplies with fast local delivery.
            </p>
        </div>
    </div>

    {{-- Categories Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="group bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#ececec] hover:border-[#3BB77E] hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl overflow-hidden mb-3 sm:mb-4 bg-[#F2FCE4]/60 border border-[#BCE3C9]/40 shadow-2xs group-hover:shadow-md group-hover:border-[#3BB77E]/40 transition-all duration-300 flex items-center justify-center p-1.5 relative">
                        {!! $category->renderIcon('w-full h-full object-contain rounded-xl group-hover:scale-108 transition-transform duration-300') !!}
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-[#253D4E] group-hover:text-[#3BB77E] transition font-['Quicksand']">
                        {{ $category->name }}
                    </h3>
                    <p class="text-[11px] sm:text-xs text-[#7E7E7E] mt-1.5 leading-relaxed line-clamp-2 font-['Lato']">
                        {{ $category->description }}
                    </p>
                </div>
                <div class="pt-3 sm:pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] sm:text-xs mt-3 sm:mt-4 font-['Quicksand']">
                    <span class="font-bold text-slate-400">{{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}</span>
                    <span class="text-[#3BB77E] font-bold group-hover:translate-x-1 transition-transform">Browse &rarr;</span>
                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection
