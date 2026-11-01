@extends('layouts.admin')

@section('title', 'Add New Bulk Category')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    name: '{{ old('name', '') }}',
    metaTitle: '{{ old('meta_title', '') }}',
    metaDesc: '{{ old('meta_description', '') }}',
    slug: '{{ old('slug', '') }}',
    icon: '{{ old('icon', 'box') }}',
    generateSlug() {
        this.slug = this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.categories.index') }}" class="hover:text-primary">Categories</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Create</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Add New Bulk Category</h1>
        </div>
        <button type="submit" form="categoryForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Category</button>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <h4 class="text-sm font-bold text-red-800">Please correct the following errors:</h4>
            <ul class="mt-2 text-xs text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Category Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Category Name *</label>
                    <input type="text" name="name" x-model="name" @input="if(!metaTitle) metaTitle = name; generateSlug();" required value="{{ old('name') }}" placeholder="e.g. Beverages & Soft Drinks" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" x-model="slug" value="{{ old('slug') }}" placeholder="auto-generated-slug" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Parent Category</label>
                    <select name="parent_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($parentCategories as $pcat)
                            <option value="{{ $pcat->id }}" {{ old('parent_id') == $pcat->id ? 'selected' : '' }}>{{ $pcat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <!-- Dynamic Category Icon Section -->
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Category Icon (Dynamic Selection)</h4>
                        <p class="text-xs text-slate-500">Choose a vector preset icon below or upload a custom icon file</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-slate-500">Selected:</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-mono text-xs font-bold text-emerald-700" x-text="icon || 'None'"></span>
                    </div>
                </div>

                <!-- Hidden Input Bound to Form -->
                <input type="hidden" name="icon" x-model="icon">

                <!-- Preset Icon Grid -->
                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 gap-2.5">
                    @foreach($presetIcons as $key => $preset)
                        <button type="button" 
                                @click="icon = '{{ $key }}'"
                                :class="icon === '{{ $key }}' ? 'border-sky-600 bg-sky-50 ring-2 ring-sky-500/20 text-sky-800 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700'"
                                class="p-3 border rounded-xl flex flex-col items-center justify-center gap-2 transition-all cursor-pointer group text-center">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-xs p-2 group-hover:scale-110 transition-transform"
                                 style="background: {{ $preset['style_gradient'] }};">
                                {!! $preset['svg'] !!}
                            </div>
                            <span class="text-[10px] font-bold truncate max-w-full capitalize">{{ $key }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Custom Icon Upload & Manual Input -->
                <div class="pt-3 border-t border-slate-200/80 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Upload Custom Icon (SVG / PNG)</label>
                        <input type="file" name="icon_file" accept=".svg,.png,.jpg,.jpeg,.webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Custom Key or Emoji</label>
                        <input type="text" x-model="icon" placeholder="e.g. drink, oil, or 🥤" class="w-full text-xs border-slate-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Category description..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Banner / Background Image (Optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Active (Visible in mega-menu and storefront)</span>
                </label>
            </div>
        </div>

        <!-- SEO Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                <span>🔍 Category SEO & Social Tags</span>
                <span class="text-xs text-primary font-normal">SERP Snippet</span>
            </h3>

            <!-- Google Snippet Preview -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 font-sans text-left space-y-1">
                <div class="text-xs text-gray-500 truncate flex items-center gap-1">
                    <span>{{ url('/') }}/category/</span><span class="font-semibold text-gray-700" x-text="slug || 'category-slug'"></span>
                </div>
                <h4 class="text-base text-blue-700 font-medium hover:underline truncate" x-text="metaTitle || name || 'Category Name — Wholesale Grocery Cartons'"></h4>
                <p class="text-xs text-gray-600 line-clamp-2" x-text="metaDesc || 'Shop wholesale bulk groceries and carton supplies with fast local delivery.'"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta Title</label>
                    <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title') }}" placeholder="Custom SEO title..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Focus Keyword</label>
                    <input type="text" name="focus_keyword" value="{{ old('focus_keyword') }}" placeholder="e.g. wholesale soft drinks puchong" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" x-model="metaDesc" rows="2" placeholder="Custom SEO snippet for search results..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('meta_description') }}</textarea>
            </div>
        </div>
    </form>
</div>
@endsection
