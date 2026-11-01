@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{
    title: '{{ old('name', addslashes($product->name)) }}',
    metaTitle: '{{ old('meta_title', addslashes($product->meta_title ?? '')) }}',
    metaDesc: '{{ old('meta_description', addslashes($product->meta_description ?? '')) }}',
    slug: '{{ old('slug', $product->slug) }}',
    price: '{{ old('price', $product->price) }}',
    salePrice: '{{ old('sale_price', $product->sale_price ?? '') }}'
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.products.index') }}" class="hover:text-primary">Products</a>
                <span>/</span>
                <span class="text-gray-900 font-medium truncate max-w-xs">{{ $product->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Edit Wholesale Product</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="px-3.5 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View on Store
            </a>
            <button type="submit" form="productForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow transition">Update Product</button>
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

    <form id="productForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Basic Information</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Product Name *</label>
                        <input type="text" name="name" x-model="title" required value="{{ old('name', $product->name) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">URL Slug</label>
                            <input type="text" name="slug" x-model="slug" value="{{ old('slug', $product->slug) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">SKU / Barcode *</label>
                            <input type="text" name="sku" required value="{{ old('sku', $product->sku) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Carton / Pack Size *</label>
                            <input type="text" name="pack_size" required value="{{ old('pack_size', $product->pack_size) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Unit Description</label>
                            <input type="text" name="unit_description" value="{{ old('unit_description', $product->unit_description) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Weight / Volume</label>
                            <input type="text" name="weight_volume" value="{{ old('weight_volume', $product->weight_volume) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Short Description</label>
                        <textarea name="short_description" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Detailed Description</label>
                        <textarea name="description" rows="5" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Media Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Product Media</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Featured Main Image</label>
                        @if($product->featured_image)
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                <div class="text-xs text-gray-500">Current featured image. Upload a new one to replace.</div>
                            </div>
                        @endif
                        <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Add More Gallery Images</label>
                        @if($product->images->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($product->images as $gal)
                                    <img src="{{ asset('storage/' . $gal->image_path) }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-xs">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>

                <!-- Phase 5 SEO Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <span>🔍 Search Engine Optimization (SEO)</span>
                        </h3>
                        <span class="text-xs text-primary font-semibold">Google Snippet Preview</span>
                    </div>

                    <!-- Google Snippet Preview Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 font-sans text-left space-y-1">
                        <div class="text-xs text-gray-500 truncate flex items-center gap-1">
                            <span>{{ url('/') }}/product/</span><span class="font-semibold text-gray-700" x-text="slug || 'product-slug'"></span>
                        </div>
                        <h4 class="text-base text-blue-700 font-medium hover:underline truncate" x-text="metaTitle || title || '{{ $product->name }}'"></h4>
                        <p class="text-xs text-gray-600 line-clamp-2" x-text="metaDesc || 'Buy bulk groceries and household goods at wholesale prices. Fast commercial delivery in carton packaging.'"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-xs font-semibold text-gray-700 uppercase">Meta Title</label>
                                <span class="text-[10px] text-gray-400" :class="metaTitle.length > 60 ? 'text-red-500 font-bold' : ''" x-text="metaTitle.length + '/60 chars'"></span>
                            </div>
                            <input type="text" name="meta_title" x-model="metaTitle" value="{{ old('meta_title', $product->meta_title) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Focus Keyword</label>
                            <input type="text" name="focus_keyword" value="{{ old('focus_keyword', $product->focus_keyword) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-xs font-semibold text-gray-700 uppercase">Meta Description</label>
                            <span class="text-[10px] text-gray-400" :class="metaDesc.length > 160 ? 'text-red-500 font-bold' : ''" x-text="metaDesc.length + '/160 chars'"></span>
                        </div>
                        <textarea name="meta_description" x-model="metaDesc" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Robots Tag</label>
                            <select name="robots" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="index, follow" {{ old('robots', $product->robots) === 'index, follow' ? 'selected' : '' }}>index, follow (Default)</option>
                                <option value="noindex, follow" {{ old('robots', $product->robots) === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                                <option value="index, nofollow" {{ old('robots', $product->robots) === 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                                <option value="noindex, nofollow" {{ old('robots', $product->robots) === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">OpenGraph Title</label>
                            <input type="text" name="og_title" value="{{ old('og_title', $product->og_title) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">OpenGraph Description</label>
                            <input type="text" name="og_description" value="{{ old('og_description', $product->og_description) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Col: Pricing, Inventory & Flags -->
            <div class="space-y-6">
                <!-- Pricing Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Pricing & Tax</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Wholesale Price ({{ setting('currency_symbol', 'RM') }}) *</label>
                        <input type="number" step="0.01" name="price" x-model="price" required value="{{ old('price', $product->price) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sale / Promo Price ({{ setting('currency_symbol', 'RM') }})</label>
                        <input type="number" step="0.01" name="sale_price" x-model="salePrice" value="{{ old('sale_price', $product->sale_price) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <p class="text-[11px] text-gray-500 mt-1">Leave empty if not on sale.</p>
                    </div>
                </div>

                <!-- Inventory Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Inventory</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Cartons in Stock *</label>
                        <input type="number" name="stock_quantity" required value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                </div>

                <!-- Organization Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Categorization</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Category *</label>
                        <select name="category_id" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Brand</label>
                        <select name="brand_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="">None / Generic</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Flags Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-3">
                    <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Status & Badges</h3>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700">Active (Visible in Store)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700">Featured Product</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700">New Arrival Badge</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_promo" value="1" {{ old('is_promo', $product->is_promo) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700">Special Promotion</span>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
