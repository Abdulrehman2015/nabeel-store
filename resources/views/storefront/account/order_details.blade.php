@extends('layouts.storefront')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <nav class="flex text-xs text-slate-500 font-medium">
        <ol class="flex items-center gap-2">
            <li><a href="{{ url('/') }}" class="hover:text-sky-600">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('account.orders') }}" class="hover:text-sky-600">My Orders</a></li>
            <li>/</li>
            <li class="text-slate-900 font-bold font-mono" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 font-mono">Order Number</span>
                <h1 class="text-2xl font-black text-slate-900 font-mono">{{ $order->order_number }}</h1>
                <span class="text-xs text-slate-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        {{-- Items List --}}
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Carton Line Items</h3>
            <div class="border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="p-4 flex items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl border flex items-center justify-center text-lg flex-shrink-0">
                                📦
                            </div>
                            <div class="truncate">
                                <h4 class="font-bold text-slate-900 text-sm truncate">{{ $item->product_name }}</h4>
                                <p class="text-slate-500">{{ $item->pack_size }}</p>
                                <div class="text-slate-400 font-mono">{{ $item->sku }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-6 flex-shrink-0 text-right">
                            <div>
                                <span class="text-slate-500">{{ $item->quantity }} &times; {{ format_currency($item->unit_price) }}</span>
                            </div>
                            <div class="font-bold text-slate-900 text-sm">
                                {{ format_currency($item->line_total) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Destination & Financials --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                <h4 class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Delivery Location</h4>
                <p class="leading-relaxed text-slate-700">
                    <strong class="text-slate-900 block">{{ $order->customer_name }}</strong>
                    {{ $order->delivery_address }}<br>
                    {{ $order->delivery_city }} (<strong>Floor {{ $order->floor }}</strong>)<br>
                    Phone: {{ $order->customer_phone }}<br>
                    Zone: {{ strtoupper($order->delivery_zone) }} ({{ $order->distance_km }} km)
                </p>
            </div>

            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                <h4 class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Payment Breakdown</h4>
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <strong>{{ format_currency($order->subtotal) }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Delivery Charge:</span>
                    <strong>{{ $order->delivery_charge > 0 ? format_currency($order->delivery_charge) : 'FREE' }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Floor Charge:</span>
                    <strong>{{ $order->floor_charge > 0 ? format_currency($order->floor_charge) : 'FREE' }}</strong>
                </div>
                <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-200">
                    <span>Grand Total:</span>
                    <span class="text-sky-600">{{ format_currency($order->grand_total) }}</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Advance Paid:</span>
                    <span>{{ format_currency($order->advance_amount) }}</span>
                </div>
                <div class="flex justify-between text-amber-900 font-bold bg-amber-50 p-2 rounded-lg border border-amber-200">
                    <span>Balance on Delivery:</span>
                    <span>{{ format_currency($order->balance_amount) }}</span>
                </div>
            </div>
        </div>

        @if($whatsAppUrl)
            <div class="text-center pt-2">
                <a href="{{ $whatsAppUrl }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
                    <span>💬</span>
                    <span>Send Order Update on WhatsApp</span>
                </a>
            </div>
        @endif

    </div>

</div>
@endsection
