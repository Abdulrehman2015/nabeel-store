@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-xs text-gray-500 mt-1">Manage bulk grocery category taxonomy, mega-menu order and SEO meta.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Category
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Parent</th>
                        <th class="px-6 py-3">Sort Order</th>
                        <th class="px-6 py-3">Products</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-lg">
                                        {{ $category->icon ?: '📦' }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-semibold text-gray-900 block">{{ $category->name }}</span>
                                    <span class="text-xs text-gray-400 font-mono">/category/{{ $category->slug }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-700">
                                {{ $category->parent ? $category->parent->name : '— Root —' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-600">
                                {{ $category->sort_order }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                                    {{ $category->products_count }} products
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600">Disabled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-primary hover:text-primary-hover font-semibold text-xs">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

