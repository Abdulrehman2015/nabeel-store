@php
    $badgeColors = [
        'bg-[#3BB77E]', // green
        'bg-[#67BCEE]', // blue
        'bg-[#F74B81]', // pink
        'bg-[#F59758]', // orange
    ];
    $badgeBg = $badgeColors[$product->id % count($badgeColors)];
@endphp
<div class="bg-white rounded-2xl sm:rounded-[15px] border border-[#ececec] hover:border-[#BCE3C9] hover:shadow-[5px_5px_15px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group p-2.5 sm:p-4 relative">
    
    {{-- Floating Discount / Status Badge (Matching Screenshot) --}}
    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 z-10 pointer-events-none">
        @if($product->is_on_sale)
            <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 {{ $badgeBg }} text-white text-[10px] sm:text-[11px] font-bold rounded-tl-xl sm:rounded-tl-[15px] rounded-br-xl sm:rounded-br-[15px] tracking-wide shadow-2xs font-['Quicksand']">
                Save {{ $product->discount_percent }}%
            </span>
        @elseif($product->is_new)
            <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 bg-[#3BB77E] text-white text-[10px] sm:text-[11px] font-bold rounded-tl-xl sm:rounded-tl-[15px] rounded-br-xl sm:rounded-br-[15px] tracking-wide shadow-2xs font-['Quicksand']">
                New
            </span>
        @endif
    </div>

    {{-- Product Thumbnail (Prominent and Crisp) --}}
    <div class="relative w-full aspect-square flex items-center justify-center p-1.5 sm:p-2 mb-2 sm:mb-3 bg-white rounded-xl group/img overflow-hidden">
        <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->name }}" 
                class="w-full h-full object-contain group-hover:scale-108 transition-transform duration-500 ease-out"
                loading="lazy"
                onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}';"
            >
        </a>

        @if(!$product->in_stock)
            <div class="absolute inset-0 bg-[#253D4E]/70 backdrop-blur-xs flex items-center justify-center rounded-xl z-20">
                <span class="px-3 py-1 bg-white text-[#253D4E] text-xs font-bold rounded-full uppercase tracking-wider shadow-md font-['Quicksand']">
                    Sold Out
                </span>
            </div>
        @endif
    </div>

    {{-- Category Label --}}
    <div class="mb-1">
        <a href="{{ $product->category ? route('category.show', $product->category->slug) : route('category.index') }}" class="text-[#adadad] hover:text-[#3BB77E] text-xs font-normal transition-colors font-['Lato']">
            {{ $product->category->name ?? 'Groceries' }}
        </a>
    </div>

    {{-- Product Title (Quicksand Bold) --}}
    <h3 class="text-sm sm:text-[15px] font-bold text-[#253D4E] leading-snug line-clamp-2 group-hover:text-[#3BB77E] transition min-h-[2.5rem] font-['Quicksand'] mb-1">
        <a href="{{ route('product.show', $product->slug) }}">
            {{ $product->name }}
        </a>
    </h3>

    {{-- Rating Stars & Brand Meta --}}
    <div class="flex items-center gap-1.5 text-xs mb-2">
        <div class="flex text-amber-400 text-xs">
            <span>&#9733;&#9733;&#9733;&#9733;&#9733;</span>
        </div>
        <span class="text-[#b6b6b6] text-[11px] font-['Lato']">
            @if($product->brand)
                By <span class="text-[#3BB77E]">{{ $product->brand->name }}</span>
            @elseif($product->pack_size)
                {{ $product->pack_size }}
            @endif
        </span>
    </div>

    {{-- Price and Add to Cart Button (Bottom Row) --}}
    <div class="flex items-center justify-between pt-2 mt-auto border-t border-[#f4f6fa]">
        <div class="flex items-baseline gap-1.5">
            <span class="text-base sm:text-lg font-bold text-[#3BB77E] font-['Quicksand']">
                {{ format_currency($product->current_price) }}
            </span>
            @if($product->is_on_sale)
                <span class="text-xs text-[#adadad] line-through font-normal">
                    {{ format_currency($product->price) }}
                </span>
            @endif
        </div>

        @if($product->in_stock)
            <div x-data="{ added: false }">
                <button 
                    type="button" 
                    @click="addToCart({{ $product->id }}, 1); added = true; setTimeout(() => added = false, 1800)"
                    class="h-8 px-3 rounded bg-[#DEF9EC] text-[#3BB77E] hover:bg-[#3BB77E] hover:text-white border border-[#cce7d8] font-bold text-xs transition-colors duration-200 flex items-center gap-1.5 cursor-pointer font-['Quicksand'] active:scale-95"
                >
                    <template x-if="!added">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Add</span>
                        </span>
                    </template>
                    <template x-if="added">
                        <span class="inline-flex items-center gap-1 text-[#29A56C] font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Added</span>
                        </span>
                    </template>
                </button>
            </div>
        @else
            <button disabled class="h-8 px-2.5 bg-slate-100 text-slate-400 font-bold rounded text-xs cursor-not-allowed font-['Quicksand']">
                Out
            </button>
        @endif
    </div>

</div>
