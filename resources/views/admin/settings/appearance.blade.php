@extends('layouts.admin')

@section('title', 'Store Name & Brand Identity')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Store Identity & Branding</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Store Name & Brand Settings</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage your dynamic business name and official store logo across all customer and cashier touchpoints.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" form="storeNameForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Store Changes</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <ul class="text-xs text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="storeNameForm" action="{{ route('admin.settings.appearance.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ currentName: '{{ addslashes(old('store_name', store_name())) }}' }">
        @csrf

        {{-- Store Name Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/90 p-6 sm:p-8 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center gap-2 text-sky-600 font-extrabold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Store Name & Dynamic Identity</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mt-1">Primary Business & Storefront Title</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Changing this field updates your brand dynamically across the entire application — including POS counter terminal, thermal receipts, tax invoices, storefront navbar, footer copyright, and email notifications.
                </p>
            </div>

            <div class="space-y-2">
                <label for="store_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                    Store Name <span class="text-red-500">*</span>
                </label>
                <div class="relative max-w-xl">
                    <input 
                        type="text" 
                        id="store_name" 
                        name="store_name" 
                        x-model="currentName"
                        value="{{ old('store_name', store_name()) }}" 
                        placeholder="e.g. N&J Mini Mart" 
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition shadow-2xs"
                    >
                </div>
                <p class="text-[11px] text-gray-500">
                    Example: <span class="font-bold text-slate-700">N&J Mini Mart</span>, <span class="font-bold text-slate-700">Nabeel Store</span>, <span class="font-bold text-slate-700">Super Mart</span>
                </p>
            </div>

            {{-- Dynamic Live Preview Pill --}}
            <div class="bg-gradient-to-r from-slate-900 to-slate-950 p-4 rounded-xl text-white space-y-2 max-w-xl border border-slate-800">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Live Name Preview:</div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-3 py-1.5 rounded-lg bg-sky-600/30 border border-sky-500/50 text-sky-300 text-xs font-black">
                        POS: <span x-text="currentName || 'Store Name'"></span> POS
                    </div>
                    <div class="px-3 py-1.5 rounded-lg bg-emerald-600/30 border border-emerald-500/50 text-emerald-300 text-xs font-black">
                        Receipt: <span x-text="currentName || 'Store Name'"></span>
                    </div>
                    <div class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/20 text-white text-xs font-semibold">
                        © <span x-text="new Date().getFullYear()"></span> <span x-text="currentName || 'Store Name'"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Brand Assets: Logo & Favicon --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/90 p-6 sm:p-8 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center gap-2 text-sky-600 font-extrabold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Store Logo & Favicon</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mt-1">Visual Brand Assets</h3>
                <p class="text-xs text-gray-500 mt-0.5">Upload your custom store logo and browser favicon icon.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 border border-slate-200 rounded-xl bg-slate-50/50 space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Header Store Logo</label>
                    <div class="flex items-center gap-3">
                        @if(setting('site_logo'))
                            <img src="{{ asset('storage/' . setting('site_logo')) }}" class="h-12 max-w-[160px] object-contain border border-slate-200 p-1 bg-white rounded-lg shadow-2xs">
                        @else
                            <img src="{{ asset('images/logo.png') }}" class="h-12 max-w-[160px] object-contain border border-slate-200 p-1 bg-white rounded-lg shadow-2xs" alt="Default Logo">
                        @endif
                    </div>
                    <input type="file" name="site_logo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition cursor-pointer">
                    <p class="text-[11px] text-gray-500">Recommended: Transparent PNG or SVG, max height 48px.</p>
                </div>

                <div class="p-4 border border-slate-200 rounded-xl bg-slate-50/50 space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Browser Favicon (.ico or .png)</label>
                    <div class="flex items-center gap-3">
                        @if(setting('site_favicon'))
                            <img src="{{ asset('storage/' . setting('site_favicon')) }}" class="w-10 h-10 object-contain border border-slate-200 p-1 bg-white rounded-lg shadow-2xs">
                        @else
                            <div class="w-10 h-10 border border-slate-200 p-1 bg-white rounded-lg shadow-2xs flex items-center justify-center text-xs font-bold text-slate-400">ICO</div>
                        @endif
                    </div>
                    <input type="file" name="site_favicon" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 transition cursor-pointer">
                    <p class="text-[11px] text-gray-500">Square 32x32 or 64x64 PNG or ICO format.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Store Changes</span>
            </button>
        </div>
    </form>
</div>
@endsection
