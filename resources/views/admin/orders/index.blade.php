@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="space-y-6">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Store Operations</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Orders</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Customer Orders</h1>
            <p class="text-xs text-slate-500 mt-0.5">Verify customer payments, manage fulfillment packing, schedule drivers, and download PDF invoices</p>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-bold text-slate-700 shadow-2xs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $stats['total_orders'] ?? $orders->total() }} Total Orders</span>
            </span>
        </div>
    </div>

    {{-- KPI Metric Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Pending Payment Verification --}}
        <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" 
           class="p-4 bg-white rounded-2xl border shadow-xs hover:shadow-md transition group {{ request('status') === 'pending_payment_verification' ? 'border-amber-400 ring-2 ring-amber-500/20' : 'border-slate-200/80 hover:border-amber-300' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Needs Verification</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-amber-700">{{ $stats['pending_count'] ?? 0 }}</div>
            <span class="text-[10px] text-amber-600/80 mt-1 block font-semibold">
                {{ format_currency($stats['pending_amount'] ?? 0) }} advance to review
            </span>
        </a>

        {{-- Ready for Packing / Confirmed --}}
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
           class="p-4 bg-white rounded-2xl border shadow-xs hover:shadow-md transition group {{ request('status') === 'confirmed' ? 'border-sky-400 ring-2 ring-sky-500/20' : 'border-slate-200/80 hover:border-sky-300' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-sky-800 uppercase tracking-wider">Ready to Pack</span>
                <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-sky-700">{{ $stats['ready_for_dispatch'] ?? 0 }}</div>
            <span class="text-[10px] text-sky-600/80 mt-1 block">Confirmed & assembling stock</span>
        </a>

        {{-- Out for Delivery --}}
        <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" 
           class="p-4 bg-white rounded-2xl border shadow-xs hover:shadow-md transition group {{ request('status') === 'out_for_delivery' ? 'border-indigo-400 ring-2 ring-indigo-500/20' : 'border-slate-200/80 hover:border-indigo-300' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-indigo-800 uppercase tracking-wider">Out for Delivery</span>
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-indigo-700">{{ $stats['out_for_delivery'] ?? 0 }}</div>
            <span class="text-[10px] text-indigo-600/80 mt-1 block">En route with delivery driver</span>
        </a>

        {{-- Total Fulfilled Revenue --}}
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Store Revenue</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-emerald-700">{{ format_currency($stats['total_revenue'] ?? 0) }}</div>
            <span class="text-[10px] text-emerald-600/80 mt-1 block">Confirmed order transactions</span>
        </div>
    </div>

    {{-- Filter Tabs Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-3.5 space-y-3">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1">
            <a href="{{ route('admin.orders.index', request()->only(['search', 'date'])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('status') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                All Orders ({{ $stats['total_orders'] ?? 0 }})
            </a>

            <a href="{{ route('admin.orders.index', array_merge(request()->only(['search', 'date']), ['status' => 'pending_payment_verification'])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ request('status') === 'pending_payment_verification' ? 'bg-amber-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 {{ $counts['pending_payment_verification'] > 0 ? 'animate-pulse' : '' }}"></span>
                <span>Verify Payment</span>
                @if($counts['pending_payment_verification'] > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ request('status') === 'pending_payment_verification' ? 'bg-amber-800 text-white' : 'bg-amber-100 text-amber-800' }}">
                        {{ $counts['pending_payment_verification'] }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.orders.index', array_merge(request()->only(['search', 'date']), ['status' => 'confirmed'])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ request('status') === 'confirmed' ? 'bg-sky-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                <span>Confirmed ({{ $counts['confirmed'] }})</span>
            </a>

            <a href="{{ route('admin.orders.index', array_merge(request()->only(['search', 'date']), ['status' => 'processing'])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ request('status') === 'processing' ? 'bg-purple-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                <span>In Packing ({{ $counts['processing'] }})</span>
            </a>

            <a href="{{ route('admin.orders.index', array_merge(request()->only(['search', 'date']), ['status' => 'out_for_delivery'])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ request('status') === 'out_for_delivery' ? 'bg-indigo-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                <span>Out for Delivery ({{ $counts['out_for_delivery'] }})</span>
            </a>

            <a href="{{ route('admin.orders.index', array_merge(request()->only(['search', 'date']), ['status' => 'delivered'])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ request('status') === 'delivered' ? 'bg-emerald-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>Delivered ({{ $counts['delivered'] }})</span>
            </a>
        </div>

        {{-- Search & Date Filter Bar --}}
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2 border-t border-slate-100">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="sm:col-span-7 relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by Order #, Customer Name, Phone, or Payment Reference..." 
                    class="w-full text-xs font-medium pl-9 pr-8 py-2 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition"
                >
                @if(request('search'))
                    <a href="{{ route('admin.orders.index', request()->only(['status', 'date'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">&times;</a>
                @endif
            </div>

            <div class="sm:col-span-3">
                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}" 
                    class="w-full text-xs font-medium py-2 px-3 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition"
                >
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition cursor-pointer shadow-2xs">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date']))
                    <a href="{{ route('admin.orders.index', request('status') ? ['status' => request('status')] : []) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Clear search and date filters">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Orders Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-bold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3.5 px-5">Order ID & Date</th>
                        <th class="py-3.5 px-5">Customer Details</th>
                        <th class="py-3.5 px-5">Delivery Destination</th>
                        <th class="py-3.5 px-5">Items Qty</th>
                        <th class="py-3.5 px-5">Grand Total</th>
                        <th class="py-3.5 px-5">Advance Payment</th>
                        <th class="py-3.5 px-5">Order Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition group">
                            {{-- Order ID & Date --}}
                            <td class="py-4 px-5">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-sky-600 group-hover:text-sky-700 hover:underline text-xs block">
                                    {{ $order->order_number }}
                                </a>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">
                                    {{ $order->created_at->format('M d, Y • h:i A') }}
                                </span>
                                @if($order->cashier)
                                    <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[9px] font-bold">
                                        Cashier: {{ $order->cashier->name }}
                                    </span>
                                @elseif(str_starts_with($order->order_number, 'POS-'))
                                    <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[9px] font-bold">
                                        POS Counter
                                    </span>
                                @endif
                            </td>

                            {{-- Customer Profile --}}
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                @if($order->customer_phone)
                                    <a href="tel:{{ $order->customer_phone }}" class="text-[11px] text-slate-500 hover:text-sky-600 font-mono mt-0.5 inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span>{{ $order->customer_phone }}</span>
                                    </a>
                                @endif
                            </td>

                            {{-- Delivery Destination --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px] border border-slate-200">
                                        Floor {{ $order->floor ?? '0' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">
                                        {{ $order->distance_km ?? '0.00' }} km
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate max-w-xs" title="{{ $order->delivery_city }}">
                                    {{ $order->delivery_city }}
                                </div>
                            </td>

                            {{-- Items Count --}}
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 font-black text-xs">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>{{ $order->items->sum('quantity') }} items</span>
                                </span>
                            </td>

                            {{-- Grand Total --}}
                            <td class="py-4 px-5">
                                <span class="font-black text-slate-900 text-sm block">
                                    {{ format_currency($order->grand_total) }}
                                </span>
                            </td>

                            {{-- Advance Paid --}}
                            <td class="py-4 px-5">
                                <span class="font-bold text-emerald-700 block">
                                    {{ format_currency($order->advance_amount) }}
                                </span>
                                @if($order->payment_reference)
                                    <span class="text-[10px] text-slate-400 font-mono block truncate max-w-[120px]" title="{{ $order->payment_reference }}">
                                        Ref: {{ $order->payment_reference }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                                    @if($order->order_status === 'pending_payment_verification')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    @elseif($order->order_status === 'delivered')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    @endif
                                    <span>{{ $order->status_label }}</span>
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition" title="Print Invoice (PDF)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition shadow-2xs flex items-center gap-1">
                                        <span>Manage</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span class="font-bold text-slate-600 block">No orders matching criteria</span>
                                <span class="text-xs text-slate-400 mt-1 block">Try clearing your filters or search keywords</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
