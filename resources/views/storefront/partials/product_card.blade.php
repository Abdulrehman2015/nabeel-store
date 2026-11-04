<div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 hover:border-sky-400 hover:shadow-xl hover:shadow-sky-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
    
    {{-- Card Upper Body --}}
    <div class="p-3 sm:p-4">
        
        {{-- Product Thumbnail Canvas with Flags --}}
        <div class="relative w-full aspect-square bg-gradient-to-b from-slate-50/90 via-white to-slate-50/40 rounded-xl sm:rounded-2xl overflow-hidden mb-2.5 sm:mb-3 border border-slate-100/90 flex items-center justify-center p-2.5 sm:p-4 group/img">
            <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ $product->image_url }}" 
                    alt="{{ $product->name }}" 
                    class="max-h-full max-w-full object-contain group-hover:scale-108 transition-transform duration-500 ease-out drop-shadow-2xs"
                    loading="lazy"
                >
            </a>

            {{-- Floating Badges --}}
            <div class="absolute top-2 left-2 flex flex-col gap-1 items-start z-10 pointer-events-none">
                @if($product->is_on_sale)
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-rose-600 text-white text-[9px] sm:text-[10px] font-black rounded-md tracking-wider uppercase shadow-xs">
                        🔥 -{{ $product->discount_percent }}%
                    </span>
                @endif
                @if($product->is_new)
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-sky-600 text-white text-[9px] sm:text-[10px] font-black rounded-md tracking-wider uppercase shadow-xs">
                        ✨ NEW
                    </span>
                @endif
            </div>

            {{-- Pack Size Pill --}}
            @if($product->pack_size)
                <div class="absolute bottom-2 right-2 z-10 pointer-events-none">
                    <span class="px-2 py-0.5 bg-slate-900/80 backdrop-blur-xs text-white text-[9px] sm:text-[10px] font-bold rounded-md shadow-2xs max-w-[140px] truncate block">
                        {{ $product->pack_size }}
                    </span>
                </div>
            @endif

            {{-- Sold Out Overlay --}}
            @if(!$product->in_stock)
                <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center z-20">
                    <span class="px-3 py-1 bg-white text-slate-950 text-xs font-black rounded-full uppercase tracking-wider shadow-md">
                        Sold Out
                    </span>
                </div>
            @endif
        </div>

        {{-- Category & Brand Meta Line --}}
        <div class="flex items-center justify-between text-[10px] sm:text-[11px] mb-1 gap-1.5">
            <span class="font-extrabold text-sky-600 uppercase tracking-wider text-[10px] truncate max-w-[65%]">
                {{ $product->category->name ?? 'General' }}
            </span>
            @if($product->brand)
                <span class="font-semibold text-slate-400 text-[10px] truncate max-w-[35%] text-right">
                    {{ $product->brand->name }}
                </span>
            @else
                <span class="font-mono text-[10px] text-slate-400">
                    {{ $product->sku }}
                </span>
            @endif
        </div>

        {{-- Product Title --}}
        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug line-clamp-2 group-hover:text-sky-600 transition min-h-[2.4rem] sm:min-h-[2.6rem]">
            <a href="{{ route('product.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Pack Size / Unit Description Subtitle --}}
        @if($product->pack_size)
            <p class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                {{ $product->pack_size }}
            </p>
        @endif

        {{-- Stock Indicator Pill --}}
        <div class="mt-2 flex items-center gap-1.5">
            @if($product->in_stock)
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    In Stock ({{ $product->stock_quantity }})
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Restocking Soon
                </span>
            @endif
        </div>

    </div>

    {{-- Bottom Pricing & Cart Action Area --}}
    <div class="p-3 sm:p-4 pt-2.5 sm:pt-3 border-t border-slate-100 bg-slate-50/50 mt-auto">
        
        {{-- Price Display --}}
        <div class="flex items-baseline justify-between mb-2 sm:mb-2.5">
            <div class="flex items-baseline gap-1.5">
                <span class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                    {{ format_currency($product->current_price) }}
                </span>
            </div>
            @if($product->is_on_sale)
                <span class="text-[11px] sm:text-xs text-slate-400 line-through font-medium">
                    {{ format_currency($product->price) }}
                </span>
            @endif
        </div>

        {{-- Action Control: Stepper + Add Button --}}
        @if($product->in_stock)
            <div class="flex items-center gap-1.5 sm:gap-2" x-data="{ qty: 1, added: false }">
                
                {{-- Quantity Stepper --}}
                <div class="flex items-center border border-slate-200 rounded-xl bg-white shadow-2xs overflow-hidden h-8 sm:h-9">
                    <button 
                        type="button" 
                        @click="qty = Math.max(1, qty - 1)" 
                        class="w-6 sm:w-7 h-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 font-bold text-xs flex items-center justify-center cursor-pointer select-none active:bg-slate-200"
                        aria-label="Decrease quantity"
                    >
                        &minus;
                    </button>
                    <span class="w-6 sm:w-7 text-center text-xs font-black text-slate-800" x-text="qty"></span>
                    <button 
                        type="button" 
                        @click="qty = Math.min({{ $product->stock_quantity }}, qty + 1)" 
                        class="w-6 sm:w-7 h-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 font-bold text-xs flex items-center justify-center cursor-pointer select-none active:bg-slate-200"
                        aria-label="Increase quantity"
                    >
                        &#43;
                    </button>
                </div>

                {{-- +Add Button with Live Feedback --}}
                <button 
                    type="button" 
                    @click="addToCart({{ $product->id }}, qty); added = true; setTimeout(() => added = false, 1800)"
                    class="flex-1 h-8 sm:h-9 px-2.5 sm:px-3 rounded-xl bg-sky-600 hover:bg-sky-500 active:scale-95 text-white font-extrabold text-xs shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1 cursor-pointer border border-sky-500"
                >
                    <template x-if="!added">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Add</span>
                        </span>
                    </template>
                    <template x-if="added">
                        <span class="inline-flex items-center gap-1 text-emerald-100 font-bold animate-pulse">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Added!</span>
                        </span>
                    </template>
                </button>

            </div>
        @else
            <button disabled class="w-full h-8 sm:h-9 bg-slate-200 text-slate-400 font-bold rounded-xl text-xs cursor-not-allowed">
                Sold Out
            </button>
        @endif

    </div>

</div>
