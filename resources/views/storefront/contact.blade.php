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
                Contact Us
            </li>
        </ol>
    </nav>

    {{-- Header Banner (Matching Products & Categories Style) --}}
    <div class="bg-gradient-to-r from-[#DEF9EC]/60 via-[#F2FCE4]/40 to-white rounded-3xl p-5 sm:p-8 border border-[#BCE3C9]/40 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#3BB77E] text-white font-['Quicksand']">
                    💬 Direct Support
                </span>
                <span class="text-xs text-[#7E7E7E] font-['Quicksand']">
                    📞 Hotline: {{ setting('store_phone', '+60 12-889 9900') }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#253D4E] tracking-tight font-['Quicksand']">
                Contact Customer Support
            </h1>
            <p class="text-xs sm:text-sm text-[#7E7E7E] mt-1 font-['Lato']">
                Have questions about our grocery items, store availability, home delivery, or special orders? Our customer care team is ready to assist.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Contact Form (7 cols) --}}
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-slate-900">Send Us an Inquiry</h2>

            <form action="{{ route('page.contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- Honeypot anti-spam (invisible) --}}
                <div class="hidden">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Name *</label>
                        <input type="text" name="name" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subject *</label>
                        <input type="text" name="subject" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Message *</label>
                    <textarea name="message" rows="4" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500" placeholder="Tell us about the products you require or inquiry details..."></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm rounded-xl shadow-lg transition">
                    Send Inquiry
                </button>
            </form>
        </div>

        {{-- Warehouse Info (5 cols) --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl border border-slate-800">
                <h3 class="text-lg font-bold">Distribution Center</h3>

                <div class="space-y-4 text-xs text-slate-300">
                    <div class="flex items-start gap-3">
                        <span class="text-xl text-sky-400">📍</span>
                        <div>
                            <strong class="text-white block text-sm">Warehouse Hub:</strong>
                            <span class="leading-relaxed">{{ setting('store_address') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xl text-sky-400">📞</span>
                        <div>
                            <strong class="text-white block text-sm">Telephone Hotline:</strong>
                            <span>{{ setting('store_phone') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xl text-sky-400">✉️</span>
                        <div>
                            <strong class="text-white block text-sm">Customer Support:</strong>
                            <span>{{ setting('store_email') }}</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-xl text-sky-400">🕒</span>
                        <div>
                            <strong class="text-white block text-sm">Operating Hours:</strong>
                            <span>{{ setting('working_hours') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800">
                    <a 
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('admin_phone', '+60128899900')) }}?text=Hello,%20I%20have%20an%20inquiry%20regarding%20products%20and%20orders." 
                        target="_blank"
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl transition flex items-center justify-center gap-2"
                    >
                        <span>💬</span>
                        <span>Chat Directly on WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

