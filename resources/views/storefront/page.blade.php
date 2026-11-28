@extends('layouts.storefront')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

    {{-- Breadcrumbs Navigation --}}
    <nav class="flex text-xs text-slate-500 font-medium overflow-x-auto no-scrollbar py-1" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 flex-nowrap font-['Quicksand'] font-semibold">
            <li>
                <a href="{{ url('/') }}" class="hover:text-[#3BB77E] transition flex items-center gap-1 text-[#7E7E7E]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Home</span>
                </a>
            </li>
            <li class="text-slate-300">/</li>
            <li class="text-[#253D4E] font-bold" aria-current="page">
                {{ $page->title }}
            </li>
        </ol>
    </nav>

    {{-- Header Banner (Matching Products & Categories Style) --}}
    <div class="bg-gradient-to-r from-[#DEF9EC]/60 via-[#F2FCE4]/40 to-white rounded-3xl p-5 sm:p-8 border border-[#BCE3C9]/40 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#3BB77E] text-white font-['Quicksand']">
                    📄 Legal &amp; Compliance
                </span>
                <span class="text-xs text-[#7E7E7E] font-['Quicksand']">
                    Version {{ $page->current_version }}.0
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                {{ $page->title }}
            </h1>
            <p class="text-xs sm:text-sm text-[#7E7E7E] mt-1 font-['Lato']">
                Official store policies, transparent compliance documents, and customer service guidelines for store purchases.
            </p>
        </div>
    </div>

    {{-- Page Body Card --}}
    <div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex items-center justify-between text-xs text-slate-400 pb-3 border-b border-slate-100">
            <span>Official Policy Document</span>
            <span>Last Updated: {{ $page->updated_at->format('F d, Y') }}</span>
        </div>

        {{-- Render parsed content with replaced dynamic placeholders --}}
        <div class="prose prose-sm sm:prose-base max-w-none text-slate-700 leading-relaxed space-y-4 pt-2">
            {!! $page->parsed_content !!}
        </div>
    </div>

</div>
@endsection
