@extends('layouts.storefront')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- Order Success Banner --}}
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 text-center shadow-sm space-y-4">
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-4xl shadow-inner">
            ✓
        </div>

        <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-black rounded-full uppercase tracking-wider">
            {{ $order->status_label }}
        </span>

        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
            Order Submitted Successfully!
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
            Thank you, <strong>{{ $order->customer_name }}</strong>. Your wholesale carton order has been registered and your payment receipt is currently under verification.
        </p>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block text-left text-xs space-y-1">
            <div>Order Number: <strong class="font-mono text-slate-900 text-sm">{{ $order->order_number }}</strong></div>
            <div>Transaction Reference: <strong class="font-mono text-slate-900">{{ $order->payment_reference }}</strong></div>
            <div>Date Placed: <strong class="text-slate-900">{{ $order->created_at->format('M d, Y h:i A') }}</strong></div>
        </div>
    </div>

    {{-- Next Steps Card --}}
    <div class="bg-sky-50 rounded-3xl p-6 sm:p-8 border border-sky-200 space-y-4 text-xs text-sky-950">
        <h3 class="font-black text-sm text-sky-900 flex items-center gap-2">
            <span>ℹ️</span>
            <span>What happens next?</span>
        </h3>
        <ol class="list-decimal pl-5 space-y-2 leading-relaxed">
            <li><strong>Admin Verification:</strong> Our warehouse administration will inspect your uploaded payment screenshot against our Maybank / DuitNow ledger within 15–30 minutes.</li>
            <li><strong>Stock Allocation & Picking:</strong> Once verified, your status transitions to <em>Confirmed</em> and your cartons are packed onto our dispatch vehicle.</li>
            <li><strong>Doorstep Delivery & Balance:</strong> Our delivery driver will transport your bulk order to Floor {{ $order->floor }}. You settle the remaining balance of <strong class="text-slate-900 font-bold">{{ format_currency($order->balance_amount) }}</strong> upon arrival.</li>
        </ol>
    </div>

    {{-- Financial Summary Breakdown --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4 text-xs">
        <h3 class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-100">
            Payment & Pricing Summary
        </h3>

        <div class="space-y-2">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal ({{ $order->items->sum('quantity') }} cartons):</span>
                <span class="font-bold text-slate-900">{{ format_currency($order->subtotal) }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Delivery Charge ({{ $order->distance_km }} km):</span>
                <span class="font-bold {{ $order->delivery_charge > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                    {{ $order->delivery_charge > 0 ? format_currency($order->delivery_charge) : 'FREE' }}
                </span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Floor Charges (Floor {{ $order->floor }}):</span>
                <span class="font-bold {{ $order->floor_charge > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                    {{ $order->floor_charge > 0 ? format_currency($order->floor_charge) : 'FREE' }}
                </span>
            </div>
            <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-100">
                <span>Grand Total:</span>
                <span class="text-sky-600 text-base">{{ format_currency($order->grand_total) }}</span>
            </div>
            <div class="flex justify-between p-3 rounded-xl bg-emerald-50 text-emerald-900 font-bold">
                <span>Advance Paid ({{ $order->advance_percentage }}%):</span>
                <span>{{ format_currency($order->advance_amount) }}</span>
            </div>
            <div class="flex justify-between p-3 rounded-xl bg-amber-50 text-amber-900 font-bold">
                <span>Balance Due on Delivery:</span>
                <span>{{ format_currency($order->balance_amount) }}</span>
            </div>
        </div>
    </div>

    {{-- Action Buttons: WhatsApp Fallback + Live Tracking (Phase 4.2 C) --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-2">
        @if($whatsAppUrl)
            <a 
                href="{{ $whatsAppUrl }}" 
                target="_blank" 
                class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg transition flex items-center justify-center gap-2"
            >
                <span>💬</span>
                <span>Send Order via WhatsApp to Admin</span>
            </a>
        @endif

        <a 
            href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" 
            class="px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-lg transition flex items-center justify-center gap-2"
        >
            <span>🔍</span>
            <span>Track Live Order Status</span>
        </a>
    </div>

</div>
@endsection
