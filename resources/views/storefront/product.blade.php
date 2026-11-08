@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8 space-y-8 sm:space-y-12">

    {{-- 1. Modern Pill Breadcrumbs (Mobile Scrollable) --}}
    <nav class="flex items-center text-xs font-semibold text-slate-500 overflow-x-auto whitespace-nowrap pb-1 scrollbar-none" aria-label="Breadcrumb">
        <ol class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white rounded-full border border-slate-200/90 shadow-2xs">
            <li>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-sky-600 transition">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Home</span>
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li>
                <a href="{{ route('category.show', $product->category->slug) }}" class="text-slate-600 hover:text-sky-600 transition">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li class="text-slate-900 font-bold truncate max-w-[200px] sm:max-w-xs md:max-w-md" aria-current="page">
                {{ $product->name }}
            </li>
        </ol>
    </nav>

    {{-- 2. Main Product Studio & Purchasing Control Card --}}
    <div 
        class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-7 lg:p-10 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-start"
        x-data="{
            selectedImage: '{{ $product->image_url }}',
            orderQty: 1,
            addedNotice: false,
            handleAddToCart() {
                this.addToCart({{ $product->id }}, this.orderQty);
                this.addedNotice = true;
                setTimeout(() => this.addedNotice = false, 3000);
            }
        }"
    >

        {{-- Left Studio Column: Gallery & Images (Sticky on Desktop) --}}
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-28">
            
            {{-- Main Studio Packshot Display --}}
            <div class="relative w-full aspect-square bg-gradient-to-b from-slate-50/90 via-white to-slate-50/50 rounded-2xl sm:rounded-3xl border border-slate-200/90 flex items-center justify-center p-4 sm:p-8 overflow-hidden group shadow-2xs">
                
                {{-- Decorative background glow matching mart branding --}}
                <div class="absolute -top-16 -right-16 w-44 h-44 bg-lime-200/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-44 h-44 bg-sky-200/25 rounded-full blur-3xl pointer-events-none"></div>

                {{-- Badges --}}
                <div class="absolute top-3 left-3 sm:top-4 sm:left-4 flex flex-col gap-1.5 z-10">
                    @if($product->is_on_sale)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[10px] sm:text-[11px] font-black uppercase tracking-wider shadow-sm">
                            🔥 Save {{ $product->discount_percent }}% OFF
                        </span>
                    @endif
                    @if($product->is_new)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-600 text-white text-[10px] sm:text-[11px] font-black uppercase tracking-wider shadow-sm">
                            ✨ New Arrival
                        </span>
                    @endif
                </div>

                {{-- Authenticity Guaranteed Floating Chip --}}
                <div class="absolute top-3 right-3 sm:top-4 sm:right-4 z-10">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-900/85 backdrop-blur-md text-lime-400 border border-slate-700/80 text-[10px] font-extrabold tracking-wide shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime-400 animate-pulse"></span>
                        {{ store_name() }} Verified
                    </span>
                </div>

                {{-- Main Image with Smooth Zoom --}}
                <img 
                    :src="selectedImage" 
                    alt="{{ $product->name }}"
                    class="max-h-[300px] sm:max-h-[380px] lg:max-h-[420px] max-w-full object-contain transition-transform duration-500 group-hover:scale-106 drop-shadow-md select-none"
                    loading="eager"
                >

                {{-- Sold Out Overlay --}}
                @if(!$product->in_stock)
                    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-xs flex items-center justify-center z-20">
                        <span class="px-4 py-2 bg-white text-slate-950 text-xs font-black rounded-full uppercase tracking-wider shadow-xl">
                            Temporarily Sold Out
                        </span>
                    </div>
                @endif
            </div>

            {{-- Image Thumbnails (if multiple images) --}}
            @if($product->images->count() > 0)
                <div class="flex items-center gap-2.5 overflow-x-auto pb-1 pt-0.5 scrollbar-none">
                    <button 
                        type="button" 
                        @click="selectedImage = '{{ $product->image_url }}'"
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl border-2 p-1.5 bg-slate-50 flex items-center justify-center shrink-0 transition-all cursor-pointer overflow-hidden shadow-2xs"
                        :class="selectedImage === '{{ $product->image_url }}' ? 'border-sky-500 ring-2 ring-sky-300/40 bg-white' : 'border-slate-200 hover:border-slate-300'"
                    >
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                    </button>
                    @foreach($product->images as $img)
                        <button 
                            type="button" 
                            @click="selectedImage = '{{ $img->url }}'"
                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl border-2 p-1.5 bg-slate-50 flex items-center justify-center shrink-0 transition-all cursor-pointer overflow-hidden shadow-2xs"
                            :class="selectedImage === '{{ $img->url }}' ? 'border-sky-500 ring-2 ring-sky-300/40 bg-white' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <img src="{{ $img->url }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Quick Trust Strip (Desktop/Tablet) --}}
            <div class="grid grid-cols-3 gap-2 pt-1 text-center text-[10px] sm:text-[11px] text-slate-600 font-semibold">
                <div class="p-2 sm:p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-0.5">
                    <span class="text-sm sm:text-base">🚚</span>
                    <span class="leading-tight text-slate-800">Free Within 2km</span>
                </div>
                <div class="p-2 sm:p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-0.5">
                    <span class="text-sm sm:text-base">🏢</span>
                    <span class="leading-tight text-slate-800">Floor 0 & 1 Free</span>
                </div>
                <div class="p-2 sm:p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-0.5">
                    <span class="text-sm sm:text-base">🛡️</span>
                    <span class="leading-tight text-slate-800">100% Authentic</span>
                </div>
            </div>

        </div>

        {{-- Right Column: Information, Pricing & Action Center --}}
        <div class="lg:col-span-7 space-y-5 sm:space-y-6">
            
            {{-- Meta Tags Row --}}
            <div class="flex flex-wrap items-center gap-2 text-xs">
                
                {{-- Category Pill --}}
                <a 
                    href="{{ route('category.show', $product->category->slug) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold border border-sky-200/80 transition text-[11px] sm:text-xs"
                >
                    <span class="w-3.5 h-3.5 inline-flex items-center justify-center">
                        {!! $product->category->renderIcon('w-full h-full object-contain') !!}
                    </span>
                    <span>{{ $product->category->name }}</span>
                </a>

                {{-- Brand Pill --}}
                @if($product->brand)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-bold border border-slate-200 text-[11px] sm:text-xs">
                        <span class="text-slate-400 font-normal">Brand:</span>
                        <span class="text-slate-900">{{ $product->brand->name }}</span>
                        <svg class="w-3.5 h-3.5 text-sky-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </span>
                @endif

                {{-- SKU Pill --}}
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 text-slate-500 font-mono text-[10px] sm:text-[11px] border border-slate-200">
                    SKU: {{ $product->sku }}
                </span>

                {{-- Customer Trust Stars --}}
                <div class="ml-auto hidden sm:flex items-center gap-1 text-amber-500 text-xs font-bold bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200/60">
                    <span>★★★★★</span>
                    <span class="text-slate-700 text-[11px]">5.0 (Verified)</span>
                </div>

            </div>

            {{-- Product Title --}}
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight sm:leading-snug">
                {{ $product->name }}
            </h1>

            {{-- Pack Size & Unit Specs Card --}}
            @if($product->pack_size || $product->unit_description || $product->weight_volume)
                <div class="p-3.5 sm:p-4 bg-slate-50/90 rounded-2xl border border-slate-200/90 flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-lime-100 text-lime-800 border border-lime-200 flex items-center justify-center text-lg sm:text-xl shrink-0">
                        📦
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-black text-lime-800 uppercase tracking-wider bg-lime-200/60 px-2 py-0.5 rounded-md">
                                Packaging & Size
                            </span>
                            @if($product->weight_volume)
                                <span class="text-xs font-bold text-slate-600 font-mono">
                                    Weight: {{ $product->weight_volume }}
                                </span>
                            @endif
                        </div>
                        @if($product->pack_size)
                            <div class="text-xs sm:text-sm font-extrabold text-slate-900 mt-0.5 truncate">
                                {{ $product->pack_size }}
                            </div>
                        @endif
                        @if($product->unit_description)
                            <div class="text-[11px] sm:text-xs text-slate-500 truncate">
                                {{ $product->unit_description }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Clean, Airy Modern Price Card (Integrated with Store Scheme) --}}
            <div class="p-4 sm:p-5 bg-gradient-to-br from-slate-50 via-sky-50/30 to-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                <div class="flex flex-wrap items-baseline justify-between gap-3">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">
                            Customer Mart Price
                        </span>
                        <div class="flex items-baseline gap-2.5 sm:gap-3">
                            <span class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                                {{ format_currency($product->current_price) }}
                            </span>
                            @if($product->pack_size)
                                <span class="text-xs sm:text-sm text-slate-500 font-semibold">
                                    / {{ $product->pack_size }}
                                </span>
                            @endif

                            @if($product->is_on_sale)
                                <span class="text-sm sm:text-base text-slate-400 line-through font-medium">
                                    {{ format_currency($product->price) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Savings Pill --}}
                    @if($product->is_on_sale)
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-xs font-extrabold shadow-2xs">
                                <span>Save {{ format_currency($product->price - $product->sale_price) }}</span>
                                <span class="text-[10px] font-bold">({{ $product->discount_percent }}% OFF)</span>
                            </span>
                        </div>
                    @endif
                </div>

                <div class="pt-2.5 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-600">
                    <span class="flex items-center gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        All taxes included &bull; No hidden markups
                    </span>
                    <span class="font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-100">
                        Floor 0 & 1 Free Delivery
                    </span>
                </div>
            </div>

            {{-- Stock Status Indicator --}}
            <div class="flex items-center justify-between pt-0.5">
                @if($product->in_stock)
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200/80 rounded-full text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>In Stock ({{ $product->stock_quantity }} units available)</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-rose-50 text-rose-800 border border-rose-200 rounded-full text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Temporarily Out of Stock</span>
                    </div>
                @endif

                <span class="text-xs text-slate-500 hidden sm:inline">
                    ⚡ Fast delivery to your address
                </span>
            </div>

            {{-- ======================================================== --}}
            {{-- REFINED ACTION BUTTONS & QUANTITY SELECTOR (FULLY RESPONSIVE) --}}
            {{-- ======================================================== --}}
            <div class="p-4 sm:p-5 bg-slate-50/90 rounded-2xl sm:rounded-3xl border border-slate-200/90 space-y-3.5 shadow-2xs">
                
                @if($product->in_stock)
                    
                    {{-- Primary Row: Stepper + Add to Cart --}}
                    <div class="flex flex-row items-center gap-2.5 sm:gap-3 w-full">
                        
                        {{-- Quantity Stepper --}}
                        <div class="flex items-center border border-slate-300 rounded-xl sm:rounded-2xl bg-white shadow-2xs overflow-hidden shrink-0 h-12 sm:h-13 w-28 sm:w-36">
                            <button 
                                type="button" 
                                @click="orderQty = Math.max(1, orderQty - 1)"
                                class="w-8 sm:w-11 h-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-bold text-base sm:text-lg transition flex items-center justify-center cursor-pointer select-none active:bg-slate-200"
                                aria-label="Decrease quantity"
                            >
                                &minus;
                            </button>
                            <input 
                                type="number" 
                                x-model.number="orderQty" 
                                min="1" 
                                max="{{ $product->stock_quantity }}"
                                class="flex-1 w-full h-full text-center text-sm sm:text-base font-extrabold border-0 focus:ring-0 text-slate-900 p-0 select-all"
                            >
                            <button 
                                type="button" 
                                @click="orderQty = Math.min({{ $product->stock_quantity }}, orderQty + 1)"
                                class="w-8 sm:w-11 h-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-bold text-base sm:text-lg transition flex items-center justify-center cursor-pointer select-none active:bg-slate-200"
                                aria-label="Increase quantity"
                            >
                                &#43;
                            </button>
                        </div>

                        {{-- Primary Add to Cart Button --}}
                        <button 
                            type="button" 
                            @click="handleAddToCart()"
                            class="flex-1 h-12 sm:h-13 px-4 sm:px-6 rounded-xl sm:rounded-2xl bg-sky-600 hover:bg-sky-500 active:scale-[0.98] text-white font-extrabold text-sm sm:text-base shadow-lg shadow-sky-600/25 hover:shadow-sky-600/35 transition-all flex items-center justify-center gap-2 cursor-pointer border border-sky-400 group"
                        >
                            <svg class="w-5 h-5 text-white group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="tracking-wide">Add to Cart</span>
                        </button>

                    </div>

                    {{-- Secondary Action: WhatsApp Direct Ordering --}}
                    @php
                        $adminPhone = setting('admin_phone', setting('store_phone', '+60128899900'));
                        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
                        $waText = urlencode("Hello " . store_name() . ", I would like to order: " . $product->name . " (SKU: " . $product->sku . ") at " . format_currency($product->current_price));
                    @endphp
                    @if($cleanPhone)
                        <a 
                            href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" 
                            target="_blank"
                            class="w-full h-10 sm:h-11 px-4 rounded-xl sm:rounded-2xl bg-emerald-50 hover:bg-emerald-100 active:scale-[0.99] text-emerald-800 font-bold text-xs sm:text-sm border border-emerald-300 flex items-center justify-center gap-2 transition cursor-pointer shadow-2xs"
                        >
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Order via WhatsApp (Instant Mart Support)</span>
                        </a>
                    @endif

                    {{-- Feedback Toast on Add --}}
                    <div 
                        x-show="addedNotice" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        x-cloak 
                        class="p-3 bg-emerald-600 text-white text-xs font-bold rounded-xl flex items-center justify-between shadow-md"
                    >
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">✓</span>
                            <span>Item added to your shopping cart!</span>
                        </div>
                        <a href="{{ route('cart.index') }}" class="underline font-black hover:text-emerald-100 ml-2 shrink-0">
                            View Cart &rarr;
                        </a>
                    </div>
                @else
                    <button disabled class="w-full h-12 bg-slate-200 text-slate-400 font-bold rounded-xl text-sm cursor-not-allowed">
                        Temporarily Out of Stock
                    </button>
                @endif

                {{-- Delivery & Service Trust Guarantees --}}
                <div class="pt-3 border-t border-slate-200/90 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700 font-medium">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                        <span class="truncate">Free Delivery within {{ setting('free_delivery_radius', 2) }} km radius</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                        <span class="truncate">Floor 0 & 1 Free (Zero stair fees)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                        <span class="truncate">100% Original Brand Guaranteed</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                        <span class="truncate">Secure DuitNow QR & Instant Receipt</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- 3. Detailed Product Information, Specs & Policies (Responsive Segmented Tabs) --}}
    <div 
        class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-8 lg:p-10 shadow-sm space-y-6"
        x-data="{ tab: 'description' }"
    >
        {{-- Responsive Tabs Bar (Horizontally scrollable on mobile) --}}
        <div class="flex items-center gap-2 pb-4 border-b border-slate-200/90 overflow-x-auto whitespace-nowrap scrollbar-none">
            <button 
                type="button" 
                @click="tab = 'description'" 
                class="px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer flex items-center gap-2 shrink-0 select-none"
                :class="tab === 'description' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
            >
                <span>📝</span>
                <span>Product Overview & Features</span>
            </button>
            <button 
                type="button" 
                @click="tab = 'specs'" 
                class="px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer flex items-center gap-2 shrink-0 select-none"
                :class="tab === 'specs' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
            >
                <span>⚙️</span>
                <span>Specifications & Packaging</span>
            </button>
            <button 
                type="button" 
                @click="tab = 'delivery'" 
                class="px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer flex items-center gap-2 shrink-0 select-none"
                :class="tab === 'delivery' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
            >
                <span>🚚</span>
                <span>Delivery & Floor Policy</span>
            </button>
        </div>

        {{-- Tab 1: Product Description --}}
        <div x-show="tab === 'description'" x-cloak class="space-y-6">
            <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed">
                {!! $product->description !!}
            </div>

            {{-- Visual Features Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 pt-4 border-t border-slate-100">
                <div class="p-3.5 sm:p-4 rounded-2xl bg-lime-50/70 border border-lime-200/70 space-y-1">
                    <span class="text-xl">🍋</span>
                    <h4 class="text-xs font-bold text-slate-900">Real Lime Formula</h4>
                    <p class="text-[11px] text-slate-600 leading-snug">Cuts through heavy burnt oil and tough grease 100x faster.</p>
                </div>
                <div class="p-3.5 sm:p-4 rounded-2xl bg-sky-50/70 border border-sky-200/70 space-y-1">
                    <span class="text-xl">✨</span>
                    <h4 class="text-xs font-bold text-slate-900">Sparkling Shine</h4>
                    <p class="text-[11px] text-slate-600 leading-snug">Rinses away cleanly with zero chemical residue left on tableware.</p>
                </div>
                <div class="p-3.5 sm:p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/70 space-y-1">
                    <span class="text-xl">🛡️</span>
                    <h4 class="text-xs font-bold text-slate-900">Gentle on Hands</h4>
                    <p class="text-[11px] text-slate-600 leading-snug">Balanced pH formula ensures comfortable daily kitchen cleaning.</p>
                </div>
                <div class="p-3.5 sm:p-4 rounded-2xl bg-amber-50/70 border border-amber-200/70 space-y-1">
                    <span class="text-xl">🏆</span>
                    <h4 class="text-xs font-bold text-slate-900">{{ store_name() }} Quality Check</h4>
                    <p class="text-[11px] text-slate-600 leading-snug">Every item is verified for freshness, packaging integrity and shelf-life.</p>
                </div>
            </div>
        </div>

        {{-- Tab 2: Specifications Table --}}
        <div x-show="tab === 'specs'" x-cloak class="space-y-4">
            <div class="border border-slate-200 rounded-2xl overflow-x-auto shadow-2xs max-w-2xl">
                <table class="w-full text-xs text-left divide-y divide-slate-100 min-w-[320px]">
                    <tbody class="divide-y divide-slate-100">
                        <tr class="bg-slate-50/70">
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500 w-1/3">Product Title</td>
                            <td class="p-3 sm:p-3.5 font-extrabold text-slate-900">{{ $product->name }}</td>
                        </tr>
                        @if($product->brand)
                            <tr>
                                <td class="p-3 sm:p-3.5 font-bold text-slate-500">Brand</td>
                                <td class="p-3 sm:p-3.5 text-slate-900 font-semibold">{{ $product->brand->name }}</td>
                            </tr>
                        @endif
                        <tr class="bg-slate-50/70">
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500">Category</td>
                            <td class="p-3 sm:p-3.5 text-slate-900 font-semibold">{{ $product->category->name }}</td>
                        </tr>
                        <tr>
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500">Pack / Volume</td>
                            <td class="p-3 sm:p-3.5 text-slate-900 font-semibold">{{ $product->pack_size ?? 'Standard retail unit' }}</td>
                        </tr>
                        <tr class="bg-slate-50/70">
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500">Unit Breakdown</td>
                            <td class="p-3 sm:p-3.5 text-slate-900">{{ $product->unit_description ?? 'Standard retail bottle' }}</td>
                        </tr>
                        <tr>
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500">Weight / Volume</td>
                            <td class="p-3 sm:p-3.5 text-slate-900 font-mono">{{ $product->weight_volume ?? 'Standard unit' }}</td>
                        </tr>
                        <tr class="bg-slate-50/70">
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500">SKU / Item Code</td>
                            <td class="p-3 sm:p-3.5 font-mono text-slate-900">{{ $product->sku }}</td>
                        </tr>
                        <tr>
                            <td class="p-3 sm:p-3.5 font-bold text-slate-500">Stock Availability</td>
                            <td class="p-3 sm:p-3.5 text-emerald-700 font-bold">{{ $product->stock_quantity }} units in stock</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 3: Delivery & Floor Logistics Policy --}}
        <div x-show="tab === 'delivery'" x-cloak class="space-y-4 max-w-2xl text-xs text-slate-700">
            <div class="p-4 bg-sky-50/80 rounded-2xl border border-sky-200/80 space-y-1.5">
                <h4 class="font-bold text-sky-900 text-sm flex items-center gap-2">
                    <span>🚚</span>
                    <span>Local Radius Delivery Service</span>
                </h4>
                <p class="leading-relaxed">
                    We deliver directly from our local mart distribution hub. Deliveries within our 
                    <strong>{{ setting('free_delivery_radius', 2) }} km radius</strong> are completely 
                    <strong class="text-sky-900">FREE of delivery charges</strong>.
                </p>
            </div>

            <div class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200/80 space-y-1.5">
                <h4 class="font-bold text-amber-900 text-sm flex items-center gap-2">
                    <span>🏢</span>
                    <span>Doorstep & Floor Handling Policy</span>
                </h4>
                <p class="leading-relaxed">
                    Deliveries to Ground Floor (Floor 0) and 1st Floor (Floor 1) are 100% free of handling charges. 
                    If your building has an operational elevator/lift, our delivery staff will bring your goods up without extra fees.
                </p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1.5">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>💳</span>
                    <span>Instant DuitNow QR & Verification</span>
                </h4>
                <p class="leading-relaxed">
                    Checkout with DuitNow QR or online bank transfer. Our system confirms your transfer proof instantly to schedule prompt delivery dispatch.
                </p>
            </div>
        </div>

    </div>

    {{-- 4. Related Mart Products Shelf --}}
    @if($relatedProducts->count() > 0)
        <section class="space-y-5">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-lime-500"></span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Frequently Bought Together</span>
                    </div>
                    <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                        Related Products in {{ $product->category->name }}
                    </h2>
                </div>
                <a href="{{ route('category.show', $product->category->slug) }}" class="text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 hover:underline shrink-0">
                    <span>View More &rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                @foreach($relatedProducts as $related)
                    @include('storefront.partials.product_card', ['product' => $related])
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
