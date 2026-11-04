@extends('layouts.admin')

@section('title', 'Master Global SEO & Webmaster Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    siteName: '{{ addslashes(old('site_name', setting('site_name', 'N&J Mini Mart'))) }}',
    metaTitle: '{{ addslashes(old('seo_default_title', setting('seo_default_title', 'N&J Mini Mart - Groceries & Daily Household Essentials'))) }}',
    metaDesc: '{{ addslashes(old('seo_default_description', setting('seo_default_description', 'Shop groceries, drinks, cooking staples, snacks, and household cleaning supplies at N&J Mini Mart.'))) }}',
    titlePattern: '{{ addslashes(old('seo_meta_title_template', setting('seo_meta_title_template', '%title% | %site_name%'))) }}',
    tagline: '{{ addslashes(old('site_tagline', setting('site_tagline', 'Your Friendly Neighbourhood Mini Mart'))) }}',
    keywords: '{{ addslashes(old('seo_default_keywords', setting('seo_default_keywords', 'mini mart, groceries, food essentials, beverages, snacks, puchong grocery'))) }}',
    serpDevice: 'desktop',
    insertPlaceholder(token) {
        if (!this.titlePattern.includes(token)) {
            this.titlePattern += ' ' + token;
        }
    }
}">

    {{-- Top Header with Breadcrumbs & Save CTA --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Settings</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Master SEO</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Global SEO & Webmaster Tools</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure search engine SERP snippets, meta tag defaults, Google Analytics 4, and webmaster verification.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a 
                href="{{ url('/') }}" 
                target="_blank" 
                class="px-3.5 py-2.5 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-sky-400 hover:text-sky-600 transition flex items-center gap-1.5"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Storefront</span>
            </a>

            <button 
                type="submit" 
                form="seoForm" 
                class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 flex items-center gap-2 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Global SEO</span>
            </button>
        </div>
    </div>

    {{-- Error Notifications --}}
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

    {{-- Real-Time Google SERP Simulator Card --}}
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center font-bold text-sm">
                    🔍
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Live Google Search Snippet Preview</h3>
                    <p class="text-[11px] text-slate-500">Real-time simulation of how your store looks in Google search results.</p>
                </div>
            </div>

            {{-- Device View Switcher --}}
            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold self-start sm:self-center">
                <button 
                    type="button" 
                    @click="serpDevice = 'desktop'" 
                    :class="serpDevice === 'desktop' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'" 
                    class="px-3 py-1 rounded-lg transition-all flex items-center gap-1 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Desktop</span>
                </button>
                <button 
                    type="button" 
                    @click="serpDevice = 'mobile'" 
                    :class="serpDevice === 'mobile' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'" 
                    class="px-3 py-1 rounded-lg transition-all flex items-center gap-1 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Mobile</span>
                </button>
            </div>
        </div>

        {{-- Simulated SERP Box --}}
        <div class="p-4 sm:p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80">
            <div 
                class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-2xs transition-all"
                :class="serpDevice === 'mobile' ? 'max-w-sm mx-auto' : 'w-full'"
            >
                {{-- Favicon & URL Line --}}
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-6 h-6 rounded-full bg-slate-900 text-lime-400 font-black text-[10px] flex items-center justify-center shrink-0 border border-slate-800">
                        N&J
                    </div>
                    <div class="truncate">
                        <span class="text-xs font-medium text-slate-800 block truncate" x-text="siteName || 'N&J Mini Mart'"></span>
                        <span class="text-[11px] text-slate-500 font-mono block truncate">{{ url('/') }}</span>
                    </div>
                </div>

                {{-- Clickable Blue Title --}}
                <div class="text-blue-700 hover:underline font-medium text-base sm:text-lg leading-snug cursor-pointer line-clamp-1" x-text="metaTitle || 'N&J Mini Mart - Fresh Groceries & Daily Household Essentials'"></div>

                {{-- Snippet Description --}}
                <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed line-clamp-2" x-text="metaDesc || 'Shop groceries, drinks, cooking staples, snacks, and household cleaning supplies at N&J Mini Mart.'"></p>
            </div>

            {{-- Length Health Indicators --}}
            <div class="mt-3.5 pt-3 border-t border-slate-200/70 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-700">Google SERP Title:</span>
                    <span 
                        class="font-mono font-bold text-xs" 
                        :class="metaTitle.length >= 50 && metaTitle.length <= 60 ? 'text-emerald-600' : (metaTitle.length > 60 ? 'text-rose-600' : (metaTitle.length < 30 ? 'text-amber-600' : 'text-slate-600'))" 
                        x-text="metaTitle.length + ' / 60 chars'"
                    ></span>
                    <span 
                        class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                        :class="{
                            'bg-amber-100 text-amber-700 border border-amber-200': metaTitle.length < 30,
                            'bg-slate-100 text-slate-600 border border-slate-200': metaTitle.length >= 30 && metaTitle.length < 50,
                            'bg-emerald-100 text-emerald-700 border border-emerald-300 ring-1 ring-emerald-400/30': metaTitle.length >= 50 && metaTitle.length <= 60,
                            'bg-rose-100 text-rose-700 border border-rose-300': metaTitle.length > 60
                        }"
                        x-text="metaTitle.length < 30 ? 'Too Short (<30)' : (metaTitle.length <= 60 ? (metaTitle.length >= 50 ? '✓ Optimal (50-60)' : 'Acceptable (30-49)') : '⚠ Truncated (>60)')"
                    ></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-700">Google SERP Snippet:</span>
                    <span 
                        class="font-mono font-bold text-xs" 
                        :class="metaDesc.length >= 140 && metaDesc.length <= 160 ? 'text-emerald-600' : (metaDesc.length > 160 ? 'text-rose-600' : (metaDesc.length < 70 ? 'text-amber-600' : 'text-slate-600'))" 
                        x-text="metaDesc.length + ' / 160 chars'"
                    ></span>
                    <span 
                        class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                        :class="{
                            'bg-amber-100 text-amber-700 border border-amber-200': metaDesc.length < 70,
                            'bg-slate-100 text-slate-600 border border-slate-200': metaDesc.length >= 70 && metaDesc.length < 140,
                            'bg-emerald-100 text-emerald-700 border border-emerald-300 ring-1 ring-emerald-400/30': metaDesc.length >= 140 && metaDesc.length <= 160,
                            'bg-rose-100 text-rose-700 border border-rose-300': metaDesc.length > 160
                        }"
                        x-text="metaDesc.length < 70 ? 'Too Short (<70)' : (metaDesc.length <= 160 ? (metaDesc.length >= 140 ? '✓ Optimal (140-160)' : 'Acceptable (70-139)') : '⚠ Truncated (>160)')"
                    ></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Settings Form --}}
    <form id="seoForm" action="{{ route('admin.settings.master-seo.save') }}" method="POST" class="space-y-6">
        @csrf

        {{-- 1. Site Identity & SERP Defaults Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg">
                        🏷️
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Site Identity & Meta Defaults</h3>
                        <p class="text-xs text-slate-500">Google-optimized character limits, brand metadata, and title pattern templates.</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 bg-sky-50 border border-sky-200/70 rounded-full text-[11px] font-bold text-sky-700">
                    <svg class="w-3.5 h-3.5 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Google SEO Standard</span>
                </div>
            </div>

            {{-- Site Name & Tagline --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Site Brand Name *
                        </label>
                        <div class="flex items-center gap-1.5">
                            <span 
                                class="text-[11px] font-mono font-bold"
                                :class="siteName.length > 30 ? 'text-amber-600' : 'text-slate-500'"
                                x-text="siteName.length + '/30 chars'"
                            ></span>
                            <span class="text-[10px] text-slate-400 font-medium">(Max 30)</span>
                        </div>
                    </div>
                    <input 
                        type="text" 
                        name="site_name" 
                        required 
                        maxlength="60"
                        x-model="siteName"
                        value="{{ old('site_name', setting('site_name', 'N&J Mini Mart')) }}" 
                        class="w-full text-xs sm:text-sm font-semibold border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50"
                        placeholder="N&J Mini Mart"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">Short brand names (&le; 30 chars) prevent search snippet truncation when appended to titles.</p>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Store Tagline
                        </label>
                        <div class="flex items-center gap-1.5">
                            <span 
                                class="text-[11px] font-mono font-bold"
                                :class="tagline.length > 60 ? 'text-amber-600' : 'text-slate-500'"
                                x-text="tagline.length + '/60 chars'"
                            ></span>
                            <span class="text-[10px] text-slate-400 font-medium">(Max 60)</span>
                        </div>
                    </div>
                    <input 
                        type="text" 
                        name="site_tagline" 
                        maxlength="100"
                        x-model="tagline"
                        value="{{ old('site_tagline', setting('site_tagline', 'Your Friendly Neighbourhood Mini Mart')) }}" 
                        class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50"
                        placeholder="Your Friendly Neighbourhood Mini Mart"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">Punchy brand slogan displayed in SERP favicon breadcrumbs and homepage header.</p>
                </div>
            </div>

            {{-- Title Pattern Template --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Meta Title Pattern Template *
                    </label>
                    <span class="text-[11px] text-slate-400">Click to insert placeholder:</span>
                </div>
                <input 
                    type="text" 
                    name="seo_meta_title_template" 
                    required 
                    maxlength="100"
                    x-model="titlePattern"
                    value="{{ old('seo_meta_title_template', setting('seo_meta_title_template', '%title% | %site_name%')) }}" 
                    class="w-full text-xs sm:text-sm font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50"
                >
                <div class="flex flex-wrap items-center justify-between gap-2 mt-2">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <button type="button" @click="insertPlaceholder('%title%')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono font-bold transition cursor-pointer">
                            + %title%
                        </button>
                        <button type="button" @click="insertPlaceholder('%site_name%')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono font-bold transition cursor-pointer">
                            + %site_name%
                        </button>
                        <button type="button" @click="insertPlaceholder('%category%')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono font-bold transition cursor-pointer">
                            + %category%
                        </button>
                    </div>
                    <span class="text-[11px] text-slate-500">Google automatically combines these into your final 60-char title.</span>
                </div>
            </div>

            {{-- Fallback Meta Title (Google Limit: 50-60 chars) --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Default Fallback Meta Title *
                        </label>
                        <span class="text-[11px] font-bold text-slate-400">(Google SERP)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span 
                            class="text-[10px] font-bold px-2 py-0.5 rounded-md transition-all"
                            :class="{
                                'bg-amber-100 text-amber-700 border border-amber-200': metaTitle.length < 30,
                                'bg-slate-100 text-slate-600 border border-slate-200': metaTitle.length >= 30 && metaTitle.length < 50,
                                'bg-emerald-100 text-emerald-700 border border-emerald-300 ring-1 ring-emerald-400/30 font-extrabold': metaTitle.length >= 50 && metaTitle.length <= 60,
                                'bg-rose-100 text-rose-700 border border-rose-300': metaTitle.length > 60
                            }"
                            x-text="metaTitle.length < 30 ? 'Too Short (<30)' : (metaTitle.length <= 60 ? (metaTitle.length >= 50 ? '✓ Optimal for Google (50-60 chars)' : 'Acceptable (30-49 chars)') : '⚠ Truncated by Google (>60 chars)')"
                        ></span>
                        <span 
                            class="text-xs font-mono font-bold"
                            :class="metaTitle.length >= 50 && metaTitle.length <= 60 ? 'text-emerald-600' : (metaTitle.length > 60 ? 'text-rose-600' : (metaTitle.length < 30 ? 'text-amber-600' : 'text-slate-600'))"
                            x-text="metaTitle.length + '/60'"
                        ></span>
                    </div>
                </div>
                <input 
                    type="text" 
                    name="seo_default_title" 
                    required 
                    maxlength="70"
                    x-model="metaTitle"
                    value="{{ old('seo_default_title', setting('seo_default_title', 'N&J Mini Mart - Groceries & Daily Household Essentials')) }}" 
                    class="w-full text-xs sm:text-sm font-semibold border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50"
                    placeholder="e.g. N&J Mini Mart - Groceries & Daily Household Essentials"
                >
                {{-- Dynamic Visual Progress Meter --}}
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1.5">
                    <div 
                        class="h-full transition-all duration-300 rounded-full"
                        :class="{
                            'bg-amber-400': metaTitle.length < 30,
                            'bg-sky-400': metaTitle.length >= 30 && metaTitle.length < 50,
                            'bg-emerald-500': metaTitle.length >= 50 && metaTitle.length <= 60,
                            'bg-rose-500': metaTitle.length > 60
                        }"
                        :style="'width: ' + Math.min(100, (metaTitle.length / 60) * 100) + '%'"
                    ></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                    <span>Google truncates search titles after <strong>60 characters</strong> (approx. 580–600px). Optimal range: <strong>50–60 characters</strong>.</span>
                    <span class="text-slate-400 font-mono text-[10px]">Hard limit: 70 chars</span>
                </div>
            </div>

            {{-- Fallback Meta Description (Google Limit: 140-160 chars) --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Default Fallback Meta Description *
                        </label>
                        <span class="text-[11px] font-bold text-slate-400">(SERP Snippet)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span 
                            class="text-[10px] font-bold px-2 py-0.5 rounded-md transition-all"
                            :class="{
                                'bg-amber-100 text-amber-700 border border-amber-200': metaDesc.length < 70,
                                'bg-slate-100 text-slate-600 border border-slate-200': metaDesc.length >= 70 && metaDesc.length < 140,
                                'bg-emerald-100 text-emerald-700 border border-emerald-300 ring-1 ring-emerald-400/30 font-extrabold': metaDesc.length >= 140 && metaDesc.length <= 160,
                                'bg-rose-100 text-rose-700 border border-rose-300': metaDesc.length > 160
                            }"
                            x-text="metaDesc.length < 70 ? 'Too Short (<70)' : (metaDesc.length <= 160 ? (metaDesc.length >= 140 ? '✓ Optimal for Google (140-160 chars)' : 'Acceptable (70-139 chars)') : '⚠ Truncated by Google (>160 chars)')"
                        ></span>
                        <span 
                            class="text-xs font-mono font-bold"
                            :class="metaDesc.length >= 140 && metaDesc.length <= 160 ? 'text-emerald-600' : (metaDesc.length > 160 ? 'text-rose-600' : (metaDesc.length < 70 ? 'text-amber-600' : 'text-slate-600'))"
                            x-text="metaDesc.length + '/160'"
                        ></span>
                    </div>
                </div>
                <textarea 
                    name="seo_default_description" 
                    rows="3" 
                    required 
                    maxlength="200"
                    x-model="metaDesc"
                    class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/50 leading-relaxed"
                    placeholder="Shop groceries, drinks, cooking staples, snacks, and household cleaning supplies at N&J Mini Mart."
                >{{ old('seo_default_description', setting('seo_default_description', 'Shop groceries, drinks, cooking staples, snacks, and household cleaning supplies at N&J Mini Mart.')) }}</textarea>
                {{-- Dynamic Visual Progress Meter --}}
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1.5">
                    <div 
                        class="h-full transition-all duration-300 rounded-full"
                        :class="{
                            'bg-amber-400': metaDesc.length < 70,
                            'bg-sky-400': metaDesc.length >= 70 && metaDesc.length < 140,
                            'bg-emerald-500': metaDesc.length >= 140 && metaDesc.length <= 160,
                            'bg-rose-500': metaDesc.length > 160
                        }"
                        :style="'width: ' + Math.min(100, (metaDesc.length / 160) * 100) + '%'"
                    ></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                    <span>Google displays <strong>140–160 characters</strong> (desktop snippet) or ~120 on mobile. Keep primary value propositions front-loaded.</span>
                    <span class="text-slate-400 font-mono text-[10px]">Hard limit: 200 chars</span>
                </div>
            </div>

            {{-- Meta Keywords & Robots Tag --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Default Meta Keywords
                        </label>
                        <div class="flex items-center gap-1.5">
                            <span 
                                class="text-[11px] font-mono font-bold"
                                :class="keywords.length > 200 ? 'text-amber-600' : 'text-slate-500'"
                                x-text="keywords.length + '/200 chars'"
                            ></span>
                            <span class="text-[10px] text-slate-400 font-medium">(Max 5-10 terms)</span>
                        </div>
                    </div>
                    <input 
                        type="text" 
                        name="seo_default_keywords" 
                        maxlength="250"
                        x-model="keywords"
                        value="{{ old('seo_default_keywords', setting('seo_default_keywords', 'mini mart, groceries, food essentials, beverages, snacks, puchong grocery')) }}" 
                        placeholder="groceries, beverages, rice, cooking oil, mini mart"
                        class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50"
                    >
                    <p class="text-[11px] text-slate-400 mt-1">Target 5–10 specific, comma-separated keywords to prevent spam flags.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Default Search Engine Robots Tag *
                    </label>
                    <select 
                        name="seo_default_robots" 
                        class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50 text-slate-700"
                    >
                        <option value="index, follow" {{ setting('seo_default_robots', 'index, follow') === 'index, follow' ? 'selected' : '' }}>
                            index, follow (Recommended — Allow Indexing)
                        </option>
                        <option value="noindex, follow" {{ setting('seo_default_robots') === 'noindex, follow' ? 'selected' : '' }}>
                            noindex, follow (Do Not Show in Search, Follow Links)
                        </option>
                        <option value="noindex, nofollow" {{ setting('seo_default_robots') === 'noindex, nofollow' ? 'selected' : '' }}>
                            noindex, nofollow (Complete Staging Block)
                        </option>
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">Directs Googlebot and search crawlers how to index your store pages.</p>
                </div>
            </div>
        </div>

        {{-- 2. Webmaster Consoles & Analytics Tracking Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg">
                        📊
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Search Consoles & Analytics Tracking</h3>
                        <p class="text-xs text-slate-500">Official verification tokens and conversion measurement pixels.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Google Search Console --}}
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🔍</span>
                        <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Google Search Console Tag
                        </label>
                    </div>
                    <input 
                        type="text" 
                        name="google_search_console_tag" 
                        value="{{ old('google_search_console_tag', setting('google_search_console_tag', '')) }}" 
                        placeholder="e.g. google-site-verification=abc123xyz" 
                        class="w-full text-xs font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3 bg-white"
                    >
                    <p class="text-[11px] text-slate-500 leading-snug">
                        Enter your HTML verification token or full meta tag provided by Google Search Console.
                    </p>
                </div>

                {{-- Bing Webmaster --}}
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🌐</span>
                        <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Bing Webmaster Verification
                        </label>
                    </div>
                    <input 
                        type="text" 
                        name="bing_verification_tag" 
                        value="{{ old('bing_verification_tag', setting('bing_verification_tag', '')) }}" 
                        placeholder="e.g. BING_VERIFICATION_TOKEN" 
                        class="w-full text-xs font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3 bg-white"
                    >
                    <p class="text-[11px] text-slate-500 leading-snug">
                        Enables verification for Bing and Yahoo search indexing.
                    </p>
                </div>

                {{-- Google Analytics 4 --}}
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📈</span>
                        <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Google Analytics 4 Measurement ID
                        </label>
                    </div>
                    <input 
                        type="text" 
                        name="google_analytics_id" 
                        value="{{ old('google_analytics_id', setting('google_analytics_id', '')) }}" 
                        placeholder="G-XXXXXXXXXX" 
                        class="w-full text-xs font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3 bg-white"
                    >
                    <p class="text-[11px] text-slate-500 leading-snug">
                        Automatically inserts the official Google Tag (gtag.js) tracking script across all storefront pages.
                    </p>
                </div>

                {{-- Meta / Facebook Pixel --}}
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🎯</span>
                        <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Meta / Facebook Pixel ID
                        </label>
                    </div>
                    <input 
                        type="text" 
                        name="facebook_pixel_id" 
                        value="{{ old('facebook_pixel_id', setting('facebook_pixel_id', '')) }}" 
                        placeholder="123456789012345" 
                        class="w-full text-xs font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3 bg-white"
                    >
                    <p class="text-[11px] text-slate-500 leading-snug">
                        Tracks visitor page views and conversions for Meta ads and Instagram marketing.
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. Robots.txt & Automated Sitemap Engine Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg">
                        🗺️
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Robots.txt & XML Sitemap Engine</h3>
                        <p class="text-xs text-slate-500">Configure crawler permissions and view auto-generated XML sitemaps.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ url('/sitemap.xml') }}" 
                        target="_blank" 
                        class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition flex items-center gap-1"
                    >
                        <span>View Sitemap.xml</span>
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Robots.txt Directives Content
                    </label>
                    <a href="{{ url('/robots.txt') }}" target="_blank" class="text-xs text-sky-600 hover:underline font-mono inline-flex items-center gap-1">
                        <span>View Live /robots.txt</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
                <textarea 
                    name="robots_txt_content" 
                    rows="6" 
                    class="w-full text-xs sm:text-sm font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/60 leading-relaxed text-slate-800"
                >{{ old('robots_txt_content', setting('robots_txt_content', "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /account/\nDisallow: /checkout/\n\nSitemap: " . url('/sitemap.xml'))) }}</textarea>
                <p class="text-[11px] text-slate-500 mt-1">
                    Directs Googlebot and search crawlers which sections to index and which to keep private.
                </p>
            </div>
        </div>

        {{-- 4. Custom Scripts & Code Injections Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/90 p-5 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg">
                        💻
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Custom Code & Tracking Injections</h3>
                        <p class="text-xs text-slate-500">Inject custom scripts, chat widgets, or tag managers into storefront templates.</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Custom Header Scripts (Injected right before &lt;/head&gt;)
                </label>
                <textarea 
                    name="custom_header_scripts" 
                    rows="3" 
                    placeholder="<!-- Google Tag Manager, custom CSS, verification meta tags -->" 
                    class="w-full text-xs sm:text-sm font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/60 text-slate-800"
                >{{ old('custom_header_scripts', setting('custom_header_scripts', '')) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Custom Footer Scripts (Injected right before &lt;/body&gt;)
                </label>
                <textarea 
                    name="custom_footer_scripts" 
                    rows="3" 
                    placeholder="<!-- Live chat widgets, remarketing tags, customer feedback widgets -->" 
                    class="w-full text-xs sm:text-sm font-mono border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/60 text-slate-800"
                >{{ old('custom_footer_scripts', setting('custom_footer_scripts', '')) }}</textarea>
            </div>
        </div>

        {{-- Save Action Footer --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <button 
                type="submit" 
                class="px-6 py-3 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 flex items-center gap-2 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Global SEO Settings</span>
            </button>
        </div>

    </form>

</div>
@endsection
