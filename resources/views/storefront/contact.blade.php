@extends('layouts.storefront')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <div class="text-center max-w-xl mx-auto space-y-2">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Get in Touch</span>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">Contact Wholesale Distribution</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Have questions about container bulk purchasing, floor logistics, or custom quotations? Our sales team is ready to assist.
        </p>
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
                    <textarea name="message" rows="4" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500" placeholder="Tell us about the cartons you require or inquiry details..."></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm rounded-xl shadow-lg transition">
                    Send Wholesale Inquiry
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
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('admin_phone', '+60128899900')) }}?text=Hello%2099BulkSales,%20I%20have%20an%20inquiry%20regarding%20wholesale%20orders." 
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

