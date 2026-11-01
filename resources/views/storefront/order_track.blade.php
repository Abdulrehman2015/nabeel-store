@extends('layouts.storefront')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- Header --}}
    <div class="text-center max-w-xl mx-auto space-y-2">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Order Transparency</span>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">Live Order Tracking</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Enter your unique Order Number and telephone number to view live verification, packing, and dispatch progress.
        </p>
    </div>

    {{-- Tracking Lookup Form --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm max-w-2xl mx-auto">
        <form action="{{ route('order.track') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Order Number *</label>
                <input 
                    type="text" 
                    name="order_number" 
                    value="{{ request('order_number') }}" 
                    required 
                    placeholder="e.g. BS-2026-000101" 
                    class="w-full text-xs font-mono uppercase px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500"
                >
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number *</label>
                <input 
                    type="text" 
                    name="phone" 
                    value="{{ request('phone') }}" 
                    required 
                    placeholder="e.g. +60128899900" 
                    class="w-full text-xs px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500"
                >
            </div>
            <div class="sm:col-span-1 flex items-end">
                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition">
                    Track
                </button>
            </div>
        </form>
    </div>

    @if($searched && !$order)
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-6 rounded-2xl text-center max-w-md mx-auto text-xs space-y-2">
            <div class="text-2xl">⚠️</div>
            <div class="font-bold text-sm">No Order Found</div>
            <p>We could not find an order matching that Order Number and Phone. Please verify your details and try again.</p>
        </div>
    @endif

    @if($order)
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-8">
            
            {{-- Top Order Meta --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs text-slate-400 font-mono">Order Reference</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $order->order_number }}</h2>
                    <span class="text-xs text-slate-500">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</span>
                </div>
                <div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            {{-- Payment Rejection Alert (if applicable) --}}
            @if($order->order_status === 'payment_rejected')
                <div class="p-4 bg-rose-50 border border-rose-300 rounded-2xl text-rose-900 text-xs space-y-1">
                    <h4 class="font-bold text-sm flex items-center gap-1.5">
                        <span>❌</span>
                        <span>Advance Payment Proof Rejected</span>
                    </h4>
                    <p>Reason provided by administration: <strong>{{ $order->payment_rejection_reason }}</strong></p>
                    <p class="pt-1">Please contact support at {{ setting('store_phone') }} or via WhatsApp to provide valid proof.</p>
                </div>
            @endif

            {{-- Visual Progress Steps (Phase 3.7) --}}
            @php
                $statuses = [
                    'pending_payment_verification' => 'Payment Verification',
                    'confirmed' => 'Order Confirmed',
                    'processing' => 'Warehouse Packing',
                    'out_for_delivery' => 'Out for Delivery',
                    'delivered' => 'Delivered',
                ];

                $statusKeys = array_keys($statuses);
                $currentIndex = array_search($order->order_status, $statusKeys);
                if ($currentIndex === false && $order->order_status === 'payment_rejected') {
                    $currentIndex = -1;
                }
            @endphp

            <div class="py-4">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                    @foreach($statuses as $key => $label)
                        @php
                            $stepIndex = array_search($key, $statusKeys);
                            $isComplete = $currentIndex !== false && $stepIndex <= $currentIndex;
                            $isCurrent = $currentIndex !== false && $stepIndex === $currentIndex;
                        @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-black mb-2 transition-colors {{ $isComplete ? 'bg-sky-600 text-white shadow' : 'bg-slate-100 text-slate-400' }}">
                                @if($isComplete) ✓ @else {{ $stepIndex + 1 }} @endif
                            </div>
                            <span class="text-xs font-bold leading-tight {{ $isCurrent ? 'text-sky-600 font-black' : ($isComplete ? 'text-slate-900' : 'text-slate-400') }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Status Change History Timeline --}}
            @if($order->histories->count() > 0)
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-xs space-y-3">
                    <h3 class="font-bold text-slate-900">Status History Log</h3>
                    <div class="space-y-2">
                        @foreach($order->histories as $history)
                            <div class="flex items-start gap-3">
                                <span class="text-slate-400 font-mono text-[11px] whitespace-nowrap">{{ $history->created_at->format('M d, H:i') }}</span>
                                <div class="text-slate-700">
                                    <strong class="text-slate-900">{{ ucfirst(str_replace('_', ' ', $history->status_to)) }}</strong> &bull;
                                    <span>{{ $history->comment }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Items & Delivery Card --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                
                {{-- Delivery Destination --}}
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                    <h3 class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Delivery Destination</h3>
                    <div class="text-slate-800 leading-relaxed">
                        <strong class="text-sm text-slate-900 block">{{ $order->customer_name }}</strong>
                        <span>{{ $order->delivery_address }}</span><br>
                        <span>{{ $order->delivery_city }} (<strong>Floor {{ $order->floor }}</strong>)</span><br>
                        <span>Phone: {{ $order->customer_phone }}</span>
                    </div>
                </div>

                {{-- Financial Balance Card --}}
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                    <h3 class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Payment Breakdown</h3>
                    <div class="flex justify-between">
                        <span>Grand Total:</span>
                        <strong class="text-slate-900">{{ format_currency($order->grand_total) }}</strong>
                    </div>
                    <div class="flex justify-between text-emerald-700">
                        <span>Advance Paid ({{ $order->advance_percentage }}%):</span>
                        <strong>{{ format_currency($order->advance_amount) }}</strong>
                    </div>
                    <div class="flex justify-between text-sm font-extrabold text-amber-900 pt-2 border-t border-slate-200">
                        <span>Balance to Pay on Delivery:</span>
                        <span>{{ format_currency($order->balance_amount) }}</span>
                    </div>
                </div>

            </div>

            {{-- Direct WhatsApp Button (Phase 4.2 C) --}}
            @if($whatsAppUrl)
                <div class="text-center pt-2">
                    <a 
                        href="{{ $whatsAppUrl }}" 
                        target="_blank" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition"
                    >
                        <span>💬</span>
                        <span>Message Admin on WhatsApp About This Order</span>
                    </a>
                </div>
            @endif

        </div>
    @endif

</div>
@endsection
