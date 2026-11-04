@extends('layouts.storefront')

@section('title', 'Order Received #' . $order->order_number . ' - ' . setting('site_name', 'N&J Mini Mart'))

@section('content')
<div class="bg-slate-50/60 min-h-screen py-8 sm:py-12" x-data="orderSuccessApp()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- 1. Hero Celebration Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-xs relative overflow-hidden text-center">
            {{-- Decorative Gradient Corner Accents --}}
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-sky-500/10 rounded-full blur-2xl pointer-events-none"></div>

            {{-- Animated Emerald Success Badge --}}
            <div class="relative inline-flex items-center justify-center mb-4">
                <span class="absolute inset-0 rounded-full bg-emerald-400 opacity-20 animate-ping"></span>
                <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-3xl sm:text-4xl shadow-lg shadow-emerald-500/25">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            {{-- Status Pill --}}
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-black rounded-full uppercase tracking-wider border {{ $order->status_badge_class }}">
                    @if($order->order_status === 'pending_payment_verification')
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    @elseif($order->order_status === 'confirmed' || $order->order_status === 'delivered')
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                    @endif
                    <span>{{ $order->status_label }}</span>
                </span>
            </div>

            {{-- Heading & Subtitle --}}
            <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-3">
                Order Submitted Successfully!
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto mt-2 leading-relaxed">
                Thank you, <strong class="text-slate-800">{{ $order->customer_name }}</strong>. Your order has been registered in our store dispatch system and our warehouse team is processing it.
            </p>

            {{-- Order Identity Bar (Interactive with 1-Click Copy) --}}
            <div class="mt-6 inline-flex flex-wrap items-center justify-center gap-3 bg-slate-50 p-2.5 sm:px-4 sm:py-2.5 rounded-2xl border border-slate-200/90 text-xs text-slate-700 shadow-2xs">
                {{-- Order Number with Copy --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400 font-medium">Order #:</span>
                    <strong class="font-mono text-slate-900 text-sm font-black">{{ $order->order_number }}</strong>
                    <button 
                        type="button" 
                        @click="copyOrderNumber('{{ $order->order_number }}')" 
                        class="p-1 text-slate-400 hover:text-sky-600 rounded-md transition cursor-pointer relative" 
                        title="Copy Order Number"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span x-show="copied" x-cloak class="absolute -top-7 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-slate-900 text-white text-[10px] font-bold rounded-md shadow-sm whitespace-nowrap">Copied!</span>
                    </button>
                </div>

                <span class="text-slate-300 hidden sm:inline">&bull;</span>

                {{-- Date Placed --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400 font-medium">Date:</span>
                    <strong class="text-slate-900 font-semibold">{{ $order->created_at->format('M d, Y • h:i A') }}</strong>
                </div>

                <span class="text-slate-300 hidden sm:inline">&bull;</span>

                {{-- Payment Method --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400 font-medium">Payment:</span>
                    <span class="font-semibold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-800 text-[11px]">
                        @if($order->payment_method === 'qr_advance')
                            📱 DuitNow QR (Advance {{ (float) $order->advance_percentage }}%)
                        @elseif($order->payment_method === 'cash')
                            💵 Cash on Delivery
                        @else
                            💳 {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        @endif
                    </span>
                </div>
            </div>

            {{-- 2. Interactive Fulfillment Progress Stepper --}}
            <div class="mt-8 pt-8 border-t border-slate-100 max-w-4xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-left">
                    
                    {{-- Step 1: Order Placed --}}
                    <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/90 flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs shrink-0 font-black shadow-2xs">
                            ✓
                        </div>
                        <div>
                            <span class="block font-black text-xs text-emerald-950">1. Order Placed</span>
                            <span class="text-[11px] text-emerald-700 block mt-0.5">Recorded in database</span>
                        </div>
                    </div>

                    {{-- Step 2: Payment Verification --}}
                    @php
                        $isVerified = in_array($order->order_status, ['confirmed', 'processing', 'out_for_delivery', 'delivered']);
                    @endphp
                    <div class="p-3.5 rounded-2xl {{ $isVerified ? 'bg-emerald-50/80 border-emerald-200/90' : 'bg-amber-50/80 border-amber-200/90' }} border flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-full {{ $isVerified ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white animate-pulse' }} flex items-center justify-center text-xs shrink-0 font-black shadow-2xs">
                            {{ $isVerified ? '✓' : '2' }}
                        </div>
                        <div>
                            <span class="block font-black text-xs {{ $isVerified ? 'text-emerald-950' : 'text-amber-950' }}">2. Receipt Audit</span>
                            <span class="text-[11px] {{ $isVerified ? 'text-emerald-700' : 'text-amber-700' }} block mt-0.5">
                                {{ $isVerified ? 'Verified & approved' : 'Ledger check 15-30m' }}
                            </span>
                        </div>
                    </div>

                    {{-- Step 3: Packing & Dispatch --}}
                    @php
                        $isPacked = in_array($order->order_status, ['processing', 'out_for_delivery', 'delivered']);
                    @endphp
                    <div class="p-3.5 rounded-2xl {{ $isPacked ? 'bg-emerald-50/80 border-emerald-200/90' : 'bg-slate-50 border-slate-200/80' }} border flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-full {{ $isPacked ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs shrink-0 font-black shadow-2xs">
                            {{ $isPacked ? '✓' : '3' }}
                        </div>
                        <div>
                            <span class="block font-black text-xs {{ $isPacked ? 'text-emerald-950' : 'text-slate-700' }}">3. Carton Picking</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">Puchong warehouse hub</span>
                        </div>
                    </div>

                    {{-- Step 4: Doorstep Delivery --}}
                    @php
                        $isDelivered = $order->order_status === 'delivered';
                    @endphp
                    <div class="p-3.5 rounded-2xl {{ $isDelivered ? 'bg-emerald-50/80 border-emerald-200/90' : 'bg-slate-50 border-slate-200/80' }} border flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-full {{ $isDelivered ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs shrink-0 font-black shadow-2xs">
                            {{ $isDelivered ? '✓' : '4' }}
                        </div>
                        <div>
                            <span class="block font-black text-xs {{ $isDelivered ? 'text-emerald-950' : 'text-slate-700' }}">4. Doorstep Drop</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">Floor {{ $order->floor }} Delivery</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- 2. Dual Column Layout: Details & Financial Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- LEFT COLUMN: ORDER ITEMS & DELIVERY DETAILS (7 COLS) --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- Card: Ordered Items Breakdown --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📦</span>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                Items in this Order
                            </h2>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-mono font-bold">
                            {{ $order->items->sum('quantity') }} Cartons
                        </span>
                    </div>

                    {{-- Line Items List --}}
                    <div class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            @php
                                $itemImage = $item->image 
                                    ? (str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . ltrim($item->image, '/'))) 
                                    : ($item->product?->featured_image ? (str_starts_with($item->product->featured_image, 'http') ? $item->product->featured_image : asset('storage/' . ltrim($item->product->featured_image, '/'))) : asset('images/logo.png'));
                            @endphp
                            <div class="py-3.5 flex items-center justify-between gap-3 group">
                                {{-- Item Thumbnail --}}
                                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-slate-50 border border-slate-100 p-1 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                    <img 
                                        src="{{ $itemImage }}" 
                                        alt="{{ $item->product_name }}" 
                                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200"
                                        loading="lazy"
                                    >
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug truncate">
                                        {{ $item->product_name }}
                                    </h3>
                                    
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        @if($item->pack_size)
                                            <span class="text-[10px] text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded-md">
                                                {{ $item->pack_size }}
                                            </span>
                                        @endif
                                        @if($item->sku)
                                            <span class="text-[10px] font-mono text-slate-400">
                                                SKU: {{ $item->sku }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-[11px] text-slate-500 mt-1">
                                        <span>{{ format_currency($item->unit_price) }} &times; {{ $item->quantity }} carton{{ $item->quantity > 1 ? 's' : '' }}</span>
                                    </div>
                                </div>

                                {{-- Line Total --}}
                                <div class="text-right shrink-0">
                                    <div class="text-xs sm:text-sm font-black text-slate-900">
                                        {{ format_currency($item->line_total) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Card: Delivery Destination & Recipient Information --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="text-base">📍</span>
                        <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                            Delivery & Contact Details
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        {{-- Recipient Name & Phone --}}
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Recipient</span>
                            <div class="text-xs sm:text-sm font-black text-slate-900">{{ $order->customer_name }}</div>
                            <div class="text-slate-600 font-mono">{{ $order->customer_phone }}</div>
                            @if($order->customer_email)
                                <div class="text-slate-500 text-[11px] truncate">{{ $order->customer_email }}</div>
                            @endif
                        </div>

                        {{-- Delivery Floor & Zone --}}
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Delivery Handling</span>
                            <div class="text-xs sm:text-sm font-black text-slate-900">
                                Floor: {{ $order->floor == 0 ? 'Ground Floor (Level 0)' : 'Floor ' . $order->floor }}
                            </div>
                            <div class="text-slate-600">
                                Zone: <strong class="text-slate-800 uppercase text-[11px]">{{ ucfirst($order->delivery_zone) }}</strong> ({{ $order->distance_km }} km)
                            </div>
                        </div>

                        {{-- Full Address --}}
                        <div class="sm:col-span-2 p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Delivery Destination</span>
                            <p class="text-xs font-semibold text-slate-800 leading-relaxed">
                                {{ $order->delivery_address }}
                                @if($order->delivery_city)
                                    , {{ $order->delivery_city }}
                                @endif
                            </p>
                            @if($order->landmark)
                                <p class="text-[11px] text-slate-500 mt-1">
                                    <strong class="text-slate-700">Landmark / Notes:</strong> {{ $order->landmark }}
                                </p>
                            @endif
                            @if($order->order_notes)
                                <p class="text-[11px] text-slate-500 mt-1">
                                    <strong class="text-slate-700">Driver Instructions:</strong> {{ $order->order_notes }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card: Uploaded Payment Proof --}}
                @if($order->payment_proof_path)
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🧾</span>
                                <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                    Uploaded Payment Receipt
                                </h2>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black uppercase tracking-wider">
                                Receipt Attached
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100">
                            {{-- Receipt Thumbnail --}}
                            <div class="w-20 h-24 rounded-xl bg-white border border-slate-200 p-1 shrink-0 overflow-hidden shadow-2xs group cursor-pointer" @click="showReceiptModal = true">
                                <img 
                                    src="{{ asset('storage/' . $order->payment_proof_path) }}" 
                                    alt="Payment Receipt" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                >
                            </div>

                            <div class="flex-1 min-w-0 text-xs space-y-1">
                                <div class="font-bold text-slate-900">Maybank / DuitNow Transfer Slip</div>
                                <div class="text-slate-500 text-[11px]">
                                    Reference ID: <strong class="font-mono text-slate-800">{{ $order->payment_reference ?: 'Auto-attached' }}</strong>
                                </div>
                                <div class="text-slate-400 text-[10px]">
                                    Uploaded on {{ $order->created_at->format('M d, Y • h:i A') }}
                                </div>

                                <button 
                                    type="button" 
                                    @click="showReceiptModal = true"
                                    class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-bold text-xs transition cursor-pointer shadow-2xs"
                                >
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Preview Receipt</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: FINANCIAL SUMMARY & INSTANT ACTIONS (5 COLS) --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Financial Summary Card --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="text-base">💳</span>
                        <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                            Payment & Pricing Summary
                        </h2>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        {{-- Subtotal --}}
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal ({{ $order->items->sum('quantity') }} cartons):</span>
                            <span class="font-bold text-slate-900">{{ format_currency($order->subtotal) }}</span>
                        </div>

                        {{-- Delivery Fee --}}
                        <div class="flex justify-between text-slate-600">
                            <span>Delivery ({{ $order->distance_km }} km):</span>
                            <span class="font-bold {{ $order->delivery_charge > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                                {{ $order->delivery_charge > 0 ? format_currency($order->delivery_charge) : 'FREE' }}
                            </span>
                        </div>

                        {{-- Floor Charge --}}
                        <div class="flex justify-between text-slate-600">
                            <span>Floor Handling (Floor {{ $order->floor }}):</span>
                            <span class="font-bold {{ $order->floor_charge > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                                {{ $order->floor_charge > 0 ? format_currency($order->floor_charge) : 'FREE' }}
                            </span>
                        </div>

                        {{-- Grand Total --}}
                        <div class="flex justify-between items-baseline pt-3 border-t border-slate-100">
                            <span class="font-black text-slate-900 text-sm">Grand Total:</span>
                            <span class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">{{ format_currency($order->grand_total) }}</span>
                        </div>

                        {{-- Advance Paid Pill --}}
                        <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 flex items-center justify-between">
                            <div>
                                <span class="block font-black text-xs text-emerald-900">
                                    Advance Paid ({{ (float) $order->advance_percentage }}%)
                                </span>
                                <span class="text-[10px] text-emerald-700 block">Submitted via DuitNow QR</span>
                            </div>
                            <span class="text-sm font-black text-emerald-800">
                                {{ format_currency($order->advance_amount) }}
                            </span>
                        </div>

                        {{-- Balance Due on Delivery Pill --}}
                        <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 flex items-center justify-between">
                            <div>
                                <span class="block font-black text-xs text-amber-900">
                                    Remaining Balance on Delivery
                                </span>
                                <span class="text-[10px] text-amber-700 block">Pay cash / QR to driver at doorstep</span>
                            </div>
                            <span class="text-sm font-black text-amber-900">
                                {{ format_currency($order->balance_amount) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action CTAs Card --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Order Next Steps</h3>

                    {{-- 1. WhatsApp Button --}}
                    @if($whatsAppUrl)
                        <a 
                            href="{{ $whatsAppUrl }}" 
                            target="_blank" 
                            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-2xl shadow-sm shadow-emerald-600/25 transition flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>Chat with Mart Admin on WhatsApp</span>
                        </a>
                    @endif

                    {{-- 2. Live Tracking Button --}}
                    <a 
                        href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" 
                        class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-black text-xs rounded-2xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Track Live Delivery Progress</span>
                    </a>

                    {{-- 3. Print Tax Invoice Button --}}
                    <button 
                        type="button" 
                        @click="window.print()" 
                        class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print Order Summary</span>
                    </button>

                    {{-- 4. Back to Storefront --}}
                    <div class="pt-2 text-center">
                        <a 
                            href="{{ url('/') }}" 
                            class="text-xs font-bold text-sky-600 hover:text-sky-700 hover:underline transition"
                        >
                            &larr; Continue Shopping at N&J Mini Mart
                        </a>
                    </div>
                </div>

                {{-- Need Help / Puchong Hub Support Card --}}
                <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl p-6 border border-slate-800 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-sm font-bold">
                            📞
                        </div>
                        <div>
                            <span class="block font-black text-xs text-white">Need Assistance with this Order?</span>
                            <span class="text-[10px] text-slate-400 block">Puchong Central Fulfillment Hub</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-300 leading-relaxed">
                        If you need to change your delivery time or add instructions, contact our customer operations desk directly:
                    </p>

                    <div class="pt-1 flex items-center justify-between text-xs border-t border-slate-800">
                        <span class="text-slate-400">Direct Hotline:</span>
                        <a href="tel:+60128899900" class="font-mono font-bold text-emerald-400 hover:underline">+60 12-889 9900</a>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Dispatch Hours:</span>
                        <span class="text-slate-300 font-medium">Mon - Sat: 8:00 AM - 7:00 PM</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Receipt Full-Screen Modal --}}
    @if($order->payment_proof_path)
        <div 
            x-show="showReceiptModal" 
            x-cloak 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs"
            @click.self="showReceiptModal = false"
            @keydown.escape.window="showReceiptModal = false"
        >
            <div class="bg-white rounded-3xl max-w-lg w-full p-5 space-y-4 shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="font-black text-sm text-slate-900">Payment Screenshot</h4>
                        <span class="text-[10px] text-slate-400 font-mono">Ref: {{ $order->payment_reference ?: 'Auto-attached' }}</span>
                    </div>
                    <button 
                        type="button" 
                        @click="showReceiptModal = false" 
                        class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="rounded-2xl border border-slate-100 overflow-hidden bg-slate-50 max-h-[70vh] flex items-center justify-center">
                    <img 
                        src="{{ asset('storage/' . $order->payment_proof_path) }}" 
                        alt="Uploaded payment proof" 
                        class="w-full h-auto object-contain max-h-[70vh]"
                    >
                </div>

                <div class="pt-1 flex items-center justify-end">
                    <button 
                        type="button" 
                        @click="showReceiptModal = false" 
                        class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition cursor-pointer"
                    >
                        Close Preview
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Alpine Application Logic --}}
<script>
function orderSuccessApp() {
    return {
        copied: false,
        showReceiptModal: false,

        copyOrderNumber(num) {
            navigator.clipboard.writeText(num).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
    };
}
</script>
@endsection
