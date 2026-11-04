@extends('layouts.admin')

@section('title', 'Add Hero Banner')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.banners.index') }}" class="hover:text-primary">Banners</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Create New</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Add Hero Banner</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.banners.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
            <button type="submit" form="bannerForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Banner</button>
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

    <form id="bannerForm" action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Banner Title *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Fresh Groceries & Essentials" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Promo Badge</label>
                <input type="text" name="badge" value="{{ old('badge') }}" placeholder="e.g. Save Up To 35%" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Subtitle / Description</label>
            <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="Order full cartons at everyday prices delivered directly to your store or home." class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Target Link URL</label>
                <input type="text" name="link" value="{{ old('link') }}" placeholder="e.g. /category/beverages" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Call to Action Button Text</label>
                <input type="text" name="button_text" value="{{ old('button_text', 'Shop Now') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Desktop Image (1920x600px) *</label>
                <input type="file" name="desktop_image" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Mobile Image (800x800px or vertical)</label>
                <input type="file" name="mobile_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>
            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>
        </div>
    </form>
</div>
@endsection

