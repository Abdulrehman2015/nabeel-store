@extends('layouts.storefront')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <div class="text-center max-w-xl mx-auto space-y-2">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Help & Support</span>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">Frequently Asked Questions</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Find immediate answers regarding delivery zones, floor calculations, DuitNow QR payments, and carton returns.
        </p>
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
            Our wholesale logistics team is available on phone and WhatsApp to answer any specific delivery queries.
        </p>
        <div class="pt-2">
            <a href="{{ route('page.contact') }}" class="inline-block px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                Contact Customer Care
            </a>
        </div>
    </div>

</div>
@endsection
