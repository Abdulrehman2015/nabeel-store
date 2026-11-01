@extends('layouts.admin')

@section('title', 'Edit Category: ' . $category->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    name: '{{ old('name', addslashes($category->name)) }}',
    metaTitle: '{{ old('meta_title', addslashes($category->meta_title ?? '')) }}',
    metaDesc: '{{ old('meta_description', addslashes($category->meta_description ?? '')) }}',
    slug: '{{ old('slug', $category->slug) }}'
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.categories.index') }}" class="hover:text-primary">Categories</a>
                <span>/</span>
                <span class="text-gray-900 font-medium truncate max-w-xs">{{ $category->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Edit Bulk Category</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('category.show', $category->slug) }}" target="_blank" class="px-3.5 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View
            </a>
            <button type="submit" form="categoryForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Update Category</button>
        </div>
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

    <form id="categoryForm" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Category Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Category Name *</label>
                    <input type="text" name="name" x-model="name" required value="{{ old('name', $category->name) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" x-model="slug" value="{{ old('slug', $category->slug) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Parent Category</label>
                    <select name="parent_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($parentCategories as $pcat)
                            <option value="{{ $pcat->id }}" {{ old('parent_id', $category->parent_id) == $pcat->id ? 'selected' : '' }}>{{ $pcat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Icon (Emoji or SVG)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Category Image</label>
                @if($category->image)
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                        <span class="text-xs text-gray-500">Current image. Upload new file to replace.</span>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
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
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-semibold text-gray-700 uppercase">Meta Title</label>
                        <span class="text-[10px] text-gray-400" :class="metaTitle.length > 60 ? 'text-red-500 font-bold' : ''" x-text="metaTitle.length + '/60 chars'"></span>
                    </div>
                    <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title', $category->meta_title) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Focus Keyword</label>
                    <input type="text" name="focus_keyword" value="{{ old('focus_keyword', $category->focus_keyword) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-xs font-semibold text-gray-700 uppercase">Meta Description</label>
                    <span class="text-[10px] text-gray-400" :class="metaDesc.length > 160 ? 'text-red-500 font-bold' : ''" x-text="metaDesc.length + '/160 chars'"></span>
                </div>
                <textarea name="meta_description" x-model="metaDesc" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('meta_description', $category->meta_description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $category->meta_keywords) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Robots Tag</label>
                    <select name="robots" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="index, follow" {{ old('robots', $category->robots) === 'index, follow' ? 'selected' : '' }}>index, follow (Default)</option>
                        <option value="noindex, follow" {{ old('robots', $category->robots) === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
