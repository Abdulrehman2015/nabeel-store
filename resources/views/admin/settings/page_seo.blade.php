@extends('layouts.admin')

@section('title', 'Page-Specific SEO Overrides')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    metaTitle: '{{ old('meta_title', $savedData['meta_title'] ?? '') }}',
    metaDesc: '{{ old('meta_description', $savedData['meta_description'] ?? '') }}',
    pageKey: '{{ $selectedPage }}',
    onPageChange(e) {
        window.location.href = '{{ route('admin.settings.page-seo') }}?page=' + e.target.value;
    }
}">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Page SEO</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Page-Level SEO Customization</h1>
        </div>
        <button type="submit" form="pageSeoForm" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 flex items-center gap-2 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Save Page SEO</span>
        </button>
    </div>

    <!-- Page Selector Dropdown Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Select Page to Optimize</label>
        <div class="flex gap-4">
            <select @change="onPageChange($event)" class="w-full text-sm font-semibold text-gray-800 border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                @foreach($availablePages as $key => $title)
                    <option value="{{ $key }}" {{ $selectedPage === $key ? 'selected' : '' }}>
                        {{ $title }} ({{ $key }})
                    </option>
                @endforeach
            </select>
        </div>
        <p class="text-[11px] text-gray-500 mt-1">Choose any storefront landing, transactional, or legal page to set tailored meta descriptions and OpenGraph tags.</p>
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

    <form id="pageSeoForm" action="{{ route('admin.settings.page-seo.save') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="page_key" value="{{ $selectedPage }}">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                <span>SEO Parameters for: <span class="text-primary">{{ $availablePages[$selectedPage] ?? $selectedPage }}</span></span>
                <span class="text-xs text-primary font-normal">Google SERP Snippet</span>
            </h3>

            <!-- Google Snippet Preview -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 font-sans text-left space-y-1">
                <div class="text-xs text-slate-500 truncate flex items-center gap-1">
                    <span>{{ url('/') }}/{{ $selectedPage === 'home' ? '' : $selectedPage }}</span>
                </div>
                <h4 class="text-base text-blue-700 font-medium hover:underline truncate" x-text="metaTitle || '{{ $availablePages[$selectedPage] ?? 'Page Title' }} — {{ setting('site_name', 'N&J Mini Mart') }}'"></h4>
                <p class="text-xs text-slate-600 line-clamp-2" x-text="metaDesc || 'Shop groceries, fresh essentials, snacks, and daily household items at N&J Mini Mart.'"></p>
            </div>

            {{-- Meta Title with Google 50-60 Limit --}}
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Meta Title</label>
                        <span class="text-[10px] text-slate-400 font-medium">(Google SERP)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span 
                            class="text-[10px] font-bold px-2 py-0.5 rounded-md"
                            :class="{
                                'bg-amber-100 text-amber-700 border border-amber-200': metaTitle.length < 30,
                                'bg-slate-100 text-slate-600 border border-slate-200': metaTitle.length >= 30 && metaTitle.length < 50,
                                'bg-emerald-100 text-emerald-700 border border-emerald-300 ring-1 ring-emerald-400/30 font-bold': metaTitle.length >= 50 && metaTitle.length <= 60,
                                'bg-rose-100 text-rose-700 border border-rose-300': metaTitle.length > 60
                            }"
                            x-text="metaTitle.length < 30 ? 'Too Short (<30)' : (metaTitle.length <= 60 ? (metaTitle.length >= 50 ? '✓ Optimal (50-60)' : 'Acceptable (30-49)') : '⚠ Truncated (>60)')"
                        ></span>
                        <span 
                            class="text-xs font-mono font-bold"
                            :class="metaTitle.length >= 50 && metaTitle.length <= 60 ? 'text-emerald-600' : (metaTitle.length > 60 ? 'text-rose-600' : 'text-slate-500')"
                            x-text="metaTitle.length + '/60'"
                        ></span>
                    </div>
                </div>
                <input 
                    type="text" 
                    name="meta_title" 
                    maxlength="70"
                    x-model="metaTitle" 
                    value="{{ old('meta_title', $savedData['meta_title'] ?? '') }}" 
                    placeholder="Custom SERP Title" 
                    class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 py-2.5 px-3.5 bg-slate-50/50"
                >
                <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden mt-1.5">
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
            </div>

            {{-- Meta Description with Google 140-160 Limit --}}
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Meta Description</label>
                        <span class="text-[10px] text-slate-400 font-medium">(SERP Snippet)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span 
                            class="text-[10px] font-bold px-2 py-0.5 rounded-md"
                            :class="{
                                'bg-amber-100 text-amber-700 border border-amber-200': metaDesc.length < 70,
                                'bg-slate-100 text-slate-600 border border-slate-200': metaDesc.length >= 70 && metaDesc.length < 140,
                                'bg-emerald-100 text-emerald-700 border border-emerald-300 ring-1 ring-emerald-400/30 font-bold': metaDesc.length >= 140 && metaDesc.length <= 160,
                                'bg-rose-100 text-rose-700 border border-rose-300': metaDesc.length > 160
                            }"
                            x-text="metaDesc.length < 70 ? 'Too Short (<70)' : (metaDesc.length <= 160 ? (metaDesc.length >= 140 ? '✓ Optimal (140-160)' : 'Acceptable (70-139)') : '⚠ Truncated (>160)')"
                        ></span>
                        <span 
                            class="text-xs font-mono font-bold"
                            :class="metaDesc.length >= 140 && metaDesc.length <= 160 ? 'text-emerald-600' : (metaDesc.length > 160 ? 'text-rose-600' : 'text-slate-500')"
                            x-text="metaDesc.length + '/160'"
                        ></span>
                    </div>
                </div>
                <textarea 
                    name="meta_description" 
                    maxlength="200"
                    x-model="metaDesc" 
                    rows="3" 
                    placeholder="Targeted description for search results" 
                    class="w-full text-xs sm:text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 p-3.5 bg-slate-50/50 leading-relaxed"
                >{{ old('meta_description', $savedData['meta_description'] ?? '') }}</textarea>
                <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden mt-1.5">
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
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $savedData['meta_keywords'] ?? '') }}" placeholder="keyword1, keyword2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Canonical URL</label>
                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $savedData['canonical_url'] ?? '') }}" placeholder="https://..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Robots Tag</label>
                    <select name="robots" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="index, follow" {{ ($savedData['robots'] ?? 'index, follow') === 'index, follow' ? 'selected' : '' }}>index, follow</option>
                        <option value="noindex, follow" {{ ($savedData['robots'] ?? '') === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                        <option value="noindex, nofollow" {{ ($savedData['robots'] ?? '') === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Schema Type</label>
                    <select name="schema_type" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="WebPage" {{ ($savedData['schema_type'] ?? 'WebPage') === 'WebPage' ? 'selected' : '' }}>WebPage</option>
                        <option value="AboutPage" {{ ($savedData['schema_type'] ?? '') === 'AboutPage' ? 'selected' : '' }}>AboutPage</option>
                        <option value="ContactPage" {{ ($savedData['schema_type'] ?? '') === 'ContactPage' ? 'selected' : '' }}>ContactPage</option>
                        <option value="FAQPage" {{ ($savedData['schema_type'] ?? '') === 'FAQPage' ? 'selected' : '' }}>FAQPage</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

