@extends('layouts.admin')

@section('title', 'Add New Staff / Cashier')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb & Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('admin.staff.index') }}" class="hover:text-white transition">Staff Management</a>
                <span>/</span>
                <span class="text-white">New Account</span>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight mt-1">Create Staff / Cashier Account</h1>
        </div>

        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back</span>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xs">
        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Full Name --}}
            <div>
                <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Staff / Cashier Full Name <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    placeholder="e.g. Muhammad Ali (Cashier 1)"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-hidden"
                >
                @error('name')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email & Phone --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Login Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="cashier1@nandjminimart.com"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-hidden"
                    >
                    @error('email')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Phone / Contact Number (Optional)
                    </label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        placeholder="+60 12-345 6789"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-hidden"
                    >
                    @error('phone')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Role Selection with Explanations --}}
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    Assign Role & Permissions <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Cashier Option --}}
                    <label class="relative flex flex-col p-4 rounded-xl border border-slate-800 bg-slate-950/60 hover:bg-slate-950 hover:border-emerald-500/50 cursor-pointer transition has-checked:border-emerald-500 has-checked:bg-emerald-950/20">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                <span class="text-xs font-bold text-white">Cashier / POS Operator</span>
                            </div>
                            <input type="radio" name="role" value="cashier" {{ old('role', 'cashier') === 'cashier' ? 'checked' : '' }} class="text-emerald-500 focus:ring-0">
                        </div>
                        <ul class="text-[11px] text-slate-400 space-y-1 list-disc list-inside">
                            <li><strong class="text-slate-200">POS Register:</strong> Take walk-in counter sales, print receipts.</li>
                            <li><strong class="text-slate-200">Shift Tracking:</strong> Sees ONLY their own counter sales for today.</li>
                            <li><strong class="text-slate-200">Products:</strong> Can view catalog and ADD new products.</li>
                            <li class="text-rose-400 font-semibold">CANNOT view monthly/daily store revenue or profit.</li>
                            <li class="text-rose-400 font-semibold">CANNOT delete products, categories, or orders.</li>
                        </ul>
                    </label>

                    {{-- Admin Option --}}
                    <label class="relative flex flex-col p-4 rounded-xl border border-slate-800 bg-slate-950/60 hover:bg-slate-950 hover:border-purple-500/50 cursor-pointer transition has-checked:border-purple-500 has-checked:bg-purple-950/20">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>
                                <span class="text-xs font-bold text-white">Store Administrator</span>
                            </div>
                            <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} class="text-purple-500 focus:ring-0">
                        </div>
                        <ul class="text-[11px] text-slate-400 space-y-1 list-disc list-inside">
                            <li><strong class="text-slate-200">Full Dashboard:</strong> Total store revenue, cost, profit margins, sales graphs.</li>
                            <li><strong class="text-slate-200">Catalog Master:</strong> Add, edit, and DELETE products/categories.</li>
                            <li><strong class="text-slate-200">Reports:</strong> View all cashier sales, audit logs, and analytics.</li>
                            <li><strong class="text-slate-200">Settings:</strong> Manage delivery, payments, and staff accounts.</li>
                        </ul>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password & Confirm Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        minlength="6"
                        placeholder="At least 6 characters"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-hidden"
                    >
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        minlength="6"
                        placeholder="Re-type password"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-hidden"
                    >
                </div>
            </div>

            {{-- Active Status --}}
            <div class="pt-2 border-t border-slate-800">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1" 
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded-sm bg-slate-950 border-slate-700 text-sky-600 focus:ring-0"
                    >
                    <div>
                        <span class="text-xs font-bold text-white block">Active Account Immediately</span>
                        <span class="text-[11px] text-slate-400 block">The cashier can log in immediately upon creation.</span>
                    </div>
                </label>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold transition shadow-sm shadow-sky-600/30">
                    Create Account
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

