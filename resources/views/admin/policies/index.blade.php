@extends('layouts.admin')

@section('title', 'Policy & Legal Pages')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Legal & Policy Pages</h1>
            <p class="text-xs text-gray-500 mt-1">Manage e-commerce compliance documents, terms, return policies and audit versions.</p>
        </div>
        <a href="{{ route('admin.policies.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Legal Page
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Page Document</th>
                        <th class="px-6 py-3">Slug / Route</th>
                        <th class="px-6 py-3">Current Version</th>
                        <th class="px-6 py-3">Placements</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($policies as $p)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block">{{ $p->title }}</span>
                                <span class="text-xs text-gray-400">Updated {{ $p->updated_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                /page/{{ $p->slug }}
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-blue-700">
                                <span class="px-2 py-0.5 bg-blue-50 border border-blue-200 rounded">v{{ $p->current_version }}.0</span>
                            </td>
                            <td class="px-6 py-4 text-xs space-y-0.5">
                                @if($p->show_in_footer)
                                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold bg-gray-100 text-gray-700 rounded mr-1">Footer</span>
                                @endif
                                @if($p->show_at_checkout)
                                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-800 rounded">Checkout</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($p->is_published)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Published</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('page.show', $p->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-700 font-semibold text-xs">View</a>
                                <a href="{{ route('admin.policies.edit', $p) }}" class="text-primary hover:text-primary-hover font-semibold text-xs">Edit / History</a>
                                <form action="{{ route('admin.policies.destroy', $p) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this policy?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">No policy pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
