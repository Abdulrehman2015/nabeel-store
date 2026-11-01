@extends('layouts.admin')

@section('title', 'Wholesale Products')

@section('content')
<div class="space-y-6" x-data="{ importModal: false }">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Wholesale Products (Cartons)</h1>
            <p class="text-xs text-slate-500 mt-1">Manage bulk catalog, pack sizes, stock quantities, and bulk CSV import/export.</p>
        </div>
        <div class="flex items-center gap-2">
            <button 
                type="button" 
                @click="importModal = true" 
                class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5"
            >
                <span>📥</span>
                <span>Bulk Import CSV</span>
            </button>
            <a 
                href="{{ route('admin.products.export') }}" 
                class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5"
            >
                <span>📤</span>
                <span>Export CSV</span>
            </a>
            <a 
                href="{{ route('admin.products.create') }}" 
                class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5"
            >
                <span>+</span>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by Product Name, SKU, or Carton size..." 
                    class="w-full text-xs px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-sky-500"
                >
            </div>
            <div>
                <select name="category" class="w-full text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-sky-500 bg-slate-50">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <select name="stock_status" class="flex-1 text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-sky-500 bg-slate-50">
                    <option value="">All Stock</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock (&le; 20)</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                        <th class="py-3 px-4">Product / Carton</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Wholesale Price</th>
                        <th class="py-3 px-4">Stock (Cartons)</th>
                        <th class="py-3 px-4">Badges</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" class="w-10 h-10 object-contain rounded-lg border bg-slate-50 p-1 flex-shrink-0">
                                    <div class="truncate max-w-xs">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="font-bold text-slate-900 hover:text-sky-600 block truncate">
                                            {{ $product->name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 block">{{ $product->pack_size }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">{{ $product->sku }}</td>
                            <td class="py-3.5 px-4 font-medium text-slate-800">{{ $product->category->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900">{{ format_currency($product->current_price) }}</span>
                                @if($product->is_on_sale)
                                    <span class="text-[10px] text-slate-400 line-through block">{{ format_currency($product->price) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded font-black text-xs {{ $product->stock_quantity <= 10 ? 'bg-rose-100 text-rose-800' : ($product->stock_quantity <= 25 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-50 text-emerald-800') }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 space-x-1">
                                @if($product->is_promo) <span class="px-1.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded text-[10px] font-bold">PROMO</span> @endif
                                @if($product->is_new) <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold">NEW</span> @endif
                                @if($product->is_featured) <span class="px-1.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 rounded text-[10px] font-bold">FEATURED</span> @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $product->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg text-[11px] transition inline-block">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-[11px] font-bold">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No wholesale products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Bulk CSV Import Modal (Phase 2 Requirement) --}}
    <div x-show="importModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60" @click="importModal = false"></div>
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full relative z-10 shadow-2xl space-y-4">
            <h3 class="text-lg font-black text-slate-900">Bulk Import Wholesale Cartons</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Upload a CSV or Excel spreadsheet containing your carton catalog. Products with matching SKUs will be automatically updated, and new products will be created.
            </p>

            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Select CSV or Excel File *</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.xls" required class="w-full text-xs border border-slate-300 rounded-xl p-2 bg-slate-50">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="importModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow">Upload & Process</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
