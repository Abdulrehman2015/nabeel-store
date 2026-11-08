@extends('layouts.admin')

@section('title', 'Point of Sale (POS) Counter Terminal')

@section('content')
<div 
    x-data="posApp()" 
    x-init="initPos()" 
    @click="handlePosClick($event)"
    @keydown.window="handleGlobalKeydown($event)"
    @keyup.window="handleGlobalKeyup($event)"
    :class="isFullscreen ? 'fixed inset-0 z-50 bg-[#f1f5f9] p-3 flex flex-col h-screen overflow-hidden' : 'flex-1 flex flex-col min-h-0 h-full space-y-2.5 overflow-hidden'"
>

    {{-- POS Terminal Master Top Command Bar (Compact Single-Row) --}}
    <div class="shrink-0 bg-white px-3 sm:px-4 py-2 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-wrap items-center justify-between gap-2.5">
        
        {{-- Left: Register Identity & Cashier Status --}}
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-sky-600 to-sky-700 text-white flex items-center justify-center text-sm shadow-xs shrink-0">
                🛒
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-black text-slate-900 tracking-tight">{{ store_name() }} POS</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Register #1</span>
                </span>
                <span class="text-[11px] font-mono text-slate-400 font-bold hidden sm:inline" x-text="currentTime"></span>
                <span class="text-slate-300 hidden sm:inline">&bull;</span>
                <span class="text-xs text-slate-600 hidden md:inline">Cashier: <strong class="text-slate-800">{{ auth()->user()->name }}</strong></span>
            </div>
        </div>

        {{-- Right: Today's Shift Stats & Control Buttons --}}
        <div class="flex items-center gap-2">
            @if(!$isCashier && $allCashiers->count() > 1)
                {{-- Admin Cashier Switcher Filter --}}
                <div class="hidden lg:flex items-center gap-1.5 bg-slate-50 border border-slate-200/80 rounded-xl px-2 py-0.5">
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase">Cashier:</span>
                    <select 
                        onchange="location.href = '{{ route('admin.pos.index') }}' + (this.value ? '?cashier_id=' + this.value : '')" 
                        class="bg-transparent border-0 text-xs font-bold text-slate-800 py-1 pr-6 focus:ring-0 cursor-pointer"
                    >
                        <option value="">All Cashiers (Total RM {{ number_format($todaySales, 2) }})</option>
                        @foreach($allCashiers as $c)
                            <option value="{{ $c->id }}" {{ request('cashier_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ ucfirst($c->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Today's / Shift Sales Metric & Drawer Summary Trigger --}}
            <button 
                type="button" 
                @click="openShiftSummaryModal()"
                class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 rounded-xl text-left hidden sm:flex items-center gap-1.5 cursor-pointer transition shadow-2xs group"
                title="Click to view Cashier Shift Drawer & Payment Breakdown"
            >
                <span class="text-[9px] font-extrabold text-emerald-700 uppercase tracking-wider">
                    {{ $isCashier ? 'My Shift:' : (request('cashier_id') ? 'Cashier Shift:' : 'Today POS:') }}
                </span>
                <span class="text-xs font-black text-emerald-950 group-hover:underline">RM {{ number_format($todaySales, 2) }}</span>
                <span class="text-[10px] text-emerald-700 font-bold">({{ $todayTransactions }})</span>
                <span class="text-[10px] text-emerald-600 bg-emerald-100/80 px-1 py-0.2 rounded font-bold">Drawer</span>
            </button>

            {{-- Sound Beep Toggle --}}
            <button 
                type="button" 
                @click="soundEnabled = !soundEnabled" 
                :class="soundEnabled ? 'bg-sky-50 text-sky-700 border-sky-200' : 'bg-slate-100 text-slate-400 border-slate-200'"
                class="px-2.5 py-1.5 border rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer shadow-2xs"
                title="Toggle Scanner Sound Feedback"
            >
                <span x-text="soundEnabled ? '🔊' : '🔇'"></span>
                <span class="hidden xl:inline text-[10px]" x-text="soundEnabled ? 'Sound On' : 'Muted'"></span>
            </button>

            {{-- Parked / Held Baskets --}}
            <button 
                type="button" 
                @click="showHeldOrdersModal = true" 
                class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/90 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                title="View Held / Parked Baskets"
            >
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="hidden sm:inline text-[11px]">Parked</span>
                <span class="px-1.5 py-0.2 rounded-full bg-amber-500 text-white text-[9px] font-black" x-text="heldOrders.length"></span>
            </button>

            {{-- Fullscreen Workstation Toggle --}}
            <button 
                type="button" 
                @click="isFullscreen = !isFullscreen" 
                :class="isFullscreen ? 'bg-slate-900 text-white' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200'"
                class="px-2.5 py-1.5 border rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer shadow-2xs"
                title="Toggle Viewport Fullscreen Mode"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                <span class="hidden md:inline text-[11px]" x-text="isFullscreen ? 'Exit Fullscreen' : 'Fullscreen'"></span>
            </button>

            {{-- Thermal Printer Configuration --}}
            <button 
                type="button" 
                @click="showPrinterConfigModal = true"
                class="px-2.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                title="Slip Printer & Thermal Receipt Configuration"
            >
                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span class="hidden md:inline text-[11px]">Print Config</span>
            </button>

            {{-- Windows Desktop App Download Button --}}
            <a 
                href="{{ asset('downloads/N_I_POS_Setup_v1.0.exe') }}" 
                download="N_I_POS_Setup_v1.0.exe"
                class="px-2.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs group"
                title="Download Standalone Windows Desktop App (.exe)"
            >
                <svg class="w-3.5 h-3.5 text-sky-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span class="hidden md:inline text-[11px]">Windows App</span>
            </a>

            {{-- Keyboard Shortcuts Info --}}
            <button 
                type="button" 
                @click="showShortcutsModal = true"
                class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl border border-slate-200 transition cursor-pointer" 
                title="View Keyboard Shortcuts"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </button>
        </div>
    </div>

    {{-- Main POS Dual-Column Workstation (Flex 100% Viewport-Fitted) --}}
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-12 gap-3 items-stretch overflow-hidden">
        
        {{-- ======================================================== --}}
        {{-- LEFT COLUMN: FAST CATALOG & BARCODE SCANNER (7-8 Cols)   --}}
        {{-- ======================================================== --}}
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col min-h-0 h-full overflow-hidden space-y-2.5">
            
            {{-- Search & Category Controls Toolbar --}}
            <div class="shrink-0 bg-white p-3 rounded-2xl border border-slate-200/90 shadow-2xs space-y-2.5">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    
                    {{-- Barcode Scanner & Search Input --}}
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <input 
                            type="text" 
                            x-ref="barcodeInput"
                            x-model="searchQuery" 
                            @keydown.enter="handleBarcodeEnter($event)"
                            placeholder="Scan barcode, SKU or search item (F2 or /)..."
                            class="w-full pl-9 pr-14 py-2 bg-slate-50/80 hover:bg-slate-50 focus:bg-white text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-slate-900 placeholder-slate-400 transition"
                            autofocus
                        >
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center gap-1">
                            <button 
                                type="button" 
                                x-show="searchQuery.length > 0" 
                                @click="searchQuery = ''; $refs.barcodeInput.focus()"
                                class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                                title="Clear Search"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 text-[9px] font-mono text-slate-400 bg-white border border-slate-200 rounded shadow-2xs">F2</kbd>
                        </div>
                    </div>

                    {{-- Professional Category Custom Dropdown Selector with Real Icons --}}
                    <div class="relative sm:w-64 shrink-0" @click.outside="categoryDropdownOpen = false">
                        {{-- Trigger Button --}}
                        <button 
                            type="button" 
                            @click="categoryDropdownOpen = !categoryDropdownOpen" 
                            class="w-full h-10 px-3 bg-white hover:bg-slate-50 border border-slate-200/90 rounded-xl text-xs font-bold transition flex items-center justify-between gap-2 shadow-2xs cursor-pointer focus:outline-hidden focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500"
                            :class="categoryDropdownOpen ? 'ring-2 ring-sky-500/20 border-sky-500 bg-slate-50' : ''"
                            title="Filter by Category"
                        >
                            <div class="flex items-center gap-2 min-w-0">
                                {{-- Category Icon / Thumbnail --}}
                                <template x-if="selectedCategoryObj.id === 'all'">
                                    <div class="w-6 h-6 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0 text-xs shadow-2xs font-extrabold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    </div>
                                </template>
                                <template x-if="selectedCategoryObj.id !== 'all'">
                                    <div class="w-6 h-6 rounded-lg bg-slate-100 border border-slate-200/70 p-0.5 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                        <template x-if="selectedCategoryObj.image_url">
                                            <img :src="selectedCategoryObj.image_url" :alt="selectedCategoryObj.name" class="w-full h-full object-contain">
                                        </template>
                                        <template x-if="!selectedCategoryObj.image_url">
                                            <span class="text-xs">🏷️</span>
                                        </template>
                                    </div>
                                </template>

                                {{-- Category Name --}}
                                <span class="text-slate-900 font-bold truncate text-xs text-left" x-text="selectedCategoryObj.name"></span>
                            </div>

                            {{-- Product Count Badge & Animated Chevron --}}
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-mono font-bold" x-text="selectedCategoryObj.products_count"></span>
                                <svg 
                                    class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" 
                                    :class="categoryDropdownOpen ? 'rotate-180 text-sky-600' : ''"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        {{-- Dropdown Floating Popover Menu --}}
                        <div 
                            x-show="categoryDropdownOpen" 
                            x-cloak 
                            x-transition:enter="transition ease-out duration-150" 
                            x-transition:enter-start="opacity-0 translate-y-1 scale-98" 
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                            x-transition:leave="transition ease-in duration-100" 
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                            x-transition:leave-end="opacity-0 translate-y-1 scale-98" 
                            class="absolute left-0 mt-1.5 w-72 sm:w-80 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 overflow-hidden"
                        >
                            {{-- Search Input inside Dropdown for Fast Cashier Lookup --}}
                            <div class="px-2.5 pb-2 border-b border-slate-100">
                                <div class="relative">
                                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input 
                                        type="text" 
                                        x-model="categoryFilterSearch" 
                                        placeholder="Find category..." 
                                        class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 transition"
                                        @click.stop
                                    >
                                </div>
                            </div>

                            {{-- Categories List --}}
                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-50 p-1 text-xs">
                                {{-- All Categories Option --}}
                                <button 
                                    type="button" 
                                    @click="selectCategory('all')" 
                                    class="w-full px-2.5 py-2 rounded-xl flex items-center justify-between text-left transition cursor-pointer group"
                                    :class="selectedCategory === 'all' ? 'bg-sky-50/90 text-sky-900 font-extrabold ring-1 ring-sky-200' : 'hover:bg-slate-50 text-slate-700'"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0 text-xs shadow-2xs font-extrabold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                        </div>
                                        <div>
                                            <span class="block font-bold leading-tight">All Categories</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5">Show all catalog products</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold" :class="selectedCategory === 'all' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'" x-text="products.length"></span>
                                        <template x-if="selectedCategory === 'all'">
                                            <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                </button>

                                {{-- Category Items with Real Icons --}}
                                <template x-for="cat in dropdownFilteredCategories" :key="cat.id">
                                    <button 
                                        type="button" 
                                        @click="selectCategory(cat.id)" 
                                        class="w-full px-2.5 py-2 rounded-xl flex items-center justify-between text-left transition cursor-pointer group"
                                        :class="String(selectedCategory) === String(cat.id) ? 'bg-sky-50/90 text-sky-900 font-extrabold ring-1 ring-sky-200' : 'hover:bg-slate-50 text-slate-700'"
                                    >
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            {{-- Icon Thumbnail with Rounded Frame --}}
                                            <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200/80 p-0.5 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs group-hover:scale-105 transition-transform">
                                                <template x-if="cat.image_url">
                                                    <img :src="cat.image_url" :alt="cat.name" class="w-full h-full object-contain">
                                                </template>
                                                <template x-if="!cat.image_url">
                                                    <span class="text-xs">🏷️</span>
                                                </template>
                                            </div>

                                            <span class="font-bold truncate text-xs" x-text="cat.name"></span>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold" :class="String(selectedCategory) === String(cat.id) ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'" x-text="cat.products_count"></span>
                                            <template x-if="String(selectedCategory) === String(cat.id)">
                                                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                        </div>
                                    </button>
                                </template>

                                {{-- Empty Search Inside Dropdown --}}
                                <template x-if="dropdownFilteredCategories.length === 0">
                                    <div class="py-4 text-center text-slate-400 text-xs">
                                        No categories matching "<span class="font-bold text-slate-600" x-text="categoryFilterSearch"></span>"
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- In Stock Only Toggle --}}
                    <button 
                        type="button" 
                        @click="inStockOnly = !inStockOnly" 
                        :class="inStockOnly ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-200'"
                        class="px-2.5 py-2 border rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shrink-0 cursor-pointer shadow-2xs"
                        title="Filter Out-of-Stock Items"
                    >
                        <span class="w-2 h-2 rounded-full" :class="inStockOnly ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                        <span class="whitespace-nowrap text-[11px]">In Stock</span>
                    </button>

                    {{-- Sync / Refresh Products Button --}}
                    <button 
                        type="button" 
                        @click="syncProductsFromServer()" 
                        :disabled="isSyncingProducts"
                        class="p-2 bg-slate-100 hover:bg-sky-50 text-slate-600 hover:text-sky-700 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center justify-center shrink-0 cursor-pointer shadow-2xs"
                        :class="isSyncingProducts ? 'animate-spin text-sky-600' : ''"
                        title="Sync latest products from database (No page refresh needed)"
                    >
                        <svg class="w-4 h-4" :class="isSyncingProducts ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>

                    {{-- Quick Add Product Button (Available for Cashier and Admin) --}}
                    <button 
                        type="button" 
                        @click="showQuickProductModal = true" 
                        class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shrink-0 cursor-pointer shadow-md shadow-emerald-600/25"
                        title="Quick Add New Product to Store Catalog"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="whitespace-nowrap text-[11px] hidden sm:inline">Add Product</span>
                    </button>

                    {{-- Staff Consumption / Store Expense Shortcut --}}
                    <a 
                        href="{{ route('admin.expenses.index') }}" 
                        target="_blank"
                        class="px-2.5 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shrink-0 cursor-pointer shadow-md shadow-amber-600/25"
                        title="Record Staff Consumption (Food/Drinks) or Store Expense"
                    >
                        <span>🥪</span>
                        <span class="whitespace-nowrap text-[11px] hidden sm:inline">Staff Use</span>
                    </a>
                </div>

                {{-- Smooth Category Carousel Bar with Left & Right Arrows --}}
                <div class="pt-1.5 border-t border-slate-100 flex items-center gap-1.5">
                    
                    {{-- Carousel Left Scroll Button --}}
                    <button 
                        type="button" 
                        @click="scrollCategoryCarousel(-220)"
                        class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-600 flex items-center justify-center shrink-0 transition cursor-pointer shadow-2xs"
                        title="Scroll Categories Left"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    {{-- Horizontally Scrollable Category Pills --}}
                    <div 
                        x-ref="categoryCarousel"
                        class="flex-1 flex items-center gap-1.5 overflow-x-auto no-scrollbar scroll-smooth py-0.5"
                    >
                        {{-- All Products Pill --}}
                        <button 
                            type="button" 
                            @click="selectCategory('all')" 
                            data-cat-pill="all"
                            :class="selectedCategory === 'all' ? 'bg-slate-900 text-white shadow-xs ring-2 ring-slate-800' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'" 
                            class="px-3 py-1 rounded-xl text-xs font-bold transition shrink-0 cursor-pointer whitespace-nowrap flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>All Products</span>
                            <span class="ml-0.5 text-[10px] opacity-75 font-mono" x-text="'(' + products.length + ')'"></span>
                        </button>

                        {{-- Active Category Pills with Real Icons --}}
                        <template x-for="cat in activeCategories" :key="cat.id">
                            <button 
                                type="button" 
                                @click="selectCategory(cat.id)" 
                                :data-cat-pill="cat.id"
                                :class="String(selectedCategory) === String(cat.id) ? 'bg-sky-600 text-white shadow-xs ring-2 ring-sky-500/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'" 
                                class="px-2.5 py-1 rounded-xl text-xs font-bold transition shrink-0 cursor-pointer flex items-center gap-1.5 whitespace-nowrap group"
                            >
                                <div class="w-4 h-4 rounded-md bg-white/80 p-0.5 flex items-center justify-center shrink-0 overflow-hidden" x-show="cat.image_url">
                                    <img :src="cat.image_url" :alt="cat.name" class="w-full h-full object-contain">
                                </div>
                                <span x-text="cat.name"></span>
                                <span class="text-[10px] opacity-75 font-mono" x-text="'(' + cat.products_count + ')'"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Carousel Right Scroll Button --}}
                    <button 
                        type="button" 
                        @click="scrollCategoryCarousel(220)"
                        class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-600 flex items-center justify-center shrink-0 transition cursor-pointer shadow-2xs"
                        title="Scroll Categories Right"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            {{-- Products Tile Grid (Independently Scrollable within Viewport) --}}
            <div class="flex-1 min-h-0 overflow-y-auto pr-1">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-2.5 pb-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div 
                            @click="addToCart(product)"
                            :class="{
                                'opacity-40 pointer-events-none grayscale': product.stock_quantity <= 0,
                                'ring-2 ring-sky-500/30 border-sky-400 bg-sky-50/15 shadow-sm': getCartItemQty(product.id) > 0,
                                'hover:border-sky-400 hover:shadow-md hover:-translate-y-0.5 cursor-pointer': product.stock_quantity > 0
                            }"
                            class="group bg-white rounded-2xl border border-slate-200/90 p-2.5 flex flex-col justify-between transition-all duration-150 relative select-none"
                        >
                            {{-- Top: SKU & In-Cart / Stock Badges --}}
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <span 
                                    class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 truncate max-w-[85px]" 
                                    x-text="product.sku || 'SKU'"
                                ></span>

                                {{-- "X In Cart" Badge if item is in basket --}}
                                <template x-if="getCartItemQty(product.id) > 0">
                                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full bg-sky-600 text-white shadow-2xs">
                                        <span x-text="getCartItemQty(product.id)"></span> in cart
                                    </span>
                                </template>

                                {{-- Stock Level Badge (when not in cart) --}}
                                <template x-if="getCartItemQty(product.id) === 0">
                                    <span 
                                        class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-full"
                                        :class="product.stock_quantity > 10 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (product.stock_quantity > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200')"
                                        x-text="product.stock_quantity > 0 ? product.stock_quantity + ' stock' : 'Out of Stock'"
                                    ></span>
                                </template>
                            </div>

                            {{-- Product Image & Title --}}
                            <div class="text-center py-0.5">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-xl bg-slate-50/90 border border-slate-100 flex items-center justify-center p-1 mb-1.5 overflow-hidden group-hover:scale-105 transition-transform duration-200">
                                    <img 
                                        :src="product.image_url" 
                                        :alt="product.name" 
                                        class="w-full h-full object-contain rounded-lg"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}';"
                                    >
                                </div>
                                <h3 
                                    class="text-xs font-bold text-slate-900 group-hover:text-sky-600 line-clamp-2 leading-snug min-h-[30px] text-left"
                                    x-text="product.name"
                                ></h3>
                                <p 
                                    class="text-[10px] text-slate-400 truncate mt-0.5 text-left" 
                                    x-text="product.pack_size || product.category_name"
                                ></p>
                            </div>

                            {{-- Bottom: Price & Tactile Add Button --}}
                            <div class="pt-1.5 mt-1 border-t border-slate-100 flex items-center justify-between gap-1">
                                <div>
                                    <div class="text-xs sm:text-sm font-black text-slate-900">
                                        RM <span x-text="product.effective_price.toFixed(2)"></span>
                                    </div>
                                    <template x-if="product.sale_price && product.sale_price < product.price">
                                        <div class="text-[9px] text-slate-400 line-through">
                                            RM <span x-text="product.price.toFixed(2)"></span>
                                        </div>
                                    </template>
                                </div>

                                <button 
                                    type="button" 
                                    class="h-6 px-2 rounded-lg bg-sky-50 group-hover:bg-sky-600 text-sky-700 group-hover:text-white flex items-center justify-center font-bold text-[11px] transition shadow-2xs gap-0.5 cursor-pointer"
                                >
                                    <span>+ Add</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Empty Search Results State --}}
                <div 
                    x-show="filteredProducts.length === 0" 
                    class="bg-white rounded-3xl border border-slate-200/90 p-8 text-center"
                >
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-lg mb-2">
                        🔍
                    </div>
                    <h3 class="text-xs font-bold text-slate-800">No matching products found</h3>
                    <p class="text-[11px] text-slate-500 mt-1 max-w-xs mx-auto">
                        No active items match "<span class="font-bold text-slate-700" x-text="searchQuery"></span>". Try another barcode or keyword.
                    </p>
                    <div class="mt-3.5 flex flex-wrap items-center justify-center gap-2">
                        <button 
                            type="button" 
                            @click="searchQuery = ''; selectedCategory = 'all'" 
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer"
                        >
                            Reset Search Filters
                        </button>
                        <button 
                            type="button" 
                            @click="syncProductsFromServer()" 
                            :disabled="isSyncingProducts"
                            class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 text-xs font-bold rounded-xl transition cursor-pointer flex items-center gap-1.5 shadow-2xs"
                        >
                            <svg class="w-3.5 h-3.5" :class="isSyncingProducts ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span x-text="isSyncingProducts ? 'Syncing...' : 'Sync Products from DB'"></span>
                        </button>
                        <template x-if="searchQuery && searchQuery.trim().length >= 3">
                            <button 
                                type="button" 
                                @click="openQuickProductModalWithBarcode(searchQuery)" 
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition cursor-pointer flex items-center gap-1.5 shadow-xs"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Add Product with Barcode</span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- RIGHT COLUMN: CASHIER REGISTER & ACTIVE CART (4-5 Cols)  --}}
        {{-- 100% VIEWPORT-LOCKED CONTAINER (NEVER SCROLLS BELOW FOLD)--}}
        {{-- ======================================================== --}}
        <div class="lg:col-span-5 xl:col-span-4 flex flex-col min-h-0 h-full overflow-hidden bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-md">
            
            {{-- 1. Customer Selector Banner (Fixed Top) --}}
            <div class="shrink-0 p-2.5 sm:px-3 sm:py-2 bg-slate-50/90 border-b border-slate-200 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 truncate">
                    <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 border border-sky-200 font-bold flex items-center justify-center text-xs shrink-0">
                        👤
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-slate-900 truncate" x-text="customer.name"></div>
                        <div class="text-[10px] text-slate-500 font-mono truncate" x-text="customer.phone || 'Walk-in Retail Shopper'"></div>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <button 
                        type="button" 
                        @click="showCustomerModal = true" 
                        class="px-2 py-0.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 transition cursor-pointer"
                    >
                        Switch
                    </button>
                    <button 
                        type="button" 
                        x-show="customer.id"
                        @click="resetCustomer()" 
                        class="px-1.5 py-0.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 transition cursor-pointer"
                        title="Reset to Walk-in"
                    >
                        Reset
                    </button>
                </div>
            </div>

            {{-- 2. Cart Header & Batch Actions (Fixed Top) --}}
            <div class="shrink-0 px-3 py-1.5 bg-slate-100/70 border-b border-slate-200/80 flex items-center justify-between text-xs font-bold text-slate-700">
                <div class="flex items-center gap-1.5">
                    <span>Active Basket</span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-200/90 text-slate-800 text-[10px] font-black" x-text="totalItemCount + ' items'"></span>
                    <span class="text-[9px] text-slate-400 font-normal hidden xl:inline">
                        &bull; Hold <kbd class="px-1 py-0.5 bg-white border border-slate-200 rounded font-mono text-[9px] text-slate-700 font-bold">Ctrl</kbd> + digits / <kbd class="px-1 py-0.5 bg-white border border-slate-200 rounded font-mono text-[9px] text-slate-700 font-bold">&uarr;&darr;</kbd> for qty
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="holdCurrentOrder()" 
                        :disabled="cart.length === 0"
                        class="text-[10px] text-amber-700 hover:text-amber-800 disabled:opacity-40 disabled:pointer-events-none font-semibold cursor-pointer"
                    >
                        Hold Basket
                    </button>
                    <span class="text-slate-300">|</span>
                    <button 
                        type="button" 
                        @click="clearCart()" 
                        :disabled="cart.length === 0"
                        class="text-[10px] text-rose-600 hover:text-rose-700 disabled:opacity-40 disabled:pointer-events-none font-semibold cursor-pointer"
                    >
                        Clear All
                    </button>
                </div>
            </div>

            {{-- 3. Cart Items Scrollable List (FLEX-1: SCROLLS INSIDE ITS CONTAINER) --}}
            <div class="flex-1 min-h-0 overflow-y-auto divide-y divide-slate-100 p-2 text-xs">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div 
                        :data-cart-item="index"
                        @click="if (selectedCartIndex === index) { openItemDiscountModal(index); } else { selectedCartIndex = index; }"
                        @dblclick="openItemDiscountModal(index)"
                        :class="getActiveCartIndex() === index 
                            ? 'bg-sky-50/90 ring-2 ring-sky-500/60 border-sky-300 shadow-xs' 
                            : 'hover:bg-slate-50/80 border-slate-100/80'"
                        class="p-1.5 rounded-xl border transition-all flex items-center justify-between gap-2 cursor-pointer relative"
                        title="Click to select or edit discount (Ctrl+Enter)"
                    >
                        
                        {{-- Small Item Thumbnail --}}
                        <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-0.5 shrink-0 overflow-hidden">
                            <img :src="item.image_url" :alt="item.name" class="w-full h-full object-contain" onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}';">
                        </div>

                        {{-- Item Name & Price --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-bold text-slate-900 truncate text-[11px]" x-text="item.name"></span>
                                <span x-show="getActiveCartIndex() === index" class="text-[8px] font-black uppercase tracking-wider text-sky-700 bg-sky-200/90 px-1 py-0.2 rounded shrink-0">Selected</span>
                                <button 
                                    type="button" 
                                    @click.stop="openItemDiscountModal(index)" 
                                    class="px-1.5 py-0.2 rounded text-[8px] font-bold border transition flex items-center gap-0.5 cursor-pointer shrink-0"
                                    :class="item.discount_price !== null ? 'bg-rose-100 text-rose-800 border-rose-300' : 'bg-slate-100 hover:bg-sky-100 text-slate-600 border-slate-200'"
                                    title="Set discount for this product (Ctrl+Enter)"
                                >
                                    <span>🏷️</span>
                                    <span x-text="item.discount_price !== null ? 'RM ' + Number(item.discount_price).toFixed(2) : 'Discount'"></span>
                                </button>
                            </div>
                            <div class="text-[9px] font-mono flex items-center gap-1.5 mt-0.5">
                                <template x-if="item.discount_price !== null && Number(item.discount_price) < Number(item.original_price || item.price)">
                                    <span class="flex items-center gap-1">
                                        <span class="line-through text-slate-400">RM <span x-text="Number(item.original_price).toFixed(2)"></span></span>
                                        <span class="text-rose-600 font-bold">RM <span x-text="Number(item.discount_price).toFixed(2)"></span></span>
                                    </span>
                                </template>
                                <template x-if="item.discount_price === null || Number(item.discount_price) >= Number(item.original_price || item.price)">
                                    <span class="text-slate-500">RM <span x-text="Number(item.price).toFixed(2)"></span></span>
                                </template>
                                <span x-show="item.sku" class="text-slate-300">&bull;</span>
                                <span x-show="item.sku" class="truncate max-w-[70px] text-slate-400" x-text="item.sku"></span>
                            </div>
                        </div>

                        {{-- Quantity Stepper --}}
                        <div class="flex items-center border border-slate-200 rounded-lg bg-white overflow-hidden shrink-0 shadow-2xs">
                            <button 
                                type="button" 
                                @click.stop="decrementQty(index)" 
                                class="w-5 h-5 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold text-xs cursor-pointer"
                            >
                                -
                            </button>
                            <input 
                                type="number" 
                                min="1" 
                                :max="item.max_stock"
                                x-model.number="item.quantity" 
                                @change="validateQty(index)"
                                @click.stop=""
                                class="w-7 h-5 text-center text-xs font-bold border-0 p-0 focus:ring-0 text-slate-900 bg-transparent"
                            >
                            <button 
                                type="button" 
                                @click.stop="incrementQty(index)" 
                                class="w-5 h-5 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold text-xs cursor-pointer"
                            >
                                +
                            </button>
                        </div>

                        {{-- Line Total & Trash Action --}}
                        <div class="text-right shrink-0 min-w-[60px]">
                            <template x-if="item.discount_price !== null && Number(item.discount_price) < Number(item.original_price || item.price)">
                                <span class="line-through text-slate-400 text-[9px] block">RM <span x-text="(Number(item.original_price) * item.quantity).toFixed(2)"></span></span>
                            </template>
                            <div class="font-black text-[11px]" :class="item.discount_price !== null ? 'text-rose-600' : 'text-slate-900'">
                                RM <span x-text="(item.price * item.quantity).toFixed(2)"></span>
                            </div>
                            <button 
                                type="button" 
                                @click.stop="removeItem(index)" 
                                class="text-[9px] text-rose-500 hover:text-rose-700 cursor-pointer font-medium"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Empty Register Placeholder --}}
                <div x-show="cart.length === 0" class="py-8 text-center text-slate-400">
                    <div class="text-2xl mb-1">🛒</div>
                    <p class="font-bold text-xs text-slate-600">Basket is empty</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Scan a barcode or tap products to build order.</p>
                </div>
            </div>

            {{-- 4. Financials Summary (Fixed Bottom Section) --}}
            <div class="shrink-0 p-2.5 bg-slate-50/90 border-t border-slate-200 space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-600 text-[11px]">
                    <span>Subtotal:</span>
                    <span class="font-bold text-slate-900">RM <span x-text="subtotal.toFixed(2)"></span></span>
                </div>

                {{-- Discount Row --}}
                <div class="flex items-center justify-between text-slate-600 text-[11px]">
                    <span>Discount (RM):</span>
                    <div class="flex items-center gap-1">
                        <template x-if="discountAmount > 0">
                            <button 
                                type="button" 
                                @click="discountAmount = 0; amountTendered = grandTotal" 
                                class="text-[9px] text-rose-500 hover:underline cursor-pointer"
                            >
                                Reset
                            </button>
                        </template>
                        <input 
                            type="number" 
                            min="0" 
                            step="0.50" 
                            x-model.number="discountAmount" 
                            @input="if (paymentMethod === 'cash') amountTendered = grandTotal"
                            placeholder="0.00"
                            class="w-16 text-right text-[11px] font-bold py-0.5 px-1.5 border-slate-300 rounded-md bg-white"
                        >
                    </div>
                </div>

                {{-- High-Contrast Grand Total Banner --}}
                <div class="p-2.5 bg-slate-900 text-white rounded-xl flex items-baseline justify-between shadow-xs">
                    <div>
                        <div class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Total Payable</div>
                        <div class="text-[9px] text-emerald-400 font-medium">{{ store_name() }} Cash Register</div>
                    </div>
                    <div class="text-xl font-black text-lime-400 tracking-tight">
                        RM <span x-text="grandTotal.toFixed(2)"></span>
                    </div>
                </div>
            </div>

            {{-- 5. Payment Engine & Complete Sale CTA (Anchored Bottom within Viewport) --}}
            <div class="shrink-0 p-2.5 bg-white border-t border-slate-200 space-y-2">
                <div class="grid grid-cols-3 gap-1 p-0.5 bg-slate-100 rounded-xl text-xs font-bold">
                    <button 
                        type="button" 
                        @click="paymentMethod = 'cash'; amountTendered = grandTotal" 
                        :class="paymentMethod === 'cash' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-900'"
                        class="py-1 rounded-lg transition text-center cursor-pointer flex items-center justify-center gap-1 text-[11px]"
                    >
                        <span>💵</span>
                        <span>Cash</span>
                    </button>
                    <button 
                        type="button" 
                        @click="paymentMethod = 'duitnow_qr'" 
                        :class="paymentMethod === 'duitnow_qr' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-900'"
                        class="py-1 rounded-lg transition text-center cursor-pointer flex items-center justify-center gap-1 text-[11px]"
                    >
                        <span>📱</span>
                        <span>DuitNow QR</span>
                    </button>
                    <button 
                        type="button" 
                        @click="paymentMethod = 'card'" 
                        :class="paymentMethod === 'card' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-900'"
                        class="py-1 rounded-lg transition text-center cursor-pointer flex items-center justify-center gap-1 text-[11px]"
                    >
                        <span>💳</span>
                        <span>Card</span>
                    </button>
                </div>

                {{-- 1. CASH PAYMENT CONTROLS --}}
                <div x-show="paymentMethod === 'cash'" class="space-y-1.5">
                    <div class="flex items-center justify-between gap-2">
                        <label class="text-[11px] font-bold text-slate-700">Cash Tendered:</label>
                        <div class="relative w-32">
                            <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-[10px] font-bold text-slate-400">RM</span>
                            <input 
                                type="number" 
                                min="0" 
                                step="0.50" 
                                x-model.number="amountTendered"
                                class="w-full pl-7 pr-1.5 py-1 text-right font-black text-xs border-slate-300 rounded-lg focus:ring-sky-500 bg-slate-50/50"
                            >
                        </div>
                    </div>

                    {{-- Dynamic & Preset Cash Chips --}}
                    <div class="grid grid-cols-4 gap-1">
                        <button 
                            type="button" 
                            @click="amountTendered = grandTotal" 
                            class="py-0.5 px-1 text-[10px] font-extrabold bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-md transition cursor-pointer"
                        >
                            Exact
                        </button>
                        <template x-for="preset in cashPresets" :key="preset">
                            <button 
                                type="button" 
                                @click="amountTendered = preset" 
                                class="py-0.5 px-1 text-[10px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md transition cursor-pointer"
                                x-text="'RM ' + preset"
                            ></button>
                        </template>
                    </div>

                    {{-- Change Due Banner --}}
                    <div 
                        class="p-2 rounded-xl border flex items-center justify-between transition-colors text-xs"
                        :class="changeDue >= 0 ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900'"
                    >
                        <span class="text-[10px] font-extrabold uppercase" x-text="changeDue >= 0 ? 'Change Due:' : 'Short Amount:'"></span>
                        <span class="text-sm font-black">
                            RM <span x-text="Math.abs(changeDue).toFixed(2)"></span>
                        </span>
                    </div>
                </div>

                {{-- 2. DUITNOW QR CONTROLS --}}
                <div x-show="paymentMethod === 'duitnow_qr'" class="p-2.5 bg-sky-50 border border-sky-200 rounded-xl text-center space-y-1.5">
                    <div class="text-[11px] font-extrabold text-sky-950">Customer DuitNow / TNG QR Transfer</div>
                    <button 
                        type="button" 
                        @click="showQrModal = true" 
                        class="px-3 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1 mx-auto shadow-xs cursor-pointer"
                    >
                        <span>Display Counter QR Code</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </button>
                </div>

                {{-- 3. CARD CONTROLS --}}
                <div x-show="paymentMethod === 'card'" class="p-2 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-xs">
                    <span class="font-bold text-slate-700 text-[10px]">Card Slip Auth Ref (Optional)</span>
                    <input 
                        type="text" 
                        x-model="paymentReference" 
                        placeholder="e.g. Visa/Master Auth #98213" 
                        class="w-full text-xs border-slate-300 rounded-lg py-1 px-2"
                    >
                </div>

                {{-- Primary Complete Sale Button (ALWAYS VISIBLE IN VIEWPORT) --}}
                <button 
                    type="button" 
                    @click="processCheckout()" 
                    :disabled="cart.length === 0 || isProcessing || (paymentMethod === 'cash' && changeDue < 0)"
                    class="w-full py-2.5 px-3 bg-emerald-600 hover:bg-emerald-500 active:scale-98 disabled:opacity-50 disabled:pointer-events-none text-white rounded-xl text-xs sm:text-sm font-black shadow-md shadow-emerald-600/25 flex items-center justify-center gap-2 transition cursor-pointer"
                >
                    <template x-if="!isProcessing">
                        <span class="flex items-center gap-1.5">
                            <span>Complete Sale & Print Receipt</span>
                            <span class="text-[10px] opacity-75 font-mono">(F4)</span>
                        </span>
                    </template>
                    <template x-if="isProcessing">
                        <span class="flex items-center gap-1.5">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Processing...</span>
                        </span>
                    </template>
                </button>
            </div>
        </div>

    </div>

    {{-- ======================================================== --}}
    {{-- POST-SALE SUCCESS & THERMAL RECEIPT MODAL               --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showReceiptModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="reEditCurrentOrder()"
            class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4 animate-in fade-in zoom-in-95 duration-150 relative"
        >
            <button 
                type="button" 
                @click="reEditCurrentOrder()" 
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 transition cursor-pointer"
                title="Re-Edit Order (Esc)"
            >
                ✕
            </button>

            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center text-2xl shadow-xs">
                ✓
            </div>

            <div>
                <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 bg-emerald-50 text-emerald-800 rounded-full border border-emerald-200">
                    Payment Verified & Stock Deducted
                </span>
                <h3 class="text-xl font-black text-slate-900 mt-2">Sale Completed!</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Order #: <span class="font-bold text-slate-800" x-text="completedOrder.order_number"></span></p>
            </div>

            {{-- Financial Summary Box --}}
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Total Amount Paid:</span>
                    <span class="font-bold text-slate-900">RM <span x-text="completedOrder.grand_total ? Number(completedOrder.grand_total).toFixed(2) : '0.00'"></span></span>
                </div>
                <template x-if="completedOrder.payment_method === 'cash'">
                    <div>
                        <div class="flex justify-between text-slate-600">
                            <span>Cash Tendered:</span>
                            <span class="font-semibold text-slate-800">RM <span x-text="Number(completedOrder.tendered).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-emerald-700 font-black text-sm pt-1 border-t border-slate-200">
                            <span>Change Due:</span>
                            <span>RM <span x-text="Number(completedOrder.change_due).toFixed(2)"></span></span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-2.5 pt-2">
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        type="button" 
                        @click="printReceiptAndNext(completedOrder.receipt_url)" 
                        class="py-2.5 px-4 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center gap-1.5 transition cursor-pointer"
                        title="Print Thermal Receipt (Enter)"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print Receipt</span>
                        <span class="text-[10px] text-sky-200 font-mono font-bold">(Enter)</span>
                    </button>

                    <button 
                        type="button" 
                        @click="resetForNextSale()"
                        class="py-2.5 px-4 bg-slate-900 hover:bg-slate-800 active:scale-98 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                        title="New Order Entry (Space)"
                    >
                        <span>Next Customer</span>
                        <span class="text-[10px] text-slate-400 font-mono font-bold">(Space)</span>
                    </button>
                </div>

                {{-- Re-Edit Order Button --}}
                <button 
                    type="button" 
                    @click="reEditCurrentOrder()"
                    class="w-full py-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-900 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs"
                    title="Re-open order for editing (Esc)"
                >
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Re-Edit This Order</span>
                    <span class="text-[10px] text-amber-600 font-mono font-bold">(Esc)</span>
                </button>

                {{-- Quick Config Link & Active Printer Status --}}
                <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1.5 border-t border-slate-100">
                    <button 
                        type="button" 
                        @click="showPrinterConfigModal = true" 
                        class="text-sky-600 hover:text-sky-700 font-semibold flex items-center gap-1 cursor-pointer transition hover:underline"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Printer Settings</span>
                    </button>
                    <span class="font-mono text-[10px] text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md" x-text="printerConfig.paperWidth + (printerConfig.autoPrint ? ' • Auto-Print ON' : '')"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- SINGLE ITEM DISCOUNT MODAL (Ctrl + Enter / Item Click)   --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showItemDiscountModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
        @keydown.escape.window="showItemDiscountModal = false; $nextTick(() => focusBarcodeInput())"
    >
        <div 
            @click.outside="showItemDiscountModal = false; $nextTick(() => focusBarcodeInput())"
            class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in-95 duration-150 relative"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-black shrink-0">
                        🏷️
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-black text-slate-900">Item Discount</h3>
                        <p class="text-[10px] text-slate-400">Set custom / promo price for this product</p>
                    </div>
                </div>
                <button type="button" @click="showItemDiscountModal = false; $nextTick(() => focusBarcodeInput())" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg cursor-pointer">✕</button>
            </div>

            {{-- Selected Product Snapshot Card --}}
            <template x-if="discountModalItem">
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2 text-left">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-900 text-xs truncate" x-text="discountModalItem.name"></h4>
                            <p class="text-[10px] text-slate-500 font-mono" x-text="(discountModalItem.sku ? discountModalItem.sku + ' • ' : '') + (discountModalItem.pack_size || '')"></p>
                        </div>
                        <span class="px-2 py-0.5 bg-sky-100 text-sky-800 rounded-md font-mono font-bold text-[10px] shrink-0">
                            Qty: <span x-text="discountModalItem.quantity"></span>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/70 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-500 block">Original Unit Price:</span>
                            <span class="font-bold text-slate-800">RM <span x-text="Number(discountModalItem.original_price || discountModalItem.price).toFixed(2)"></span></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-500 block">Original Total Price:</span>
                            <span class="font-bold text-slate-800">RM <span x-text="((discountModalItem.original_price || discountModalItem.price) * discountModalItem.quantity).toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Discount Input Field --}}
            <div class="space-y-1.5 text-left">
                <label class="block text-xs font-bold text-slate-800">
                    Discount Price (Per Item / Unit):
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-black text-slate-400">RM</span>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0"
                        x-ref="discountPriceInput"
                        x-model="discountModalInput" 
                        @keydown.enter.prevent="applyItemDiscount()"
                        placeholder="e.g. 5.00" 
                        class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                    >
                </div>
            </div>

            {{-- Live Calculation Preview Box --}}
            <template x-if="discountModalItem && discountModalInput !== '' && !isNaN(parseFloat(discountModalInput))">
                <div class="p-2.5 bg-emerald-50/70 border border-emerald-200 rounded-xl space-y-1 text-xs text-left">
                    <div class="flex justify-between text-emerald-900">
                        <span>New Line Total:</span>
                        <span class="font-black">RM <span x-text="(parseFloat(discountModalInput) * discountModalItem.quantity).toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-emerald-700 text-[11px]">
                        <span>Customer Savings:</span>
                        <span class="font-bold">-RM <span x-text="Math.max(0, ((discountModalItem.original_price || discountModalItem.price) - parseFloat(discountModalInput)) * discountModalItem.quantity).toFixed(2)"></span></span>
                    </div>
                </div>
            </template>

            {{-- Action Buttons --}}
            <div class="space-y-2 pt-1">
                <button 
                    type="button" 
                    @click="applyItemDiscount()"
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <span>Apply Discount</span>
                    <span class="text-[10px] text-emerald-200 font-mono font-bold">(Enter)</span>
                </button>

                <div class="grid grid-cols-2 gap-2">
                    <button 
                        type="button" 
                        @click="resetItemDiscount()"
                        class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer"
                    >
                        Reset to Original
                    </button>
                    <button 
                        type="button" 
                        @click="showItemDiscountModal = false; $nextTick(() => focusBarcodeInput())"
                        class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer"
                    >
                        Cancel (Esc)
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- CUSTOMER SELECTION / QUICK ADD MODAL                     --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showCustomerModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="showCustomerModal = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Select Customer</h3>
                <button type="button" @click="showCustomerModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            {{-- Existing Customer Search --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Search Existing Customers</label>
                <input 
                    type="text" 
                    x-model="customerSearch" 
                    placeholder="Search by name, phone or email..." 
                    class="w-full text-xs rounded-xl border-slate-300"
                >
                <div class="max-h-40 overflow-y-auto mt-2 divide-y divide-slate-100 border border-slate-200 rounded-xl">
                    <template x-for="c in filteredCustomers" :key="c.id">
                        <div 
                            @click="selectCustomer(c)" 
                            class="p-2 hover:bg-sky-50 text-xs flex justify-between items-center cursor-pointer"
                        >
                            <div>
                                <span class="font-bold text-slate-900" x-text="c.name"></span>
                                <span class="text-[10px] text-slate-400 block" x-text="c.phone"></span>
                            </div>
                            <span class="text-sky-600 font-bold text-[11px]">Select &rarr;</span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Quick Create Customer Form --}}
            <div class="pt-3 border-t border-slate-100">
                <span class="block text-xs font-bold text-slate-700 uppercase mb-2">Or Register New Customer</span>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" x-model="newCustomer.name" placeholder="Full Name *" class="text-xs border-slate-300 rounded-xl">
                    <input type="text" x-model="newCustomer.phone" placeholder="Phone # *" class="text-xs border-slate-300 rounded-xl">
                </div>
                <button 
                    type="button" 
                    @click="createQuickCustomer()" 
                    class="mt-2 w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition"
                >
                    + Register & Select
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- DUITNOW QR DISPLAY MODAL                                 --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showQrModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="showQrModal = false"
            class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4"
        >
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">DuitNow QR Counter Display</h3>
                <button type="button" @click="showQrModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 inline-block">
                @if(setting('duitnow_qr_image'))
                    <img src="{{ asset('storage/' . setting('duitnow_qr_image')) }}" alt="DuitNow QR" class="w-48 h-48 object-contain mx-auto">
                @else
                    <div class="w-48 h-48 bg-white border border-slate-300 flex items-center justify-center text-slate-400 text-xs text-center p-4">
                        Upload DuitNow QR image in Settings &gt; Payment &amp; QR
                    </div>
                @endif
            </div>

            <div class="text-xs text-slate-600">
                <p class="font-bold text-slate-900">{{ store_name() }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Ask customer to scan with Touch 'n Go, Maybank, or any banking app.</p>
                <div class="text-xl font-black text-sky-700 mt-2">
                    RM <span x-text="grandTotal.toFixed(2)"></span>
                </div>
            </div>

            <button 
                type="button" 
                @click="showQrModal = false" 
                class="w-full py-2 bg-slate-900 text-white rounded-xl text-xs font-bold"
            >
                Done / Received
            </button>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- HELD / PARKED BASKETS MODAL                              --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showHeldOrdersModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="showHeldOrdersModal = false"
            class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4"
        >
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Held / Parked Customer Baskets</h3>
                <button type="button" @click="showHeldOrdersModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="space-y-2 max-h-60 overflow-y-auto divide-y divide-slate-100">
                <template x-for="(basket, idx) in heldOrders" :key="idx">
                    <div class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl flex items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="font-bold text-slate-900" x-text="basket.customer.name"></span>
                            <div class="text-[10px] text-slate-400">
                                <span x-text="basket.items.length + ' items'"></span> &bull; 
                                <span class="font-semibold text-slate-700">RM <span x-text="basket.grandTotal.toFixed(2)"></span></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @click="restoreHeldOrder(idx)" 
                                class="px-3 py-1 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-lg text-xs"
                            >
                                Restore
                            </button>
                            <button 
                                type="button" 
                                @click="deleteHeldOrder(idx)" 
                                class="text-rose-500 hover:text-rose-700 p-1"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="heldOrders.length === 0" class="py-8 text-center text-slate-400 text-xs">
                    No held baskets parked at the moment.
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- KEYBOARD SHORTCUTS MODAL                                 --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showShortcutsModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="showShortcutsModal = false"
            class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4"
        >
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Cashier Keyboard Shortcuts</h3>
                <button type="button" @click="showShortcutsModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <span class="text-slate-600">Focus Barcode / Search</span>
                    <kbd class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-700 font-mono font-bold">F2 or /</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <div>
                        <span class="text-slate-800 font-semibold block">Set Quantity (e.g. 12)</span>
                        <span class="text-[10px] text-slate-400">Hold Ctrl and type numbers</span>
                    </div>
                    <kbd class="px-2 py-0.5 bg-sky-50 border border-sky-200 text-sky-800 rounded font-mono font-bold text-[11px]">Ctrl + 1, 2...</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <div>
                        <span class="text-slate-800 font-semibold block">Increase / Decrease Qty</span>
                        <span class="text-[10px] text-slate-400">Step active item quantity</span>
                    </div>
                    <kbd class="px-2 py-0.5 bg-sky-50 border border-sky-200 text-sky-800 rounded font-mono font-bold text-[11px]">Ctrl + &uarr; / &darr;</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <span class="text-slate-600">Select Previous / Next Item</span>
                    <kbd class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-700 font-mono font-bold">Alt + &uarr; / &darr;</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <div>
                        <span class="text-slate-800 font-semibold block">Discount on Selected Item</span>
                        <span class="text-[10px] text-slate-400">Set custom promo price</span>
                    </div>
                    <kbd class="px-2 py-0.5 bg-rose-50 border border-rose-200 text-rose-800 rounded font-mono font-bold text-[11px]">Ctrl + Enter</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <span class="text-slate-600">Complete Sale / Checkout</span>
                    <kbd class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-700 font-mono font-bold">F4</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <span class="text-slate-600">Print Receipt (On Completed Modal)</span>
                    <kbd class="px-2 py-0.5 bg-sky-50 border border-sky-200 text-sky-800 rounded font-mono font-bold">Enter</kbd>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100">
                    <span class="text-slate-600">Next Customer / Order Entry</span>
                    <kbd class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-700 font-mono font-bold">Space</kbd>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-slate-600">Re-Edit Order (or Close Modal)</span>
                    <kbd class="px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-800 rounded font-mono font-bold">Esc</kbd>
                </div>
            </div>

            <button type="button" @click="showShortcutsModal = false" class="w-full py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">
                Got It
            </button>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- THERMAL PRINTER & RECEIPT CONFIGURATION MODAL            --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showPrinterConfigModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
    >
        <div 
            @click.outside="showPrinterConfigModal = false"
            class="bg-white rounded-3xl max-w-xl w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 my-8"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center font-bold text-base shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-extrabold text-slate-900">POS Slip Printer Configuration</h3>
                        <p class="text-[11px] text-slate-500">Configure thermal receipt printer machine, paper size & auto-print settings.</p>
                    </div>
                </div>
                <button type="button" @click="showPrinterConfigModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1 text-xs">
                
                {{-- 1. Paper Width Selection (80mm vs 58mm) --}}
                <div class="space-y-1.5">
                    <label class="block text-slate-700 font-extrabold">Thermal Paper Roll Width</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button 
                            type="button" 
                            @click="printerConfig.paperWidth = '80mm'"
                            class="p-3 rounded-2xl border text-left transition flex items-center justify-between cursor-pointer"
                            :class="printerConfig.paperWidth === '80mm' ? 'bg-sky-50/90 border-sky-400 ring-2 ring-sky-400/30' : 'bg-slate-50 border-slate-200 hover:bg-white'"
                        >
                            <div>
                                <span class="font-extrabold text-slate-900 block text-xs">80mm (Standard)</span>
                                <span class="text-[10px] text-slate-500 block mt-0.5">Most common POS thermal printers (Epson, Sunmi, Xprinter 80mm)</span>
                            </div>
                            <span class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0" :class="printerConfig.paperWidth === '80mm' ? 'border-sky-600 bg-sky-600 text-white text-[9px]' : 'border-slate-300'">
                                <template x-if="printerConfig.paperWidth === '80mm'">✓</template>
                            </span>
                        </button>

                        <button 
                            type="button" 
                            @click="printerConfig.paperWidth = '58mm'"
                            class="p-3 rounded-2xl border text-left transition flex items-center justify-between cursor-pointer"
                            :class="printerConfig.paperWidth === '58mm' ? 'bg-sky-50/90 border-sky-400 ring-2 ring-sky-400/30' : 'bg-slate-50 border-slate-200 hover:bg-white'"
                        >
                            <div>
                                <span class="font-extrabold text-slate-900 block text-xs">58mm (Mini Roll)</span>
                                <span class="text-[10px] text-slate-500 block mt-0.5">Compact Bluetooth/USB mobile slip printers (58mm roll)</span>
                            </div>
                            <span class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0" :class="printerConfig.paperWidth === '58mm' ? 'border-sky-600 bg-sky-600 text-white text-[9px]' : 'border-slate-300'">
                                <template x-if="printerConfig.paperWidth === '58mm'">✓</template>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- 2. Print Automation & Output Mode --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Auto-Print Interactive Toggle Card --}}
                    <div 
                        @click="printerConfig.autoPrint = !printerConfig.autoPrint"
                        class="p-3.5 bg-slate-50/90 rounded-2xl border transition-all cursor-pointer select-none flex flex-col justify-between group"
                        :class="printerConfig.autoPrint ? 'border-emerald-400 bg-emerald-50/40 ring-2 ring-emerald-400/20' : 'border-slate-200/90 hover:bg-slate-100/70'"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-7 h-7 rounded-xl flex items-center justify-center text-xs shrink-0 font-bold transition-colors" :class="printerConfig.autoPrint ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200/70 text-slate-500'">⚡</span>
                                <span class="font-extrabold text-slate-900 text-xs truncate">Auto-Print on Checkout</span>
                            </div>

                            {{-- Clean, Non-Deforming Tactile Toggle Switch --}}
                            <div 
                                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full p-0.5 transition-colors duration-200 ease-in-out shadow-inner"
                                :class="printerConfig.autoPrint ? 'bg-emerald-600' : 'bg-slate-300'"
                            >
                                <span 
                                    class="pointer-events-none block h-5 w-5 aspect-square rounded-full bg-white shadow-md transition-transform duration-200 ease-in-out"
                                    :class="printerConfig.autoPrint ? 'translate-x-5' : 'translate-x-0'"
                                ></span>
                            </div>
                        </div>

                        <div class="mt-2.5 pt-2 border-t flex items-center justify-between text-[10px]" :class="printerConfig.autoPrint ? 'border-emerald-200/60' : 'border-slate-200/70'">
                            <span class="text-slate-500 leading-tight">Instant print when sale completes</span>
                            <span 
                                class="font-black uppercase tracking-wider text-[9px] px-2 py-0.5 rounded-full shrink-0 transition" 
                                :class="printerConfig.autoPrint ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-200 text-slate-600'" 
                                x-text="printerConfig.autoPrint ? 'Enabled' : 'Disabled'"
                            ></span>
                        </div>
                    </div>

                    {{-- Print Delivery Mode Card --}}
                    <div class="p-3.5 bg-slate-50/90 rounded-2xl border border-slate-200/90 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="w-7 h-7 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-xs shrink-0 font-bold">🖨️</span>
                                <label class="font-extrabold text-slate-900 text-xs">Print Execution Mode</label>
                            </div>
                            <select 
                                x-model="printerConfig.printMode" 
                                class="w-full py-1.5 px-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-2xs transition cursor-pointer"
                            >
                                <option value="iframe">Direct Slip Print (Instant In-Page Dialog)</option>
                                <option value="popup">Popup Preview Window (Visual Review)</option>
                            </select>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">Use "Direct Slip Print" for fast counter checkout.</p>
                    </div>
                </div>

                {{-- 3. Print Copies & Display Options --}}
                <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800">Number of Receipt Copies</span>
                        <div class="inline-flex p-0.5 bg-white border border-slate-200 rounded-xl">
                            <button 
                                type="button" 
                                @click="printerConfig.copies = 1"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition"
                                :class="printerConfig.copies == 1 ? 'bg-sky-600 text-white' : 'text-slate-600 hover:text-slate-900'"
                            >
                                1x (Customer)
                            </button>
                            <button 
                                type="button" 
                                @click="printerConfig.copies = 2"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition"
                                :class="printerConfig.copies == 2 ? 'bg-sky-600 text-white' : 'text-slate-600 hover:text-slate-900'"
                            >
                                2x (Customer + Merchant)
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox Toggles --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 border-t border-slate-200/80">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" x-model="printerConfig.showLogo" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-[11px] text-slate-700">Store Logo</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" x-model="printerConfig.showBarcode" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-[11px] text-slate-700">Receipt Barcode</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" x-model="printerConfig.showCashier" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-[11px] text-slate-700">Cashier Name</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" x-model="printerConfig.showCustomer" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-[11px] text-slate-700">Customer Info</span>
                        </label>
                    </div>
                </div>

                {{-- 4. Store Branding & Header Details --}}
                <div class="space-y-2">
                    <span class="font-bold text-slate-800 block">Receipt Header Details</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Store / Business Name</label>
                            <input type="text" x-model="printerConfig.storeName" placeholder="e.g. {{ store_name() }}" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:border-sky-500">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Tagline / Subtitle</label>
                            <input type="text" x-model="printerConfig.storeTagline" placeholder="e.g. Retail Mart & Everyday Essentials" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-sky-500">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Address / Location</label>
                            <input type="text" x-model="printerConfig.storeAddress" placeholder="e.g. Puchong Central Distribution Hub" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-sky-500">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Contact Telephone</label>
                            <input type="text" x-model="printerConfig.storePhone" placeholder="e.g. +60 11-6412 9173" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-sky-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Business Registration / SSM No.</label>
                            <input type="text" x-model="printerConfig.regNo" placeholder="e.g. SSM: 202401023456 (1549876-X)" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-sky-500">
                        </div>
                    </div>
                </div>

                {{-- 5. Custom Footer Message --}}
                <div>
                    <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Receipt Footer Message / Return Policy</label>
                    <textarea x-model="printerConfig.footerMessage" rows="2" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-sky-500"></textarea>
                </div>

            </div>

            {{-- Modal Actions Footer --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5 pt-3 border-t border-slate-100">
                <button 
                    type="button" 
                    @click="testPrintReceipt()"
                    class="w-full sm:w-auto px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs"
                >
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>🧪 Test Print (Slip Machine)</span>
                </button>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button 
                        type="button" 
                        @click="resetPrinterConfig()" 
                        class="px-3 py-2 text-slate-400 hover:text-slate-600 text-xs font-semibold cursor-pointer"
                    >
                        Reset Defaults
                    </button>
                    <button 
                        type="button" 
                        @click="savePrinterConfig()" 
                        class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-extrabold shadow-md shadow-emerald-600/20 transition cursor-pointer"
                    >
                        Save Configuration
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Print Iframe for Instant/Silent POS Slip Output --}}
    <iframe id="pos-thermal-print-iframe" class="hidden" style="position:fixed;right:0;bottom:0;width:0;height:0;border:0;visibility:hidden;"></iframe>

    {{-- Toast Notification for Shortcut Quantity / Action HUD --}}
    <div 
        x-show="shortcutToast.show"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
        class="fixed bottom-4 right-4 z-50 pointer-events-none"
        x-cloak
    >
        <div 
            class="px-3.5 py-2 rounded-2xl shadow-2xl flex items-center gap-2 text-xs font-bold border backdrop-blur-md"
            :class="{
                'bg-slate-900/95 text-white border-slate-700 shadow-slate-900/30': shortcutToast.type === 'info',
                'bg-amber-600/95 text-white border-amber-500 shadow-amber-900/30': shortcutToast.type === 'warning',
                'bg-emerald-600/95 text-white border-emerald-500 shadow-emerald-900/30': shortcutToast.type === 'success'
            }"
        >
            <span class="text-sm" x-text="shortcutToast.icon"></span>
            <span x-text="shortcutToast.message"></span>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- QUICK ADD PRODUCT MODAL FOR CASHIERS & ADMINS            --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showQuickProductModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="showQuickProductModal = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        ➕
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Quick Add Product to POS</h3>
                        <p class="text-[11px] text-slate-400">Instantly create and add item directly into cart</p>
                    </div>
                </div>
                <button type="button" @click="showQuickProductModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
            </div>

            <form @submit.prevent="submitQuickProduct()" class="space-y-4 text-xs">
                {{-- Product Name --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Product Name <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        x-model="newProduct.name" 
                        required 
                        placeholder="e.g. Milo Activ-Go 1kg Softpack" 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                    >
                </div>

                {{-- Category & Brand --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            x-model="newProduct.category_id" 
                            required 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        >
                            <option value="">Select Category</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Brand (Optional)
                        </label>
                        <select 
                            x-model="newProduct.brand_id" 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        >
                            <option value="">No Specific Brand</option>
                            <template x-for="b in brands" :key="b.id">
                                <option :value="b.id" x-text="b.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Price & Sale Price --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Selling Price (RM) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 font-bold text-slate-400">RM</span>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0.01" 
                                x-model="newProduct.price" 
                                required 
                                placeholder="0.00" 
                                class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Promo / Discount Price (Optional)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 font-bold text-slate-400">RM</span>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0" 
                                x-model="newProduct.sale_price" 
                                placeholder="Leave blank if normal" 
                                class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                            >
                        </div>
                    </div>
                </div>

                {{-- Stock & Pack Size --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Initial Stock <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            min="0" 
                            x-model="newProduct.stock_quantity" 
                            required 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        >
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Pack / Unit Size (Optional)
                        </label>
                        <input 
                            type="text" 
                            x-model="newProduct.pack_size" 
                            placeholder="e.g. 500g, 1 Carton, 1kg" 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        >
                    </div>
                </div>

                {{-- Barcode / SKU --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Barcode / SKU (Optional)
                    </label>
                    <div class="flex items-center gap-2">
                        <input 
                            type="text" 
                            x-model="newProduct.sku" 
                            placeholder="Scan barcode or auto-generate" 
                            class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                        >
                        <button 
                            type="button" 
                            @click="generateRandomSku()" 
                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition whitespace-nowrap"
                        >
                            Auto-Gen
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button 
                        type="button" 
                        @click="showQuickProductModal = false" 
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        :disabled="isAddingProduct"
                        class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/25 flex items-center gap-1.5 cursor-pointer"
                    >
                        <span x-show="!isAddingProduct">Save &amp; Add to Cart</span>
                        <span x-show="isAddingProduct">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- CASHIER SHIFT SUMMARY & DRAWER BALANCE MODAL             --}}
    {{-- ======================================================== --}}
    <div 
        x-show="showShiftModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div 
            @click.outside="showShiftModal = false"
            class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] flex flex-col overflow-hidden"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200/80 flex items-center justify-center font-bold text-lg">
                        🧾
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Cashier Shift Drawer &amp; Balance</h3>
                        <p class="text-xs text-slate-500">
                            Cashier: <strong class="text-slate-800" x-text="shiftSummaryData.cashier_name || '{{ auth()->user()->name }}'"></strong> 
                            &bull; <span x-text="shiftSummaryData.shift_date || '{{ now()->format('d M Y') }}'"></span>
                        </p>
                    </div>
                </div>
                <button type="button" @click="showShiftModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                {{-- Metric Cards Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <div class="p-3 bg-emerald-50/70 border border-emerald-200/60 rounded-2xl text-left">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">Total Shift Sales</span>
                        <div class="text-base sm:text-lg font-black text-emerald-950 mt-0.5">
                            RM <span x-text="(shiftSummaryData.total_sales || 0).toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="p-3 bg-sky-50/70 border border-sky-200/60 rounded-2xl text-left">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-sky-700 block">Cash Tendered</span>
                        <div class="text-base sm:text-lg font-black text-sky-950 mt-0.5">
                            RM <span x-text="(shiftSummaryData.cash_sales || 0).toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="p-3 bg-purple-50/70 border border-purple-200/60 rounded-2xl text-left">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 block">DuitNow QR</span>
                        <div class="text-base sm:text-lg font-black text-purple-950 mt-0.5">
                            RM <span x-text="(shiftSummaryData.qr_sales || 0).toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-100 border border-slate-200/80 rounded-2xl text-left">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 block">Transactions</span>
                        <div class="text-base sm:text-lg font-black text-slate-900 mt-0.5" x-text="shiftSummaryData.total_transactions || 0">
                        </div>
                    </div>
                </div>

                {{-- Shift Sales History Table --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Shift Receipts Today</h4>
                        <span class="text-[11px] text-slate-400" x-text="(shiftSummaryData.orders ? shiftSummaryData.orders.length : 0) + ' Sales recorded'"></span>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3">Receipt #</th>
                                    <th class="py-2.5 px-3">Customer</th>
                                    <th class="py-2.5 px-3">Time</th>
                                    <th class="py-2.5 px-3">Method</th>
                                    <th class="py-2.5 px-3 text-right">Total</th>
                                    <th class="py-2.5 px-3 text-right">Receipt</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <template x-for="ord in (shiftSummaryData.orders || [])" :key="ord.id">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="py-2 px-3 font-mono font-bold text-slate-900" x-text="ord.order_number"></td>
                                        <td class="py-2 px-3" x-text="ord.customer"></td>
                                        <td class="py-2 px-3 text-slate-400 font-mono text-[11px]" x-text="ord.time"></td>
                                        <td class="py-2 px-3">
                                            <span 
                                                class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                                                :class="{
                                                    'bg-emerald-100 text-emerald-800': ord.method === 'CASH',
                                                    'bg-purple-100 text-purple-800': ord.method === 'DUITNOW_QR',
                                                    'bg-sky-100 text-sky-800': ord.method === 'CARD'
                                                }"
                                                x-text="ord.method"
                                            ></span>
                                        </td>
                                        <td class="py-2 px-3 text-right font-extrabold text-slate-900">
                                            RM <span x-text="ord.total.toFixed(2)"></span>
                                        </td>
                                        <td class="py-2 px-3 text-right">
                                            <a :href="ord.receipt_url" target="_blank" class="text-sky-600 hover:text-sky-700 font-bold text-[11px] underline">
                                                Slip
                                            </a>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="!shiftSummaryData.orders || shiftSummaryData.orders.length === 0">
                                    <td colspan="6" class="py-6 text-center text-slate-400 text-xs">
                                        No sales completed in this shift yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Controls --}}
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 shrink-0">
                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Shift Summary</span>
                </button>

                <button 
                    type="button" 
                    @click="showShiftModal = false" 
                    class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

{{-- Reactive Alpine.js Application Engine for POS --}}
<script>
function posApp() {
    return {
        // Data sets passed from Controller
        products: @json($products),
        categories: @json($categories),
        brands: @json($brands ?? []),
        customers: @json($customers),

        // Quick Add Product State
        showQuickProductModal: false,
        isSyncingProducts: false,
        newProduct: {
            name: '',
            category_id: '',
            brand_id: '',
            price: '',
            sale_price: '',
            stock_quantity: 10,
            pack_size: '',
            sku: ''
        },
        isAddingProduct: false,

        // Cashier Shift & Drawer State
        showShiftModal: false,
        shiftSummaryData: {
            cashier_name: '',
            shift_date: '',
            total_transactions: 0,
            total_sales: 0,
            cash_sales: 0,
            qr_sales: 0,
            card_sales: 0,
            orders: []
        },
        isLoadingShift: false,

        // Search & Filter
        searchQuery: '',
        selectedCategory: 'all',
        categoryDropdownOpen: false,
        categoryFilterSearch: '',
        inStockOnly: true,

        // Cart & Customer state
        cart: [],
        selectedCartIndex: -1,
        qtyKeyBuffer: '',
        qtyBufferTimeout: null,
        barcodeScanBuffer: '',
        lastScanTime: 0,
        scanBufferTimeout: null,
        scanAutoProcessTimeout: null,
        audioCtx: null,
        shortcutToast: {
            show: false,
            message: '',
            icon: '⚡',
            type: 'info',
            timeout: null,
        },
        customer: { id: null, name: 'Walk-in Customer', phone: '', email: '' },
        customerSearch: '',
        newCustomer: { name: '', phone: '' },
        heldOrders: [],

        // Payment State
        paymentMethod: 'cash',
        amountTendered: 0,
        discountAmount: 0,
        paymentReference: '',
        isProcessing: false,
        soundEnabled: true,
        isFullscreen: false,

        // Modals & UI Controls
        showReceiptModal: false,
        showCustomerModal: false,
        showQrModal: false,
        showHeldOrdersModal: false,
        showShortcutsModal: false,
        showPrinterConfigModal: false,
        showItemDiscountModal: false,
        discountModalItem: null,
        discountModalInput: '',
        completedOrder: {},
        lastCompletedBasket: [],
        currentTime: '',

        // Thermal Slip Printer Configuration
        printerConfig: {
            paperWidth: '58mm',
            autoPrint: false,
            printMode: 'iframe',
            copies: 1,
            showLogo: true,
            showBarcode: true,
            showCashier: true,
            showCustomer: true,
            storeName: {!! json_encode(html_entity_decode(store_name(), ENT_QUOTES, 'UTF-8')) !!},
            storeTagline: 'Retail Mart & Everyday Essentials',
            storeAddress: "34, Jalan Perkasa 3, Taman Ungku Tun Aminah, 81300 Skudai, Johor Darul Ta'zim, Malaysia",
            storePhone: '+60 11-6412 9173',
            regNo: '',
            footerMessage: 'Thank you for shopping at ' + {!! json_encode(html_entity_decode(store_name(), ENT_QUOTES, 'UTF-8')) !!},
        },

        initPos() {
            // High-priority capturing keydown listener to guarantee Ctrl+Enter always triggers discount modal
            window.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && (e.key === 'Enter' || e.code === 'Enter' || e.code === 'NumpadEnter')) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.openItemDiscountModal();
                }
            }, true);

            // Update clock
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);

            // Load saved held baskets from local storage
            try {
                const saved = localStorage.getItem('nj_pos_held_baskets');
                if (saved) this.heldOrders = JSON.parse(saved);
            } catch(e) {}

            // Load saved thermal printer configuration & auto-migrate stale address/footer/storeName
            try {
                const savedPrinter = localStorage.getItem('pos_thermal_printer_config');
                if (savedPrinter) {
                    const parsed = JSON.parse(savedPrinter);
                    if (!parsed.storeAddress || parsed.storeAddress.includes('Puchong')) {
                        parsed.storeAddress = "34, Jalan Perkasa 3, Taman Ungku Tun Aminah, 81300 Skudai, Johor Darul Ta'zim, Malaysia";
                    }
                    if (!parsed.storePhone || parsed.storePhone.includes('859') || parsed.storePhone.includes('889')) {
                        parsed.storePhone = "+60 11-6412 9173";
                    }
                    if (!parsed.footerMessage || parsed.footerMessage.includes('non-refundable') || parsed.footerMessage.includes('Exchange allowed') || parsed.footerMessage.includes('N&J') || parsed.footerMessage.includes('99')) {
                        parsed.footerMessage = 'Thank you for shopping at ' + {!! json_encode(html_entity_decode(store_name(), ENT_QUOTES, 'UTF-8')) !!};
                    }
                    if (parsed.storeName && (parsed.storeName.includes('N&J') || parsed.storeName.includes('99') || parsed.storeName.includes('N&I'))) {
                        parsed.storeName = {!! json_encode(html_entity_decode(store_name(), ENT_QUOTES, 'UTF-8')) !!};
                    }
                    this.printerConfig = Object.assign({}, this.printerConfig, parsed);
                    if (this.printerConfig.storeName) {
                        this.printerConfig.storeName = this.printerConfig.storeName.replace(/&amp;/g, '&');
                    }
                    localStorage.setItem('pos_thermal_printer_config', JSON.stringify(this.printerConfig));
                }
            } catch(e) {}

            // Auto-focus barcode scanner input on POS launch
            this.$nextTick(() => {
                this.focusBarcodeInput();
            });
        },

        focusBarcodeInput() {
            if (this.$refs.barcodeInput && document.activeElement !== this.$refs.barcodeInput) {
                this.$refs.barcodeInput.focus();
            }
        },

        handlePosClick(e) {
            if (this.isAnyModalOpen()) return;
            const target = e.target;
            if (!target.closest('input, textarea, button, a, select, [data-cart-item]')) {
                this.focusBarcodeInput();
            }
        },

        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        },

        // Persistent Audio Context for Zero-Delay Web Audio (avoids Chrome hardware limit)
        getAudioContext() {
            if (!this.audioCtx) {
                const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
                if (AudioCtxClass) {
                    this.audioCtx = new AudioCtxClass();
                }
            }
            if (this.audioCtx && this.audioCtx.state === 'suspended') {
                this.audioCtx.resume();
            }
            return this.audioCtx;
        },

        // Crisp Supermarket Barcode Scanner Beep (1850Hz) & Warning Buzz
        playBeep(type = 'success') {
            if (!this.soundEnabled) return;
            try {
                const ctx = this.getAudioContext();
                if (!ctx) return;

                const now = ctx.currentTime;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                if (type === 'success') {
                    // Crisp supermarket barcode scanner beep (1850 Hz)
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(1850, now);
                    gain.gain.setValueAtTime(0.35, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.12);
                } else if (type === 'warning') {
                    // Low double buzz for warning / out-of-stock
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(320, now);
                    gain.gain.setValueAtTime(0.25, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.2);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.2);
                }
            } catch(e) {}
        },

        playChime() {
            if (!this.soundEnabled) return;
            try {
                const ctx = this.getAudioContext();
                if (!ctx) return;
                const now = ctx.currentTime;
                // Double chime: 880Hz then 1320Hz
                [880, 1320].forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, now + (idx * 0.12));
                    gain.gain.setValueAtTime(0.25, now + (idx * 0.12));
                    gain.gain.exponentialRampToValueAtTime(0.001, now + (idx * 0.12) + 0.22);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + (idx * 0.12));
                    osc.stop(now + (idx * 0.12) + 0.22);
                });
            } catch(e) {}
        },

        // Filter out empty/dummy categories
        get activeCategories() {
            return this.categories.filter(c => c.products_count > 0);
        },

        // Dropdown filtered categories based on quick filter search inside dropdown
        get dropdownFilteredCategories() {
            if (!this.categoryFilterSearch.trim()) return this.activeCategories;
            const q = this.categoryFilterSearch.toLowerCase().trim();
            return this.activeCategories.filter(c => c.name.toLowerCase().includes(q));
        },

        // Currently selected category object for dropdown trigger
        get selectedCategoryObj() {
            if (this.selectedCategory === 'all') {
                return {
                    id: 'all',
                    name: 'All Categories',
                    products_count: this.products.length,
                    image_url: null,
                };
            }
            return this.categories.find(c => String(c.id) === String(this.selectedCategory)) || {
                id: 'all',
                name: 'All Categories',
                products_count: this.products.length,
                image_url: null,
            };
        },

        // Select category with auto-scroll and synchronization
        selectCategory(catId) {
            this.selectedCategory = catId;
            this.categoryDropdownOpen = false;
            this.categoryFilterSearch = '';
            this.$nextTick(() => {
                const activeBtn = document.querySelector('[data-cat-pill="' + catId + '"]');
                if (activeBtn) {
                    activeBtn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
                this.focusBarcodeInput();
            });
        },

        // Computed filtered products list
        get filteredProducts() {
            return this.products.filter(p => {
                const q = this.searchQuery.trim().toLowerCase();
                const cleanQ = q.replace(/[^a-z0-9]/g, '');
                const cleanSku = (p.sku || '').toLowerCase().replace(/[^a-z0-9]/g, '');

                // Category match:
                // If user has a category filter active, but specifically scanned or typed a barcode/SKU,
                // allow SKU match across categories so the product is not hidden!
                if (this.selectedCategory !== 'all' && String(p.category_id) !== String(this.selectedCategory)) {
                    const isSkuSearch = cleanQ.length >= 3 && cleanSku.includes(cleanQ);
                    if (!isSkuSearch) {
                        return false;
                    }
                }
                // Stock filter
                if (this.inStockOnly && p.stock_quantity <= 0) {
                    return false;
                }
                // Text search match
                if (q !== '') {
                    const nameMatch = p.name && p.name.toLowerCase().includes(q);
                    const skuMatch = p.sku && (p.sku.toLowerCase().includes(q) || (cleanQ.length >= 3 && cleanSku.includes(cleanQ)));
                    const brandMatch = p.brand_name && p.brand_name.toLowerCase().includes(q);
                    return nameMatch || skuMatch || brandMatch;
                }
                return true;
            });
        },

        get filteredCustomers() {
            if (!this.customerSearch.trim()) return this.customers.slice(0, 10);
            const q = this.customerSearch.toLowerCase().trim();
            return this.customers.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) ||
                (c.phone && c.phone.toLowerCase().includes(q)) ||
                (c.email && c.email.toLowerCase().includes(q))
            );
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get grandTotal() {
            const raw = this.subtotal - (Number(this.discountAmount) || 0);
            return Math.max(0, Math.round(raw * 100) / 100);
        },

        get changeDue() {
            return (Number(this.amountTendered) || 0) - this.grandTotal;
        },

        get totalItemCount() {
            return this.cart.reduce((sum, item) => sum + item.quantity, 0);
        },

        // Dynamic cash preset chips based on current grand total
        get cashPresets() {
            const total = this.grandTotal;
            const presets = [];

            if (total <= 0) return [5, 10, 50];

            // 1. Minimum cash note in Malaysia (RM 5) when total is under RM 5
            if (total < 5) {
                presets.push(5);
            }

            // 2. Next rounded RM 10
            const next10 = Math.ceil(total / 10) * 10;
            if (next10 > total && !presets.includes(next10)) presets.push(next10);

            // 3. Next rounded RM 20 (useful when total between 5 and 20)
            if (total > 5 && total < 20 && !presets.includes(20)) {
                presets.push(20);
            }

            // 4. Next rounded RM 50
            const next50 = Math.ceil(total / 50) * 50;
            if (next50 > total && !presets.includes(next50)) presets.push(next50);

            // 5. Next rounded RM 100
            const next100 = Math.ceil(total / 100) * 100;
            if (next100 > total && !presets.includes(next100)) presets.push(next100);

            // Standard banknote denominations in Malaysia
            [5, 10, 20, 50, 100, 200].forEach(p => {
                if (p > total && presets.length < 3 && !presets.includes(p)) {
                    presets.push(p);
                }
            });

            return presets.sort((a, b) => a - b).slice(0, 3);
        },

        scrollCategoryCarousel(offset) {
            if (this.$refs.categoryCarousel) {
                this.$refs.categoryCarousel.scrollBy({ left: offset, behavior: 'smooth' });
            }
        },

        getCartItemQty(productId) {
            const item = this.cart.find(i => i.id === productId);
            return item ? item.quantity : 0;
        },

        // Barcode enter event
        handleBarcodeEnter(e) {
            if (e && e.preventDefault) e.preventDefault();
            // If Ctrl + Enter was pressed while inside barcode input:
            if (e && (e.ctrlKey || e.metaKey)) {
                if (e.stopPropagation) e.stopPropagation();
                this.openItemDiscountModal();
                return;
            }
            if (this.scanAutoProcessTimeout) clearTimeout(this.scanAutoProcessTimeout);
            const code = this.barcodeScanBuffer.trim() || this.searchQuery.trim();
            if (code) {
                this.barcodeScanBuffer = '';
                this.processBarcodeScan(code);
            }
        },

        // Robust Product Resolver by Code, SKU, ID, or Clean Alphanumeric String
        findProductByCode(rawCode) {
            if (!rawCode) return null;
            let code = String(rawCode).replace(/[\r\n\t]/g, '').trim();
            if (!code) return null;

            // Strip symbology AIM identifiers if scanner sent them (e.g. ]C1, ]e0, etc.)
            code = code.replace(/^\][a-zA-Z0-9]{2}/, '');
            const q = code.toLowerCase();

            // 1. Exact SKU match
            let p = this.products.find(item => item.sku && item.sku.trim().toLowerCase() === q);
            if (p) return p;

            // 2. Exact ID match
            p = this.products.find(item => String(item.id).trim() === q);
            if (p) return p;

            // 3. Exact Name match
            p = this.products.find(item => item.name && item.name.trim().toLowerCase() === q);
            if (p) return p;

            // 4. Case-insensitive SKU without hyphens/spaces (e.g. BEV10003 matching BEV-100-03)
            const cleanQ = q.replace(/[^a-z0-9]/g, '');
            if (cleanQ.length >= 3) {
                p = this.products.find(item => {
                    const cleanSku = (item.sku || '').toLowerCase().replace(/[^a-z0-9]/g, '');
                    return cleanSku === cleanQ;
                });
                if (p) return p;
            }

            // 5. Leading zero normalization (e.g. UPC-A 12-digit vs EAN-13 13-digit with leading 0)
            const unpaddedQ = cleanQ.replace(/^0+/, '');
            if (unpaddedQ.length >= 3) {
                p = this.products.find(item => {
                    const cleanSku = (item.sku || '').toLowerCase().replace(/[^a-z0-9]/g, '');
                    const unpaddedSku = cleanSku.replace(/^0+/, '');
                    return unpaddedSku === unpaddedQ;
                });
                if (p) return p;
            }

            // 6. Fallback: single partial match on SKU or Name
            const partial = this.products.filter(item => 
                (item.sku && item.sku.toLowerCase().includes(q)) ||
                (item.name && item.name.toLowerCase().includes(q))
            );
            if (partial.length === 1) {
                return partial[0];
            }

            return null;
        },

        // Universal Barcode Processor (searches memory, queries server if newly added, adds to cart, audio/visual feedback)
        async processBarcodeScan(rawCode) {
            if (this.scanAutoProcessTimeout) clearTimeout(this.scanAutoProcessTimeout);
            if (!rawCode) return;
            let code = String(rawCode).replace(/[\r\n\t]/g, '').trim();
            if (!code) return;
            code = code.replace(/^\][a-zA-Z0-9]{2}/, '');

            // Direct Order Barcode Scan detection (e.g. POS-2026-01014 or Order Number)
            if (code.toUpperCase().startsWith('POS-') || code.toUpperCase().startsWith('ORD-') || code.toUpperCase().startsWith('BS-')) {
                const targetUrl = `{{ url('/admin/orders/barcode') }}/${encodeURIComponent(code)}`;
                const link = document.createElement('a');
                link.href = targetUrl;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                document.body.appendChild(link);
                link.click();
                setTimeout(() => link.remove(), 200);

                this.showToast(`Opening scanned Order in new tab: ${code}`, '🧾', 'success');
                this.playBeep('success');
                this.searchQuery = '';
                this.barcodeScanBuffer = '';
                return;
            }

            // 1. Search in-memory products first
            let product = this.findProductByCode(code);

            // 2. Real-Time Server Fallback:
            // If not found in browser memory (e.g. product was recently added in admin or page not refreshed):
            if (!product) {
                try {
                    const res = await fetch(`{{ route('admin.pos.find-barcode') }}?code=${encodeURIComponent(code)}`);
                    const data = await res.json();
                    if (data.success && data.product) {
                        const serverProd = data.product;

                        // Check if marked inactive
                        if (serverProd.is_active === false) {
                            this.showToast(`Product "${serverProd.name}" is marked INACTIVE in admin. Set status to Active to sell in POS.`, '⚠️', 'warning');
                            this.playBeep('warning');
                            this.searchQuery = code;
                            return;
                        }

                        // Add or update to local products array
                        const localIdx = this.products.findIndex(p => String(p.id) === String(serverProd.id));
                        if (localIdx > -1) {
                            this.products[localIdx] = serverProd;
                        } else {
                            this.products.unshift(serverProd);
                        }

                        // Reset category filter if it was hidden under another category tab
                        if (this.selectedCategory !== 'all' && String(serverProd.category_id) !== String(this.selectedCategory)) {
                            this.selectedCategory = 'all';
                        }

                        product = serverProd;
                    }
                } catch (err) {
                    console.error('Server barcode lookup error:', err);
                }
            }

            if (product) {
                if (product.stock_quantity <= 0) {
                    this.showToast(`Out of stock: ${product.name} (Stock: 0)`, '⚠️', 'warning');
                    this.playBeep('warning');
                    this.searchQuery = code;
                } else {
                    this.addToCart(product);
                    this.searchQuery = '';
                    this.barcodeScanBuffer = '';
                }
            } else {
                this.showToast(`Barcode not found in catalog: "${code}"`, '❌', 'warning');
                this.playBeep('warning');
                this.searchQuery = code;
            }

            this.$nextTick(() => {
                this.focusBarcodeInput();
            });
        },

        // Live Sync all active products from backend without full page reload
        async syncProductsFromServer() {
            if (this.isSyncingProducts) return;
            this.isSyncingProducts = true;
            try {
                const res = await fetch(`{{ route('admin.pos.sync-products') }}`);
                const data = await res.json();
                if (data.success && Array.isArray(data.products)) {
                    this.products = data.products;
                    this.showToast(`Synced ${data.total} products from database!`, '🔄', 'success');
                    this.playBeep('success');
                } else {
                    this.showToast('Could not load products from server.', '⚠️', 'warning');
                }
            } catch (err) {
                this.showToast('Network error while syncing products.', '⚠️', 'warning');
            } finally {
                this.isSyncingProducts = false;
            }
        },

        // Open quick product modal with pre-filled barcode
        openQuickProductModalWithBarcode(barcode) {
            this.newProduct.sku = barcode ? String(barcode).trim() : '';
            this.newProduct.name = '';
            this.newProduct.price = '';
            this.newProduct.stock_quantity = 10;
            this.showQuickProductModal = true;
        },

        // Cart Actions (Updates existing entry quantity if scanned again; otherwise appends new item)
        addToCart(product) {
            if (!product || product.stock_quantity <= 0) return;

            // String-safe ID check to ensure existing cart item is matched every time
            const existingIndex = this.cart.findIndex(i => String(i.id) === String(product.id));

            if (existingIndex > -1) {
                // Same product scanned again -> Keep existing row, increment quantity
                const item = this.cart[existingIndex];
                const maxStock = Number(item.max_stock || product.stock_quantity || 9999);

                if (item.quantity < maxStock) {
                    item.quantity++;
                    this.selectedCartIndex = existingIndex;
                    this.playBeep('success');
                    this.showToast(`${item.name} (Qty: ${item.quantity})`, '🛒', 'success');
                } else {
                    this.showToast(`Max stock reached for ${item.name} (${maxStock})`, '⚠️', 'warning');
                    this.playBeep('warning');
                }
            } else {
                // New product scanned -> Add new row with Qty 1
                const basePrice = Number(product.effective_price || product.price);
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    original_price: basePrice,
                    price: basePrice,
                    discount_price: null,
                    quantity: 1,
                    max_stock: Number(product.stock_quantity || 9999),
                    image_url: product.image_url,
                    pack_size: product.pack_size,
                });
                this.selectedCartIndex = this.cart.length - 1;
                this.playBeep('success');
                this.showToast(`Added: ${product.name}`, '🛒', 'success');
            }

            // Sync total cash tendered
            this.amountTendered = this.grandTotal;

            // Auto-scroll basket to keep the active item in view & keep focus
            this.$nextTick(() => {
                this.scrollSelectedCartItemIntoView();
                this.focusBarcodeInput();
            });
        },

        incrementQty(index) {
            const item = this.cart[index];
            if (!item) return;
            this.selectedCartIndex = index;
            if (item.quantity < item.max_stock) {
                item.quantity++;
                this.amountTendered = this.grandTotal;
            }
        },

        decrementQty(index) {
            const item = this.cart[index];
            if (!item) return;
            this.selectedCartIndex = index;
            if (item.quantity > 1) {
                item.quantity--;
            } else {
                this.cart.splice(index, 1);
                if (this.cart.length === 0) {
                    this.selectedCartIndex = -1;
                } else if (this.selectedCartIndex >= this.cart.length) {
                    this.selectedCartIndex = this.cart.length - 1;
                }
            }
            this.amountTendered = this.grandTotal;
        },

        validateQty(index) {
            const item = this.cart[index];
            if (!item) return;
            this.selectedCartIndex = index;
            if (!item.quantity || item.quantity < 1) item.quantity = 1;
            if (item.quantity > item.max_stock) item.quantity = item.max_stock;
            this.amountTendered = this.grandTotal;
        },

        removeItem(index) {
            this.cart.splice(index, 1);
            if (this.cart.length === 0) {
                this.selectedCartIndex = -1;
            } else if (this.selectedCartIndex >= this.cart.length) {
                this.selectedCartIndex = this.cart.length - 1;
            }
            this.amountTendered = this.grandTotal;
        },

        clearCart() {
            if (confirm('Clear all items from current register?')) {
                this.cart = [];
                this.selectedCartIndex = -1;
                this.qtyKeyBuffer = '';
                this.discountAmount = 0;
                this.amountTendered = 0;
                this.$refs.barcodeInput.focus();
            }
        },

        // Hold / Park Sale
        holdCurrentOrder() {
            if (this.cart.length === 0) return;
            this.heldOrders.push({
                items: JSON.parse(JSON.stringify(this.cart)),
                customer: JSON.parse(JSON.stringify(this.customer)),
                grandTotal: this.grandTotal,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            });
            localStorage.setItem('nj_pos_held_baskets', JSON.stringify(this.heldOrders));
            this.cart = [];
            this.discountAmount = 0;
            this.amountTendered = 0;
            alert('Basket parked successfully. You can resume it anytime.');
        },

        restoreHeldOrder(idx) {
            const basket = this.heldOrders[idx];
            this.cart = basket.items;
            this.customer = basket.customer;
            this.heldOrders.splice(idx, 1);
            localStorage.setItem('nj_pos_held_baskets', JSON.stringify(this.heldOrders));
            this.showHeldOrdersModal = false;
            this.amountTendered = this.grandTotal;
        },

        deleteHeldOrder(idx) {
            this.heldOrders.splice(idx, 1);
            localStorage.setItem('nj_pos_held_baskets', JSON.stringify(this.heldOrders));
        },

        // Customer Selection
        selectCustomer(c) {
            this.customer = { id: c.id, name: c.name, phone: c.phone, email: c.email };
            this.showCustomerModal = false;
        },

        resetCustomer() {
            this.customer = { id: null, name: 'Walk-in Customer', phone: '', email: '' };
        },

        async createQuickCustomer() {
            if (!this.newCustomer.name || !this.newCustomer.phone) {
                alert('Please enter both customer name and phone.');
                return;
            }

            try {
                const res = await fetch('{{ route("admin.pos.customer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.newCustomer)
                });
                const data = await res.json();
                if (data.success) {
                    this.customers.unshift(data.customer);
                    this.selectCustomer(data.customer);
                    this.newCustomer = { name: '', phone: '' };
                } else {
                    alert(data.message || 'Error registering customer.');
                }
            } catch(e) {
                alert('Connection error.');
            }
        },

        // Process POS Checkout Submission
        async processCheckout() {
            if (this.cart.length === 0 || this.isProcessing) return;

            if (this.paymentMethod === 'cash' && this.changeDue < 0) {
                alert('Tendered cash is less than the grand total!');
                return;
            }

            this.isProcessing = true;

            try {
                const payload = {
                    items: this.cart.map(i => ({ 
                        id: i.id, 
                        quantity: i.quantity,
                        unit_price: Number(i.price),
                        original_price: Number(i.original_price || i.price),
                        discount_price: i.discount_price !== null ? Number(i.discount_price) : null,
                    })),
                    payment_method: this.paymentMethod,
                    amount_tendered: Number(this.amountTendered) || this.grandTotal,
                    discount_amount: Number(this.discountAmount) || 0,
                    customer_name: this.customer.name,
                    customer_phone: this.customer.phone,
                    customer_email: this.customer.email,
                    user_id: this.customer.id,
                    notes: this.paymentReference ? 'Ref: ' + this.paymentReference : null,
                };

                const res = await fetch('{{ route("admin.pos.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (data.success) {
                    // Do NOT play chime or notification bell on walk-in sale
                    this.completedOrder = data;
                    this.lastCompletedBasket = JSON.parse(JSON.stringify(this.cart));
                    this.showReceiptModal = true;

                    // Update live stock in memory
                    this.cart.forEach(item => {
                        const p = this.products.find(prod => prod.id === item.id);
                        if (p) p.stock_quantity = Math.max(0, p.stock_quantity - item.quantity);
                    });

                    // Keep basket visible in background until Space or Enter is pressed for next customer

                    // Auto-print thermal slip if enabled in settings
                    if (this.printerConfig && this.printerConfig.autoPrint) {
                        setTimeout(() => {
                            this.printReceipt(this.completedOrder.receipt_url);
                        }, 350);
                    }
                } else {
                    alert(data.message || 'Checkout failed. Please check stock or inputs.');
                }
            } catch(e) {
                alert('Network error while completing transaction.');
            } finally {
                this.isProcessing = false;
            }
        },

        resetForNextSale() {
            this.showReceiptModal = false;
            this.completedOrder = {};
            this.lastCompletedBasket = [];
            this.cart = [];
            this.discountAmount = 0;
            this.paymentReference = '';
            this.amountTendered = 0;
            this.resetCustomer();
            this.$nextTick(() => {
                this.focusBarcodeInput();
            });
        },

        printReceiptAndNext(receiptUrl) {
            this.printReceipt(receiptUrl);
            this.resetForNextSale();
        },

        async reEditCurrentOrder() {
            const orderId = this.completedOrder.order_id;
            this.showReceiptModal = false;

            // Ensure cart has the items to re-edit
            if ((!this.cart || this.cart.length === 0) && this.lastCompletedBasket && this.lastCompletedBasket.length > 0) {
                this.cart = JSON.parse(JSON.stringify(this.lastCompletedBasket));
            }

            // Restore in-memory stock for each item
            if (this.cart && this.cart.length > 0) {
                this.cart.forEach(item => {
                    const p = this.products.find(prod => prod.id === item.id);
                    if (p) p.stock_quantity += item.quantity;
                });
            }

            // Void database order on server so it doesn't double-count
            if (orderId) {
                try {
                    await fetch('{{ route("admin.pos.void-order") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ order_id: orderId })
                    });
                } catch(e) {
                    console.warn('Could not void order on server:', e);
                }
            }

            this.completedOrder = {};
            this.lastCompletedBasket = [];
            this.showToast('Order reopened for editing (Esc)', '⚡', 'warning');
            this.$nextTick(() => {
                this.focusBarcodeInput();
            });
        },

        getActiveCartIndex() {
            if (!this.cart || this.cart.length === 0) return -1;
            if (this.selectedCartIndex < 0 || this.selectedCartIndex >= this.cart.length) {
                return this.cart.length - 1; // default to the latest added cart item
            }
            return this.selectedCartIndex;
        },

        showToast(message, icon = '⚡', type = 'info') {
            if (this.shortcutToast.timeout) {
                clearTimeout(this.shortcutToast.timeout);
            }
            this.shortcutToast.message = message;
            this.shortcutToast.icon = icon;
            this.shortcutToast.type = type;
            this.shortcutToast.show = true;
            this.shortcutToast.timeout = setTimeout(() => {
                this.shortcutToast.show = false;
            }, 1800);
        },

        isAnyModalOpen() {
            return this.showReceiptModal || 
                   this.showCustomerModal || 
                   this.showQrModal || 
                   this.showHeldOrdersModal || 
                   this.showShortcutsModal ||
                   this.showQuickProductModal ||
                   this.showShiftModal ||
                   this.showItemDiscountModal ||
                   this.showPrinterConfigModal;
        },

        openItemDiscountModal(index = null) {
            if (!this.cart || this.cart.length === 0) {
                this.showToast('Please add products to cart first', '🛒', 'info');
                return;
            }
            let targetIndex = (index !== null && index >= 0) ? index : this.getActiveCartIndex();
            if (targetIndex < 0 || targetIndex >= this.cart.length) {
                targetIndex = this.cart.length - 1;
            }
            if (targetIndex < 0 || !this.cart[targetIndex]) return;

            this.selectedCartIndex = targetIndex;
            const item = this.cart[targetIndex];
            if (!item.original_price) {
                item.original_price = Number(item.price);
            }
            this.discountModalItem = item;
            this.discountModalInput = (item.discount_price !== null && item.discount_price !== undefined) 
                ? Number(item.discount_price).toFixed(2) 
                : '';
            this.showItemDiscountModal = true;
            this.$nextTick(() => {
                if (this.$refs.discountPriceInput) {
                    this.$refs.discountPriceInput.focus();
                    this.$refs.discountPriceInput.select();
                }
            });
        },

        applyItemDiscount() {
            if (this.selectedCartIndex < 0 || this.selectedCartIndex >= this.cart.length) {
                this.showItemDiscountModal = false;
                return;
            }
            const item = this.cart[this.selectedCartIndex];
            const val = parseFloat(this.discountModalInput);

            if (!isNaN(val) && val >= 0) {
                item.discount_price = Math.round(val * 100) / 100;
                item.price = item.discount_price;
                this.playBeep('success');
                this.showToast(`Discount set on ${item.name}: RM ${item.price.toFixed(2)}`, '🏷️', 'success');
            } else {
                item.discount_price = null;
                item.price = item.original_price;
            }

            if (this.paymentMethod === 'cash') {
                this.amountTendered = this.grandTotal;
            }
            this.showItemDiscountModal = false;
            this.$nextTick(() => {
                this.focusBarcodeInput();
            });
        },

        resetItemDiscount() {
            if (this.selectedCartIndex >= 0 && this.selectedCartIndex < this.cart.length) {
                const item = this.cart[this.selectedCartIndex];
                item.discount_price = null;
                item.price = item.original_price;
                if (this.paymentMethod === 'cash') {
                    this.amountTendered = this.grandTotal;
                }
                this.showToast(`Discount removed for ${item.name}`, '🔄', 'info');
            }
            this.showItemDiscountModal = false;
            this.$nextTick(() => {
                this.focusBarcodeInput();
            });
        },

        openShiftSummaryModal() {
            this.showShiftModal = true;
            this.isLoadingShift = true;
            fetch('{{ route('admin.pos.shift-summary') }}')
                .then(res => res.json())
                .then(data => {
                    this.shiftSummaryData = data;
                })
                .catch(err => {
                    console.error('Failed to load shift summary:', err);
                })
                .finally(() => {
                    this.isLoadingShift = false;
                });
        },

        generateRandomSku() {
            this.newProduct.sku = 'POS' + Math.floor(100000 + Math.random() * 900000);
        },

        submitQuickProduct() {
            if (!this.newProduct.name || !this.newProduct.category_id || !this.newProduct.price) {
                alert('Please provide Product Name, Category, and Selling Price.');
                return;
            }

            this.isAddingProduct = true;
            fetch('{{ route('admin.pos.quick-product') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.newProduct)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.product) {
                    this.products.unshift(data.product);
                    this.showQuickProductModal = false;
                    this.newProduct = {
                        name: '',
                        category_id: '',
                        brand_id: '',
                        price: '',
                        sale_price: '',
                        stock_quantity: 10,
                        pack_size: '',
                        sku: ''
                    };
                    this.showToast('Product created & added to cart!', '📦', 'success');
                    this.addToCart(data.product);
                } else {
                    alert(data.message || 'Could not save product.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred while creating product.');
            })
            .finally(() => {
                this.isAddingProduct = false;
            });
        },

        extractDigit(e) {
            if (/^[0-9]$/.test(e.key)) {
                return e.key;
            }
            if (/^Digit([0-9])$/.test(e.code)) {
                return e.code.replace('Digit', '');
            }
            if (/^Numpad([0-9])$/.test(e.code)) {
                return e.code.replace('Numpad', '');
            }
            return null;
        },

        handleQuantityDigit(digit) {
            if (this.cart.length === 0) {
                this.showToast('Cart is empty. Please add a product first.', '⚠️', 'warning');
                return;
            }

            const activeIdx = this.getActiveCartIndex();
            const item = this.cart[activeIdx];
            if (!item) return;

            // Reset any pending timeout
            if (this.qtyBufferTimeout) {
                clearTimeout(this.qtyBufferTimeout);
            }

            // Append digit to buffer (e.g. '1' then '2' becomes '12')
            this.qtyKeyBuffer = (this.qtyKeyBuffer || '') + digit;
            let targetQty = parseInt(this.qtyKeyBuffer, 10);

            if (isNaN(targetQty) || targetQty < 1) {
                targetQty = 1;
            }

            // Cap at available stock
            if (targetQty > item.max_stock) {
                targetQty = item.max_stock;
                item.quantity = targetQty;
                this.showToast(item.name + ': Stock limit reached (' + item.max_stock + ')', '⚠️', 'warning');
            } else {
                item.quantity = targetQty;
                this.showToast(item.name + ' Qty: ' + targetQty, '⚡', 'info');
            }

            this.amountTendered = this.grandTotal;
            this.playBeep();

            // Clear buffer after 1.5 seconds of inactivity
            this.qtyBufferTimeout = setTimeout(() => {
                this.qtyKeyBuffer = '';
            }, 1500);
        },

        handleQuantityArrow(direction) {
            if (this.cart.length === 0) {
                this.showToast('Cart is empty. Please add a product first.', '⚠️', 'warning');
                return;
            }

            this.qtyKeyBuffer = '';
            if (this.qtyBufferTimeout) {
                clearTimeout(this.qtyBufferTimeout);
            }

            const activeIdx = this.getActiveCartIndex();
            const item = this.cart[activeIdx];
            if (!item) return;

            if (direction === 'up') {
                if (item.quantity < item.max_stock) {
                    item.quantity++;
                    this.amountTendered = this.grandTotal;
                    this.playBeep();
                    this.showToast(item.name + ' Qty: ' + item.quantity, '▲', 'info');
                } else {
                    this.showToast(item.name + ': Max stock limit reached (' + item.max_stock + ')', '⚠️', 'warning');
                }
            } else if (direction === 'down') {
                if (item.quantity > 1) {
                    item.quantity--;
                    this.amountTendered = this.grandTotal;
                    this.playBeep();
                    this.showToast(item.name + ' Qty: ' + item.quantity, '▼', 'info');
                } else {
                    this.showToast(item.name + ' minimum quantity is 1', 'ℹ️', 'info');
                }
            }
        },

        selectPrevCartItem() {
            if (this.cart.length === 0) return;
            const current = this.getActiveCartIndex();
            this.selectedCartIndex = current > 0 ? current - 1 : 0;
            this.qtyKeyBuffer = '';
            const item = this.cart[this.selectedCartIndex];
            if (item) {
                this.showToast('Selected: ' + item.name, '👉', 'info');
            }
            this.scrollSelectedCartItemIntoView();
        },

        selectNextCartItem() {
            if (this.cart.length === 0) return;
            const current = this.getActiveCartIndex();
            this.selectedCartIndex = current < this.cart.length - 1 ? current + 1 : this.cart.length - 1;
            this.qtyKeyBuffer = '';
            const item = this.cart[this.selectedCartIndex];
            if (item) {
                this.showToast('Selected: ' + item.name, '👉', 'info');
            }
            this.scrollSelectedCartItemIntoView();
        },

        scrollSelectedCartItemIntoView() {
            this.$nextTick(() => {
                const el = document.querySelector('[data-cart-item="' + this.selectedCartIndex + '"]');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        },

        // Global Keyboard Shortcuts
        handleGlobalKeydown(e) {
            // Check modals first
            if (this.isAnyModalOpen()) {
                // Receipt / Sale Completed Modal specific hotkeys
                if (this.showReceiptModal) {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        this.reEditCurrentOrder();
                        return;
                    }
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.printReceiptAndNext(this.completedOrder.receipt_url);
                        return;
                    }
                    if (e.key === ' ') {
                        e.preventDefault();
                        this.resetForNextSale();
                        return;
                    }
                    return;
                }

                // Item Discount Modal specific hotkeys
                if (this.showItemDiscountModal) {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        this.showItemDiscountModal = false;
                        this.$nextTick(() => this.focusBarcodeInput());
                        return;
                    }
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.applyItemDiscount();
                        return;
                    }
                    return;
                }

                // Esc to close other modals
                if (e.key === 'Escape') {
                    this.categoryDropdownOpen = false;
                    this.showCustomerModal = false;
                    this.showQrModal = false;
                    this.showHeldOrdersModal = false;
                    this.showShortcutsModal = false;
                    this.showQuickProductModal = false;
                    this.showShiftModal = false;
                    this.showItemDiscountModal = false;
                    this.showPrinterConfigModal = false;
                    this.$nextTick(() => this.focusBarcodeInput());
                }
                return;
            }

            // Alt + ArrowUp / ArrowDown to navigate selected cart item
            if (e.altKey && !e.ctrlKey && !e.metaKey) {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.selectPrevCartItem();
                    return;
                }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.selectNextCartItem();
                    return;
                }
            }

            // Ctrl/Cmd shortcuts for Item Discount and Quantity manipulation
            if (e.ctrlKey || e.metaKey) {
                // Ctrl + Enter: Open Item Discount Modal for selected item
                if (e.key === 'Enter' || e.code === 'Enter' || e.code === 'NumpadEnter') {
                    e.preventDefault();
                    e.stopPropagation();
                    this.openItemDiscountModal();
                    return;
                }
                // Ctrl + ArrowUp: increase quantity
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    e.stopPropagation();
                    this.handleQuantityArrow('up');
                    return;
                }

                // Ctrl + ArrowDown: decrease quantity
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    e.stopPropagation();
                    this.handleQuantityArrow('down');
                    return;
                }

                // Ctrl + Digits 0-9: multi-digit quantity input (e.g. hold ctrl + 1, 2 => 12)
                const digit = this.extractDigit(e);
                if (digit !== null) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!e.repeat) {
                        this.handleQuantityDigit(digit);
                    }
                    return;
                }
            }

            // 1. Universal Automatic Barcode Scanner Enter / Tab Interceptor
            // Detects barcode scan ending with Enter or scanner Tab delimiter
            if (e.key === 'Enter' || (e.key === 'Tab' && this.barcodeScanBuffer.trim().length >= 2 && (Date.now() - this.lastScanTime < 80))) {
                const code = this.barcodeScanBuffer.trim() || (document.activeElement === this.$refs.barcodeInput ? this.searchQuery.trim() : '');
                if (code) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.barcodeScanBuffer = '';
                    this.processBarcodeScan(code);
                    return;
                }
            }

            // 2. Universal Barcode Keystroke Listener (Captures scanner wedge even when search field is not focused)
            if (e.key && e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                const now = Date.now();
                const timeDiff = now - this.lastScanTime;
                this.lastScanTime = now;

                const active = document.activeElement;
                const isOtherInput = active && active !== this.$refs.barcodeInput && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA');

                if (isOtherInput) {
                    // When another input is focused, only collect if keystrokes arrive at scanner speed (< 50ms)
                    if (timeDiff < 50) {
                        this.barcodeScanBuffer += e.key;
                    } else {
                        this.barcodeScanBuffer = '';
                    }
                } else {
                    // Not in another input: collect into scanner buffer
                    if (timeDiff > 250) {
                        this.barcodeScanBuffer = e.key;
                    } else {
                        this.barcodeScanBuffer += e.key;
                    }

                    // Ensure search input is focused and synced
                    if (active !== this.$refs.barcodeInput) {
                        this.focusBarcodeInput();
                        this.searchQuery = this.barcodeScanBuffer;
                    }

                    // Automatic scanner timeout fallback:
                    // If a rapid burst of characters (>= 6 chars, scanner speed) finishes and scanner didn't send Enter:
                    if (this.scanAutoProcessTimeout) clearTimeout(this.scanAutoProcessTimeout);
                    if (this.barcodeScanBuffer.length >= 6 && timeDiff < 75) {
                        this.scanAutoProcessTimeout = setTimeout(() => {
                            if (this.barcodeScanBuffer.length >= 6) {
                                const autoCode = this.barcodeScanBuffer.trim();
                                this.barcodeScanBuffer = '';
                                this.processBarcodeScan(autoCode);
                            }
                        }, 260);
                    }

                    if (this.scanBufferTimeout) clearTimeout(this.scanBufferTimeout);
                    this.scanBufferTimeout = setTimeout(() => {
                        this.barcodeScanBuffer = '';
                    }, 2000);
                }
            }

            // F2 or '/' to focus search
            if (e.key === 'F2' || (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA')) {
                e.preventDefault();
                this.$refs.barcodeInput?.focus();
                this.$refs.barcodeInput?.select();
                return;
            }

            // F4 to complete checkout
            if (e.key === 'F4') {
                e.preventDefault();
                this.processCheckout();
                return;
            }

            // Esc to clear search or close dropdown
            if (e.key === 'Escape') {
                this.categoryDropdownOpen = false;
                if (this.searchQuery) {
                    this.searchQuery = '';
                }
                this.barcodeScanBuffer = '';
                this.$nextTick(() => this.focusBarcodeInput());
            }
        },

        // Thermal Printer Configuration Actions
        savePrinterConfig() {
            try {
                localStorage.setItem('pos_thermal_printer_config', JSON.stringify(this.printerConfig));
                this.showToast('Printer settings saved successfully!', '🖨️', 'success');
                this.showPrinterConfigModal = false;
            } catch (e) {
                alert('Failed to save printer configuration.');
            }
        },

        resetPrinterConfig() {
            this.printerConfig = {
                paperWidth: '58mm',
                autoPrint: false,
                printMode: 'iframe',
                copies: 1,
                showLogo: true,
                showBarcode: true,
                showCashier: false,
                showCustomer: true,
                storeName: {!! json_encode(html_entity_decode(store_name(), ENT_QUOTES, 'UTF-8')) !!},
                storeTagline: 'Retail Mart & Everyday Essentials',
                storeAddress: "34, Jalan Perkasa 3, Taman Ungku Tun Aminah, 81300 Skudai, Johor Darul Ta'zim, Malaysia",
                storePhone: '+60 11-6412 9173',
                regNo: '',
                footerMessage: 'Thank you for shopping at ' + {!! json_encode(html_entity_decode(store_name(), ENT_QUOTES, 'UTF-8')) !!},
            };
            localStorage.removeItem('pos_thermal_printer_config');
            this.showToast('Printer settings restored to defaults (58mm)', '🔄', 'info');
        },

        // Universal Thermal Receipt Printer Dispatcher
        printReceipt(rawUrl) {
            if (!rawUrl) return;
            const cfg = this.printerConfig || {};
            const params = new URLSearchParams();
            params.set('print', '1');
            params.set('paper', cfg.paperWidth || '80mm');
            params.set('copies', cfg.copies || 1);
            params.set('show_logo', cfg.showLogo ? '1' : '0');
            params.set('show_barcode', cfg.showBarcode ? '1' : '0');
            params.set('show_cashier', cfg.showCashier ? '1' : '0');
            params.set('show_customer', cfg.showCustomer ? '1' : '0');
            if (cfg.storeName) params.set('header_name', cfg.storeName);
            if (cfg.storeTagline) params.set('tagline', cfg.storeTagline);
            if (cfg.storeAddress) params.set('address', cfg.storeAddress);
            if (cfg.storePhone) params.set('phone', cfg.storePhone);
            if (cfg.regNo) params.set('reg_no', cfg.regNo);
            if (cfg.footerMessage) params.set('footer', cfg.footerMessage);

            const delim = rawUrl.includes('?') ? '&' : '?';
            const finalUrl = `${rawUrl}${delim}${params.toString()}`;

            if (cfg.printMode === 'popup') {
                const width = cfg.paperWidth === '58mm' ? 330 : 420;
                const win = window.open(`${finalUrl}&popup=1`, 'ThermalReceiptSlip', `width=${width},height=700,left=150,top=100,scrollbars=yes,status=no`);
                if (win) {
                    win.focus();
                } else {
                    this.printViaIframe(finalUrl);
                }
            } else {
                this.printViaIframe(finalUrl);
            }

            this.showToast('Sending slip to printer...', '🖨️', 'success');
        },

        printViaIframe(url) {
            let iframe = document.getElementById('pos-thermal-print-iframe');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'pos-thermal-print-iframe';
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                iframe.style.visibility = 'hidden';
                document.body.appendChild(iframe);
            }
            iframe.src = 'about:blank';
            setTimeout(() => {
                iframe.src = url;
            }, 60);
        },

        testPrintReceipt() {
            const testUrl = "{{ route('admin.pos.receipt.test') }}";
            this.printReceipt(testUrl);
        },

        handleGlobalKeyup(e) {
            // When Control or Meta is released, clear digit buffer after a 500ms grace period
            if (e.key === 'Control' || e.key === 'Meta') {
                if (this.qtyBufferTimeout) {
                    clearTimeout(this.qtyBufferTimeout);
                }
                this.qtyBufferTimeout = setTimeout(() => {
                    this.qtyKeyBuffer = '';
                }, 500);
            }
        }
    };
}
</script>
@endsection
