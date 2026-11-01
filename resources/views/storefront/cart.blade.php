@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Shopping Basket</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Wholesale Cart</h1>
        </div>
        @if(!empty($summary['items']))
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Empty all items from your cart?')" class="text-xs text-rose-600 hover:underline font-semibold">
                    Clear Entire Cart
                </button>
            </form>
        @endif
    </div>

    @if(empty($summary['items']))
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 max-w-lg mx-auto shadow-sm">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-3xl mb-4 text-slate-400">
                🛒
            </div>
            <h2 class="text-lg font-bold text-slate-900">Your cart is currently empty</h2>
            <p class="text-xs text-slate-500 mt-1">Explore our wholesale catalog to discover bulk beverages, cooking oil, rice and household goods.</p>
            <div class="mt-6">
                <a href="{{ route('category.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
                    <span>Browse All Categories</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    @else

        {{-- Minimum Order Value Progress Bar --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-slate-700">Minimum Order Progress:</span>
                <span class="font-bold text-slate-900">{{ $summary['subtotal_formatted'] }} / {{ $summary['min_order_formatted'] }}</span>
            </div>

            @php
                $pct = min(100, round(($summary['subtotal'] / max(1, $summary['min_order_value'])) * 100));
            @endphp

            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div 
                    class="h-full rounded-full transition-all duration-500 {{ $summary['is_minimum_met'] ? 'bg-emerald-500' : 'bg-amber-500' }}"
                    style="width: {{ $pct }}%;"
                ></div>
            </div>

            @if($summary['is_minimum_met'])
                <p class="text-xs text-emerald-700 font-semibold flex items-center gap-1.5 pt-1">
                    <span>✅</span>
                    <span>Minimum order requirement met! You can proceed to checkout.</span>
                </p>
            @else
                <p class="text-xs text-amber-800 font-semibold flex items-center gap-1.5 pt-1">
                    <span>⚠️</span>
                    <span>Please add <strong>{{ $summary['deficit_formatted'] }}</strong> more in bulk goods to reach the minimum order requirement.</span>
                </p>
            @endif
        </div>

        {{-- Cart Items Table & Order Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Items List (2 cols) --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm divide-y divide-slate-100">
                    @foreach($summary['items'] as $item)
                        <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <img src="{{ $item['image'] }}" class="w-16 h-16 object-contain rounded-xl bg-slate-50 border border-slate-200 p-1 flex-shrink-0">
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-slate-900 leading-snug truncate">
                                        <a href="{{ route('product.show', $item['slug']) }}" class="hover:text-sky-600 transition">
                                            {{ $item['name'] }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-500">{{ $item['pack_size'] }}</p>
                                    <div class="text-xs font-mono text-slate-400 mt-0.5">SKU: {{ $item['sku'] }}</div>
                                    <div class="text-xs font-bold text-sky-600 mt-1">
                                        {{ format_currency($item['unit_price']) }} / carton
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full sm:w-auto gap-6 sm:justify-end">
                                {{-- Quantity Selector Form --}}
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white shadow-sm">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                    <button 
                                        type="submit" 
                                        name="quantity" 
                                        value="{{ $item['quantity'] - 1 }}" 
                                        class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs"
                                    >-</button>
                                    <span class="px-3 text-xs font-bold text-slate-800">{{ $item['quantity'] }}</span>
                                    <button 
                                        type="submit" 
                                        name="quantity" 
                                        value="{{ $item['quantity'] + 1 }}" 
                                        class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs"
                                    >+</button>
                                </form>

                                <div class="text-right">
                                    <div class="text-sm font-black text-slate-900">
                                        {{ format_currency($item['line_total']) }}
                                    </div>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="mt-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button type="submit" class="text-[11px] text-rose-500 hover:underline">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Column: Summary Card --}}
            <div>
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5 sticky top-28">
                    <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                        Cart Summary
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal ({{ $summary['item_count'] }} {{ Str::plural('carton', $summary['item_count']) }}):</span>
                            <span class="font-bold text-slate-900">{{ $summary['subtotal_formatted'] }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Delivery Charges:</span>
                            <span class="text-slate-500 italic">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Floor Charges:</span>
                            <span class="text-slate-500 italic">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between text-xs text-sky-700 bg-sky-50 p-2.5 rounded-xl border border-sky-200">
                            <span>Advance Deposit:</span>
                            <span class="font-bold">{{ setting('advance_percentage', 10) }}% via QR at checkout</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200">
                        @if($summary['is_minimum_met'])
                            <a href="{{ route('checkout.index') }}" class="w-full block text-center py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm rounded-xl shadow-lg transition">
                                Proceed to Checkout &rarr;
                            </a>
                        @else
                            <button disabled class="w-full py-3.5 bg-slate-300 text-slate-500 font-bold text-sm rounded-xl cursor-not-allowed">
                                Minimum Order Not Met
                            </button>
                            <p class="text-[11px] text-slate-400 text-center mt-2">
                                Add {{ $summary['deficit_formatted'] }} more to enable checkout.
                            </p>
                        @endif
                    </div>

                    <div class="text-[11px] text-slate-400 space-y-1 pt-2">
                        <p>✓ Genuine manufacturer sealed packaging</p>
                        <p>✓ Free local delivery within {{ setting('free_delivery_radius', 5) }} km</p>
                        <p>✓ Free delivery up to Floor {{ setting('free_floor_limit', 1) }}</p>
                    </div>
                </div>
            </div>

        </div>

    @endif

</div>
@endsection

