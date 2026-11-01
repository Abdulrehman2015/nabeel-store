@extends('layouts.admin')

@section('title', 'Hero Banners & Sliders')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hero Banners & Sliders</h1>
            <p class="text-xs text-gray-500 mt-1">Manage promotional banners for the storefront homepage carousel with separate desktop and mobile visuals.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Banner
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Banner</th>
                        <th class="px-6 py-3">Badge & Button</th>
                        <th class="px-6 py-3">Link</th>
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($banners as $b)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                @if($b->desktop_image)
                                    <img src="{{ asset('storage/' . $b->desktop_image) }}" class="w-24 h-12 object-cover rounded border">
                                @endif
                                <div>
                                    <span class="font-semibold text-gray-900 block">{{ $b->title }}</span>
                                    <span class="text-xs text-gray-400">{{ Str::limit($b->subtitle, 40) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($b->badge)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase mr-1">{{ $b->badge }}</span>
                                @endif
                                <span class="text-gray-600">{{ $b->button_text ?? 'Shop Now' }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 font-mono">
                                {{ $b->link ?? '/' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                {{ $b->sort_order }}
                            </td>
                            <td class="px-6 py-4">
                                @if($b->is_active)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600">Hidden</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.banners.edit', $b) }}" class="text-primary hover:text-primary-hover font-semibold text-xs">Edit</a>
                                <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">No banners found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
