@extends('layouts.admin')

@section('title', 'Store Data Reset & Catalog Cleanup')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    wipeProducts: false,
    wipeCategories: false,
    wipeBrands: false,
    wipeCustomers: false,
    confirmText: '',
    selectAll(val) {
        this.wipeProducts = val;
        this.wipeCategories = val;
        this.wipeBrands = val;
        this.wipeCustomers = val;
    },
    get hasSelection() {
        return this.wipeProducts || this.wipeCategories || this.wipeBrands || this.wipeCustomers;
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
            <p class="text-xs text-slate-500 mt-0.5">Bulk delete all products, categories, manufacturer brands, and customer accounts</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs transition">
            Dashboard
        </a>
    </div>

    {{-- Danger Warning Alert --}}
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="text-xs text-rose-900 leading-relaxed">
            <strong class="font-black text-rose-950 block text-sm mb-0.5">Danger Zone — Irreversible Data Deletion</strong>
            Deleting records from this tool permanently removes them from the database and deletes associated storage images. Admin staff accounts and completed order invoices are preserved.
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs font-bold text-emerald-800 flex items-center gap-2">
            <span>✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.data-reset.process') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Selection Header with Quick Toggles --}}
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Select Entities to Delete:</span>
            <div class="flex items-center gap-2">
                <button type="button" @click="selectAll(true)" class="text-xs font-bold text-sky-600 hover:text-sky-800 hover:underline">Select All</button>
                <span class="text-slate-300">|</span>
                <button type="button" @click="selectAll(false)" class="text-xs font-bold text-slate-500 hover:text-slate-700 hover:underline">Deselect All</button>
            </div>
        </div>

        {{-- 4 Entity Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            {{-- 1. Products --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4"
                :class="wipeProducts ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_products" value="1" x-model="wipeProducts" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">All Products</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['products']) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes all catalog inventory, retail prices, SKU barcodes, unit specs, and uploaded product images.
                    </p>
                </div>
            </label>

            {{-- 2. Categories --}}
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

            {{-- 3. Brands --}}
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

            {{-- 4. Customers --}}
            <label class="p-5 bg-white rounded-2xl border-2 transition cursor-pointer flex items-start gap-4"
                :class="wipeCustomers ? 'border-rose-500 bg-rose-50/20 shadow-sm' : 'border-slate-200/90 hover:border-slate-300'">
                <input type="checkbox" name="wipe_customers" value="1" x-model="wipeCustomers" class="mt-1 w-5 h-5 text-rose-600 border-slate-300 rounded-lg focus:ring-rose-500">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-black text-slate-900 text-sm">All Customers</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-mono font-bold text-xs">{{ number_format($counts['customers']) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Deletes registered shopper accounts, saved addresses, and login profiles. Admin accounts remain intact.
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
