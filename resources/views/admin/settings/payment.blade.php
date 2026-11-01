@extends('layouts.admin')

@section('title', 'Payment & Advance Deposit Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Payment Settings</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Payment & Advance Deposit Gate</h1>
        </div>
        <button type="submit" form="paymentForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Payment Settings</button>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <ul class="text-xs text-red-700 list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="paymentForm" action="{{ route('admin.settings.payment.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Advance Deposit Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Advance Booking Deposit</h3>

            <div class="p-3.5 bg-blue-50/70 border border-blue-200 rounded-lg text-xs text-blue-900">
                <strong>Anti-Abuse Bulk Shield:</strong> Customers must transfer an advance deposit (default 10%) and upload their payment screenshot to place an order. Orders remain pending until manually verified by store staff.
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="require_advance_payment" value="1" {{ old('require_advance_payment', setting('require_advance_payment', true)) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-semibold text-gray-800">Require Advance Deposit for Bulk Orders</span>
                </label>
            </div>

            <div class="w-full sm:w-1/2 pt-2">
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Advance Percentage (%) *</label>
                <div class="relative">
                    <input type="number" step="0.5" name="advance_percentage" required value="{{ old('advance_percentage', setting('advance_percentage', 10)) }}" min="1" max="100" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary pr-8">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 font-semibold">%</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1">E.g. If set to 10%, an order of RM 300 requires RM 30 advance payment.</p>
            </div>
        </div>

        <!-- QR Code & Bank Accounts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">DuitNow / Touch 'n Go QR & Bank Transfer</h3>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Payment QR Code (DuitNow QR / TnG eWallet)</label>
                @if(setting('qr_code_image'))
                    <div class="flex items-center gap-4 mb-3">
                        <img src="{{ asset(setting('qr_code_image')) }}" class="w-28 h-28 object-contain border border-gray-200 rounded-lg shadow-sm p-2 bg-white">
                        <span class="text-xs text-gray-500">Current QR displayed at checkout. Upload to replace.</span>
                    </div>
                @endif
                <input type="file" name="qr_code_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Bank Account Transfer Details *</label>
                <textarea name="bank_account_details" rows="4" required class="w-full text-sm border-gray-300 rounded-lg font-mono focus:ring-primary focus:border-primary">{{ old('bank_account_details', setting('bank_account_details', "Bank: Maybank Berhad\nAccount Name: Nabeel Bulk Wholesale Sdn Bhd\nAccount No: 5140 1234 5678\nReference: Order ID")) }}</textarea>
                <p class="text-[11px] text-gray-500 mt-1">Displayed to customer at checkout alongside the QR code.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Customer Payment Instructions *</label>
                <textarea name="payment_instructions" rows="3" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('payment_instructions', setting('payment_instructions', "Scan the DuitNow QR or transfer the 10% advance deposit to our bank account. Attach your transfer receipt screenshot and enter your transaction reference number to confirm your order.")) }}</textarea>
            </div>
        </div>
    </form>
</div>
@endsection
