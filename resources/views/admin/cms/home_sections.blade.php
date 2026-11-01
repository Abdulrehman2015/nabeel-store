@extends('layouts.admin')

@section('title', 'Manage Homepage Sections')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>CMS</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Homepage Sections</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Homepage Sections & Block Ordering</h1>
        </div>
        <button type="submit" form="sectionsForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Changes</button>
    </div>

    <div class="p-4 bg-blue-50/70 border border-blue-200 rounded-xl text-xs text-blue-900">
        Customize the title, enable or disable sections, and set display order (lower numbers appear first) on the customer storefront homepage.
    </div>

    <form id="sectionsForm" action="{{ route('admin.home-sections.reorder') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @csrf

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Section Identifier</th>
                        <th class="px-6 py-3">Display Title</th>
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3 text-center">Visible</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sections as $idx => $s)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4">
                                <input type="hidden" name="sections[{{ $idx }}][id]" value="{{ $s->id }}">
                                <span class="font-mono text-xs font-bold text-gray-800 uppercase px-2 py-1 bg-gray-100 rounded">
                                    {{ $s->section_key }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" name="sections[{{ $idx }}][title]" value="{{ $s->title }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="sections[{{ $idx }}][sort_order]" value="{{ $s->sort_order }}" required class="w-20 text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="sections[{{ $idx }}][is_active]" value="1" {{ $s->is_active ? 'checked' : '' }} class="rounded text-primary focus:ring-primary w-4 h-4">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection

