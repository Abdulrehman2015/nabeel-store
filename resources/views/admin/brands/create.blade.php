@extends('layouts.admin')

@section('title', 'Add Brand')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.brands.index') }}" class="hover:text-primary">Brands</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Create New</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Add Brand</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
            <button type="submit" form="brandForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Brand</button>
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

    <form id="brandForm" action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Brand Name *</label>
            <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Coca-Cola, Nestlé, Unilever" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Slug (Optional)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Leave blank to auto-generate" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Brand Logo</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                <span class="text-sm font-medium text-gray-700">Active</span>
            </label>
        </div>
    </form>
</div>
@endsection

