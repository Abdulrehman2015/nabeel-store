@extends('layouts.admin')

@section('title', 'Theme & Appearance Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    primary: '{{ old('color_primary', setting('color_primary', '#0284c7')) }}',
    primaryHover: '{{ old('color_primary_hover', setting('color_primary_hover', '#0369a1')) }}',
    secondary: '{{ old('color_secondary', setting('color_secondary', '#0f172a')) }}',
    accent: '{{ old('color_accent', setting('color_accent', '#f59e0b')) }}',
    headerBg: '{{ old('color_header_bg', setting('color_header_bg', '#ffffff')) }}',
    footerBg: '{{ old('color_footer_bg', setting('color_footer_bg', '#0f172a')) }}',
    btnText: '{{ old('color_button_text', setting('color_button_text', '#ffffff')) }}',
    radius: '{{ old('border_radius', setting('border_radius', '0.5rem')) }}',
    resetDefaults() {
        this.primary = '#0284c7';
        this.primaryHover = '#0369a1';
        this.secondary = '#0f172a';
        this.accent = '#f59e0b';
        this.headerBg = '#ffffff';
        this.footerBg = '#0f172a';
        this.btnText = '#ffffff';
        this.radius = '0.5rem';
    }
}">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Appearance & Branding</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Storefront Visual Theme & Live Customizer</h1>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="resetDefaults()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">Reset Defaults</button>
            <button type="submit" form="themeForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow transition">Save Live Theme</button>
        </div>
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

    <!-- Live Interactive Preview Box -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Live Preview (Updates as you pick colors)</h3>
        
        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-inner bg-gray-50 p-4 space-y-4">
            <!-- Mock Header -->
            <div class="p-3 rounded-lg flex items-center justify-between border" :style="{ backgroundColor: headerBg }">
                <span class="font-black text-lg tracking-tight" :style="{ color: primary }">99BULKSALES</span>
                <div class="flex gap-2">
                    <span class="px-3 py-1.5 text-xs font-semibold rounded" :style="{ backgroundColor: primary, color: btnText, borderRadius: radius }">Cart (3)</span>
                </div>
            </div>

            <!-- Mock Product Card -->
            <div class="bg-white p-4 rounded-lg border max-w-xs shadow-xs space-y-3" :style="{ borderRadius: radius }">
                <div class="h-24 bg-gray-100 rounded flex items-center justify-center text-gray-400 font-bold text-xs" :style="{ borderRadius: radius }">
                    [FANTA 12 x 1.25L]
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded" :style="{ backgroundColor: accent, color: '#ffffff' }">Promo</span>
                    <h5 class="text-sm font-bold text-gray-900 mt-1">FANTA Orange Wholesale Carton</h5>
                    <p class="text-base font-extrabold mt-0.5" :style="{ color: primary }">RM 42.50</p>
                </div>
                <button type="button" class="w-full py-2 text-xs font-bold uppercase tracking-wider shadow" :style="{ backgroundColor: primary, color: btnText, borderRadius: radius }">
                    Add To Cart
                </button>
            </div>

            <!-- Mock Footer -->
            <div class="p-4 rounded-lg text-white text-xs flex justify-between" :style="{ backgroundColor: footerBg }">
                <span>© 2026 Nabeel Bulk Sales Store</span>
                <span class="text-gray-400">Halal Certified Wholesale</span>
            </div>
        </div>
    </div>

    <form id="themeForm" action="{{ route('admin.settings.appearance.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Colors Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Color Palette (CSS Variables)</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Primary Brand Color *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="primary" name="color_primary" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="primary" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Primary Hover State *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="primaryHover" name="color_primary_hover" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="primaryHover" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Secondary / Dark *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="secondary" name="color_secondary" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="secondary" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Accent / Promo Gold *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="accent" name="color_accent" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="accent" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Header Background *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="headerBg" name="color_header_bg" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="headerBg" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Footer Background *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="footerBg" name="color_footer_bg" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="footerBg" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Button Text Color *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" x-model="btnText" name="color_button_text" class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                        <input type="text" x-model="btnText" class="w-28 text-xs font-mono uppercase border-gray-300 rounded-lg">
                    </div>
                </div>

                <input type="hidden" name="color_bg" value="{{ setting('color_bg', '#f8fafc') }}">
                <input type="hidden" name="color_text" value="{{ setting('color_text', '#0f172a') }}">
            </div>
        </div>

        <!-- Typography & Radius -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Typography & Component Geometry</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Font Family *</label>
                    <select name="font_family" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="'Inter', sans-serif" {{ setting('font_family') === "'Inter', sans-serif" ? 'selected' : '' }}>Inter (Clean Modern UI)</option>
                        <option value="'Plus Jakarta Sans', sans-serif" {{ setting('font_family') === "'Plus Jakarta Sans', sans-serif" ? 'selected' : '' }}>Plus Jakarta Sans (Trendy & Rounded)</option>
                        <option value="'Roboto', sans-serif" {{ setting('font_family') === "'Roboto', sans-serif" ? 'selected' : '' }}>Roboto (Crisp Google Sans Style)</option>
                        <option value="'Open Sans', sans-serif" {{ setting('font_family') === "'Open Sans', sans-serif" ? 'selected' : '' }}>Open Sans (High Legibility)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Corner Radius (Buttons & Cards) *</label>
                    <select name="border_radius" x-model="radius" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="0.25rem">Small (4px - Sharp)</option>
                        <option value="0.5rem">Medium (8px - Standard)</option>
                        <option value="0.75rem">Large (12px - Friendly Soft)</option>
                        <option value="1rem">Extra Large (16px - Modern Pill)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Brand Assets -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Logo & Favicon</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Header Store Logo</label>
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}" class="h-10 object-contain mb-2 border p-1 bg-white rounded">
                    @endif
                    <input type="file" name="site_logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    <p class="text-[11px] text-gray-500 mt-1">Recommended: PNG with transparent background, height 40px.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Favicon (.ico or .png)</label>
                    @if(setting('site_favicon'))
                        <img src="{{ asset('storage/' . setting('site_favicon')) }}" class="w-8 h-8 object-contain mb-2 border p-1 bg-white rounded">
                    @endif
                    <input type="file" name="site_favicon" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

