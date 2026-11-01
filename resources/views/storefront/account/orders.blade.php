@extends('layouts.storefront')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Account Portal</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">My Bulk Orders</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('account.addresses') }}" class="text-xs font-bold text-slate-700 hover:text-sky-600 bg-white border border-slate-200 px-3 py-2 rounded-xl transition">
                Saved Addresses
            </a>
            <a href="{{ route('account.export-data') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 bg-sky-50 border border-sky-200 px-3 py-2 rounded-xl transition">
                Download JSON Data
            </a>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm divide-y divide-slate-100">
            @foreach($orders as $order)
                <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-slate-900 text-sm">{{ $order->order_number }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Placed on {{ $order->created_at->format('M d, Y h:i A') }} &bull; {{ $order->items->sum('quantity') }} cartons
                        </p>
                        <p class="text-xs text-slate-600">
                            Delivery to: {{ $order->delivery_city }} (Floor {{ $order->floor }})
                        </p>
                    </div>

                    <div class="flex items-center justify-between w-full sm:w-auto gap-6 sm:justify-end">
                        <div class="text-right">
                            <div class="text-base font-black text-slate-900">{{ format_currency($order->grand_total) }}</div>
                            <div class="text-[11px] text-slate-500">
                                Advance: {{ format_currency($order->advance_amount) }}
                            </div>
                        </div>

                        <a href="{{ route('account.orders.details', $order->order_number) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition">
                            View Order
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 max-w-md mx-auto shadow-sm">
            <div class="text-3xl mb-2">📦</div>
            <h3 class="text-base font-bold text-slate-900">No orders placed yet</h3>
            <p class="text-xs text-slate-500 mt-1">When you place wholesale carton orders, they will appear here with live tracking.</p>
            <div class="mt-4">
                <a href="{{ route('category.index') }}" class="inline-block px-4 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-700 transition">
                    Browse Wholesale Store
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
