@extends('layouts.storefront')

@section('content')
<div class="space-y-12 pb-16">

    {{-- Hero Banner Slider (Dynamic from Admin) --}}
    @if($banners->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div 
                x-data="{
                    activeSlide: 0,
                    totalSlides: {{ $banners->count() }},
                    interval: null,
                    start() {
                        this.interval = setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                        }, 5000);
                    },
                    stop() { clearInterval(this.interval); }
                }" 
                x-init="start()" 
                @mouseenter="stop()" 
                @mouseleave="start()"
                class="relative rounded-2xl overflow-hidden shadow-xl bg-slate-900 border border-slate-800 text-white min-h-[380px] sm:min-h-[460px] flex items-center"
            >
                @foreach($banners as $index => $banner)
                    <div 
                        x-show="activeSlide === {{ $index }}" 
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute inset-0 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12 z-10 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-transparent"
                    >
                        <div class="max-w-xl space-y-4">
                            @if($banner->badge)
                                <span class="inline-block px-3 py-1 bg-amber-500 text-slate-950 text-xs font-black rounded-full uppercase tracking-wider">
                                    {{ $banner->badge }}
                                </span>
                            @endif
                            <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight tracking-tight">
                                {{ $banner->title }}
                            </h1>
                            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                                {{ $banner->subtitle }}
                            </p>
                            @if($banner->link)
                                <div class="pt-2">
                                    <a href="{{ $banner->link }}" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white font-extrabold text-sm rounded-xl shadow-lg transition">
                                        <span>{{ $banner->button_text ?: 'Shop Now' }}</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Slider Nav Indicators --}}
                <div class="absolute bottom-4 left-0 right-0 z-20 flex justify-center gap-2">
                    @foreach($banners as $index => $banner)
                        <button 
                            type="button" 
                            @click="activeSlide = {{ $index }}" 
                            class="h-2 rounded-full transition-all"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-sky-500' : 'w-2 bg-white/40 hover:bg-white/70'"
                        ></button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Value Props Bar --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 p-5 bg-white rounded-2xl border border-slate-200 shadow-sm divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            <div class="flex items-center gap-3.5 p-2">
                <div class="w-11 h-11 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-xl flex-shrink-0">
                    🚚
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900">Free Local Delivery</h4>
                    <p class="text-[11px] sm:text-xs text-slate-500">Within {{ setting('free_delivery_radius', 5) }} km radius</p>
                </div>
            </div>
            <div class="flex items-center gap-3.5 p-2">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl flex-shrink-0">
                    🏢
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900">Free Floor 0 & 1</h4>
                    <p class="text-[11px] sm:text-xs text-slate-500">Up to Floor {{ setting('free_floor_limit', 1) }} completely free</p>
                </div>
            </div>
            <div class="flex items-center gap-3.5 p-2">
                <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl flex-shrink-0">
                    📦
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900">Factory Carton Prices</h4>
                    <p class="text-[11px] sm:text-xs text-slate-500">Unopened bulk case wholesale</p>
                </div>
            </div>
            <div class="flex items-center gap-3.5 p-2">
                <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl flex-shrink-0">
                    🛡️
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900">Secure QR Advance</h4>
                    <p class="text-[11px] sm:text-xs text-slate-500">10% deposit, balance on delivery</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Category Grid Section --}}
    @if(!isset($sections['categories']) || $sections['categories']->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Wholesale Catalog</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $sections['categories']->title ?? 'Shop Wholesale by Category' }}
                    </h2>
                </div>
                <a href="{{ route('category.index') }}" class="text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                    <span>View All ({{ $categories->count() }})</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="group bg-white rounded-2xl p-4 border border-slate-200 hover:border-sky-500 hover:shadow-md transition text-center flex flex-col items-center">
                        <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl group-hover:bg-sky-600 group-hover:text-white transition transform group-hover:scale-105 mb-3">
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
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-sky-600 transition leading-snug line-clamp-2">
                            {{ $category->name }}
                        </h3>
                        <span class="text-[11px] text-slate-400 mt-1 font-medium">
                            {{ $category->products_count }} {{ Str::plural('Carton', $category->products_count) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Hot Promotions / Bulk Deals --}}
    @if((!isset($sections['promo']) || $sections['promo']->is_active) && $promoProducts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 uppercase tracking-wider bg-amber-100 px-2.5 py-0.5 rounded-full mb-1">
                        🔥 SPECIAL OFFERS
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $sections['promo']->title ?? 'Hot Promotions & Carton Deals' }}
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($promoProducts as $product)
                    @include('storefront.partials.product_card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- New Bulk Arrivals --}}
    @if((!isset($sections['new_arrivals']) || $sections['new_arrivals']->is_active) && $newArrivalProducts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Fresh In Warehouse</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $sections['new_arrivals']->title ?? 'New Bulk Arrivals' }}
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($newArrivalProducts as $product)
                    @include('storefront.partials.product_card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured Wholesale Staples --}}
    @if((!isset($sections['featured']) || $sections['featured']->is_active) && $featuredProducts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Top Commercial Staples</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $sections['featured']->title ?? 'Featured Wholesale Staples' }}
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    @include('storefront.partials.product_card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- How It Works Section (Editable from Admin) --}}
    @if(!isset($sections['how_it_works']) || $sections['how_it_works']->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 rounded-3xl p-8 sm:p-12 text-white border border-slate-800 shadow-2xl">
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Simple Logistics</span>
                    <h2 class="text-2xl sm:text-4xl font-black tracking-tight mt-1">
                        {{ $sections['how_it_works']->title ?? 'How Ordering Works' }}
                    </h2>
                    <p class="text-slate-400 text-sm mt-2">
                        {{ $sections['how_it_works']->subtitle ?? 'Streamlined wholesale carton ordering with guaranteed door-to-door delivery.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($steps as $step)
                        <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-6 border border-slate-700/60 relative flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-500/30 font-black text-sm flex items-center justify-center mb-4">
                                    0{{ $step->step_number }}
                                </div>
                                <h3 class="text-base font-bold text-white mb-2">{{ $step->title }}</h3>
                                <p class="text-xs text-slate-300 leading-relaxed">{{ $step->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Why Choose Us (Benefits - Editable from Admin) --}}
    @if(!isset($sections['benefits']) || $sections['benefits']->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">The Wholesale Advantage</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    {{ $sections['benefits']->title ?? 'Why Choose 99 Bulk Sales?' }}
                </h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Direct factory cases, reliable logistics, and full price transparency.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($benefits as $benefit)
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 text-2xl flex items-center justify-center mx-auto mb-4">
                            @switch($benefit->icon)
                                @case('tag') 🏷️ @break
                                @case('truck') 🚚 @break
                                @case('building') 🏢 @break
                                @case('shield-check') 🛡️ @break
                                @default ⭐
                            @endswitch
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">{{ $benefit->title }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $benefit->description }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- FAQ Accordion (Editable from Admin) --}}
    @if(!isset($sections['faqs']) || $sections['faqs']->is_active)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Got Questions?</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    {{ $sections['faqs']->title ?? 'Frequently Asked Questions' }}
                </h2>
            </div>

            <div class="space-y-3" x-data="{ activeFaq: null }">
                @foreach($faqs as $index => $faq)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <button 
                            type="button" 
                            @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-5 py-4 text-left flex justify-between items-center text-sm font-bold text-slate-900 hover:text-sky-600 transition"
                        >
                            <span>{{ $faq->question }}</span>
                            <svg 
                                class="w-5 h-5 text-slate-400 transition-transform duration-200"
                                :class="activeFaq === {{ $index }} ? 'transform rotate-180 text-sky-600' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="activeFaq === {{ $index }}" 
                            x-collapse 
                            class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3"
                        >
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('page.faqs') }}" class="text-xs font-bold text-sky-600 hover:underline">
                    View More FAQs &rarr;
                </a>
            </div>
        </section>
    @endif

</div>
@endsection
