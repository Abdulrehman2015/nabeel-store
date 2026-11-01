<div class="bg-white rounded-2xl border border-slate-200 hover:border-sky-400 hover:shadow-lg transition-all duration-200 flex flex-col justify-between overflow-hidden group">
    <div class="p-4">
        {{-- Product Thumbnail with Flags --}}
        <div class="relative w-full aspect-square bg-slate-50 rounded-xl overflow-hidden mb-3 border border-slate-100 flex items-center justify-center p-3">
            <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ $product->image_url }}" 
                    alt="{{ $product->name }}" 
                    class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                    loading="lazy"
                >
            </a>

            {{-- Badges --}}
            <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 items-start">
                @if($product->is_on_sale)
                    <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] font-black rounded-md tracking-wider uppercase shadow-sm">
                        -{{ $product->discount_percent }}% OFF
                    </span>
                @endif
                @if($product->is_new)
                    <span class="px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-black rounded-md tracking-wider uppercase shadow-sm">
                        NEW
                    </span>
                @endif
            </div>

            @if(!$product->in_stock)
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px] flex items-center justify-center">
                    <span class="px-3 py-1 bg-white text-slate-900 text-xs font-black rounded-full uppercase tracking-wider shadow">
                        Out of Stock
                    </span>
                </div>
            @endif
        </div>

        {{-- Category & SKU --}}
        <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
            <span class="font-semibold text-sky-600 truncate">{{ $product->category->name }}</span>
            <span class="font-mono">{{ $product->sku }}</span>
        </div>

        {{-- Product Name --}}
        <h3 class="text-sm font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-sky-600 transition">
            <a href="{{ route('product.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Carton / Pack Size Callout --}}
        <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-slate-100 text-slate-700 rounded-md text-[11px] font-semibold">
            <span>📦</span>
            <span class="truncate">{{ $product->pack_size }}</span>
        </div>
    </div>

    {{-- Bottom Pricing & Add to Cart --}}
    <div class="p-4 pt-0 border-t border-slate-100 mt-2 bg-slate-50/50">
        <div class="flex items-baseline justify-between pt-3 mb-3">
            <div>
                <span class="text-lg font-black text-slate-900">
                    {{ format_currency($product->current_price) }}
                </span>
                <span class="text-[10px] text-slate-500 font-semibold block">/ carton</span>
            </div>
            @if($product->is_on_sale)
                <span class="text-xs text-slate-400 line-through font-medium">
                    {{ format_currency($product->price) }}
                </span>
            @endif
        </div>

        @if($product->in_stock)
            <div class="flex items-center gap-2" x-data="{ qty: 1 }">
                <div class="flex items-center border border-slate-300 rounded-xl bg-white overflow-hidden shadow-sm">
                    <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-2.5 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs">-</button>
                    <span class="px-2 text-xs font-bold text-slate-800" x-text="qty"></span>
                    <button type="button" @click="qty = Math.min({{ $product->stock_quantity }}, qty + 1)" class="px-2.5 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs">+</button>
                </div>
                <button 
                    type="button" 
                    @click="addToCart({{ $product->id }}, qty)"
                    class="flex-1 py-2 px-3 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center justify-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Add</span>
                </button>
            </div>
        @else
            <button disabled class="w-full py-2 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed">
                Sold Out
            </button>
        @endif
    </div>
</div>

