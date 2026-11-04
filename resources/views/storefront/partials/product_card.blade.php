<div class="bg-white rounded-2xl border border-slate-200/90 hover:border-sky-500 hover:shadow-xl hover:shadow-sky-500/10 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
    <div class="p-3.5 sm:p-4">
        {{-- Product Thumbnail with Flags --}}
        <div class="relative w-full aspect-square bg-slate-50/80 rounded-xl overflow-hidden mb-3 border border-slate-100 flex items-center justify-center p-3">
            <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ $product->image_url }}" 
                    alt="{{ $product->name }}" 
                    class="max-h-full max-w-full object-contain group-hover:scale-106 transition-transform duration-300 drop-shadow-xs"
                    loading="lazy"
                >
            </a>

            {{-- Discount & Status Badges --}}
            <div class="absolute top-2 left-2 flex flex-col gap-1 items-start z-10">
                @if($product->is_on_sale)
                    <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] font-black rounded-md tracking-wider uppercase shadow-xs">
                        -{{ $product->discount_percent }}% OFF
                    </span>
                @endif
                @if($product->is_new)
                    <span class="px-2 py-0.5 bg-sky-600 text-white text-[10px] font-black rounded-md tracking-wider uppercase shadow-xs">
                        NEW CASE
                    </span>
                @endif
            </div>

            {{-- Pack Size Micro-Badge --}}
            @if($product->pack_size)
                <div class="absolute bottom-2 right-2 z-10">
                    <span class="px-2 py-0.5 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-bold rounded-md shadow-xs">
                        {{ $product->pack_size }}
                    </span>
                </div>
            @endif

            @if(!$product->in_stock)
                <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center z-20">
                    <span class="px-3 py-1 bg-white text-slate-950 text-xs font-black rounded-full uppercase tracking-wider shadow-md">
                        Sold Out
                    </span>
                </div>
            @endif
        </div>

        {{-- Category & SKU Line --}}
        <div class="flex items-center justify-between text-[11px] mb-1">
            <span class="font-bold text-sky-600 uppercase tracking-wider text-[10px] truncate max-w-[65%]">
                {{ $product->category->name ?? 'General' }}
            </span>
            <span class="font-mono text-[10px] text-slate-400">
                {{ $product->sku }}
            </span>
        </div>

        {{-- Product Title --}}
        <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-sky-600 transition min-h-[2.5rem]">
            <a href="{{ route('product.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Stock Indicator Pill --}}
        <div class="mt-2 flex items-center gap-1.5">
            @if($product->in_stock)
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    In Stock ({{ $product->stock_quantity }} cases)
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Restocking Soon
                </span>
            @endif
        </div>
    </div>

    {{-- Bottom Pricing & Cart Action --}}
    <div class="p-3.5 sm:p-4 pt-0 border-t border-slate-100 mt-1 bg-slate-50/40">
        <div class="flex items-baseline justify-between pt-2.5 mb-2.5">
            <div>
                <span class="text-base sm:text-lg font-black text-slate-900">
                    {{ format_currency($product->current_price) }}
                </span>
                <span class="text-[10px] text-slate-500 font-semibold block">/ per pack</span>
            </div>
            @if($product->is_on_sale)
                <span class="text-xs text-slate-400 line-through font-medium">
                    {{ format_currency($product->price) }}
                </span>
            @endif
        </div>

        @if($product->in_stock)
            <div class="flex items-center gap-2" x-data="{ qty: 1 }">
                <div class="flex items-center border border-slate-300 rounded-xl bg-white overflow-hidden shadow-2xs">
                    <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-2 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs cursor-pointer">-</button>
                    <span class="px-2 text-xs font-bold text-slate-800" x-text="qty"></span>
                    <button type="button" @click="qty = Math.min({{ $product->stock_quantity }}, qty + 1)" class="px-2 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs cursor-pointer">+</button>
                </div>
                <button 
                    type="button" 
                    @click="addToCart({{ $product->id }}, qty)"
                    class="flex-1 py-2 px-3 bg-sky-600 hover:bg-sky-500 active:scale-95 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Add Carton</span>
                </button>
            </div>
        @else
            <button disabled class="w-full py-2 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed">
                Sold Out
            </button>
        @endif
    </div>
</div>
