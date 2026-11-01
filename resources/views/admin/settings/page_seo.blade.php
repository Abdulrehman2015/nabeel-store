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
        <button type="submit" form="pageSeoForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Page SEO</button>
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
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 font-sans text-left space-y-1">
                <div class="text-xs text-gray-500 truncate flex items-center gap-1">
                    <span>{{ url('/') }}/{{ $selectedPage === 'home' ? '' : $selectedPage }}</span>
                </div>
                <h4 class="text-base text-blue-700 font-medium hover:underline truncate" x-text="metaTitle || '{{ $availablePages[$selectedPage] ?? 'Page Title' }} — {{ setting('site_name') }}'"></h4>
                <p class="text-xs text-gray-600 line-clamp-2" x-text="metaDesc || 'Wholesale bulk groceries and household essentials delivered in carton quantities.'"></p>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-xs font-semibold text-gray-700 uppercase">Meta Title</label>
                    <span class="text-[10px] text-gray-400" :class="metaTitle.length > 60 ? 'text-red-500 font-bold' : ''" x-text="metaTitle.length + '/60 chars'"></span>
                </div>
                <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title', $savedData['meta_title'] ?? '') }}" placeholder="Custom SERP Title" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-xs font-semibold text-gray-700 uppercase">Meta Description</label>
                    <span class="text-[10px] text-gray-400" :class="metaDesc.length > 160 ? 'text-red-500 font-bold' : ''" x-text="metaDesc.length + '/160 chars'"></span>
                </div>
                <textarea name="meta_description" x-model="metaDesc" rows="3" placeholder="Targeted description for search results" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('meta_description', $savedData['meta_description'] ?? '') }}</textarea>
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
