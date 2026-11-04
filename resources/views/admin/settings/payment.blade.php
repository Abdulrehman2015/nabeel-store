@extends('layouts.admin')

@section('title', 'Payment & Advance Deposit Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    requireDeposit: {{ old('require_advance_payment', setting('require_advance_payment', true)) ? 'true' : 'false' }},
    percentage: {{ old('advance_percentage', setting('advance_percentage', 10)) }},
    qrPreview: '{{ setting('qr_code_image') ? asset(setting('qr_code_image')) : '' }}',
    handleQrUpload(event) {
        const file = event.target.files[0];
        if (file) {
            this.qrPreview = URL.createObjectURL(file);
        }
    }
}">

    {{-- Top Header with Breadcrumbs & Save CTA --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Settings</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Payment & QR Gate</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Payment & Advance Deposit Gate</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure DuitNow QR, bank account transfer credentials, and checkout deposit requirements.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button 
                type="submit" 
                form="paymentForm" 
                class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 flex items-center gap-2 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Payment Settings</span>
            </button>
        </div>
    </div>

    {{-- Validation Error Alerts --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl shadow-2xs">
            <div class="flex items-center gap-2 text-rose-800 font-bold text-xs mb-1">
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="text-xs text-rose-700 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Form Container --}}
    <form id="paymentForm" action="{{ route('admin.settings.payment.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- 1. Advance Booking Deposit Configuration Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg">
                        🛡️
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Order Confirmation & Advance Deposit</h3>
                        <p class="text-xs text-slate-500">Require customers to transfer a deposit to confirm order and reserve items.</p>
                    </div>
                </div>
            </div>

            {{-- Policy Info Callout Banner --}}
            <div class="p-4 bg-gradient-to-r from-sky-50 via-slate-50 to-white border border-sky-200/80 rounded-2xl text-xs text-slate-700 space-y-1">
                <div class="flex items-center gap-2 font-extrabold text-sky-900">
                    <svg class="w-4 h-4 text-sky-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>Store Dispatch Assurance Policy</span>
                </div>
                <p class="leading-relaxed text-slate-600">
                    Customers pay an advance deposit percentage (default 10%) via instant DuitNow QR or bank transfer. Once payment screenshot is submitted, orders are placed in <strong>Pending Verification</strong> until verified by your store staff.
                </p>
            </div>

            {{-- Toggle: Require Advance Deposit --}}
            <div class="flex items-center justify-between p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80">
                <div>
                    <label for="require_advance_payment" class="text-sm font-extrabold text-slate-900 block cursor-pointer">
                        Require Advance Deposit for Orders
                    </label>
                    <p class="text-xs text-slate-500 mt-0.5">
                        When enabled, customers must transfer the deposit percentage before checkout can be completed.
                    </p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input 
                        type="checkbox" 
                        id="require_advance_payment"
                        name="require_advance_payment" 
                        value="1" 
                        x-model="requireDeposit"
                        class="sr-only peer"
                    >
                    <div class="w-12 h-6.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-sky-600"></div>
                </label>
            </div>

            {{-- Percentage Input & Presets --}}
            <div x-show="requireDeposit" x-transition x-cloak class="space-y-4 pt-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Advance Percentage (%) *
                        </label>
                        <div class="relative max-w-xs">
                            <input 
                                type="number" 
                                step="1" 
                                name="advance_percentage" 
                                required 
                                x-model.number="percentage"
                                min="1" 
                                max="100" 
                                class="w-full text-base font-black px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 pr-10 bg-white"
                            >
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 font-black text-sm">%</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            Recommended: 10% (standard store confirmation deposit).
                        </p>
                    </div>

                    {{-- Quick Preset Chips --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Quick Presets
                        </label>
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" @click="percentage = 5" class="px-3 py-1.5 rounded-xl text-xs font-bold transition border" :class="percentage === 5 ? 'bg-sky-600 text-white border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">5%</button>
                            <button type="button" @click="percentage = 10" class="px-3 py-1.5 rounded-xl text-xs font-bold transition border" :class="percentage === 10 ? 'bg-sky-600 text-white border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">10% (Recommended)</button>
                            <button type="button" @click="percentage = 20" class="px-3 py-1.5 rounded-xl text-xs font-bold transition border" :class="percentage === 20 ? 'bg-sky-600 text-white border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">20%</button>
                            <button type="button" @click="percentage = 50" class="px-3 py-1.5 rounded-xl text-xs font-bold transition border" :class="percentage === 50 ? 'bg-sky-600 text-white border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">50%</button>
                            <button type="button" @click="percentage = 100" class="px-3 py-1.5 rounded-xl text-xs font-bold transition border" :class="percentage === 100 ? 'bg-sky-600 text-white border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">100% Full Payment</button>
                        </div>
                    </div>
                </div>

                {{-- Live Customer Simulator Pill --}}
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <span class="text-slate-600 font-medium">
                        💡 Example: Customer order total of <strong>RM 200.00</strong> requires:
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-sky-100 text-sky-800 font-mono font-black" x-text="'Deposit Due: RM ' + ((200 * (percentage || 10)) / 100).toFixed(2)"></span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-mono font-bold" x-text="'Balance on Arrival: RM ' + (200 - ((200 * (percentage || 10)) / 100)).toFixed(2)"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. DuitNow & QR Code Image Management Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg">
                        📱
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Payment QR Code (DuitNow QR / TnG eWallet)</h3>
                        <p class="text-xs text-slate-500">Scan code displayed at checkout for instant customer payment.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                
                {{-- QR Preview Frame --}}
                <div class="md:col-span-4 flex flex-col items-center">
                    <div class="w-full max-w-[200px] aspect-square rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/80 p-3 flex flex-col items-center justify-center relative overflow-hidden shadow-2xs group">
                        <template x-if="qrPreview">
                            <img :src="qrPreview" alt="Payment QR Code" class="max-h-full max-w-full object-contain rounded-lg">
                        </template>
                        <template x-if="!qrPreview">
                            <div class="text-center p-3 text-slate-400">
                                <span class="text-3xl block mb-1">📷</span>
                                <span class="text-xs font-semibold">No QR uploaded</span>
                            </div>
                        </template>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-2 text-center">Customer Checkout Scan View</span>
                </div>

                {{-- QR Uploader & Guidelines --}}
                <div class="md:col-span-8 space-y-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Upload New QR Code Image
                    </label>
                    <input 
                        type="file" 
                        name="qr_code_image" 
                        accept="image/*" 
                        @change="handleQrUpload($event)"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer border border-slate-300 rounded-xl p-2 bg-slate-50/50"
                    >
                    
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-[11px] text-slate-600 leading-relaxed space-y-1">
                        <div class="font-bold text-slate-800">Supported Malaysian QR Standards:</div>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                            <li><strong>DuitNow National QR</strong> (compatible with Maybank, CIMB, Public Bank, Hong Leong, RHB, etc.)</li>
                            <li><strong>Touch 'n Go eWallet Merchant QR</strong></li>
                            <li><strong>GrabPay / Boost eWallet QR</strong></li>
                        </ul>
                        <p class="text-slate-400 pt-1">Recommended format: Square PNG or JPG, high resolution (min 500x500 px).</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- 3. Bank Account Credentials & Transfer Details --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg">
                        🏦
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Bank Account Transfer Details</h3>
                        <p class="text-xs text-slate-500">Official bank account credentials displayed to customers who prefer manual IBG / instant transfer.</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Bank Account Details (Displayed at Checkout) *
                </label>
                <textarea 
                    name="bank_account_details" 
                    rows="4" 
                    required 
                    class="w-full text-xs sm:text-sm font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/60 leading-relaxed text-slate-800"
                >{{ old('bank_account_details', setting('bank_account_details', "Bank: Maybank Berhad\nAccount Name: N&J Mini Mart Enterprise\nAccount No: 5148 7921 6890\nDuitNow QR ID: 202401019988")) }}</textarea>
                <p class="text-[11px] text-slate-500 mt-1.5">
                    Include Bank Name, Account Holder Name, Account Number, and DuitNow ID for fast customer copying.
                </p>
            </div>
        </div>

        {{-- 4. Customer Checkout Payment Instructions --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg">
                        📋
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Customer Payment Instructions</h3>
                        <p class="text-xs text-slate-500">Step-by-step guidance shown directly on the checkout payment modal.</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Step-by-Step Instructions Text *
                </label>
                <textarea 
                    name="payment_instructions" 
                    rows="4" 
                    required 
                    class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/60 leading-relaxed text-slate-800"
                >{{ old('payment_instructions', setting('payment_instructions', "1. Scan the DuitNow QR code or transfer to our Maybank account.\n2. Pay the exact Advance Deposit shown below.\n3. Take a screenshot of the completed transfer.\n4. Enter the Transaction/Reference ID and upload the screenshot to confirm your order.")) }}</textarea>
                <p class="text-[11px] text-slate-500 mt-1.5">
                    Clear instructions prevent payment misunderstandings and expedite order dispatch.
                </p>
            </div>
        </div>

        {{-- Save Action Footer --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <button 
                type="submit" 
                class="px-6 py-3 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 flex items-center gap-2 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Payment Settings</span>
            </button>
        </div>

    </form>

</div>
@endsection
