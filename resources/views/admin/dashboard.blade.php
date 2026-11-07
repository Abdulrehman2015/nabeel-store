@extends('layouts.admin')

@section('title', 'Mart Operations Dashboard')

@section('content')
<div class="space-y-4">

    {{-- Top Action & Status Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-1 border-b border-slate-200/60">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Store Operations</h1>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Sync
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Real-time store volume, advance payment validations & warehouse queue.</p>
        </div>

        <div class="flex items-center gap-2">
            {{-- Date Chip --}}
            <div class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ now()->format('D, M d, Y') }}</span>
            </div>

            {{-- Quick Order Link --}}
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>All Orders</span>
            </a>

            {{-- Add Product CTA --}}
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-sky-600 hover:bg-sky-700 text-white transition shadow-2xs shadow-sky-600/30">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    {{-- 6 High-Density KPI Metric Cards (Includes Realized Profit) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-3.5">
        
        {{-- 1. Today's Revenue --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Today's Revenue</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                {{ format_currency($todayRevenue) }}
            </div>
            <div class="mt-1 flex items-center gap-1.5 text-[11px] text-emerald-600 font-semibold">
                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Confirmed & Delivered</span>
            </div>
        </div>

        {{-- 2. Total Gross Profit (Sale - Purchase = Profit) --}}
        <div class="bg-white p-4 rounded-xl border border-emerald-200/90 shadow-2xs hover:shadow-xs transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-wider block">Gross Profit</span>
                    <span class="text-[9px] text-emerald-600 font-bold block">Sale &minus; Purchase</span>
                </div>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2 text-xl sm:text-2xl font-black text-emerald-700 tracking-tight">
                {{ format_currency($totalProfit) }}
            </div>
            <div class="mt-1 flex flex-wrap items-center gap-1.5 text-[11px] font-semibold">
                <span class="px-1.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                    +{{ $totalProfitMargin }}% margin
                </span>
                <span class="text-slate-500 text-[10px]">
                    Today: <strong class="text-slate-700">{{ format_currency($todayProfit) }}</strong>
                </span>
            </div>
        </div>

        {{-- 2. Today's Orders --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Today's Orders</span>
                <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                {{ $todayOrdersCount }}
            </div>
            <div class="mt-1 flex items-center gap-1.5 text-[11px] text-sky-600 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                <span>Store product orders</span>
            </div>
        </div>

        {{-- 3. Verify Payment Proof (Urgent Action) --}}
        <div class="p-4 rounded-xl border {{ $pendingPaymentsCount > 0 ? 'bg-amber-50/40 border-amber-300 ring-2 ring-amber-100/80' : 'bg-white border-slate-200/90' }} shadow-2xs hover:shadow-xs transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold {{ $pendingPaymentsCount > 0 ? 'text-amber-800' : 'text-slate-400' }} uppercase tracking-wider">Verify Payment Proof</span>
                <div class="w-7 h-7 rounded-lg {{ $pendingPaymentsCount > 0 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div class="mt-2 text-xl sm:text-2xl font-black {{ $pendingPaymentsCount > 0 ? 'text-amber-800' : 'text-slate-900' }} tracking-tight">
                {{ $pendingPaymentsCount }}
            </div>
            <div class="mt-1">
                @if($pendingPaymentsCount > 0)
                    <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" class="inline-flex items-center gap-1 text-[11px] text-amber-800 font-extrabold hover:text-amber-900 group">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>Needs verification</span>
                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </a>
                @else
                    <span class="text-[11px] text-slate-400 font-medium">All proofs cleared ✓</span>
                @endif
            </div>
        </div>

        {{-- 4. In Packing / Transit --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">In Packing / Transit</span>
                <div class="w-7 h-7 rounded-lg bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
            </div>
            <div class="mt-2 text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                {{ $pendingOrdersCount }}
            </div>
            <div class="mt-1 flex items-center gap-1.5 text-[11px] text-violet-600 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
                <span>Active fulfillment queue</span>
            </div>
        </div>

        {{-- 5. Low Stock Alert --}}
        <div class="p-4 rounded-xl border {{ $lowStockCount > 0 ? 'bg-rose-50/40 border-rose-300 ring-2 ring-rose-100/80' : 'bg-white border-slate-200/90' }} shadow-2xs hover:shadow-xs transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold {{ $lowStockCount > 0 ? 'text-rose-800' : 'text-slate-400' }} uppercase tracking-wider">Low Stock Alert</span>
                <div class="w-7 h-7 rounded-lg {{ $lowStockCount > 0 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-2 text-xl sm:text-2xl font-black {{ $lowStockCount > 0 ? 'text-rose-800' : 'text-slate-900' }} tracking-tight">
                {{ $lowStockCount }}
            </div>
            <div class="mt-1">
                @if($lowStockCount > 0)
                    <a href="{{ route('admin.products.index', ['stock_status' => 'low']) }}" class="inline-flex items-center gap-1 text-[11px] text-rose-800 font-extrabold hover:text-rose-900 group">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Below alert threshold</span>
                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </a>
                @else
                    <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Stocks healthy
                    </span>
                @endif
            </div>
        </div>

    </div>

    {{-- Store Profit Equation Card (Sale Price - Purchase Price = Profit) --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Real-time Formula
                    </span>
                    <span class="text-xs text-slate-500 font-bold">Store Profit Calculation</span>
                </div>
                <h3 class="text-base sm:text-lg font-black tracking-tight text-slate-900 mt-1.5 flex flex-wrap items-center gap-2">
                    <span>Sale Product Price</span>
                    <span class="text-rose-500 font-black">&minus;</span>
                    <span>Purchase Product Price</span>
                    <span class="text-emerald-600 font-black">=</span>
                    <span class="text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded-lg border border-emerald-300 font-extrabold">Net Profit</span>
                </h3>
            </div>

            {{-- Dynamic Equation Breakdown Calculation Block --}}
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 bg-slate-50/90 p-2.5 sm:p-3 rounded-2xl border border-slate-200/90 shadow-inner">
                <div class="text-center px-3 py-1.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <div class="text-[9px] uppercase tracking-wider text-slate-500 font-bold">Sale Revenue</div>
                    <div class="font-black text-slate-900 text-xs sm:text-sm">{{ format_currency($allTimeSaleRevenue) }}</div>
                </div>

                <div class="text-rose-500 font-black text-base px-0.5">&minus;</div>

                <div class="text-center px-3 py-1.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <div class="text-[9px] uppercase tracking-wider text-slate-500 font-bold">Purchase Cost</div>
                    <div class="font-black text-rose-600 text-xs sm:text-sm">{{ format_currency($allTimePurchaseCost) }}</div>
                </div>

                <div class="text-emerald-600 font-black text-base px-0.5">=</div>

                <div class="text-center px-3.5 py-1.5 bg-emerald-600 text-white rounded-xl shadow-xs">
                    <div class="text-[9px] uppercase tracking-wider text-emerald-100 font-bold">Total Profit</div>
                    <div class="font-black text-white text-xs sm:text-sm">{{ format_currency($totalProfit) }}</div>
                </div>

                <div class="pl-2 border-l border-slate-200 flex flex-col justify-center text-left">
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Margin</span>
                    <span class="text-xs font-black text-emerald-700">+{{ $totalProfitMargin }}%</span>
                </div>
            </div>
        </div>

        {{-- Secondary Insight Tiles: Today Realized vs Stock Inventory Potential --}}
        <div class="mt-3.5 pt-3 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-2.5 text-xs">
            <div class="flex flex-wrap items-center justify-between gap-1.5 bg-emerald-50/60 px-3.5 py-2 rounded-xl border border-emerald-200/80">
                <span class="flex items-center gap-2 font-bold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Today's Realized Profit:</span>
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="font-black text-emerald-800 text-xs sm:text-sm">{{ format_currency($todayProfit) }}</span>
                    <span class="text-[10px] text-slate-500 font-medium">({{ format_currency($todaySaleRevenue) }} &minus; {{ format_currency($todayPurchaseCost) }})</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-1.5 bg-sky-50/60 px-3.5 py-2 rounded-xl border border-sky-200/80">
                <span class="flex items-center gap-2 font-bold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    <span>Warehouse Inventory Profit Potential:</span>
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="font-black text-sky-800 text-xs sm:text-sm">{{ format_currency($potentialInventoryProfit) }}</span>
                    <span class="text-[10px] text-slate-500 font-medium">({{ format_currency($totalInventoryValue) }} &minus; {{ format_currency($totalInventoryCost) }})</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Pipeline Strip (At-a-Glance Queue Status) --}}
    @if(isset($pipelineCounts))
    <div class="bg-white rounded-xl border border-slate-200/90 p-3 shadow-2xs">
        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Fulfillment Workflow Pipeline
            </span>
            <span class="text-[10px] text-slate-400">Total Orders: {{ $totalOrdersCount ?? 'All Time' }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs">
            {{-- Stage 1 --}}
            <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" class="p-2 rounded-lg bg-amber-50/60 hover:bg-amber-100/70 border border-amber-200/80 transition flex items-center justify-between sm:flex-col sm:justify-center gap-1">
                <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wide">1. Payment Verification</span>
                <span class="text-sm font-black text-amber-900 bg-white px-2 py-0.5 rounded-md border border-amber-200">{{ $pipelineCounts['pending_verification'] ?? 0 }}</span>
            </a>
            {{-- Stage 2 --}}
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="p-2 rounded-lg bg-sky-50/60 hover:bg-sky-100/70 border border-sky-200/80 transition flex items-center justify-between sm:flex-col sm:justify-center gap-1">
                <span class="text-[10px] font-bold text-sky-800 uppercase tracking-wide">2. Confirmed & Paid</span>
                <span class="text-sm font-black text-sky-900 bg-white px-2 py-0.5 rounded-md border border-sky-200">{{ $pipelineCounts['confirmed'] ?? 0 }}</span>
            </a>
            {{-- Stage 3 --}}
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-2 rounded-lg bg-indigo-50/60 hover:bg-indigo-100/70 border border-indigo-200/80 transition flex items-center justify-between sm:flex-col sm:justify-center gap-1">
                <span class="text-[10px] font-bold text-indigo-800 uppercase tracking-wide">3. Warehouse Packing</span>
                <span class="text-sm font-black text-indigo-900 bg-white px-2 py-0.5 rounded-md border border-indigo-200">{{ $pipelineCounts['processing'] ?? 0 }}</span>
            </a>
            {{-- Stage 4 --}}
            <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" class="p-2 rounded-lg bg-purple-50/60 hover:bg-purple-100/70 border border-purple-200/80 transition flex items-center justify-between sm:flex-col sm:justify-center gap-1">
                <span class="text-[10px] font-bold text-purple-800 uppercase tracking-wide">4. Out for Delivery</span>
                <span class="text-sm font-black text-purple-900 bg-white px-2 py-0.5 rounded-md border border-purple-200">{{ $pipelineCounts['out_for_delivery'] ?? 0 }}</span>
            </a>
            {{-- Stage 5 --}}
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="p-2 rounded-lg bg-emerald-50/60 hover:bg-emerald-100/70 border border-emerald-200/80 transition flex items-center justify-between sm:flex-col sm:justify-center gap-1 col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wide">5. Delivered / Final</span>
                <span class="text-sm font-black text-emerald-900 bg-white px-2 py-0.5 rounded-md border border-emerald-200">{{ $pipelineCounts['delivered'] ?? 0 }}</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Sales Chart & Low Stock Alerts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        {{-- Sales Trend Chart (2 cols) --}}
        <div class="lg:col-span-2 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        7-Day Store Revenue Velocity
                    </h2>
                    <p class="text-[11px] text-slate-400">Total daily confirmed sales volume</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-100">
                        7-Day: RM {{ number_format($sevenDayRevenueTotal ?? array_sum($chartRevenue), 2) }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-50 text-slate-600 border border-slate-200">
                        {{ $sevenDayOrdersTotal ?? array_sum($chartOrders) }} Orders
                    </span>
                </div>
            </div>

            <div class="h-60 relative">
                <canvas id="salesChart"></canvas>
            </div>

            {{-- Compact Chart Summary Footer --}}
            <div class="pt-2 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-[11px]">
                <div class="py-1 bg-slate-50 rounded-lg border border-slate-100">
                    <span class="text-slate-400 block text-[10px]">Avg Daily Revenue</span>
                    <span class="font-bold text-slate-800">RM {{ number_format(($sevenDayRevenueTotal ?? array_sum($chartRevenue)) / 7, 2) }}</span>
                </div>
                <div class="py-1 bg-slate-50 rounded-lg border border-slate-100">
                    <span class="text-slate-400 block text-[10px]">Active Products</span>
                    <span class="font-bold text-slate-800">{{ $totalActiveProducts ?? \App\Models\Product::where('is_active', true)->count() }} SKUs</span>
                </div>
                <div class="py-1 bg-slate-50 rounded-lg border border-slate-100">
                    <span class="text-slate-400 block text-[10px]">Peak 7-Day</span>
                    <span class="font-bold text-emerald-600">RM {{ number_format(max($chartRevenue ?? [0]), 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Inventory Health & Low Stock Products (1 col) --}}
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs flex flex-col justify-between space-y-3">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Inventory Health
                        </h2>
                        <span class="text-[11px] text-slate-400">At or below product alert limit</span>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="text-[11px] font-bold text-sky-600 hover:text-sky-700">
                        View All &rarr;
                    </a>
                </div>

                @if($lowStockProducts->count() > 0)
                    <div class="divide-y divide-slate-100 mt-1">
                        @foreach($lowStockProducts as $p)
                            <div class="py-2.5 flex items-center justify-between gap-2 text-xs">
                                <div class="truncate pr-1">
                                    <a href="{{ route('admin.products.edit', $p->id) }}" class="font-bold text-slate-900 hover:text-sky-600 block truncate" title="{{ $p->name }}">
                                        {{ $p->name }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-slate-400 font-mono text-[10px] mt-0.5">
                                        <span>{{ $p->sku }}</span>
                                        <span>&bull;</span>
                                        <span class="text-slate-500 font-sans font-medium">{{ $p->pack_size }}</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-extrabold shrink-0 {{ $p->stock_quantity <= ($p->low_stock_threshold ?? 10) ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $p->stock_quantity }} left (limit: {{ $p->low_stock_threshold ?? 10 }})
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center flex flex-col items-center justify-center space-y-2">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Warehouse Stocks Optimal</span>
                            <span class="text-[11px] text-slate-400 max-w-[220px] block mt-0.5">All products exceed the minimum safety threshold.</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="pt-2 border-t border-slate-100 text-right">
                <a href="{{ route('admin.products.create') }}" class="text-[11px] font-semibold text-slate-500 hover:text-sky-600 inline-flex items-center gap-1">
                    <span>+ Quick Stock Restock / New SKU</span>
                </a>
            </div>
        </div>

    </div>

    {{-- Recent Orders Table (Compact & High-Density) --}}
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
        
        <div class="p-3.5 sm:p-4 flex items-center justify-between border-b border-slate-100 bg-slate-50/50">
            <div>
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Recent Orders
                </h2>
                <p class="text-[11px] text-slate-400">Latest storefront orders & advance payment submissions</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                <span>View All Orders</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold text-[10px] border-b border-slate-200/80">
                        <th class="py-2.5 px-3.5">Order #</th>
                        <th class="py-2.5 px-3.5">Customer</th>
                        <th class="py-2.5 px-3.5">Delivery & Floor</th>
                        <th class="py-2.5 px-3.5">Grand Total</th>
                        <th class="py-2.5 px-3.5">Advance Paid</th>
                        <th class="py-2.5 px-3.5">Status</th>
                        <th class="py-2.5 px-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            {{-- Order Number --}}
                            <td class="py-2.5 px-3.5 font-mono font-bold text-slate-900 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sky-600 hover:text-sky-700 hover:underline inline-flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                    <span>{{ $order->order_number }}</span>
                                </a>
                                <span class="block text-[10px] font-sans font-normal text-slate-400">{{ $order->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Customer --}}
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[10px] shrink-0 border border-slate-200">
                                        {{ strtoupper(substr($order->customer_name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 leading-tight">{{ $order->customer_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono leading-tight">{{ $order->customer_phone }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Delivery & Floor --}}
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-[11px] font-semibold text-slate-700">
                                    Floor {{ $order->floor }} {{ $order->floor == 0 ? '(Free)' : '' }}
                                </span>
                                <span class="text-[10px] text-slate-400 ml-1">({{ $order->delivery_zone ?? 'Zone A' }})</span>
                            </td>

                            {{-- Grand Total --}}
                            <td class="py-2.5 px-3.5 font-black text-slate-900 whitespace-nowrap">
                                {{ format_currency($order->grand_total) }}
                            </td>

                            {{-- Advance Paid --}}
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <div class="font-bold text-emerald-600">
                                    {{ format_currency($order->advance_amount) }}
                                </div>
                                @if($order->advance_percentage > 0)
                                    <span class="text-[10px] text-slate-400">({{ (int)$order->advance_percentage }}% adv)</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border {{ $order->status_badge_class }}">
                                    @if($order->order_status === 'pending_payment_verification')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    @elseif($order->order_status === 'delivered')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    @endif
                                    {{ $order->status_label }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="py-2.5 px-3.5 text-right whitespace-nowrap">
                                @if($order->order_status === 'pending_payment_verification')
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold rounded-md text-[10px] uppercase tracking-wider transition shadow-2xs">
                                        <span>Verify</span>
                                        <span>&rarr;</span>
                                    </a>
                                @else
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-md text-[10px] transition">
                                        <span>Manage</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <span class="text-xs font-semibold text-slate-500">No orders recorded yet.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Render Sales Chart --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        const currencySymbol = '{{ setting("currency_symbol", "RM") }}';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [{
                    label: 'Revenue (' + currencySymbol + ')',
                    data: {!! json_encode($chartRevenue) !!},
                    backgroundColor: 'rgba(2, 132, 199, 0.85)',
                    hoverBackgroundColor: 'rgba(2, 132, 199, 1)',
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 36,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold', size: 12 },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                        callbacks: {
                            label: function(context) {
                                return ' ' + currencySymbol + ' ' + Number(context.raw).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [3, 3]
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            callback: function(value) { return currencySymbol + ' ' + value; }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
