@extends('layouts.storefront')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

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
                FAQs
            </li>
        </ol>
    </nav>

    {{-- Header Banner (Matching Products & Categories Style) --}}
    <div class="bg-gradient-to-r from-[#DEF9EC]/60 via-[#F2FCE4]/40 to-white rounded-3xl p-5 sm:p-8 border border-[#BCE3C9]/40 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#3BB77E] text-white font-['Quicksand']">
                    ⚡ Help &amp; Knowledge Base
                </span>
                <span class="text-xs text-[#7E7E7E] font-['Quicksand']">
                    Instant Answers
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                Frequently Asked Questions
            </h1>
            <p class="text-xs sm:text-sm text-[#7E7E7E] mt-1 font-['Lato']">
                Find immediate answers regarding delivery zones, floor logistics, item return policies, and order tracking.
            </p>
        </div>
    </div>

    <div class="space-y-8" x-data="{ activeFaq: null }">
        @foreach($faqs as $categoryName => $faqList)
            <div class="space-y-3">
                <h3 class="text-sm font-black text-sky-600 uppercase tracking-wider flex items-center gap-2">
                    <span>📌</span>
                    <span>{{ $categoryName }}</span>
                </h3>

                <div class="space-y-2">
                    @foreach($faqList as $faq)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <button 
                                type="button" 
                                @click="activeFaq = activeFaq === {{ $faq->id }} ? null : {{ $faq->id }}" 
                                class="w-full px-5 py-4 text-left flex justify-between items-center text-sm font-bold text-slate-900 hover:text-sky-600 transition"
                            >
                                <span>{{ $faq->question }}</span>
                                <svg 
                                    class="w-5 h-5 text-slate-400 transition-transform duration-200 flex-shrink-0"
                                    :class="activeFaq === {{ $faq->id }} ? 'transform rotate-180 text-sky-600' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div 
                                x-show="activeFaq === {{ $faq->id }}" 
                                x-collapse 
                                class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3"
                            >
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Contact fallback --}}
    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 text-center space-y-3">
        <h4 class="text-base font-bold text-slate-900">Still have questions?</h4>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
            Our store team is available on phone and WhatsApp to answer any specific delivery queries.
        </p>
        <div class="pt-2">
            <a href="{{ route('page.contact') }}" class="inline-block px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                Contact Customer Care
            </a>
        </div>
    </div>

</div>
@endsection

