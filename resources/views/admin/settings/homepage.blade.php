@extends('layouts.admin')

@section('title', 'Homepage Settings - Hero Slider & Banners')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{
    newSlides: [],
    addSlide() {
        this.newSlides.push({
            id: Date.now(),
            title: '',
            subtitle: '',
            badge: '⚡ SPECIAL OFFER',
            link: '/category/beverages-soft-drinks',
            button_text: 'Shop Now',
            sort_order: {{ $banners->count() }} + this.newSlides.length + 1,
            is_active: true
        });
    },
    removeSlide(index) {
        this.newSlides.splice(index, 1);
    },
    previewImage(event, previewId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const el = document.getElementById(previewId);
                if (el) { el.src = e.target.result; el.classList.remove('hidden'); }
            };
            reader.readAsDataURL(file);
        }
    }
}">
    {{-- Header & Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Admin</a>
                <span>/</span>
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Homepage Settings</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Homepage Settings: Hero Sliders & Banners</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage promotional slider images, headings, descriptions, and add new slides dynamically.</p>
        </div>

        <div class="flex items-center gap-2.5">
            {{-- Prominent Plus Button --}}
            <button 
                type="button" 
                @click="addSlide()" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-sm hover:shadow transition flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Add Slide</span>
            </button>

            <button 
                type="submit" 
                form="homepageForm" 
                class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center justify-between">
            <div class="flex items-center gap-2 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                <span>View Storefront Live</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <div class="font-bold text-red-800 text-sm mb-1">Please fix the following validation errors:</div>
            <ul class="text-xs text-red-700 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Form --}}
    <form id="homepageForm" action="{{ route('admin.settings.homepage.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Section 1: Active Sliders List --}}
        <div class="space-y-5">
            <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-sm">🎞️</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Hero Slider Banners</h2>
                        <p class="text-xs text-gray-500">Each slide represents a banner on your storefront homepage carousel.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                    {{ $banners->count() }} Saved Slide(s)
                </span>
            </div>

            @forelse($banners as $index => $banner)
                @php
                    $rawPath = ltrim($banner->desktop_image ?? '', '/');
                    if (str_starts_with($rawPath, 'storage/')) {
                        $imgUrl = asset($rawPath);
                    } elseif (str_starts_with($rawPath, 'images/')) {
                        $imgUrl = asset($rawPath);
                    } else {
                        $imgUrl = asset('storage/' . $rawPath);
                    }
                    $fallbackUrl = asset('images/' . basename($rawPath));
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:border-gray-300">
                    {{-- Slide Card Header --}}
                    <div class="px-5 py-3.5 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $banner->is_active ? 'bg-sky-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                Slide #{{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-gray-600 truncate max-w-sm">
                                {{ $banner->title }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-gray-700">
                                <input type="hidden" name="banners[{{ $banner->id }}][is_active]" value="0">
                                <input type="checkbox" name="banners[{{ $banner->id }}][is_active]" value="1" {{ $banner->is_active ? 'checked' : '' }} class="rounded text-primary focus:ring-primary h-4 w-4">
                                <span>Visible</span>
                            </label>
                            
                            {{-- Individual Delete Action --}}
                            <button 
                                type="submit" 
                                form="deleteBannerForm-{{ $banner->id }}" 
                                onclick="return confirm('Delete this slide permanently?');"
                                class="text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition"
                                title="Delete this slide"
                            >
                                ✕ Remove
                            </button>
                        </div>
                    </div>

                    {{-- Slide Card Body --}}
                    <div class="p-5 grid grid-cols-1 lg:grid-cols-12 gap-5">
                        {{-- Left Column: Image Preview & Upload --}}
                        <div class="lg:col-span-4 space-y-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Banner Image</label>
                            <div class="relative aspect-[16/9] rounded-xl overflow-hidden border border-gray-200 bg-slate-900 group/img">
                                <img 
                                    id="preview-existing-{{ $banner->id }}"
                                    src="{{ $imgUrl }}" 
                                    onerror="this.onerror=null; this.src='{{ $fallbackUrl }}'"
                                    alt="Slide Preview" 
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <div>
                                <label class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 cursor-pointer shadow-sm w-full justify-center">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Replace Banner Image</span>
                                    <input 
                                        type="file" 
                                        name="banners[{{ $banner->id }}][desktop_image]" 
                                        accept="image/*" 
                                        class="hidden" 
                                        @change="previewImage($event, 'preview-existing-{{ $banner->id }}')"
                                    >
                                </label>
                                <p class="text-[11px] text-gray-400 mt-1 text-center">Recommended: 1920x800 or 1600x680 (16:9)</p>
                            </div>
                        </div>

                        {{-- Right Column: Headings, Descriptions, Links --}}
                        <div class="lg:col-span-8 space-y-3.5">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                    Banner Heading (Title) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="banners[{{ $banner->id }}][title]" 
                                    required 
                                    value="{{ old('banners.'.$banner->id.'.title', $banner->title) }}" 
                                    class="w-full text-sm font-semibold border-gray-300 rounded-xl focus:ring-primary focus:border-primary px-3.5 py-2.5"
                                    placeholder="e.g. Direct Wholesale Groceries in Bulk Cartons"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                    Banner Description (Subtitle)
                                </label>
                                <textarea 
                                    name="banners[{{ $banner->id }}][subtitle]" 
                                    rows="2"
                                    class="w-full text-sm border-gray-300 rounded-xl focus:ring-primary focus:border-primary px-3.5 py-2"
                                    placeholder="e.g. Save up to 35% on genuine carton drinks, dairy, cooking oil & supplies."
                                >{{ old('banners.'.$banner->id.'.subtitle', $banner->subtitle) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Tag / Badge</label>
                                    <input 
                                        type="text" 
                                        name="banners[{{ $banner->id }}][badge]" 
                                        value="{{ old('banners.'.$banner->id.'.badge', $banner->badge) }}" 
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                        placeholder="⚡ DIRECT BULK DISCOUNTS"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Button Text</label>
                                    <input 
                                        type="text" 
                                        name="banners[{{ $banner->id }}][button_text]" 
                                        value="{{ old('banners.'.$banner->id.'.button_text', $banner->button_text) }}" 
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                        placeholder="Shop Bulk Drinks"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Button Link URL</label>
                                    <input 
                                        type="text" 
                                        name="banners[{{ $banner->id }}][link]" 
                                        value="{{ old('banners.'.$banner->id.'.link', $banner->link) }}" 
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                        placeholder="/category/beverages-soft-drinks"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Sort Order</label>
                                    <input 
                                        type="number" 
                                        name="banners[{{ $banner->id }}][sort_order]" 
                                        value="{{ old('banners.'.$banner->id.'.sort_order', $banner->sort_order) }}" 
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center bg-white rounded-2xl border border-dashed border-gray-300 text-gray-500">
                    <p class="font-semibold">No slides configured yet.</p>
                    <p class="text-xs mt-1 text-gray-400">Click the button below to add your first hero banner slide.</p>
                </div>
            @endforelse

            {{-- Dynamically Added Slides from the "+" Button --}}
            <template x-for="(slide, index) in newSlides" :key="slide.id">
                <div class="bg-white rounded-2xl shadow-sm border-2 border-emerald-400 overflow-hidden transition-all duration-300">
                    {{-- Header for New Slide --}}
                    <div class="px-5 py-3.5 bg-emerald-50 border-b border-emerald-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-600 text-white">
                                New Slide #<span x-text="index + {{ $banners->count() + 1 }}"></span>
                            </span>
                            <span class="text-xs font-bold text-emerald-800">Unsaved Slide</span>
                        </div>
                        <button 
                            type="button" 
                            @click="removeSlide(index)" 
                            class="text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition"
                        >
                            ✕ Cancel / Remove
                        </button>
                    </div>

                    {{-- Body for New Slide --}}
                    <div class="p-5 grid grid-cols-1 lg:grid-cols-12 gap-5">
                        {{-- Left Column: Image Upload --}}
                        <div class="lg:col-span-4 space-y-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Upload Banner Image</label>
                            <div class="relative aspect-[16/9] rounded-xl overflow-hidden border-2 border-dashed border-gray-300 bg-slate-900 flex items-center justify-center">
                                <img 
                                    :id="'preview-new-' + slide.id"
                                    src="" 
                                    alt="Preview" 
                                    class="w-full h-full object-cover hidden"
                                >
                                <div :id="'placeholder-new-' + slide.id" class="text-center p-4 text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs">No image chosen</span>
                                </div>
                            </div>
                            <div>
                                <label class="inline-flex items-center gap-2 px-3 py-2 border border-emerald-300 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 cursor-pointer shadow-sm w-full justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Choose Banner Image *</span>
                                    <input 
                                        type="file" 
                                        :name="'new_banners[' + index + '][desktop_image]'" 
                                        accept="image/*" 
                                        required
                                        class="hidden" 
                                        @change="previewImage($event, 'preview-new-' + slide.id); document.getElementById('placeholder-new-' + slide.id).classList.add('hidden');"
                                    >
                                </label>
                            </div>
                        </div>

                        {{-- Right Column: Form Fields --}}
                        <div class="lg:col-span-8 space-y-3.5">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                    Banner Heading (Title) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    :name="'new_banners[' + index + '][title]'" 
                                    required 
                                    x-model="slide.title"
                                    class="w-full text-sm font-semibold border-gray-300 rounded-xl focus:ring-primary focus:border-primary px-3.5 py-2.5"
                                    placeholder="e.g. Commercial Kitchen Essentials & Bulk Rice"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                    Banner Description (Subtitle)
                                </label>
                                <textarea 
                                    :name="'new_banners[' + index + '][subtitle]'" 
                                    rows="2"
                                    x-model="slide.subtitle"
                                    class="w-full text-sm border-gray-300 rounded-xl focus:ring-primary focus:border-primary px-3.5 py-2"
                                    placeholder="e.g. Commercial 5kg to 17kg catering packs with fast doorstep delivery."
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Tag / Badge</label>
                                    <input 
                                        type="text" 
                                        :name="'new_banners[' + index + '][badge]'" 
                                        x-model="slide.badge"
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Button Text</label>
                                    <input 
                                        type="text" 
                                        :name="'new_banners[' + index + '][button_text]'" 
                                        x-model="slide.button_text"
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Button Link URL</label>
                                    <input 
                                        type="text" 
                                        :name="'new_banners[' + index + '][link]'" 
                                        x-model="slide.link"
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Sort Order</label>
                                    <input 
                                        type="number" 
                                        :name="'new_banners[' + index + '][sort_order]'" 
                                        x-model="slide.sort_order"
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2 px-2.5"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Big "+" Add Another Slide Card --}}
            <button 
                type="button" 
                @click="addSlide()" 
                class="w-full py-6 border-2 border-dashed border-gray-300 hover:border-emerald-500 rounded-2xl bg-white hover:bg-emerald-50/50 flex flex-col items-center justify-center gap-2 transition duration-200 group/add cursor-pointer shadow-sm"
            >
                <div class="w-12 h-12 rounded-full bg-emerald-100 group-hover/add:bg-emerald-600 text-emerald-600 group-hover/add:text-white flex items-center justify-center transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div class="text-center">
                    <span class="text-sm font-bold text-gray-800 group-hover/add:text-emerald-700">+ Add Another Banner Slide</span>
                    <p class="text-xs text-gray-400 mt-0.5">Click to dynamically insert a new slide with custom image, heading & description.</p>
                </div>
            </button>
        </div>

        {{-- Section 2: Slider Carousel Behavior Settings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-1 flex items-center gap-2">
                <span>⏱️</span>
                <span>Slider Auto-Play & Transition Settings</span>
            </h3>
            <p class="text-xs text-gray-500 mb-4">Control how fast the slider slides between banner images automatically.</p>

            <div class="max-w-xs">
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Auto-Play Speed</label>
                <select name="slider_autoplay_speed" class="w-full text-sm border-gray-300 rounded-xl focus:ring-primary focus:border-primary">
                    <option value="4000" {{ setting('slider_autoplay_speed', 6000) == 4000 ? 'selected' : '' }}>4 Seconds (Fast)</option>
                    <option value="6000" {{ setting('slider_autoplay_speed', 6000) == 6000 ? 'selected' : '' }}>6 Seconds (Recommended)</option>
                    <option value="8000" {{ setting('slider_autoplay_speed', 6000) == 8000 ? 'selected' : '' }}>8 Seconds (Relaxed)</option>
                    <option value="10000" {{ setting('slider_autoplay_speed', 6000) == 10000 ? 'selected' : '' }}>10 Seconds</option>
                </select>
            </div>
        </div>

        {{-- Bottom Sticky Save Bar --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                Back to Dashboard
            </a>

            <div class="flex items-center gap-3">
                <button 
                    type="button" 
                    @click="addSlide()" 
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add Slide</span>
                </button>

                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Save All Changes</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Individual Banner Delete Forms --}}
    @foreach($banners as $banner)
        <form id="deleteBannerForm-{{ $banner->id }}" action="{{ route('admin.settings.homepage.banner.delete', $banner) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</div>
@endsection

