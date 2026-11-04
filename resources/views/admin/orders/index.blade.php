@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Customer Orders</h1>
            <p class="text-xs text-slate-500 mt-1">Review orders, verify DuitNow QR screenshots, manage delivery dispatch and print PDF invoices.</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 text-xs font-bold">
        <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl transition {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            All Orders
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" class="px-3.5 py-2 rounded-xl transition flex items-center gap-2 {{ request('status') === 'pending_payment_verification' ? 'bg-amber-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            <span>Verify Payment</span>
            @if($counts['pending_payment_verification'] > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'pending_payment_verification' ? 'bg-amber-800 text-white' : 'bg-amber-100 text-amber-800' }}">
                    {{ $counts['pending_payment_verification'] }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'confirmed' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            Confirmed ({{ $counts['confirmed'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'processing' ? 'bg-purple-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            In Packing ({{ $counts['processing'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'out_for_delivery' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            Out for Delivery ({{ $counts['out_for_delivery'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            Delivered ({{ $counts['delivered'] }})
        </a>
    </div>

    {{-- Filters Card --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="sm:col-span-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by Order #, Customer Name, Phone, or Payment Ref..." 
                    class="w-full text-xs px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-sky-500"
                >
            </div>
            <div>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}" 
                    class="w-full text-xs px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-sky-500"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date']))
                    <a href="{{ route('admin.orders.index', request('status') ? ['status' => request('status')] : []) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Delivery Dest.</th>
                        <th class="py-3 px-4">Cartons</th>
                        <th class="py-3 px-4">Grand Total</th>
                        <th class="py-3 px-4">Advance Paid</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sky-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-sans font-normal">{{ $order->created_at->format('M d, H:i') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="bg-slate-100 px-2 py-0.5 rounded font-semibold text-slate-800">Floor {{ $order->floor }}</span>
                                <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $order->delivery_city }} ({{ $order->distance_km }} km)</div>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $order->items->sum('quantity') }}
                            </td>
                            <td class="py-3.5 px-4 font-black text-slate-900">
                                {{ format_currency($order->grand_total) }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-emerald-600">
                                {{ format_currency($order->advance_amount) }}
                                <div class="text-[10px] text-slate-400 font-mono">Ref: {{ Str::limit($order->payment_reference, 10) }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg text-[11px] transition inline-block">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No orders found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection

