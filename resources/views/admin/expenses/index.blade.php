@extends('layouts.admin')

@section('title', 'Internal Store Consumption & Staff Expenses')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="expenseModuleApp()">

    {{-- Header & Top Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Store Expenses & Consumption</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-2xs">
                        🥪
                    </span>
                    <span>Store Consumption & Staff Expense</span>
                </h1>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    <svg class="w-3 h-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Auto Stock Deduction</span>
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Log items consumed by staff or owners for eating/drinking or internal store use. Stock is instantly deducted from inventory, and monthly expenses are totaled.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.expenses.export', request()->query()) }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-300 shadow-2xs hover:border-slate-400 transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export CSV</span>
            </a>

            <button 
                type="button" 
                @click="openModal()" 
                class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Record Item Consumption</span>
            </button>
        </div>
    </div>

    {{-- Monthly & Daily KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 1. This Month Total Expense --}}
        <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">This Month's Expense</p>
            <h3 class="text-2xl font-black text-rose-600 mt-1">RM {{ number_format($monthlyTotalCost, 2) }}</h3>
            <p class="text-[10px] text-slate-500 mt-0.5">Total internal consumption value</p>
        </div>

        {{-- 2. This Month Units Consumed --}}
        <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Items Used This Month</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($monthlyTotalQty) }} <span class="text-xs font-semibold text-slate-400">units</span></h3>
            <p class="text-[10px] text-slate-500 mt-0.5">Deducted from store shelves</p>
        </div>

        {{-- 3. Today's Expense --}}
        <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today's Consumption</p>
            <h3 class="text-2xl font-black text-amber-600 mt-1">RM {{ number_format($todayTotalCost, 2) }}</h3>
            <p class="text-[10px] text-slate-500 mt-0.5">{{ number_format($todayTotalQty) }} item(s) logged today</p>
        </div>

        {{-- 4. Quick Action --}}
        <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 text-white shadow-2xs flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Need to record snack/drink?</p>
                <p class="text-xs text-slate-300 mt-1">Deduct water, meals, or store supplies immediately.</p>
            </div>
            <button 
                type="button" 
                @click="openModal()" 
                class="mt-2 py-1.5 px-3 bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold rounded-lg transition text-center cursor-pointer shadow-xs"
            >
                Log Item Now &rarr;
            </button>
        </div>
    </div>

    {{-- Interactive Filter & Search Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
        <form method="GET" action="{{ route('admin.expenses.index') }}" id="filterForm">
            {{-- Row 1: Period Tabs & Month Picker --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Period:</span>
                    
                    <a href="{{ route('admin.expenses.index', array_merge(request()->except(['period', 'month', 'page']), ['period' => 'this_month'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $period === 'this_month' && !$customMonth ? 'bg-sky-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        This Month
                    </a>

                    <a href="{{ route('admin.expenses.index', array_merge(request()->except(['period', 'month', 'page']), ['period' => 'today'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $period === 'today' && !$customMonth ? 'bg-sky-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Today
                    </a>

                    <a href="{{ route('admin.expenses.index', array_merge(request()->except(['period', 'month', 'page']), ['period' => 'yesterday'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $period === 'yesterday' && !$customMonth ? 'bg-sky-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Yesterday
                    </a>

                    <a href="{{ route('admin.expenses.index', array_merge(request()->except(['period', 'month', 'page']), ['period' => 'last_month'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $period === 'last_month' && !$customMonth ? 'bg-sky-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Last Month
                    </a>

                    <a href="{{ route('admin.expenses.index', array_merge(request()->except(['period', 'month', 'page']), ['period' => 'all'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $period === 'all' && !$customMonth ? 'bg-sky-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All Time
                    </a>
                </div>

                {{-- Month Picker --}}
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs font-semibold text-slate-400">Specific Month:</span>
                    <input 
                        type="month" 
                        name="month" 
                        value="{{ $customMonth }}" 
                        onchange="this.form.submit()" 
                        class="text-xs font-bold py-1.5 px-3 border border-slate-300 rounded-xl bg-slate-50/70 text-slate-700 hover:bg-white focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition cursor-pointer"
                        title="Pick a specific month to see expense totals"
                    >
                </div>
            </div>

            {{-- Row 2: Search, Category, Staff & Reset in a Unified Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 pt-0.5 items-center">
                {{-- Search Input (5 cols) --}}
                <div class="sm:col-span-5 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search product, barcode, staff, or reason..." 
                        class="w-full text-xs py-2 pl-9 pr-8 border border-slate-300 rounded-xl bg-slate-50/50 hover:bg-white focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-slate-800 placeholder-slate-400 transition"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @if(request('search'))
                        <a href="{{ route('admin.expenses.index', request()->except(['search', 'page'])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-sm font-bold">&times;</a>
                    @endif
                </div>

                {{-- Category Filter (3 cols) --}}
                <div class="sm:col-span-3">
                    <select name="category" onchange="this.form.submit()" class="w-full text-xs font-bold py-2 px-3 border border-slate-300 rounded-xl bg-slate-50/50 hover:bg-white focus:bg-white focus:border-sky-500 transition cursor-pointer text-slate-700">
                        <option value="all" {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>All Consumption Types</option>
                        <option value="staff_consumption" {{ request('category') == 'staff_consumption' ? 'selected' : '' }}>🥪 Staff Consumption (Khana/Peena)</option>
                        <option value="store_use" {{ request('category') == 'store_use' ? 'selected' : '' }}>🧹 Store Internal Use (Cleaning/Bags)</option>
                        <option value="damaged_expired" {{ request('category') == 'damaged_expired' ? 'selected' : '' }}>⚠️ Damaged / Expired Wastage</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>📦 Other Expenses</option>
                    </select>
                </div>

                {{-- Staff Filter (2 cols) --}}
                <div class="sm:col-span-2">
                    <select name="staff" onchange="this.form.submit()" class="w-full text-xs font-bold py-2 px-3 border border-slate-300 rounded-xl bg-slate-50/50 hover:bg-white focus:bg-white focus:border-sky-500 transition cursor-pointer text-slate-700">
                        <option value="all" {{ !request('staff') || request('staff') == 'all' ? 'selected' : '' }}>All Staff / Users</option>
                        @foreach($staffList as $st)
                            <option value="{{ $st }}" {{ request('staff') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter & Reset Action Buttons (2 cols) --}}
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition cursor-pointer shadow-2xs text-center">
                        Filter
                    </button>
                    @if(request()->hasAny(['category', 'staff', 'search', 'month']) || ($period !== 'this_month' && !request('month')))
                        <a href="{{ route('admin.expenses.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 text-xs font-bold rounded-xl border border-slate-200 transition text-center shrink-0" title="Reset all filters">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Expense Feed & Top Items Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        {{-- Main Table (3 cols) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Recorded Items Consumed</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $expenses->total() }}</span>
                </div>
                <div class="text-[11px] text-slate-500 font-medium">
                    Showing: {{ $period === 'this_month' ? 'Current Month' : ucwords(str_replace('_', ' ', $period)) }}
                </div>
            </div>

            @if($expenses->isEmpty())
                <div class="p-12 text-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-2xl shadow-2xs">
                        🥪
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No Consumed Items Logged</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        No product consumption or staff expenses have been recorded for this period. Click below to record an item used from the store.
                    </p>
                    <button 
                        type="button" 
                        @click="openModal()" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer"
                    >
                        <span>+ Record Consumption</span>
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-100 text-[10px] uppercase font-bold text-slate-500 tracking-wider">
                            <tr>
                                <th class="py-3 px-4">Date & Time</th>
                                <th class="py-3 px-4">Product Consumed</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4 text-center">Qty Deducted</th>
                                <th class="py-3 px-4 text-right">Unit Cost</th>
                                <th class="py-3 px-4 text-right">Total Expense</th>
                                <th class="py-3 px-4">Used By / Note</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($expenses as $exp)
                                @php
                                    $catBadge = match($exp->category) {
                                        'staff_consumption' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'store_use' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'damaged_expired' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    $catLabel = match($exp->category) {
                                        'staff_consumption' => 'Staff Food/Drinks',
                                        'store_use' => 'Store Use',
                                        'damaged_expired' => 'Damaged/Wasted',
                                        default => 'Other',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $exp->expense_date?->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $exp->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900 hover:text-sky-600 transition">
                                            @if($exp->product_id)
                                                <a href="{{ route('admin.products.edit', $exp->product_id) }}">{{ $exp->product_name }}</a>
                                            @else
                                                {{ $exp->product_name }}
                                            @endif
                                        </div>
                                        @if($exp->product_sku)
                                            <div class="text-[10px] font-mono text-slate-400">SKU: {{ $exp->product_sku }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $catBadge }}">
                                            {{ $catLabel }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 font-mono font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200">
                                            -{{ $exp->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-medium text-slate-600">
                                        RM {{ number_format((float) $exp->unit_cost, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                        RM {{ number_format((float) $exp->total_cost, 2) }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-slate-800">{{ $exp->staff_name ?: 'Staff' }}</div>
                                        @if($exp->reason)
                                            <div class="text-[10px] text-slate-500 italic truncate max-w-xs" title="{{ $exp->reason }}">{{ $exp->reason }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.expenses.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Delete this expense? This will restore {{ $exp->quantity }} unit(s) back to inventory stock.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-rose-50 transition cursor-pointer" title="Delete record & restore inventory stock">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $expenses->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Top Consumed Items Sidebar (1 col) --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Top Used Items</h3>
                    <span class="text-[10px] font-bold text-sky-600 uppercase">This Month</span>
                </div>
                
                @if($topConsumed->isEmpty())
                    <p class="text-xs text-slate-400 py-6 text-center">No consumption records this month.</p>
                @else
                    <div class="divide-y divide-slate-100 pt-1">
                        @foreach($topConsumed as $tc)
                            <div class="py-2.5 flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-slate-900 truncate">{{ $tc->product_name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $tc->total_qty }} unit(s) consumed</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="font-mono text-xs font-black text-rose-600">RM {{ number_format((float) $tc->total_spent, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-amber-50/70 rounded-2xl border border-amber-200/80 p-4 shadow-2xs space-y-2">
                <div class="flex items-center gap-2 text-amber-800 font-bold text-xs">
                    <span>💡</span>
                    <span>Inventory Protection</span>
                </div>
                <p class="text-[11px] text-amber-900/80 leading-relaxed">
                    Whenever an item is recorded, it is automatically deducted from current stock. If you ever delete an expense entry, the stock is automatically returned to inventory.
                </p>
            </div>
        </div>
    </div>

    {{-- Record Item Consumption Modal --}}
    <div 
        x-show="modalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        @keydown.escape.window="modalOpen = false"
    >
        <div 
            class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all"
            @click.outside="modalOpen = false"
        >
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">
                        🥪
                    </span>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Record Item Consumption</h3>
                        <p class="text-[11px] text-slate-400">Stock will be automatically deducted from inventory</p>
                    </div>
                </div>
                <button type="button" @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold p-1">&times;</button>
            </div>

            <form action="{{ route('admin.expenses.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                {{-- Product Select --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Select Product *</label>
                    <select 
                        name="product_id" 
                        required 
                        x-model="selectedProductId" 
                        @change="onProductChange()"
                        class="w-full text-xs font-bold py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition"
                    >
                        <option value="">-- Choose Product from Shelf --</option>
                        @foreach($products as $p)
                            <option 
                                value="{{ $p->id }}" 
                                data-cost="{{ $p->purchase_price > 0 ? $p->purchase_price : $p->price }}"
                                data-price="{{ $p->price }}"
                                data-stock="{{ $p->stock_quantity }}"
                            >
                                {{ $p->name }} (Stock: {{ $p->stock_quantity }}) — Cost: RM {{ number_format((float) ($p->purchase_price > 0 ? $p->purchase_price : $p->price), 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Current Stock & Cost Banner --}}
                <div x-show="selectedProductId" class="p-3 rounded-xl bg-sky-50/70 border border-sky-200/80 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-[10px] text-sky-600 font-bold uppercase block">Current Shelf Stock:</span>
                        <span class="font-mono font-black text-sky-900 text-sm" x-text="currentStock + ' units available'"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-sky-600 font-bold uppercase block">Remaining After Deduction:</span>
                        <span class="font-mono font-black text-emerald-700 text-sm" x-text="(currentStock - quantity) + ' units'"></span>
                    </div>
                </div>

                {{-- Quantity & Unit Cost --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Quantity Used *</label>
                        <input 
                            type="number" 
                            name="quantity" 
                            min="1" 
                            max="1000" 
                            required 
                            x-model.number="quantity" 
                            class="w-full text-xs font-bold font-mono py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unit Cost (RM)</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="unit_cost" 
                            x-model.number="unitCost" 
                            placeholder="0.00"
                            class="w-full text-xs font-bold font-mono py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 transition"
                        >
                    </div>
                </div>

                {{-- Consumption Category --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Consumption Type *</label>
                    <select name="category" required x-model="category" class="w-full text-xs font-bold py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 transition">
                        <option value="staff_consumption">🥪 Staff Consumption / Food & Drinks (Khana / Peena)</option>
                        <option value="store_use">🧹 Store Internal Use (Cleaning, Stationery, Bags)</option>
                        <option value="damaged_expired">⚠️ Damaged / Expired Wastage</option>
                        <option value="other">📦 Other</option>
                    </select>
                </div>

                {{-- Staff / Person Name & Date --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Used By (Person Name)</label>
                        <input 
                            type="text" 
                            name="staff_name" 
                            value="{{ auth()->user()->name ?? 'Staff' }}" 
                            placeholder="e.g. Nabeel, Cashier 1"
                            class="w-full text-xs font-medium py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date</label>
                        <input 
                            type="date" 
                            name="expense_date" 
                            value="{{ date('Y-m-d') }}" 
                            class="w-full text-xs font-bold py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 transition"
                        >
                    </div>
                </div>

                {{-- Reason / Note --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason / Note (Optional)</label>
                    <input 
                        type="text" 
                        name="reason" 
                        placeholder="e.g. Lunch drink, tea break, floor cleaning" 
                        class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl bg-slate-50 focus:bg-white focus:border-sky-500 transition"
                    >
                </div>

                {{-- Total Preview Banner --}}
                <div class="p-3 rounded-xl bg-slate-900 text-white flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">Total Expense Value:</span>
                    <span class="text-base font-black font-mono text-emerald-400" x-text="'RM ' + (quantity * unitCost).toFixed(2)"></span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Confirm & Deduct Stock</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function expenseModuleApp() {
    return {
        modalOpen: false,
        selectedProductId: '',
        currentStock: 0,
        quantity: 1,
        unitCost: 0.00,
        category: 'staff_consumption',

        openModal() {
            this.modalOpen = true;
        },

        onProductChange() {
            const selectEl = document.querySelector('select[name="product_id"]');
            const selectedOpt = selectEl ? selectEl.selectedOptions[0] : null;
            if (selectedOpt && selectedOpt.value) {
                this.unitCost = parseFloat(selectedOpt.dataset.cost || 0);
                this.currentStock = parseInt(selectedOpt.dataset.stock || 0);
            } else {
                this.unitCost = 0.00;
                this.currentStock = 0;
            }
        }
    }
}
</script>
@endsection

