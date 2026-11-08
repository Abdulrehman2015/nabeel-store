@extends('layouts.admin')

@section('title', 'Sales & Product Performance Reports')

@section('content')
<div class="space-y-6">

    {{-- Top Header & Quick Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 font-extrabold text-[10px] uppercase tracking-wider">
                    Analytics Hub
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">
                    {{ $startDate->format('d M Y') }} &minus; {{ $endDate->format('d M Y') }} ({{ $startDate->diffInDays($endDate) + 1 }} Days)
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                <span>Sales & Product Performance Reports</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Analyze high-selling products, zero/low-movement inventory, profit margins, and tied-up stock capital.
            </p>
        </div>

        {{-- Export Action Button --}}
        <div class="flex items-center gap-2 shrink-0">
            <a 
                href="{{ route('admin.reports.export', request()->all()) }}" 
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition shadow-2xs cursor-pointer"
                title="Export complete report to CSV/Excel spreadsheet"
            >
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export CSV Spreadsheet</span>
            </a>
        </div>
    </div>

    {{-- Filter Toolbar: Preset Pills & Custom Date Range --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
        
        {{-- Quick Preset Pills --}}
        <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1">Period:</span>
                
                @php
                    $presets = [
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        '7days' => 'Last 7 Days',
                        'this_month' => 'This Month',
                        'last_month' => 'Last Month',
                        'this_year' => 'This Year',
                        'all_time' => 'All Time',
                    ];
                @endphp

                @foreach($presets as $key => $label)
                    <a 
                        href="{{ route('admin.reports.index', ['preset' => $key]) }}"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ $preset === $key ? 'bg-sky-600 text-white shadow-2xs' : 'bg-slate-100 hover:bg-slate-200/70 text-slate-700' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="text-[11px] text-slate-400 font-mono">
                Active Filter: <strong class="text-slate-800 font-bold uppercase">{{ str_replace('_', ' ', $preset) }}</strong>
            </div>
        </div>

        {{-- Custom Date Range Form --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end">
            <input type="hidden" name="preset" value="custom">

            <div class="sm:col-span-4 lg:col-span-3">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Start Date</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ $startDate->format('Y-m-d') }}"
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-sky-500 transition"
                    required
                >
            </div>

            <div class="sm:col-span-4 lg:col-span-3">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">End Date</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ $endDate->format('Y-m-d') }}"
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-sky-500 transition"
                    required
                >
            </div>

            <div class="sm:col-span-4 lg:col-span-3">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter Category</label>
                <select 
                    name="category_id" 
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-sky-500 transition"
                >
                    <option value="all">All Categories ({{ $categories->count() }})</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-12 lg:col-span-3 flex items-center gap-2">
                <button 
                    type="submit" 
                    class="flex-1 py-1.5 px-4 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Apply Custom Filter</span>
                </button>

                <a 
                    href="{{ route('admin.reports.index') }}" 
                    class="py-1.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition"
                    title="Reset to default This Month"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- 6 Executive Summary Period KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
        
        {{-- 1. Period Revenue --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-[10px] font-bold uppercase tracking-wider">Period Revenue</span>
                <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">💰</span>
            </div>
            <div class="mt-2 text-xl font-black text-slate-900 tracking-tight">
                {{ format_currency($kpis['total_revenue']) }}
            </div>
            <div class="mt-1 text-[10px] text-slate-500 font-medium">
                Confirmed & Delivered sales
            </div>
        </div>

        {{-- 2. Units Sold --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-[10px] font-bold uppercase tracking-wider">Units Sold</span>
                <span class="w-6 h-6 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs font-bold">📦</span>
            </div>
            <div class="mt-2 text-xl font-black text-sky-700 tracking-tight">
                {{ number_format($kpis['total_units_sold']) }} <span class="text-xs font-bold text-slate-400">items</span>
            </div>
            <div class="mt-1 text-[10px] text-slate-500 font-medium">
                Across all product catalog
            </div>
        </div>

        {{-- 3. Total Cost (COGS) --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-[10px] font-bold uppercase tracking-wider">Purchase Cost (COGS)</span>
                <span class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">🏷️</span>
            </div>
            <div class="mt-2 text-xl font-black text-rose-700 tracking-tight">
                {{ format_currency($kpis['total_cogs']) }}
            </div>
            <div class="mt-1 text-[10px] text-slate-500 font-medium">
                Cost of sold products
            </div>
        </div>

        {{-- 4. Net Profit --}}
        <div class="bg-white p-4 rounded-xl border border-emerald-300 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800">Net Period Profit</span>
                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">📈</span>
            </div>
            <div class="mt-2 text-xl font-black text-emerald-700 tracking-tight">
                {{ format_currency($kpis['gross_profit']) }}
            </div>
            <div class="mt-1 flex items-center gap-1.5">
                <span class="px-1.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                    +{{ $kpis['profit_margin'] }}% Margin
                </span>
            </div>
        </div>

        {{-- 5. Orders & AOV --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-[10px] font-bold uppercase tracking-wider">Completed Orders</span>
                <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">🛒</span>
            </div>
            <div class="mt-2 text-xl font-black text-slate-900 tracking-tight">
                {{ number_format($kpis['total_orders']) }} <span class="text-xs font-bold text-slate-400">orders</span>
            </div>
            <div class="mt-1 text-[10px] text-slate-500 font-medium">
                Avg Order: <strong class="text-slate-800">{{ format_currency($kpis['average_order_value']) }}</strong>
            </div>
        </div>

        {{-- 6. Sales Channel Split --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-[10px] font-bold uppercase tracking-wider">Channel Share</span>
                <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">🏪</span>
            </div>
            <div class="mt-2 text-xs font-bold text-slate-800 space-y-1">
                <div class="flex justify-between">
                    <span>POS Counter:</span>
                    <strong class="text-emerald-700">{{ $channelStats['pos_percentage'] }}%</strong>
                </div>
                <div class="flex justify-between text-[11px] text-slate-500">
                    <span>Online Web:</span>
                    <strong class="text-sky-700">{{ $channelStats['online_percentage'] }}%</strong>
                </div>
            </div>
        </div>

    </div>

    {{-- Cashier Shift & Sales Performance Breakdown --}}
    @if(isset($cashierPerformance) && $cashierPerformance->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                        👤
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-900">Cashier Shift &amp; Sales Performance</h2>
                        <p class="text-[11px] text-slate-500">Sales volume and transaction count attributed to each cashier during selected period.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-full border border-purple-200">
                    {{ $cashierPerformance->count() }} Active Cashiers
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">Cashier / Staff Member</th>
                            <th class="py-2.5 px-3">Role</th>
                            <th class="py-2.5 px-3 text-right">Transactions</th>
                            <th class="py-2.5 px-3 text-right">Period Sales (RM)</th>
                            <th class="py-2.5 px-3 text-right">Avg Sale Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($cashierPerformance as $cashier)
                            @php
                                $avg = $cashier->period_orders_count > 0 ? ($cashier->period_sales_sum / $cashier->period_orders_count) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-2.5 px-3">
                                    <div class="font-bold text-slate-900">{{ $cashier->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $cashier->email }}</div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cashier->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ ucfirst($cashier->role) }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-bold text-slate-800">
                                    {{ number_format($cashier->period_orders_count) }}
                                </td>
                                <td class="py-2.5 px-3 text-right font-black text-slate-900">
                                    RM {{ number_format($cashier->period_sales_sum ?? 0, 2) }}
                                </td>
                                <td class="py-2.5 px-3 text-right text-slate-500 font-medium">
                                    RM {{ number_format($avg, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Main Core Comparison: High Selling vs Least Selling Products --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 1. TOP SELLING PRODUCTS (HIGH VELOCITY) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-base shadow-2xs">
                            🚀
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Top High-Selling Products</h2>
                            <p class="text-[11px] text-slate-500">Highest volume items driving store revenue in this period.</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase">
                        {{ $topSelling->count() }} Top Performers
                    </span>
                </div>

                @if($topSelling->count() > 0)
                    <div class="divide-y divide-slate-100 mt-2">
                        @foreach($topSelling as $index => $item)
                            <div class="py-3 flex items-center justify-between gap-3 group hover:bg-slate-50/60 rounded-xl px-2 transition">
                                
                                {{-- Rank & Image --}}
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center text-xs font-black shrink-0 {{ $index === 0 ? 'bg-amber-100 text-amber-900 border border-amber-300' : ($index === 1 ? 'bg-slate-200 text-slate-800' : ($index === 2 ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-600')) }}">
                                        #{{ $index + 1 }}
                                    </span>

                                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 p-0.5 shrink-0 overflow-hidden flex items-center justify-center">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="max-h-full max-w-full object-contain" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.png') }}';">
                                    </div>

                                    <div class="min-w-0">
                                        <a href="{{ route('admin.products.edit', $item->id) }}" class="font-bold text-xs text-slate-900 hover:text-sky-600 truncate block transition">
                                            {{ $item->name }}
                                        </a>
                                        <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1.5 mt-0.5">
                                            <span>SKU: {{ $item->sku ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span class="text-slate-600">{{ $item->category->name ?? 'Retail' }}</span>
                                            <span>&bull;</span>
                                            <span class="text-emerald-700 font-bold">Stock: {{ $item->stock_quantity }} left</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Sales Quantity & Financials --}}
                                <div class="text-right shrink-0">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 font-black text-xs border border-emerald-200">
                                            {{ $item->period_qty_sold }} sold
                                        </span>
                                    </div>

                                    <div class="mt-1 text-[11px] font-black text-slate-900">
                                        {{ format_currency($item->period_revenue) }}
                                    </div>

                                    <div class="text-[9px] text-emerald-600 font-semibold">
                                        Profit: +{{ format_currency($item->computed_period_profit) }} ({{ $item->computed_profit_margin }}%)
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-slate-400">
                        <div class="text-2xl mb-1">📭</div>
                        <p class="font-bold text-xs text-slate-600">No sales recorded in this date range</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Try selecting a broader date range or "This Month".</p>
                    </div>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Top items contribution:</span>
                <span class="font-bold text-slate-800">
                    {{ $topSelling->sum('period_qty_sold') }} units ({{ format_currency($topSelling->sum('period_revenue')) }})
                </span>
            </div>
        </div>

        {{-- 2. LEAST SELLING PRODUCTS (LOW MOVEMENT & DEAD STOCK) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-800 flex items-center justify-center text-base shadow-2xs">
                            ❄️
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Lowest Selling & Dead Stock</h2>
                            <p class="text-[11px] text-slate-500">Slow-moving items with tied-up inventory capital in this period.</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-black uppercase">
                        Action Required
                    </span>
                </div>

                <div class="divide-y divide-slate-100 mt-2">
                    @foreach($leastSelling as $index => $item)
                        <div class="py-3 flex items-center justify-between gap-3 group hover:bg-slate-50/60 rounded-xl px-2 transition">
                            
                            {{-- Image & Product Info --}}
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 p-0.5 shrink-0 overflow-hidden flex items-center justify-center">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="max-h-full max-w-full object-contain" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.png') }}';">
                                </div>

                                <div class="min-w-0">
                                    <a href="{{ route('admin.products.edit', $item->id) }}" class="font-bold text-xs text-slate-900 hover:text-sky-600 truncate block transition">
                                        {{ $item->name }}
                                    </a>
                                    <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1.5 mt-0.5">
                                        <span>SKU: {{ $item->sku ?? '-' }}</span>
                                        <span>&bull;</span>
                                        <span class="text-slate-600">{{ $item->category->name ?? 'Retail' }}</span>
                                        <span>&bull;</span>
                                        <span class="text-slate-700 font-bold">Price: {{ format_currency($item->price) }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Sold Count & Tied-Up Capital Alert --}}
                            <div class="text-right shrink-0">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($item->period_qty_sold == 0)
                                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-black text-xs border border-rose-200">
                                            0 sold in period
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 font-black text-xs border border-amber-200">
                                            {{ $item->period_qty_sold }} sold
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-1 text-[11px] font-bold text-slate-700">
                                    Stock: <strong class="text-slate-900">{{ $item->stock_quantity }} units</strong>
                                </div>

                                <div class="text-[10px] text-rose-600 font-extrabold">
                                    Tied Up: {{ format_currency($item->tied_up_capital) }}
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Tip for slow movers:</span>
                <span class="font-bold text-amber-800">
                    Bundle or offer discounts to liquidate tied-up stock
                </span>
            </div>
        </div>

    </div>

    {{-- Daily Revenue Trend Bar Chart & Category Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Daily Velocity Bar Chart (8 Cols) --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Period Sales Velocity Trend</h3>
                    <p class="text-[11px] text-slate-400">Daily revenue distribution for selected period.</p>
                </div>
                <span class="text-xs font-mono font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-100">
                    Total: {{ format_currency($kpis['total_revenue']) }}
                </span>
            </div>

            @php
                $maxRev = count($dailyTrend['revenues']) > 0 ? max(max($dailyTrend['revenues']), 1) : 1;
            @endphp

            @if(count($dailyTrend['labels']) > 0 && max($dailyTrend['revenues']) > 0)
                <div class="h-44 flex items-end gap-1.5 sm:gap-2 pt-6 pb-2 overflow-x-auto">
                    @foreach($dailyTrend['labels'] as $i => $lbl)
                        @php
                            $revVal = $dailyTrend['revenues'][$i] ?? 0;
                            $pctHeight = max(6, round(($revVal / $maxRev) * 100));
                        @endphp
                        <div class="flex-1 min-w-[28px] sm:min-w-[36px] flex flex-col items-center gap-1 group relative">
                            {{-- Tooltip --}}
                            <div class="absolute -top-8 hidden group-hover:flex flex-col items-center z-20 pointer-events-none">
                                <span class="bg-slate-900 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow-md whitespace-nowrap">
                                    {{ $lbl }}: {{ format_currency($revVal) }} ({{ $dailyTrend['orders'][$i] ?? 0 }} orders)
                                </span>
                            </div>

                            {{-- Bar --}}
                            <div 
                                class="w-full rounded-t-md transition-all duration-300 {{ $revVal > 0 ? 'bg-sky-500 hover:bg-sky-400 group-hover:scale-y-105' : 'bg-slate-100' }}" 
                                style="height: {{ $pctHeight }}%;"
                            ></div>

                            {{-- Label --}}
                            <span class="text-[9px] font-mono text-slate-400 truncate max-w-[32px] sm:max-w-none">
                                {{ $lbl }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-40 flex items-center justify-center text-slate-400 text-xs">
                    No daily revenue recorded during this period.
                </div>
            @endif
        </div>

        {{-- Category Share Table (4 Cols) --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-3">
            <div class="pb-2 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Category Sales Share</h3>
                <p class="text-[11px] text-slate-400">Revenue contribution by product category.</p>
            </div>

            @if($categoryBreakdown->count() > 0)
                <div class="space-y-2.5 max-h-52 overflow-y-auto pr-1">
                    @foreach($categoryBreakdown as $cat)
                        @php
                            $catPct = $kpis['total_revenue'] > 0 ? round(($cat->category_revenue / $kpis['total_revenue']) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-slate-800">{{ $cat->category_name }}</span>
                                <span class="text-slate-900">{{ format_currency($cat->category_revenue) }} ({{ $catPct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $catPct }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-40 flex items-center justify-center text-slate-400 text-xs">
                    No category sales data for period.
                </div>
            @endif
        </div>

    </div>

    {{-- Comprehensive All-Products Catalog Performance Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden space-y-3 p-5">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Complete Product Sales & Inventory Breakdown</h3>
                <p class="text-xs text-slate-500">Every product's units sold, period revenue, purchase cost, profit, and stock health.</p>
            </div>

            {{-- Table Search & Sorter Form --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="preset" value="{{ $preset }}">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">

                {{-- Search Box --}}
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search product or SKU..."
                        class="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-sky-500"
                    >
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                {{-- Sort Dropdown --}}
                <select 
                    name="sort" 
                    onchange="this.form.submit()" 
                    class="py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-sky-500 cursor-pointer"
                >
                    <option value="qty_desc" {{ request('sort') == 'qty_desc' ? 'selected' : '' }}>Highest Units Sold (Top)</option>
                    <option value="qty_asc" {{ request('sort') == 'qty_asc' ? 'selected' : '' }}>Lowest Units Sold (Least)</option>
                    <option value="rev_desc" {{ request('sort') == 'rev_desc' ? 'selected' : '' }}>Highest Revenue</option>
                    <option value="profit_desc" {{ request('sort') == 'profit_desc' ? 'selected' : '' }}>Highest Profit</option>
                    <option value="tied_up_desc" {{ request('sort') == 'tied_up_desc' ? 'selected' : '' }}>Most Tied-Up Capital</option>
                    <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Lowest Stock Left</option>
                </select>

                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition">
                    Search
                </button>
            </form>
        </div>

        {{-- Performance Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-y border-slate-200/80">
                    <tr>
                        <th class="py-2.5 px-3">#</th>
                        <th class="py-2.5 px-3">Product Name & SKU</th>
                        <th class="py-2.5 px-3">Category</th>
                        <th class="py-2.5 px-3 text-right">Sale Price</th>
                        <th class="py-2.5 px-3 text-right">Cost Price</th>
                        <th class="py-2.5 px-3 text-center">Units Sold</th>
                        <th class="py-2.5 px-3 text-right">Period Revenue</th>
                        <th class="py-2.5 px-3 text-right">Period Profit</th>
                        <th class="py-2.5 px-3 text-center">Margin</th>
                        <th class="py-2.5 px-3 text-center">Current Stock</th>
                        <th class="py-2.5 px-3 text-right">Tied-Up Capital</th>
                        <th class="py-2.5 px-3 text-center">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($productsPerformance as $index => $prod)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 p-0.5 shrink-0 overflow-hidden flex items-center justify-center">
                                        <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="max-h-full max-w-full object-contain" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.png') }}';">
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="font-bold text-slate-900 hover:text-sky-600 truncate block">
                                            {{ $prod->name }}
                                        </a>
                                        <span class="text-[9px] text-slate-400 font-mono">{{ $prod->sku ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-slate-600">
                                {{ $prod->category->name ?? 'Uncategorized' }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                {{ format_currency($prod->price) }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-500">
                                {{ format_currency($prod->purchase_price) }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-full font-black text-xs {{ $prod->period_qty_sold > 0 ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $prod->period_qty_sold }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                {{ format_currency($prod->period_revenue) }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold {{ $prod->computed_period_profit > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                {{ format_currency($prod->computed_period_profit) }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold {{ $prod->computed_profit_margin > 0 ? 'bg-emerald-50 text-emerald-800' : 'text-slate-400' }}">
                                    {{ $prod->computed_profit_margin }}%
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold {{ $prod->stock_quantity <= 10 ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $prod->stock_quantity }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-600">
                                {{ format_currency($prod->tied_up_capital) }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($prod->period_qty_sold >= 5)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-extrabold uppercase tracking-wider">
                                        🚀 High Seller
                                    </span>
                                @elseif($prod->period_qty_sold > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-sky-100 text-sky-800 text-[9px] font-extrabold uppercase tracking-wider">
                                        ⚡ Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-extrabold uppercase tracking-wider">
                                        ❄️ Zero Sales
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 text-center text-slate-400">
                                No products found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

