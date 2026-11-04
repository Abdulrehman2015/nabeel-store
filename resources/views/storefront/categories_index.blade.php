@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

    {{-- Universal Hero Section --}}
    <x-storefront-hero 
        title="All Categories"
        description="Browse our complete catalog of food essentials, beverages, snacks, and cleaning supplies with fast local delivery."
        badge="Category Directory"
        subBadge="📦 Pallet & Case Supply"
        :breadcrumbs="['Categories' => null]"
    >
        <x-slot:icon>
            <svg class="w-10 h-10 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
        </x-slot:icon>
    </x-storefront-hero>

    {{-- Categories Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="group bg-white rounded-3xl p-6 border border-slate-200 hover:border-sky-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-20 h-20 rounded-2xl overflow-hidden mb-4 bg-slate-50 border border-slate-200/80 shadow-sm group-hover:shadow-md group-hover:border-sky-300 transition-all duration-300 flex items-center justify-center p-1.5 relative">
                        {!! $category->renderIcon('w-full h-full object-contain rounded-xl group-hover:scale-108 transition-transform duration-300') !!}
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
