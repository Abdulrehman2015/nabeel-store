@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 font-['Quicksand']">
        
        {{-- Showing Results Info --}}
        <div class="text-xs text-[#7E7E7E] font-medium order-2 sm:order-1 font-['Lato']">
            Showing 
            <span class="font-bold text-[#253D4E]">{{ $paginator->firstItem() }}</span>
            to 
            <span class="font-bold text-[#253D4E]">{{ $paginator->lastItem() }}</span>
            of 
            <span class="font-bold text-[#253D4E]">{{ $paginator->total() }}</span>
            products
        </div>

        {{-- Page Buttons Container --}}
        <div class="flex items-center gap-1.5 order-1 sm:order-2">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center cursor-not-allowed border border-slate-200/60" aria-disabled="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 rounded-2xl bg-white border border-[#ececec] text-[#253D4E] hover:border-[#3BB77E] hover:bg-[#DEF9EC] hover:text-[#3BB77E] flex items-center justify-center transition-all shadow-2xs hover:shadow-sm" aria-label="Previous Page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 flex items-center justify-center text-[#7E7E7E] text-xs font-bold" aria-disabled="true">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-10 h-10 rounded-2xl bg-[#3BB77E] text-white font-extrabold flex items-center justify-center text-xs shadow-md shadow-[#3BB77E]/25 ring-2 ring-[#3BB77E]/20" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 rounded-2xl bg-white border border-[#ececec] text-[#253D4E] hover:border-[#3BB77E] hover:bg-[#DEF9EC] hover:text-[#3BB77E] font-bold flex items-center justify-center text-xs transition-all shadow-2xs hover:shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 rounded-2xl bg-white border border-[#ececec] text-[#253D4E] hover:border-[#3BB77E] hover:bg-[#DEF9EC] hover:text-[#3BB77E] flex items-center justify-center transition-all shadow-2xs hover:shadow-sm" aria-label="Next Page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center cursor-not-allowed border border-slate-200/60" aria-disabled="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif

        </div>

    </nav>
@endif
