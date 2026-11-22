@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="space-y-6" x-data="{
    importModal: false,
    searchQuery: '{{ addslashes(request('search', '')) }}',
    suggestions: [],
    showSuggestions: false,
    isSearching: false,
    isLoadingTable: false,
    selectedIndex: -1,
    searchTimeout: null,

    // 1. Live suggestions dropdown
    fetchSuggestions() {
        clearTimeout(this.searchTimeout);
        const q = (this.searchQuery || '').trim();
        if (q.length < 1) {
            this.suggestions = [];
            this.showSuggestions = false;
            return;
        }
        this.searchTimeout = setTimeout(async () => {
            this.isSearching = true;
            try {
                const endpoint = '{{ route('admin.products.suggestions', [], false) }}?q=' + encodeURIComponent(q);
                const res = await fetch(endpoint, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success && Array.isArray(data.products)) {
                    this.suggestions = data.products;
                    this.showSuggestions = this.suggestions.length > 0;
                    this.selectedIndex = -1;
                } else {
                    this.suggestions = [];
                }
            } catch (e) {
                console.error('Products suggestions error:', e);
            } finally {
                this.isSearching = false;
            }
        }, 120);
    },

    // 2. Real-time AJAX load table without page reload
    async loadTable(customUrl = null) {
        this.isLoadingTable = true;
        try {
            let targetUrl;
            if (customUrl) {
                targetUrl = new URL(customUrl, window.location.origin);
            } else {
                targetUrl = new URL('{{ route('admin.products.index', [], false) }}', window.location.origin);
                const form = this.$refs.searchForm;
                if (form) {
                    const formData = new FormData(form);
                    formData.set('search', (this.searchQuery || '').trim());
                    for (const [k, v] of formData.entries()) {
                        if (v && String(v).trim() !== '') {
                            targetUrl.searchParams.set(k, String(v).trim());
                        }
                    }
                }
            }
            targetUrl.searchParams.set('table_only', '1');

            const res = await fetch(targetUrl.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (res.ok) {
                const html = await res.text();
                const container = document.getElementById('productsTableContainer');
                if (container) {
                    container.innerHTML = html;
                }
                // Sync URL bar in browser without page refresh
                const displayUrl = new URL(targetUrl.toString());
                displayUrl.searchParams.delete('table_only');
                window.history.replaceState({}, '', displayUrl.pathname + (displayUrl.search || ''));
            }
        } catch (err) {
            console.error('Failed to load products table:', err);
        } finally {
            this.isLoadingTable = false;
        }
    },

    // 3. Select suggestion - Real-time update without page refresh
    selectSuggestion(item) {
        if (!item) return;
        this.searchQuery = item.name;
        this.showSuggestions = false;
        const input = document.getElementById('catalogSearchInput') || document.querySelector('input[name=\'search\']');
        if (input) {
            input.value = item.name;
        }
        this.loadTable();
    },

    // 4. Press Enter in search input - Real-time update without page refresh
    handleSearchEnter() {
        if (this.showSuggestions && this.selectedIndex >= 0 && this.suggestions[this.selectedIndex]) {
            this.selectSuggestion(this.suggestions[this.selectedIndex]);
            return;
        }
        this.showSuggestions = false;
        this.loadTable();
    },

    // 5. Cross (✕) Click - Real-time clear without page refresh
    clearSearch() {
        this.searchQuery = '';
        this.suggestions = [];
        this.showSuggestions = false;
        const input = document.getElementById('catalogSearchInput') || document.querySelector('input[name=\'search\']');
        if (input) {
            input.value = '';
            input.focus();
        }
        this.loadTable();
    },

    // 6. Reset all filters - Real-time without page refresh
    resetAllFilters() {
        this.searchQuery = '';
        this.suggestions = [];
        this.showSuggestions = false;
        const form = this.$refs.searchForm;
        if (form) {
            form.reset();
            const selects = form.querySelectorAll('select');
            selects.forEach(s => s.value = '');
        }
        const input = document.getElementById('catalogSearchInput');
        if (input) {
            input.value = '';
        }
        this.loadTable('{{ route('admin.products.index', [], false) }}');
    },

    // 7. Handle pagination clicks via AJAX
    handleTableClick(e) {
        const link = e.target.closest('.pagination a, a[rel=\'prev\'], a[rel=\'next\'], a.page-link');
        if (link && link.href) {
            e.preventDefault();
            this.loadTable(link.href);
        }
    }
}">

    {{-- 1. Top Header with Breadcrumbs & Action Bar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Catalog</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Products</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Products Catalog</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage store inventory, retail pricing, unit packaging, and bulk operations.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <a 
                href="{{ route('category.index') }}" 
                target="_blank" 
                class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-sky-400 hover:text-sky-600 transition flex items-center gap-1.5"
                title="Open customer storefront"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span class="hidden sm:inline">Storefront</span>
            </a>

            <button 
                type="button" 
                @click="importModal = true" 
                class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-sky-400 hover:text-sky-600 transition flex items-center gap-1.5 cursor-pointer"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Bulk Import</span>
            </button>

            <a 
                href="{{ route('admin.products.export') }}" 
                class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-sky-400 hover:text-sky-600 transition flex items-center gap-1.5"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export CSV</span>
            </a>

            <a 
                href="{{ route('admin.products.create') }}" 
                class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 flex items-center gap-2 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    {{-- 2. KPI Metrics Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        
        {{-- Total Products --}}
        <a href="{{ route('admin.products.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-sky-400 hover:shadow-sm transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Total Catalog</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                    📦
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($stats['total']) }}</div>
            <span class="text-[10px] text-slate-400 font-medium">Unique product items</span>
        </a>

        {{-- Active in Store --}}
        <a href="{{ route('admin.products.index', ['status' => 'active']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-emerald-400 hover:shadow-sm transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Active Live</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                    ✓
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-700">{{ number_format($stats['active']) }}</div>
            <span class="text-[10px] text-emerald-600/80 font-medium">Visible to customers</span>
        </a>

        {{-- Low Stock Alert --}}
        <a href="{{ route('admin.products.index', ['stock_status' => 'low']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-amber-400 hover:shadow-sm transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Low Stock</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                    ⚠️
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600">{{ number_format($stats['low_stock']) }}</div>
            <span class="text-[10px] text-amber-600/80 font-medium">&le; 20 units remaining</span>
        </a>

        {{-- Out of Stock --}}
        <a href="{{ route('admin.products.index', ['stock_status' => 'out']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-rose-400 hover:shadow-sm transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Out of Stock</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                    ⛔
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600">{{ number_format($stats['out_of_stock']) }}</div>
            <span class="text-[10px] text-rose-600/80 font-medium">Needs replenishment</span>
        </a>

        {{-- Promo Deals --}}
        <a href="{{ route('admin.products.index', ['badge' => 'promo']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-purple-400 hover:shadow-sm transition-all group col-span-2 sm:col-span-1">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Promo Deals</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                    🔥
                </div>
            </div>
            <div class="text-2xl font-black text-purple-700">{{ number_format($stats['promo']) }}</div>
            <span class="text-[10px] text-purple-600/80 font-medium">Active price discounts</span>
        </a>

    </div>

    {{-- 3. Comprehensive Filter & Search Bar --}}
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <form x-ref="searchForm" @submit.prevent="loadTable()" action="{{ route('admin.products.index') }}" method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                
                {{-- Search Input with Live Suggestions Dropdown --}}
                <div class="lg:col-span-4 relative" @click.outside="showSuggestions = false">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        id="catalogSearchInput"
                        name="search" 
                        x-model="searchQuery"
                        @input="fetchSuggestions()"
                        @focus="if((searchQuery || '').trim().length > 0) { showSuggestions = true; fetchSuggestions(); }"
                        @keydown.arrow-down.prevent="if(suggestions.length > 0) { showSuggestions = true; selectedIndex = (selectedIndex + 1) % suggestions.length; }"
                        @keydown.arrow-up.prevent="if(suggestions.length > 0) { showSuggestions = true; selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length; }"
                        @keydown.enter.prevent="handleSearchEnter()"
                        @keydown.escape="showSuggestions = false"
                        value="{{ request('search') }}" 
                        placeholder="Search product title, SKU, packaging..." 
                        class="w-full text-xs pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50"
                        autocomplete="off"
                    >
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1 z-30">
                        <span x-show="isSearching || isLoadingTable" class="text-slate-400 animate-spin">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </span>
                        <button 
                            type="button" 
                            x-show="!isSearching && !isLoadingTable && searchQuery && searchQuery.length > 0" 
                            @click.stop.prevent="clearSearch()"
                            class="text-slate-400 hover:text-rose-600 p-1.5 cursor-pointer transition flex items-center justify-center rounded-lg hover:bg-slate-100"
                            title="Clear search"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Autocomplete Dropdown List --}}
                    <div 
                        x-show="showSuggestions && suggestions.length > 0" 
                        x-cloak
                        class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100 max-h-80 overflow-y-auto"
                    >
                        <div class="px-3 py-1.5 bg-slate-50 text-[10px] uppercase font-bold text-slate-400 tracking-wider flex items-center justify-between">
                            <span>Product Suggestions</span>
                            <span class="text-sky-600 font-bold" x-text="suggestions.length + ' found'"></span>
                        </div>
                        <template x-for="(item, idx) in suggestions" :key="item.id">
                            <div 
                                @click="selectSuggestion(item)"
                                @mouseenter="selectedIndex = idx"
                                :class="selectedIndex === idx ? 'bg-sky-50/80 text-sky-950 ring-1 ring-inset ring-sky-300' : 'hover:bg-slate-50 text-slate-800'"
                                class="p-2.5 flex items-center justify-between gap-3 cursor-pointer transition select-none"
                            >
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <img :src="item.image_url" :alt="item.name" class="w-8 h-8 rounded-lg object-contain bg-slate-50 border border-slate-200/80 shrink-0">
                                    <div class="min-w-0">
                                        <div class="font-bold text-xs truncate" x-text="item.name"></div>
                                        <div class="text-[10px] text-slate-400 flex items-center gap-1.5 font-mono">
                                            <span x-text="item.sku"></span>
                                            <span>•</span>
                                            <span class="text-slate-500 font-sans" x-text="item.category"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="font-mono font-extrabold text-xs text-slate-900">
                                        RM <span x-text="item.price.toFixed(2)"></span>
                                    </div>
                                    <div class="text-[10px]" :class="item.stock_quantity > 0 ? 'text-emerald-600 font-semibold' : 'text-rose-500 font-bold'">
                                        <span x-text="item.stock_quantity"></span> units
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="lg:col-span-3">
                    <select name="category" @change="loadTable()" class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50 text-slate-700">
                        <option value="">All Categories ({{ $categories->count() }})</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stock Status Filter --}}
                <div class="lg:col-span-2">
                    <select name="stock_status" @change="loadTable()" class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50 text-slate-700">
                        <option value="">All Stock Levels</option>
                        <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (Optimal)</option>
                        <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock (&le; Alert Limit)</option>
                        <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                    </select>
                </div>

                {{-- Badges Filter --}}
                <div class="lg:col-span-2">
                    <select name="badge" @change="loadTable()" class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50 text-slate-700">
                        <option value="">All Badges</option>
                        <option value="promo" {{ request('badge') === 'promo' ? 'selected' : '' }}>🔥 Promo Deals</option>
                        <option value="new" {{ request('badge') === 'new' ? 'selected' : '' }}>✨ New Arrivals</option>
                        <option value="featured" {{ request('badge') === 'featured' ? 'selected' : '' }}>⭐ Featured</option>
                    </select>
                </div>

                {{-- Action Filter Buttons --}}
                <div class="lg:col-span-1 flex items-center gap-1.5">
                    <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition shadow-xs cursor-pointer text-center">
                        Filter
                    </button>
                    <button 
                        type="button" 
                        @click.prevent="resetAllFilters()" 
                        class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs transition cursor-pointer" 
                        title="Clear Filters"
                    >
                        ✕
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- 4. Products Table Card (Real-time AJAX update container) --}}
    <div 
        id="productsTableContainer" 
        class="relative transition-opacity duration-200" 
        :class="isLoadingTable ? 'opacity-50 pointer-events-none' : 'opacity-100'"
        @click="handleTableClick($event)"
    >
        @include('admin.products._table', ['products' => $products])
    </div>

    {{-- 5. Bulk CSV Import Modal --}}
    <div x-show="importModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="importModal = false"></div>
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full relative z-10 shadow-2xl space-y-5 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg">
                        📥
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Bulk Import Products</h3>
                        <p class="text-xs text-slate-500">Upload CSV spreadsheet to update or add items</p>
                    </div>
                </div>
                <button type="button" @click="importModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                    ✕
                </button>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-600 leading-relaxed space-y-1">
                <div class="font-bold text-slate-800">CSV Column Format Requirements:</div>
                <p>Ensure your CSV headers include: <code class="font-mono bg-white px-1 py-0.5 rounded border text-[11px]">name</code>, <code class="font-mono bg-white px-1 py-0.5 rounded border text-[11px]">sku</code>, <code class="font-mono bg-white px-1 py-0.5 rounded border text-[11px]">price</code>, <code class="font-mono bg-white px-1 py-0.5 rounded border text-[11px]">category</code>, and <code class="font-mono bg-white px-1 py-0.5 rounded border text-[11px]">stock</code>.</p>
                <p class="text-slate-400">Products with matching SKUs will be updated automatically.</p>
            </div>

            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Select CSV or Excel File *</label>
                    <input 
                        type="file" 
                        name="file" 
                        accept=".csv,.xlsx,.xls" 
                        required 
                        class="w-full text-xs border border-slate-300 rounded-xl p-2.5 bg-slate-50/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer"
                    >
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="importModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
