@props([
    'title',
    'description' => null,
    'badge' => 'Mart Department',
    'subBadge' => '📦 Pallet & Case Supply',
    'breadcrumbs' => [],
    'icon' => null,
])

<div class="space-y-4 sm:space-y-6 w-full">
    {{-- Breadcrumbs Navigation --}}
    @if(!empty($breadcrumbs))
        <nav class="flex text-xs text-slate-500 font-medium overflow-x-auto no-scrollbar py-1" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 flex-nowrap">
                <li>
                    <a href="{{ url('/') }}" class="hover:text-sky-600 transition flex items-center gap-1 text-slate-500 hover:text-slate-900">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Home</span>
                    </a>
                </li>
                @foreach($breadcrumbs as $label => $link)
                    <li class="text-slate-300">/</li>
                    @if($loop->last || empty($link))
                        <li class="text-slate-900 font-bold truncate max-w-[200px] sm:max-w-none" aria-current="page">
                            {{ $label }}
                        </li>
                    @else
                        <li>
                            <a href="{{ $link }}" class="hover:text-sky-600 transition text-slate-500 hover:text-slate-900 whitespace-nowrap">
                                {{ $label }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif

    {{-- Modern Mart Department Hero Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 text-white shadow-xl border border-slate-800/80 p-6 sm:p-8">
        {{-- Ambient decorative background glows --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6">
                {{-- Visual Icon / Card --}}
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-2xl bg-white/10 backdrop-blur-md p-2 flex items-center justify-center flex-shrink-0 shadow-2xl border border-white/20 ring-1 ring-white/10 group">
                    <div class="w-full h-full rounded-xl overflow-hidden flex items-center justify-center bg-slate-900/60 p-2.5 transition-transform duration-300 group-hover:scale-105 text-white">
                        @if(isset($iconSlot))
                            {{ $iconSlot }}
                        @elseif($icon)
                            {!! $icon !!}
                        @else
                            <svg class="w-10 h-10 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Banner Details --}}
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        @if($badge)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-[11px] font-bold uppercase tracking-wider border border-sky-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                                {{ $badge }}
                            </span>
                        @endif
                        @if($subBadge)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/10 text-slate-300 text-[11px] font-medium border border-white/10">
                                {{ $subBadge }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                        {{ $title }}
                    </h1>

                    @if($description)
                        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                            {{ $description }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Quick Home Delivery Guarantee Badges with 3D Delivery Character --}}
            <div class="w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-slate-800/80 flex items-center justify-between sm:justify-end gap-4">
                <div class="flex flex-col gap-2 text-xs text-slate-300">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/60 shadow-2xs">
                        <span class="text-lime-400">⚡</span>
                        <span>Free Delivery <strong class="text-white">&le; 2 km</strong></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/60 shadow-2xs">
                        <span class="text-amber-400">🏢</span>
                        <span>Free Up to <strong class="text-white">1st Floor</strong></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700/60 shadow-2xs">
                        <span class="text-sky-400">🚚</span>
                        <span>Direct <strong class="text-white">Home Delivery</strong></span>
                    </div>
                </div>

                {{-- Integrated 3D Delivery Character in Hero Banner --}}
                <div class="relative w-20 h-24 sm:w-24 sm:h-28 md:w-28 md:h-32 flex-shrink-0 flex items-center justify-center">
                    <img 
                        src="{{ asset('images/delivery-3d.png') }}" 
                        alt="3D Delivery Character" 
                        class="w-full h-full object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-300"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </div>
</div>

