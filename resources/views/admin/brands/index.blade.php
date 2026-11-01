@extends('layouts.admin')

@section('title', 'Brands Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Brands</h1>
            <p class="text-xs text-gray-500 mt-1">Manage manufacturer brands and supplier labels for bulk catalog filtering.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Brand
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Brand</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Products</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" class="w-9 h-9 rounded-md object-contain border border-gray-200 p-1">
                                @else
                                    <div class="w-9 h-9 rounded-md bg-gray-100 flex items-center justify-center font-bold text-xs text-gray-400">
                                        {{ substr($brand->name, 0, 2) }}
                                    </div>
                                @endif
                                <span class="font-semibold text-gray-900">{{ $brand->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                {{ $brand->slug }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                                    {{ $brand->products_count }} products
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($brand->is_active)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600">Disabled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="text-primary hover:text-primary-hover font-semibold text-xs">Edit</a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this brand?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No brands found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brands->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
