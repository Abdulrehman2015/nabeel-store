@extends('layouts.storefront')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <div class="text-center space-y-2">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Privacy & Compliance</span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Customer Data Request</h1>
        <p class="text-xs text-slate-500">
            In compliance with PDPA & GDPR guidelines, you can request a full machine-readable copy of your personal data or request account anonymisation.
        </p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
        <form action="{{ route('page.data-request.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name *</label>
                <input type="text" name="customer_name" value="{{ auth()->user()?->name }}" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Your Registered Email *</label>
                <input type="email" name="customer_email" value="{{ auth()->user()?->email }}" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                <input type="text" name="customer_phone" value="{{ auth()->user()?->phone }}" class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Request Type *</label>
                <select name="request_type" required class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500 bg-slate-50">
                    <option value="export">Export Data (Receive copy of purchase records & addresses)</option>
                    <option value="delete">Account Deletion & Data Anonymisation</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Additional Notes</label>
                <textarea name="notes" rows="3" class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl" placeholder="Any specific instructions or details..."></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm rounded-xl shadow-lg transition">
                Submit Data Request
            </button>
        </form>
    </div>

</div>
@endsection

