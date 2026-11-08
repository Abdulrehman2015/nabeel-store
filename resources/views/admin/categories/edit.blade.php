@extends('layouts.admin')

@section('title', 'Edit Category: ' . $category->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{
    name: '{{ old('name', addslashes($category->name)) }}',
    metaTitle: '{{ old('meta_title', addslashes($category->meta_title ?? '')) }}',
    metaDesc: '{{ old('meta_description', addslashes($category->meta_description ?? '')) }}',
    slug: '{{ old('slug', $category->slug) }}',
    autoSlug: false,
    icon: '{{ old('icon', $category->icon ?? 'drink') }}',
    isActive: {{ old('is_active', $category->is_active) ? 'true' : 'false' }},
    bannerPreview: null,
    iconFilePreview: null,
    copied: false,

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

    onNameInput() {
        if (!this.metaTitle) {
            this.metaTitle = this.name;
        }
        if (this.autoSlug) {
            this.slug = this.slugify(this.name);
        }
    },

    onSlugInput() {
        this.autoSlug = false;
        this.slug = this.slugify(this.slug);
    },

    resetSlugToTitle() {
        this.autoSlug = true;
        this.slug = this.slugify(this.name);
    },

    get liveUrl() {
        let base = '{{ url('/category') }}';
        let s = (this.slug || '').trim();
        return s ? (base + '/' + s) : '{{ route('category.show', $category->slug) }}';
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
    },

    previewIconFile(event) {
        const file = event.target.files[0];
        if (file) {
            this.iconFilePreview = URL.createObjectURL(file);
        }
    }
}">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.categories.index') }}" class="hover:text-sky-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Categories</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700 truncate max-w-xs">{{ $category->name }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Mart Category</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"
                    :class="isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'">
                    <span class="w-1.5 h-1.5 rounded-full" :class="isActive ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
                    <span x-text="isActive ? 'Published' : 'Draft / Hidden'"></span>
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Manage aisle settings, icon theme and storefront presentation</p>
        </div>

        <div class="flex items-center gap-2.5">
            {{-- Hyperlink to View Category on Store --}}
            <a :href="liveUrl" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-sky-400 hover:text-sky-600 transition flex items-center gap-2 group">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>View on Store</span>
            </a>

            <button type="submit" form="categoryForm" class="px-5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Update Category</span>
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

    <form id="categoryForm" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Main Category Details & Visuals -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Category Details Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Category Information</h3>
                                <p class="text-[11px] text-slate-400">Department name, URL slug & customer description</p>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">* Required</span>
                    </div>

                    {{-- Category Name --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Category Name *</label>
                            <span class="text-[10px] text-sky-600 font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span>Used to generate clean URL slug automatically</span>
                            </span>
                        </div>
                        <input type="text" name="name" x-model="name" @input="onNameInput()" required value="{{ old('name', $category->name) }}" class="w-full text-base font-semibold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition px-3.5 py-2.5 bg-slate-50/40 hover:bg-white focus:bg-white">
                    </div>

                    {{-- Dedicated Live Hyperlink Banner --}}
                    <div class="rounded-xl border border-sky-200/80 bg-gradient-to-r from-sky-50/80 via-white to-sky-50/40 p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-start sm:items-center gap-2.5 min-w-0">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-sky-600 text-white flex-shrink-0 shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            <div class="min-w-0">
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-sky-800">Live Storefront Hyperlink</span>
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
                            <a :href="liveUrl" target="_blank" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-1.5">
                                <span>Open Category</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- URL Slug --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">URL Slug</label>
                            <button 
                                type="button" 
                                @click="resetSlugToTitle()" 
                                class="text-[10px] font-bold text-sky-600 hover:text-sky-800 hover:underline cursor-pointer flex items-center gap-1"
                                title="Auto-sync slug cleanly from category name"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Sync from Name</span>
                            </button>
                        </div>
                        <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                            <span class="inline-flex items-center px-3 text-xs font-mono text-slate-400 bg-slate-100 border-r border-slate-200 select-none">
                                /category/
                            </span>
                            <input 
                                type="text" 
                                name="slug" 
                                x-model="slug" 
                                @input="onSlugInput()" 
                                value="{{ old('slug', $category->slug) }}" 
                                class="flex-1 min-w-0 block w-full px-3 py-2 text-xs font-mono font-semibold text-slate-800 bg-white border-0 focus:ring-0 focus:outline-none"
                            >
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">
                            Direct Hyperlink: <a :href="liveUrl" target="_blank" class="text-sky-600 hover:underline font-mono" x-text="liveUrl"></a>
                        </p>
                    </div>

                    {{-- Description --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Category Description</label>
                            <span class="text-[10px] text-slate-400">Header blurb displayed at top of category page</span>
                        </div>
                        <textarea name="description" rows="3" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition py-2 px-3 bg-slate-50/40 hover:bg-white focus:bg-white">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>

                <!-- Category Icon Section -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Category Icon & Theme</h3>
                                <p class="text-[11px] text-slate-400">Choose a vector preset icon or upload a custom graphic</p>
                            </div>
                        </div>

                        {{-- Active Selection Pill --}}
                        <div class="flex items-center gap-2 bg-slate-50 px-3 py-1 rounded-xl border border-slate-200">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Active:</span>
                            <span class="font-mono text-xs font-black text-sky-600 uppercase" x-text="icon || 'None'"></span>
                        </div>
                    </div>

                    <!-- Hidden Input Bound to Form -->
                    <input type="hidden" name="icon" x-model="icon">

                    <!-- Preset Icon Grid -->
                    <div>
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Preset Vector Icons (Click to Select)</span>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2.5">
                            @foreach($presetIcons as $key => $preset)
                                <button type="button" 
                                        @click="icon = '{{ $key }}'"
                                        :class="icon === '{{ $key }}' ? 'border-sky-500 bg-sky-50/70 ring-2 ring-sky-500/30 text-sky-900 shadow-xs' : 'border-slate-200/90 bg-white hover:border-slate-300 text-slate-700 hover:bg-slate-50/50'"
                                        class="relative p-2.5 border rounded-xl flex flex-col items-center justify-center gap-2 transition-all cursor-pointer group text-center">
                                    
                                    {{-- Selected Badge --}}
                                    <template x-if="icon === '{{ $key }}'">
                                        <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-sky-600 text-white flex items-center justify-center shadow-xs">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    </template>

                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-xs p-2 group-hover:scale-108 transition-transform"
                                         style="background: {{ $preset['style_gradient'] }};">
                                        {!! $preset['svg'] !!}
                                    </div>
                                    <span class="text-[10px] font-bold truncate max-w-full capitalize">{{ $key }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Custom Icon Upload & Emoji Input -->
                    <div class="pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Custom Upload --}}
                        <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/80 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Upload Custom Icon (SVG / PNG)</label>
                            @if($category->icon && (str_contains($category->icon, '/') || str_contains($category->icon, '.')))
                                <div class="flex items-center gap-2 p-2 bg-white rounded-lg border border-slate-200 text-xs">
                                    <img src="{{ asset('storage/' . $category->icon) }}" class="w-6 h-6 object-contain">
                                    <span class="text-[11px] text-slate-500 truncate">Current uploaded icon</span>
                                </div>
                            @endif
                            <label class="border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-xl p-3 flex flex-col items-center justify-center text-center cursor-pointer bg-white hover:bg-sky-50/30 transition group">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-500 mb-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-sky-600">Choose custom icon file</span>
                                <span class="text-[9px] text-slate-400 mt-0.5">Vector SVG or transparent PNG</span>
                                <input type="file" name="icon_file" accept=".svg,.png,.jpg,.jpeg,.webp" @change="previewIconFile" class="hidden">
                            </label>
                            <template x-if="iconFilePreview">
                                <div class="flex items-center gap-2 p-1.5 bg-emerald-50 text-emerald-800 rounded-lg text-xs font-medium border border-emerald-200">
                                    <img :src="iconFilePreview" class="w-6 h-6 object-contain">
                                    <span>New custom icon selected</span>
                                </div>
                            </template>
                        </div>

                        {{-- Emoji / Custom Key --}}
                        <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/80 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Custom Key or Emoji</label>
                            <input type="text" x-model="icon" placeholder="e.g. drink, oil, or 🥤" class="w-full text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2 px-3 bg-white">
                            <p class="text-[10px] text-slate-400">Type an emoji or preset key name if not using preset picker.</p>
                        </div>
                    </div>
                </div>

                <!-- Banner / Header Image Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Category Header Banner</h3>
                                <p class="text-[11px] text-slate-400">Optional background image for category landing page</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="relative w-32 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex-shrink-0 flex items-center justify-center p-1 shadow-2xs">
                            <template x-if="bannerPreview">
                                <img :src="bannerPreview" class="w-full h-full object-cover rounded-lg">
                            </template>
                            <template x-if="!bannerPreview">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="text-center text-slate-300">
                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[9px] block mt-0.5">No Banner</span>
                                    </div>
                                @endif
                            </template>
                        </div>

                        <div class="flex-1 w-full">
                            <label class="border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-xl p-3.5 flex flex-col items-center justify-center text-center cursor-pointer bg-white hover:bg-sky-50/30 transition group">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-500 mb-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-sky-600">
                                    {{ $category->image ? 'Replace Header Banner Image' : 'Upload Header Banner Image' }}
                                </span>
                                <span class="text-[9px] text-slate-400 mt-0.5">JPG, PNG, WEBP (1200x400px recommended)</span>
                                <input type="file" name="image" accept="image/*" @change="previewBanner" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SEO Card -->
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
                            <span>{{ url('/') }}/category/</span><span class="font-bold text-slate-700" x-text="slug || '{{ $category->slug }}'"></span>
                        </div>
                        <h4 class="text-base text-blue-700 font-semibold hover:underline truncate" x-text="metaTitle || name || '{{ $category->name }}'"></h4>
                        <p class="text-xs text-slate-600 line-clamp-2" x-text="metaDesc || 'Shop grocery essentials and household items at N&J Mini Mart.'"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-xs font-bold text-slate-700 uppercase">Meta Title</label>
                                <span class="text-[10px] text-slate-400" :class="metaTitle.length > 60 ? 'text-rose-500 font-bold' : ''" x-text="metaTitle.length + '/60' + (metaTitle.length > 60 ? ' (too long)' : '')"></span>
                            </div>
                            <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title', $category->meta_title) }}" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Focus Keyword</label>
                            <input type="text" name="focus_keyword" value="{{ old('focus_keyword', $category->focus_keyword) }}" placeholder="e.g. beverages, groceries" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Meta Description</label>
                            <span class="text-[10px] text-slate-400" :class="metaDesc.length > 160 ? 'text-rose-500 font-bold' : ''" x-text="metaDesc.length + '/160' + (metaDesc.length > 160 ? ' (too long)' : '')"></span>
                        </div>
                        <textarea name="meta_description" x-model="metaDesc" rows="2" class="w-full text-xs font-medium border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 py-2">{{ old('meta_description', $category->meta_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Col: Hierarchy, Order & Visibility -->
            <div class="space-y-6">
                
                <!-- Hierarchy & Navigation Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Hierarchy & Order</h3>
                            <p class="text-[11px] text-slate-400">Position in menu and parent aisle</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Parent Category</label>
                        <select name="parent_id" class="w-full text-xs font-bold py-2.5 px-3 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            <option value="">None (Top-Level Category)</option>
                            @foreach($parentCategories as $pcat)
                                <option value="{{ $pcat->id }}" {{ old('parent_id', $category->parent_id) == $pcat->id ? 'selected' : '' }}>{{ $pcat->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Select a parent category if this is a sub-category.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full text-xs font-mono font-bold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 py-2.5 px-3 bg-slate-50/50 hover:bg-white focus:bg-white">
                        <p class="text-[10px] text-slate-400 mt-1">Lower numbers appear first in the storefront mega-menu.</p>
                    </div>
                </div>

                <!-- Status & Mega-Menu Visibility -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Visibility</h3>
                            <p class="text-[11px] text-slate-400">Storefront and mega-menu status</p>
                        </div>
                    </div>

                    {{-- Active Toggle Switch --}}
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer" @click="isActive = !isActive">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Category</span>
                            <span class="text-[10px] text-slate-400 block">Visible in mega-menu, header search and homepage</span>
                        </div>
                        <input type="checkbox" name="is_active" value="1" class="hidden" :checked="isActive">
                        <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="isActive ? 'bg-emerald-600' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isActive ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>

                <!-- Products in this Category Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Catalog Allocation</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            {{ $category->products()->count() }} Products
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">
                        There are currently <strong class="text-slate-800">{{ $category->products()->count() }} active products</strong> assigned to this category.
                    </p>
                    <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="w-full block text-center py-2 px-3 border border-slate-200 hover:border-sky-300 bg-slate-50 hover:bg-white text-slate-700 hover:text-sky-600 rounded-xl text-xs font-bold transition shadow-2xs">
                        View Products in this Category &rarr;
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
