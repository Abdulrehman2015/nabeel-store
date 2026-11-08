@extends('layouts.admin')

@section('title', 'Store Data Reset & Clean Slate')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    cleanSlateModal: false,
    cleanSlateInput: '',
    wipeProducts: false,
    wipeCategories: false,
    wipeBrands: false,
    wipeCustomers: false,
    wipeOrders: false,
    confirmText: '',
    selectAll(val) {
        this.wipeProducts = val;
        this.wipeCategories = val;
        this.wipeBrands = val;
        this.wipeCustomers = val;
        this.wipeOrders = val;
    },
    get hasSelection() {
        return this.wipeProducts || this.wipeCategories || this.wipeBrands || this.wipeCustomers || this.wipeOrders;
    },
    get canSubmit() {
        return this.hasSelection && this.confirmText.trim().toUpperCase() === 'DELETE';
    }
}">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Settings</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Data Management</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Catalog & Store Data Reset</h1>
            <p class="text-xs text-slate-500 mt-0.5">Purge test catalog data, POS transactions, orders, or perform a total clean slate wipe.</p>
        </div>
        <div class="flex items-center gap-2">
            <button 
                type="button" 
                @click="cleanSlateModal = true; cleanSlateInput = ''"
                class="px-4 py-2 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-500 hover:to-rose-600 text-white rounded-xl text-xs font-black shadow-lg shadow-rose-600/30 transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>1-Click Clean Slate</span>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs transition">
                Dashboard
            </a>
        </div>
    </div>

    {{-- Session Flash Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-xs font-bold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-xs font-bold text-rose-800">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Highlight: 1-Click Clean Slate Feature Card --}}
    <div class="p-6 bg-gradient-to-br from-slate-900 via-rose-950 to-slate-900 border border-rose-900/50 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-rose-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2 max-w-xl">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.8 rounded-full bg-rose-500/20 border border-rose-400/30 text-[10px] font-black uppercase tracking-wider text-rose-300">
                    Recommended For Testing
                </div>
                <h2 class="text-lg font-black tracking-tight text-white flex items-center gap-2">
                    <span>Clean Slate: Total Test Data Wipe</span>
                </h2>
                <p class="text-xs text-rose-200/80 leading-relaxed">
                    Testing complete hone ke baad ek click me tamam <strong>Products, Categories, Brands, Orders, POS Sales, Customer records aur Activity Logs</strong> delete ho jayenge.
                    <br>
                    <span class="text-emerald-400 font-bold">Strict Safety Guarantee:</span> Tamam Admin, Staff aur Cashier login accounts (Total {{ $counts['staff'] ?? 0 }}) 100% mehfooz rahenge!
                </p>
            </div>
            <button 
                type="button" 
                @click="cleanSlateModal = true; cleanSlateInput = ''"
                class="px-5 py-3 bg-rose-600 hover:bg-rose-500 text-white rounded-2xl text-xs font-black shadow-lg shadow-rose-900/50 transition shrink-0 cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Wipe Store & Clean Slate</span>
            </button>
        </div>
    </div>

    {{-- Clean Slate Confirmation Modal --}}
    <div 
        x-show="cleanSlateModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
        @keydown.escape.window="cleanSlateModal = false"
    >
        <div 
            class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-rose-200 space-y-5 animate-in fade-in zoom-in duration-150"
            @click.away="cleanSlateModal = false"
        >
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Are you absolutely sure?</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Ye action poore store ka data clean kar dega. Orders, sales, inventory, images permanently wipe ho jayenge.
                        <span class="block mt-1 text-emerald-700 font-bold">Admin/Cashier accounts ko koi nuksan nahi hoga.</span>
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.data-reset.clean-slate') }}" method="POST" class="space-y-4">
                @csrf
                <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl space-y-1.5">
                    <label class="block text-[11px] font-bold text-rose-900">
                        Type <span class="font-mono text-rose-600 text-xs font-black">CLEAN</span> in capital letters to confirm:
                    </label>
                    <input 
                        type="text" 
                        name="confirm_wipe" 
                        x-model="cleanSlateInput" 
                        placeholder="Type CLEAN"
                        required
                        class="w-full px-3.5 py-2.5 bg-white border border-rose-300 rounded-xl text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500"
                    >
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button 
                        type="button" 
                        @click="cleanSlateModal = false" 
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        :disabled="cleanSlateInput.trim().toUpperCase() !== 'CLEAN'"
                        class="px-5 py-2 bg-rose-600 hover:bg-rose-500 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-black rounded-xl shadow-md transition cursor-pointer"
                    >
                        Yes, Wipe Everything & Clean Slate
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Danger Warning Alert for Granular Wipes --}}
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="text-xs text-amber-900 leading-relaxed">
            <strong class="font-black text-amber-950 block text-sm mb-0.5">Custom Granular Reset</strong>
            Agar aap sirf kisi ek entity (jaise sirf Orders ya sirf Products) ko delete karna chahte hain to neeche diye gaye boxes select kar ke DELETE type karein.
        </div>
    </div>

    {{-- Main Granular Selection Form --}}
    <form action="{{ route('admin.data-reset.process') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Select / Deselect Bar --}}
        <div class="flex items-center justify-between text-xs font-bold text-slate-600 px-1">
            <span>Select Specific Tables to Delete</span>
            <div class="space-x-3">
                <button type="button" @click="selectAll(true)" class="text-sky-600 hover:text-sky-700 underline cursor-pointer">Select All</button>
                <button type="button" @click="selectAll(false)" class="text-slate-500 hover:text-slate-700 underline cursor-pointer">Deselect All</button>
            </div>
        </div>

        {{-- Entity Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- 1. Orders & POS Transactions --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4"
                :class="wipeOrders ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_orders" value="1" x-model="wipeOrders" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">Orders & POS Transactions</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['orders'] ?? 0) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes all POS sales, online orders, line items, and order timeline logs.
                    </p>
                </div>
            </label>

            {{-- 2. Products --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4"
                :class="wipeProducts ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_products" value="1" x-model="wipeProducts" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">All Products & Stock</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['products']) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes all product listings, SKU variants, prices, inventory records, and uploaded gallery images.
                    </p>
                </div>
            </label>

            {{-- 3. Categories --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4"
                :class="wipeCategories ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_categories" value="1" x-model="wipeCategories" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">All Categories</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['categories']) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes all store aisles, parent departments, icons, and category navigation mappings.
                    </p>
                </div>
            </label>

            {{-- 4. Brands --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4"
                :class="wipeBrands ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_brands" value="1" x-model="wipeBrands" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">All Brands</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['brands']) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes all manufacturer brands and brand logos from the catalog filters.
                    </p>
                </div>
            </label>

            {{-- 5. Customers --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4 sm:col-span-2"
                :class="wipeCustomers ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_customers" value="1" x-model="wipeCustomers" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">All Shopper/Customer Accounts</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['customers']) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes registered shopper accounts, customer addresses, and customer OTPs. (All {{ $counts['staff'] ?? 0 }} Admin and Cashier accounts remain 100% untouched).
                    </p>
                </div>
            </label>

        </div>

        {{-- Final Confirmation Box --}}
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 space-y-4 shadow-xs">
            <h3 class="font-black text-slate-900 text-sm">Confirmation Required</h3>
            <p class="text-xs text-slate-500">
                To prevent accidental deletion, type <strong class="text-rose-600 font-mono">DELETE</strong> in uppercase below to confirm:
            </p>

            <div class="max-w-md">
                <input 
                    type="text" 
                    name="confirm_text"
                    x-model="confirmText"
                    placeholder="Type DELETE to enable button" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500"
                    required
                >
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button 
                    type="submit" 
                    :disabled="!canSubmit"
                    class="px-6 py-2.5 bg-rose-600 hover:bg-rose-500 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/30 transition cursor-pointer"
                >
                    Permanently Delete Selected
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>

</div>
@endsection
