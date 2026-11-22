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
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain rounded-lg" onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}';">
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
                            <div class="flex items-center gap-1.5 mt-0.5 text-[10px]">
                                <span class="text-slate-400 font-medium">Cost: <strong class="text-slate-600">{{ format_currency($product->purchase_price ?? 0) }}</strong></span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="font-extrabold {{ ($product->unit_profit ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                    +{{ format_currency($product->unit_profit ?? 0) }}
                                </span>
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
                            @elseif($product->is_low_stock)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-800 border border-amber-200" title="Low stock threshold: {{ $product->effective_low_stock_threshold }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Low: {{ $product->stock_quantity }} (limit: {{ $product->effective_low_stock_threshold }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200" title="Alert threshold: {{ $product->effective_low_stock_threshold }}">
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

                                @if(auth()->user()->isAdmin())
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
                                @endif
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
