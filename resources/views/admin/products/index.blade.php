@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="space-y-6" x-data="{ importModal: false }">

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
        <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                
                {{-- Search Input --}}
                <div class="lg:col-span-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search product title, SKU, packaging..." 
                        class="w-full text-xs pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50"
                    >
                </div>

                {{-- Category Filter --}}
                <div class="lg:col-span-3">
                    <select name="category" class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50 text-slate-700">
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
                    <select name="stock_status" class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50 text-slate-700">
                        <option value="">All Stock Levels</option>
                        <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (> 20)</option>
                        <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock (&le; 20)</option>
                        <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                    </select>
                </div>

                {{-- Badges Filter --}}
                <div class="lg:col-span-2">
                    <select name="badge" class="w-full text-xs py-2.5 px-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 bg-slate-50/50 text-slate-700">
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
                    @if(request()->hasAny(['search', 'category', 'stock_status', 'badge', 'brand', 'status']))
                        <a href="{{ route('admin.products.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs transition" title="Clear Filters">
                            ✕
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- 4. Products Table Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-extrabold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Product & Store Link</th>
                        <th class="px-4 py-3.5">SKU / Item</th>
                        <th class="px-4 py-3.5">Category & Brand</th>
                        <th class="px-4 py-3.5">Price</th>
                        <th class="px-4 py-3.5">Stock Level</th>
                        <th class="px-4 py-3.5">Badges</th>
                        <th class="px-4 py-3.5">Visibility</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/70 transition group">
                            
                            {{-- Product Thumbnail & Live Link --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3.5 min-w-[260px] max-w-sm">
                                    <div class="w-12 h-12 rounded-xl border border-slate-200 bg-white p-1 shrink-0 overflow-hidden shadow-2xs group-hover:border-sky-300 transition flex items-center justify-center">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="font-extrabold text-slate-900 group-hover:text-sky-600 transition block truncate text-xs sm:text-sm" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            @if($product->pack_size)
                                                <span class="text-[11px] text-slate-500 font-medium truncate">
                                                    {{ $product->pack_size }}
                                                </span>
                                            @endif
                                            <a 
                                                href="{{ route('product.show', $product->slug) }}" 
                                                target="_blank" 
                                                class="text-[10px] text-sky-600 hover:text-sky-800 hover:underline font-mono inline-flex items-center gap-0.5 shrink-0" 
                                                title="View product in storefront"
                                            >
                                                <span>View</span>
                                                <svg class="w-2.5 h-2.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- SKU --}}
                            <td class="px-4 py-3.5">
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/70 inline-block">
                                    {{ $product->sku }}
                                </span>
                            </td>

                            {{-- Category & Brand --}}
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-900 text-xs block truncate max-w-[160px]">
                                    {{ $product->category->name }}
                                </span>
                                @if($product->brand)
                                    <span class="text-[11px] text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        {{ $product->brand->name }}
                                    </span>
                                @endif
                            </td>

                            {{-- Price & Discounts --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-black text-slate-900 text-sm">
                                    {{ format_currency($product->current_price) }}
                                </div>
                                @if($product->is_on_sale)
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-[10px] text-slate-400 line-through font-medium">
                                            {{ format_currency($product->price) }}
                                        </span>
                                        <span class="text-[9px] font-black text-rose-600 bg-rose-50 px-1 rounded border border-rose-200">
                                            -{{ $product->discount_percent }}%
                                        </span>
                                    </div>
                                @endif
                            </td>

                            {{-- Stock Level --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($product->stock_quantity <= 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        Out of Stock (0)
                                    </span>
                                @elseif($product->stock_quantity <= 20)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Low: {{ $product->stock_quantity }} units
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $product->stock_quantity }} units
                                    </span>
                                @endif
                            </td>

                            {{-- Badges --}}
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1 max-w-[140px]">
                                    @if($product->is_promo)
                                        <span class="px-1.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200/90 rounded text-[9px] font-black tracking-wider uppercase">
                                            PROMO
                                        </span>
                                    @endif
                                    @if($product->is_new)
                                        <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/90 rounded text-[9px] font-black tracking-wider uppercase">
                                            NEW
                                        </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="px-1.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200/90 rounded text-[9px] font-black tracking-wider uppercase">
                                            FEATURED
                                        </span>
                                    @endif
                                    @if(!$product->is_promo && !$product->is_new && !$product->is_featured)
                                        <span class="text-slate-400 text-[11px]">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Visibility Status --}}
                            <td class="px-4 py-3.5">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a 
                                        href="{{ route('product.show', $product->slug) }}" 
                                        target="_blank" 
                                        class="p-1.5 bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 rounded-lg transition border border-slate-200/70" 
                                        title="View in Customer Storefront"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    
                                    <a 
                                        href="{{ route('admin.products.edit', $product->id) }}" 
                                        class="p-1.5 bg-slate-100 hover:bg-sky-50 text-slate-700 hover:text-sky-700 rounded-lg transition border border-slate-200/70" 
                                        title="Edit Product"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($product->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 rounded-lg transition border border-slate-200/70 cursor-pointer" 
                                            title="Delete Product"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-14 text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto text-2xl mb-3 border border-slate-200">
                                    📦
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">No products found</h3>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Try adjusting your search criteria or clear the filters to view all mart catalog products.</p>
                                <div class="mt-4">
                                    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-500 transition shadow-sm">
                                        + Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div>
                    Showing <span class="font-bold text-slate-800">{{ $products->firstItem() }}</span> to <span class="font-bold text-slate-800">{{ $products->lastItem() }}</span> of <span class="font-bold text-slate-800">{{ $products->total() }}</span> products
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
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
