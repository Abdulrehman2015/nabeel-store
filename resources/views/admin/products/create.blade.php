@extends('layouts.admin')

@section('title', 'Add Mart Product')

@push('styles')
{{-- Quill 2.0 Rich Text Editor --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border-color: #e2e8f0;
        background: #f8fafc;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: #e2e8f0;
        font-family: inherit;
        font-size: 0.875rem;
        min-height: 180px;
        background: #ffffff;
    }
    .ql-editor {
        min-height: 180px;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6" 
    @keydown.window="handleGlobalBarcodeKeydown($event)"
    x-data="productCreateForm()">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-sky-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Products</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Add New Product</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Add Mart Product</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"
                    :class="isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'">
                    <span class="w-1.5 h-1.5 rounded-full" :class="isActive ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
                    <span x-text="isActive ? 'Will Publish' : 'Save as Draft'"></span>
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-slate-400 transition">
                Cancel
            </a>

            <button type="submit" form="productForm" class="px-5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Product</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl shadow-xs">
            <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="mt-2 text-xs text-rose-700 list-disc list-inside space-y-1 font-medium">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Main Info & Media -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Information</h3>
                                <p class="text-[11px] text-slate-400">Core details, SKU, live hyperlink & description</p>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">* Required</span>
                    </div>

                    {{-- Product Title --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Product Title *</label>
                            <span class="text-[10px] text-sky-600 font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span>Used to generate clean URL slug automatically</span>
                            </span>
                        </div>
                        <input type="text" name="name" x-model="title" @input="onTitleInput()" required value="{{ old('name') }}" placeholder="e.g. Dutch Lady Full Cream Milk 1L" class="w-full text-base font-semibold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition px-3.5 py-2.5 bg-slate-50/40 hover:bg-white focus:bg-white">
                    </div>

                    {{-- Dedicated Live Hyperlink Banner --}}
                    <div class="rounded-xl border border-sky-200/80 bg-gradient-to-r from-sky-50/80 via-white to-sky-50/40 p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-start sm:items-center gap-2.5 min-w-0">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-sky-600 text-white flex-shrink-0 shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            <div class="min-w-0">
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-sky-800">Storefront Hyperlink Preview</span>
                                <a :href="liveUrl" target="_blank" class="text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-800 hover:underline flex items-center gap-1.5 font-mono truncate transition group">
                                    <span class="truncate" x-text="liveUrl"></span>
                                    <svg class="w-3.5 h-3.5 text-sky-500 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" @click="copyLink()" class="px-3 py-1.5 bg-white hover:bg-slate-50 border border-sky-300 text-sky-700 text-xs font-bold rounded-lg shadow-2xs transition flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span x-text="copied ? 'Copied!' : 'Copy URL'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- SKU and URL Slug Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">SKU / Barcode *</label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 transition"
                                    :class="scanSuccessFlash ? 'bg-emerald-100 text-emerald-800 ring-2 ring-emerald-400 font-black' : 'bg-slate-100 text-slate-600 border border-slate-200'">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    <span x-text="scanSuccessFlash ? '⚡ Barcode Scanned!' : 'Scanner Active'"></span>
                                </span>
                            </div>
                            <div class="relative rounded-xl shadow-2xs">
                                <input 
                                    type="text" 
                                    name="sku" 
                                    x-ref="skuField"
                                    x-model="sku" 
                                    required 
                                    value="{{ old('sku') }}" 
                                    placeholder="e.g. BEV-DL-001 or 955600123456" 
                                    class="w-full text-xs font-mono font-bold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 py-2.5 px-3 bg-slate-50/40 hover:bg-white focus:bg-white transition"
                                    :class="scanSuccessFlash ? 'ring-4 ring-emerald-400 border-emerald-500 bg-emerald-50/40' : ''"
                                >
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                                <span>Scan barcode anywhere on page &bull; isolated from URL</span>
                                <span x-show="lastScannedBarcode" class="text-emerald-700 font-bold" x-text="'Scanned: ' + lastScannedBarcode"></span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">URL Slug</label>
                                <button 
                                    type="button" 
                                    @click="resetSlugToTitle()" 
                                    class="text-[10px] font-bold text-sky-600 hover:text-sky-800 hover:underline cursor-pointer flex items-center gap-1"
                                    title="Auto-sync slug cleanly from product title"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span>Sync from Title</span>
                                </button>
                            </div>
                            <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                                <span class="inline-flex items-center px-2.5 text-[11px] font-mono text-slate-400 bg-slate-100 border-r border-slate-200 select-none">
                                    /product/
                                </span>
                                <input 
                                    type="text" 
                                    name="slug" 
                                    x-model="slug" 
                                    @input="onSlugInput()" 
                                    value="{{ old('slug') }}" 
                                    placeholder="auto-generated-from-title" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 text-xs font-mono font-semibold text-slate-800 bg-white border-0 focus:ring-0 focus:outline-none"
                                >
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">
                                Hyperlink: <a :href="liveUrl" target="_blank" class="text-sky-600 hover:underline font-mono" x-text="liveUrl"></a>
                            </p>
                        </div>
                    </div>

                    {{-- Optional Unit / Measurement Specifications --}}
                    <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200/70 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Unit & Packaging Specifications (Optional)</span>
                            <span class="text-[10px] text-slate-400 font-medium">Displayed on product details specs</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Unit / Size</label>
                                <input type="text" name="pack_size" value="{{ old('pack_size') }}" placeholder="e.g. 1L, 500g, 1 Pack" class="w-full text-xs font-medium bg-white border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-1.5">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Weight / Volume</label>
                                <input type="text" name="weight_volume" value="{{ old('weight_volume') }}" placeholder="e.g. 1.0 kg, 350 ml" class="w-full text-xs font-medium bg-white border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-1.5">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Packaging Type</label>
                                <input type="text" name="unit_description" value="{{ old('unit_description') }}" placeholder="e.g. Tetra Pak, Bottle, Pouch" class="w-full text-xs font-medium bg-white border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-1.5">
                            </div>
                        </div>
                    </div>

                    {{-- Short Summary Description --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Short Description</label>
                            <span class="text-[10px] text-slate-400">Brief summary displayed on storefront cards</span>
                        </div>
                        <textarea name="short_description" rows="2" placeholder="Brief 1-2 sentence overview for product cards..." class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition py-2 px-3 bg-slate-50/40 hover:bg-white focus:bg-white">{{ old('short_description') }}</textarea>
                    </div>

                    {{-- Professional Rich Text Description (Quill.js) --}}
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Product Description</label>
                            <span class="text-[10px] text-sky-600 font-semibold">Rich Visual Editor</span>
                        </div>

                        {{-- Hidden real textarea synced with Quill --}}
                        <textarea id="hiddenDescription" name="description" class="hidden">{{ old('description') }}</textarea>

                        {{-- Quill Editor Container --}}
                        <div id="quillEditorContainer">
                            {!! old('description') !!}
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5">Use formatting, headings, bullet points and bold text to present clean product information.</p>
                    </div>
                </div>

                <!-- Product Media & Banner Images -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Media & Banner Images</h3>
                                <p class="text-[11px] text-slate-400">Main banner photo & additional gallery angles</p>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Main Image Upload Card --}}
                    <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Product Banner / Main Image</label>
                            <span class="text-[10px] font-semibold text-slate-400">800x800px recommended</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            {{-- Image Preview Container --}}
                            <div class="relative w-28 h-28 rounded-xl overflow-hidden border border-slate-200 bg-white p-1 flex-shrink-0 shadow-2xs flex items-center justify-center">
                                <template x-if="bannerPreview">
                                    <img :src="bannerPreview" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!bannerPreview">
                                    <div class="flex flex-col items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[9px] mt-1 font-semibold">Upload Photo</span>
                                    </div>
                                </template>
                            </div>

                            {{-- Upload Dropzone Input --}}
                            <div class="flex-1 w-full">
                                <label class="border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer bg-white hover:bg-sky-50/30 transition group">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-sky-500 mb-1.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <span class="text-xs font-bold text-slate-700 group-hover:text-sky-600">Click to upload main banner photo</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WEBP up to 3MB</span>
                                    <input type="file" name="featured_image" accept="image/*" @change="previewBanner" class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Gallery Photos --}}
                    <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Media Images / Additional Gallery Photos</label>
                            <span class="text-[10px] font-semibold text-slate-400">Multiple photos supported</span>
                        </div>

                        <label class="border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer bg-white hover:bg-sky-50/30 transition group">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-sky-500 mb-1.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-sky-600">Select Gallery Images</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Hold Ctrl/Cmd to select multiple pictures</span>
                            <input type="file" name="gallery_images[]" multiple accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>

                <!-- SEO Snippet Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Search Engine Optimization</h3>
                                <p class="text-[11px] text-slate-400">Google SERP Snippet Preview & Meta Tags</p>
                            </div>
                        </div>
                    </div>

                    <!-- Live Google SERP Preview Box -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 font-sans text-left space-y-1">
                        <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 font-mono">
                            <span>{{ url('/') }}/product/</span><span class="font-bold text-slate-700" x-text="slug || 'new-product-slug'"></span>
                        </div>
                        <h4 class="text-base text-blue-700 font-semibold hover:underline truncate" x-text="metaTitle || title || 'Product Title — N&J Mini Mart'"></h4>
                        <p class="text-xs text-slate-600 line-clamp-2" x-text="metaDesc || 'Buy everyday groceries and household items at N&J Mini Mart. Fast delivery.'"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-xs font-bold text-slate-700 uppercase">Meta Title</label>
                                <span class="text-[10px] text-slate-400" :class="metaTitle.length > 60 ? 'text-rose-500 font-bold' : ''" x-text="metaTitle.length + '/60' + (metaTitle.length > 60 ? ' (too long)' : '')"></span>
                            </div>
                            <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title') }}" placeholder="SEO Title (50-60 characters)" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Focus Keyword</label>
                            <input type="text" name="focus_keyword" value="{{ old('focus_keyword') }}" placeholder="e.g. fresh milk, groceries" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Meta Description</label>
                            <span class="text-[10px] text-slate-400" :class="metaDesc.length > 160 ? 'text-rose-500 font-bold' : ''" x-text="metaDesc.length + '/160' + (metaDesc.length > 160 ? ' (too long)' : '')"></span>
                        </div>
                        <textarea name="meta_description" x-model="metaDesc" rows="2" placeholder="Summary for Google SERP results (140-160 characters)" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Col: Pricing, Stock, Category & Badges -->
            <div class="space-y-6">
                
                <!-- Pricing Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Pricing</h3>
                            <p class="text-[11px] text-slate-400">Regular and promotional price</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Purchase Price (Cost Price)</label>
                            <span class="text-[10px] text-slate-400 font-medium">Wholesale / Supplier Cost</span>
                        </div>
                        <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                            <span class="inline-flex items-center px-3.5 text-xs font-bold text-slate-600 bg-slate-100 border-r border-slate-200 select-none">
                                {{ setting('currency_symbol', 'RM') }}
                            </span>
                            <input type="number" step="0.01" name="purchase_price" x-model="purchasePrice" value="{{ old('purchase_price') }}" placeholder="0.00" class="flex-1 min-w-0 block w-full px-3 py-2 text-base font-black text-sky-700 bg-white border-0 focus:ring-0 focus:outline-none">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Used to calculate dashboard profit (Sale Price - Purchase Price = Profit).</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Regular Price (Selling Price) *</label>
                        <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-500/10 bg-slate-50 transition">
                            <span class="inline-flex items-center px-3.5 text-xs font-bold text-slate-600 bg-slate-100 border-r border-slate-200 select-none">
                                {{ setting('currency_symbol', 'RM') }}
                            </span>
                            <input type="number" step="0.01" name="price" x-model="price" required value="{{ old('price') }}" placeholder="0.00" class="flex-1 min-w-0 block w-full px-3 py-2 text-base font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Sale / Promo Price</label>
                            <template x-if="discountPercent > 0">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-200" x-text="discountPercent + '% OFF'"></span>
                            </template>
                        </div>
                        <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-rose-500 focus-within:ring-4 focus-within:ring-rose-500/10 bg-slate-50 transition">
                            <span class="inline-flex items-center px-3.5 text-xs font-bold text-slate-600 bg-slate-100 border-r border-slate-200 select-none">
                                {{ setting('currency_symbol', 'RM') }}
                            </span>
                            <input type="number" step="0.01" name="sale_price" x-model="salePrice" value="{{ old('sale_price') }}" placeholder="Optional discount" class="flex-1 min-w-0 block w-full px-3 py-2 text-base font-black text-rose-600 bg-white border-0 focus:ring-0 focus:outline-none">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Leave blank to sell at standard regular price.</p>
                    </div>

                    {{-- Live Profit Preview Badge --}}
                    <div class="p-3 bg-gradient-to-br from-slate-50 to-slate-100/70 border border-slate-200 rounded-xl flex items-center justify-between text-xs" x-show="price > 0">
                        <div>
                            <span class="text-slate-600 font-bold block text-[11px] uppercase tracking-wider">Unit Profit (Margin)</span>
                            <span class="text-[10px] text-slate-400">Sale - Purchase = Profit</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black" :class="unitProfit >= 0 ? 'text-emerald-700' : 'text-rose-600'">
                                {{ setting('currency_symbol', 'RM') }} <span x-text="unitProfit"></span>
                            </span>
                            <span class="ml-1 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full" :class="profitMargin >= 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200'" x-text="profitMargin + '%'"></span>
                        </div>
                    </div>
                </div>

                <!-- Product Quantity & Stock Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Product Quantity & Stock</h3>
                            <p class="text-[11px] text-slate-400">Inventory availability in store</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Product Quantity in Stock *</label>
                        <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden shadow-2xs focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 transition">
                            <button type="button" @click="stock = Math.max(0, stock - 1)" class="px-3.5 py-2.5 text-slate-600 hover:bg-slate-100 font-bold text-sm select-none cursor-pointer bg-slate-50">-</button>
                            <input type="number" name="stock_quantity" x-model.number="stock" required min="0" placeholder="0" class="w-full text-center text-base font-black text-slate-900 border-0 focus:ring-0 focus:outline-none">
                            <button type="button" @click="stock = stock + 1" class="px-3.5 py-2.5 text-slate-600 hover:bg-slate-100 font-bold text-sm select-none cursor-pointer bg-slate-50">+</button>
                        </div>
                    </div>

                    {{-- Low Stock Alert Quantity Field --}}
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Low Stock Alert Quantity *</label>
                            <span class="text-[10px] font-black text-amber-800 bg-amber-100/90 border border-amber-200/90 px-2 py-0.5 rounded-full flex items-center gap-1">
                                <span>⚠️ Threshold</span>
                            </span>
                        </div>
                        <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden shadow-2xs focus-within:border-amber-500 focus-within:ring-4 focus-within:ring-amber-500/10 bg-slate-50 transition">
                            <button type="button" @click="lowStockThreshold = Math.max(0, lowStockThreshold - 1)" class="px-3 py-2 text-slate-600 hover:bg-slate-200 font-bold text-sm select-none cursor-pointer bg-slate-100 border-r border-slate-200">-</button>
                            <input type="number" name="low_stock_threshold" x-model.number="lowStockThreshold" required min="0" placeholder="10" class="w-full text-center text-sm font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none py-2">
                            <button type="button" @click="lowStockThreshold = lowStockThreshold + 1" class="px-3 py-2 text-slate-600 hover:bg-slate-200 font-bold text-sm select-none cursor-pointer bg-slate-100 border-l border-slate-200">+</button>
                        </div>
                        <p class="text-[10px] text-slate-400">
                            Notify admin when available stock drops to or below this quantity.
                        </p>

                        {{-- Dynamic Stock Health Status Badge --}}
                        <div class="pt-1">
                            <template x-if="stock <= 0">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Out of Stock (Shows as Sold Out)</span>
                                </span>
                            </template>
                            <template x-if="stock > 0 && stock <= lowStockThreshold">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300 ring-2 ring-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span x-text="'⚠️ Low Stock Warning: ' + stock + ' units remaining (Threshold &le; ' + lowStockThreshold + ')'"></span>
                                </span>
                            </template>
                            <template x-if="stock > lowStockThreshold">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span x-text="stock + ' units available (Healthy Stock &bull; Alert at &le; ' + lowStockThreshold + ')'"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Categorization Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Categorization</h3>
                            <p class="text-[11px] text-slate-400">Department and brand organization</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category *</label>
                        <select name="category_id" required class="w-full text-xs font-bold py-2.5 px-3 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Brand</label>
                        <select name="brand_id" class="w-full text-xs font-bold py-2.5 px-3 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            <option value="">None / Generic Mart Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status & Badges Toggle Switches Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Status & Badges</h3>
                            <p class="text-[11px] text-slate-400">Storefront visibility & promotional tags</p>
                        </div>
                    </div>

                    {{-- Active in Store Switch --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer" @click="isActive = !isActive">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active in Store</span>
                            <span class="text-[10px] text-slate-400 block">Visible to customers and available for purchase</span>
                        </div>
                        <input type="checkbox" name="is_active" value="1" class="hidden" :checked="isActive">
                        <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="isActive ? 'bg-emerald-600' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isActive ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>

                    {{-- Featured Product Switch --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer" @click="isFeatured = !isFeatured">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-bold text-slate-900">Featured Product</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-amber-100 text-amber-800">★ Featured</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block">Showcases in the Featured Mart items section</span>
                        </div>
                        <input type="checkbox" name="is_featured" value="1" class="hidden" :checked="isFeatured">
                        <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="isFeatured ? 'bg-sky-600' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isFeatured ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>

                    {{-- New Arrival Badge Switch --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer" @click="isNew = !isNew">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-bold text-slate-900">New Arrival Badge</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-sky-100 text-sky-800">NEW</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block">Adds blue NEW tag on storefront cards</span>
                        </div>
                        <input type="checkbox" name="is_new" value="1" class="hidden" :checked="isNew">
                        <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="isNew ? 'bg-sky-600' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isNew ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>

                    {{-- Special Promotion Switch --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer" @click="isPromo = !isPromo">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-bold text-slate-900">Special Promotion</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-rose-100 text-rose-800">🔥 Promo</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block">Displays in Deals & Promotions tabs</span>
                        </div>
                        <input type="checkbox" name="is_promo" value="1" class="hidden" :checked="isPromo">
                        <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="isPromo ? 'bg-rose-600' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isPromo ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    function productCreateForm() {
        return {
            title: @json(old('name', '')),
            metaTitle: @json(old('meta_title', '')),
            metaDesc: @json(old('meta_description', '')),
            slug: @json(old('slug', '')),
            sku: @json(old('sku', '')),
            price: @json(old('price', '')),
            purchasePrice: @json(old('purchase_price', '')),
            salePrice: @json(old('sale_price', '')),
            stock: {{ (int) old('stock_quantity', 50) }},
            lowStockThreshold: {{ (int) old('low_stock_threshold', 10) }},
            autoSlug: {{ old('slug') ? 'false' : 'true' }},
            isActive: {{ old('is_active', true) ? 'true' : 'false' }},
            isFeatured: {{ old('is_featured', false) ? 'true' : 'false' }},
            isNew: {{ old('is_new', true) ? 'true' : 'false' }},
            isPromo: {{ old('is_promo', false) ? 'true' : 'false' }},
            bannerPreview: null,
            copied: false,

            // Hardware Barcode Scanner Engine State
            barcodeScanBuffer: '',
            lastScanTime: 0,
            preScanFocusTarget: null,
            preScanValue: '',
            scanSuccessFlash: false,
            lastScannedBarcode: '',
            scanAutoTimeout: null,
            audioCtx: null,

            slugify(text) {
                if (!text) return '';
                return text
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/[\s_]+/g, '-')
                    .replace(/&/g, '-and-')
                    .replace(/[^a-z0-9\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            },

            onTitleInput() {
                if (!this.metaTitle) {
                    this.metaTitle = this.title;
                }
                if (this.autoSlug) {
                    this.slug = this.slugify(this.title);
                }
            },

            onSlugInput() {
                this.autoSlug = false;
                this.slug = this.slugify(this.slug);
            },

            resetSlugToTitle() {
                this.autoSlug = true;
                this.slug = this.slugify(this.title);
            },

            playScannerBeep() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    if (!this.audioCtx) this.audioCtx = new AudioCtx();
                    if (this.audioCtx.state === 'suspended') this.audioCtx.resume();
                    const osc = this.audioCtx.createOscillator();
                    const gain = this.audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(1046.5, this.audioCtx.currentTime);
                    gain.gain.setValueAtTime(0.2, this.audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + 0.12);
                    osc.connect(gain);
                    gain.connect(this.audioCtx.destination);
                    osc.start();
                    osc.stop(this.audioCtx.currentTime + 0.12);
                } catch(e) {}
            },

            applyScannedBarcode(code) {
                if (!code) return;
                this.sku = code;
                this.lastScannedBarcode = code;
                this.scanSuccessFlash = true;
                setTimeout(() => this.scanSuccessFlash = false, 2500);

                if (this.preScanFocusTarget) {
                    if (this.preScanFocusTarget.name === 'name') {
                        this.title = this.preScanValue;
                        if (this.autoSlug) {
                            this.slug = this.slugify(this.title);
                        }
                    } else if (this.preScanFocusTarget.value !== undefined) {
                        this.preScanFocusTarget.value = this.preScanValue;
                    }
                    this.preScanFocusTarget = null;
                }

                const skuEl = this.$refs.skuField || document.querySelector('input[name="sku"]');
                if (skuEl) {
                    skuEl.focus();
                    skuEl.select();
                }
                this.playScannerBeep();
            },

            handleGlobalBarcodeKeydown(e) {
                if (e.ctrlKey || e.metaKey || e.altKey) return;

                const now = Date.now();
                const timeDiff = now - this.lastScanTime;
                this.lastScanTime = now;

                if (e.key === 'Enter' || e.key === 'Tab') {
                    if (this.barcodeScanBuffer.length >= 3) {
                        e.preventDefault();
                        e.stopPropagation();
                        const code = this.barcodeScanBuffer.trim();
                        this.barcodeScanBuffer = '';
                        this.applyScannedBarcode(code);
                        return;
                    }
                }

                if (e.key && e.key.length === 1) {
                    const activeEl = document.activeElement;
                    const isSkuInput = (activeEl === this.$refs?.skuField) || (activeEl && activeEl.name === 'sku');

                    if (timeDiff < 65) {
                        if (!this.preScanFocusTarget && activeEl && activeEl !== document.body) {
                            this.preScanFocusTarget = activeEl;
                            this.preScanValue = activeEl.value || '';
                        }
                        this.barcodeScanBuffer += e.key;

                        if (activeEl && !isSkuInput && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.isContentEditable)) {
                            e.preventDefault();
                        }
                    } else {
                        if (!isSkuInput) {
                            this.barcodeScanBuffer = e.key;
                            this.preScanFocusTarget = activeEl;
                            this.preScanValue = activeEl ? (activeEl.value || '') : '';
                        } else {
                            this.barcodeScanBuffer = '';
                        }
                    }

                    if (this.scanAutoTimeout) clearTimeout(this.scanAutoTimeout);
                    if (this.barcodeScanBuffer.length >= 4) {
                        this.scanAutoTimeout = setTimeout(() => {
                            if (this.barcodeScanBuffer.length >= 4) {
                                const code = this.barcodeScanBuffer.trim();
                                this.barcodeScanBuffer = '';
                                this.applyScannedBarcode(code);
                            }
                        }, 90);
                    }
                }
            },

            get liveUrl() {
                let base = @json(url('/product'));
                let s = (this.slug || '').trim();
                return base + '/' + (s || 'preview-slug');
            },

            get discountPercent() {
                let p = parseFloat(this.price);
                let sp = parseFloat(this.salePrice);
                if (p > 0 && sp > 0 && sp < p) {
                    return Math.round(((p - sp) / p) * 100);
                }
                return 0;
            },

            get effectiveSellPrice() {
                let sp = parseFloat(this.salePrice);
                let p = parseFloat(this.price);
                if (sp > 0 && sp < p) return sp;
                return p > 0 ? p : 0;
            },

            get unitProfit() {
                let sell = this.effectiveSellPrice;
                let cost = parseFloat(this.purchasePrice) || 0;
                return sell > 0 ? (sell - cost).toFixed(2) : '0.00';
            },

            get profitMargin() {
                let sell = this.effectiveSellPrice;
                let cost = parseFloat(this.purchasePrice) || 0;
                if (sell <= 0) return 0;
                return Math.round(((sell - cost) / sell) * 100);
            },

            copyLink() {
                navigator.clipboard.writeText(this.liveUrl);
                this.copied = true;
                setTimeout(() => this.copied = false, 2500);
            },

            previewBanner(event) {
                const file = event.target.files[0];
                if (file) {
                    this.bannerPreview = URL.createObjectURL(file);
                }
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        const quill = new Quill('#quillEditorContainer', {
            theme: 'snow',
            placeholder: 'Write complete product details, directions, ingredients, and key benefits...',
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });

        const hiddenTextarea = document.getElementById('hiddenDescription');
        const form = document.getElementById('productForm');

        form.addEventListener('submit', function () {
            hiddenTextarea.value = quill.root.innerHTML;
        });
    });
</script>
@endpush
