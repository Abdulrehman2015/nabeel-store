@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

    {{-- Breadcrumbs --}}
    <nav class="flex text-xs text-slate-500 font-medium" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2">
            <li><a href="{{ url('/') }}" class="hover:text-sky-600">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-sky-600">{{ $product->category->name }}</a></li>
            <li>/</li>
            <li class="text-slate-900 font-bold truncate max-w-xs" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    {{-- Product Primary Info Card --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12" x-data="{
        selectedImage: '{{ $product->image_url }}',
        orderQty: 1
    }">
        
        {{-- Product Image Gallery --}}
        <div class="space-y-4">
            <div class="aspect-square bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-center p-6 relative overflow-hidden">
                <img 
                    :src="selectedImage" 
                    alt="{{ $product->name }}" 
                    class="max-h-full max-w-full object-contain transition-all duration-300"
                >
                @if($product->is_on_sale)
                    <span class="absolute top-4 left-4 px-3 py-1 bg-rose-600 text-white text-xs font-black rounded-lg shadow-sm">
                        -{{ $product->discount_percent }}% OFF
                    </span>
                @endif
            </div>

            {{-- Thumbnails if multiple images --}}
            @if($product->images->count() > 0)
                <div class="flex gap-3 overflow-x-auto pb-1">
                    <button 
                        type="button" 
                        @click="selectedImage = '{{ $product->image_url }}'" 
                        class="w-16 h-16 rounded-xl border p-1 bg-slate-50 flex items-center justify-center flex-shrink-0"
                        :class="selectedImage === '{{ $product->image_url }}' ? 'border-sky-600 ring-2 ring-sky-200' : 'border-slate-200'"
                    >
                        <img src="{{ $product->image_url }}" class="max-h-full max-w-full object-contain">
                    </button>
                    @foreach($product->images as $img)
                        <button 
                            type="button" 
                            @click="selectedImage = '{{ $img->url }}'" 
                            class="w-16 h-16 rounded-xl border p-1 bg-slate-50 flex items-center justify-center flex-shrink-0"
                            :class="selectedImage === '{{ $img->url }}' ? 'border-sky-600 ring-2 ring-sky-200' : 'border-slate-200'"
                        >
                            <img src="{{ $img->url }}" class="max-h-full max-w-full object-contain">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Details & Actions --}}
        <div class="flex flex-col justify-between space-y-6">
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                    <a href="{{ route('category.show', $product->category->slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-50 hover:bg-sky-100 text-sky-800 font-bold border border-sky-200 transition shadow-xs">
                        <span class="w-4 h-4 inline-flex items-center justify-center text-sky-600">
                            {!! $product->category->renderIcon('w-3.5 h-3.5') !!}
                        </span>
                        <span>{{ $product->category->name }}</span>
                    </a>
                    @if($product->brand)
                        <span>Brand: <strong class="text-slate-800">{{ $product->brand->name }}</strong></span>
                    @endif
                    <span>SKU: <strong class="font-mono text-slate-800">{{ $product->sku }}</strong></span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                    {{ $product->name }}
                </h1>

                {{-- Carton Packaging Callout --}}
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-lg">
                        📦
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Wholesale Packaging</div>
                        <div class="text-sm font-extrabold text-slate-900">{{ $product->pack_size }}</div>
                        @if($product->unit_description)
                            <div class="text-xs text-slate-500">{{ $product->unit_description }}</div>
                        @endif
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="pt-2">
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-black text-slate-900">
                            {{ format_currency($product->current_price) }}
                        </span>
                        <span class="text-sm text-slate-500 font-semibold">/ carton</span>

                        @if($product->is_on_sale)
                            <span class="text-base text-slate-400 line-through">
                                {{ format_currency($product->price) }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Direct wholesale carton pricing. No hidden retail markups.</p>
                </div>

                {{-- Stock Status --}}
                <div class="pt-2">
                    @if($product->in_stock)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            In Stock ({{ $product->stock_quantity }} cartons available)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-800 border border-rose-200 rounded-full text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Temporarily Out of Stock
                        </span>
                    @endif
                </div>
            </div>

            {{-- Quantity & Add to Cart Block --}}
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                @if($product->in_stock)
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        {{-- Quantity selector --}}
                        <div class="flex items-center border border-slate-300 rounded-xl bg-white shadow-sm overflow-hidden w-full sm:w-auto">
                            <button 
                                type="button" 
                                @click="orderQty = Math.max(1, orderQty - 1)" 
                                class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 font-bold"
                            >-</button>
                            <input 
                                type="number" 
                                x-model.number="orderQty" 
                                min="1" 
                                max="{{ $product->stock_quantity }}"
                                class="w-16 py-2.5 text-center text-sm font-bold border-0 focus:ring-0 text-slate-900"
                            >
                            <button 
                                type="button" 
                                @click="orderQty = Math.min({{ $product->stock_quantity }}, orderQty + 1)" 
                                class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 font-bold"
                            >+</button>
                        </div>

                        {{-- Add Button --}}
                        <button 
                            type="button" 
                            @click="addToCart({{ $product->id }}, orderQty)" 
                            class="w-full sm:flex-1 py-3 px-6 bg-sky-600 hover:bg-sky-700 text-white font-black text-sm rounded-xl shadow-lg transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Add to Wholesale Cart</span>
                        </button>
                    </div>
                @else
                    <button disabled class="w-full py-3 bg-slate-200 text-slate-400 font-bold rounded-xl text-sm cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif

                {{-- Delivery perks badge --}}
                <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600 pt-2 border-t border-slate-200">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sky-600">✓</span>
                        <span>Free Delivery within {{ setting('free_delivery_radius', 5) }} km</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-sky-600">✓</span>
                        <span>Free delivery up to 1st floor</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Product Description & Carton Specs Tabs --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6" x-data="{ tab: 'description' }">
        <div class="flex border-b border-slate-200 gap-8">
            <button 
                type="button" 
                @click="tab = 'description'" 
                class="pb-3 text-sm font-bold border-b-2 transition"
                :class="tab === 'description' ? 'border-sky-600 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-900'"
            >
                Product Details
            </button>
            <button 
                type="button" 
                @click="tab = 'specs'" 
                class="pb-3 text-sm font-bold border-b-2 transition"
                :class="tab === 'specs' ? 'border-sky-600 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-900'"
            >
                Carton Specifications
            </button>
        </div>

        <div x-show="tab === 'description'" class="prose prose-sm max-w-none text-slate-700 leading-relaxed">
            {!! $product->description !!}
        </div>

        <div x-show="tab === 'specs'" style="display: none;" class="text-xs text-slate-700">
            <table class="w-full max-w-xl border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                <tr class="bg-slate-50">
                    <td class="p-3 font-bold text-slate-500 w-1/3">Packaging</td>
                    <td class="p-3 font-semibold text-slate-900">{{ $product->pack_size }}</td>
                </tr>
                <tr>
                    <td class="p-3 font-bold text-slate-500">Unit Breakdown</td>
                    <td class="p-3 text-slate-900">{{ $product->unit_description ?? 'Standard manufacturer carton' }}</td>
                </tr>
                <tr class="bg-slate-50">
                    <td class="p-3 font-bold text-slate-500">Gross Weight</td>
                    <td class="p-3 text-slate-900">{{ $product->weight_volume ?? 'Standard carton weight' }}</td>
                </tr>
                <tr>
                    <td class="p-3 font-bold text-slate-500">SKU / Barcode</td>
                    <td class="p-3 font-mono text-slate-900">{{ $product->sku }}</td>
                </tr>
                <tr class="bg-slate-50">
                    <td class="p-3 font-bold text-slate-500">Minimum Order</td>
                    <td class="p-3 text-slate-900">{{ format_currency(setting('min_order_value', 100)) }} storewide subtotal</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="space-y-6">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Related Wholesale Cartons
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    @include('storefront.partials.product_card', ['product' => $related])
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

