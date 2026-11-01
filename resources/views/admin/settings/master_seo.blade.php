@extends('layouts.admin')

@section('title', 'Master Global SEO & Webmaster Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Master SEO</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Master Global SEO & Webmaster Tools</h1>
        </div>
        <button type="submit" form="seoForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Global SEO</button>
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

    <form id="seoForm" action="{{ route('admin.settings.master-seo.save') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Store Identity & Title Templates -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Site Identity & SERP Defaults</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Site Brand Name *</label>
                    <input type="text" name="site_name" required value="{{ old('site_name', setting('site_name', '99BulkSales Malaysia')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', setting('site_tagline', 'Direct Wholesale Groceries & Household Essentials in Bulk')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta Title Pattern *</label>
                <input type="text" name="seo_meta_title_template" required value="{{ old('seo_meta_title_template', setting('seo_meta_title_template', '{title} — {site_name}')) }}" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                <p class="text-[11px] text-gray-500 mt-1">Available placeholders: <code>{title}</code>, <code>{site_name}</code></p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Default Fallback Meta Title *</label>
                <input type="text" name="seo_default_title" required value="{{ old('seo_default_title', setting('seo_default_title', '99BulkSales — Wholesale Grocery Cartons & Commercial Supplies')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Default Fallback Meta Description *</label>
                <textarea name="seo_default_description" rows="2" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('seo_default_description', setting('seo_default_description', 'Buy groceries, beverages, snacks and household cleaning goods in wholesale bulk cartons directly from 99BulkSales Malaysia.')) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Default Meta Keywords</label>
                    <input type="text" name="seo_default_keywords" value="{{ old('seo_default_keywords', setting('seo_default_keywords', 'bulk grocery, wholesale drinks malaysia, commercial groceries')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Default Robots Tag *</label>
                    <select name="seo_default_robots" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="index, follow" {{ setting('seo_default_robots', 'index, follow') === 'index, follow' ? 'selected' : '' }}>index, follow (Recommended)</option>
                        <option value="noindex, follow" {{ setting('seo_default_robots') === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Verification & Analytics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Search Consoles & Analytics Tracking</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Google Search Console Tag</label>
                    <input type="text" name="google_search_console_tag" value="{{ old('google_search_console_tag', setting('google_search_console_tag', '')) }}" placeholder="e.g. google-site-verification=abc123xyz" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Bing Webmaster Tag</label>
                    <input type="text" name="bing_verification_tag" value="{{ old('bing_verification_tag', setting('bing_verification_tag', '')) }}" placeholder="e.g. BING_VERIFICATION_TOKEN" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Google Analytics 4 Measurement ID</label>
                    <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', setting('google_analytics_id', '')) }}" placeholder="G-XXXXXXXXXX" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta / Facebook Pixel ID</label>
                    <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', setting('facebook_pixel_id', '')) }}" placeholder="123456789012345" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>

        <!-- Robots.txt & Custom Scripts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Robots.txt & Custom Code Injections</h3>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold text-gray-700 uppercase">Robots.txt Content</label>
                    <a href="{{ url('/robots.txt') }}" target="_blank" class="text-xs text-primary hover:underline">View live /robots.txt</a>
                </div>
                <textarea name="robots_txt_content" rows="4" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('robots_txt_content', setting('robots_txt_content', "User-agent: *\nDisallow: /admin/\nDisallow: /checkout/\nDisallow: /cart/\nAllow: /\n\nSitemap: " . url('/sitemap.xml'))) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Custom Header Scripts (Injected right before &lt;/head&gt;)</label>
                <textarea name="custom_header_scripts" rows="3" placeholder="<!-- Meta tags, custom CSS, Google Tag Manager -->" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('custom_header_scripts', setting('custom_header_scripts', '')) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Custom Footer Scripts (Injected right before &lt;/body&gt;)</label>
                <textarea name="custom_footer_scripts" rows="3" placeholder="<!-- Live chat scripts, remarketing tags -->" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('custom_footer_scripts', setting('custom_footer_scripts', '')) }}</textarea>
            </div>
        </div>
    </form>
</div>
@endsection

