@extends('layouts.admin')

@section('title', 'Edit Legal Policy: ' . $policy->title)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.policies.index') }}" class="hover:text-primary">Policies</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $policy->title }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Edit Policy & Audit Versions</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('page.show', $policy->slug) }}" target="_blank" class="px-3.5 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Live
            </a>
            <button type="submit" form="policyForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save & New Version</button>
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

    <form id="policyForm" action="{{ route('admin.policies.update', $policy) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @method('PUT')

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-semibold text-gray-900">Document Editor</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Current: Version {{ $policy->current_version }}.0</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Document Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $policy->title) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $policy->slug) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Policy Content *</label>
                    <textarea name="content" rows="18" required class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('content', $policy->content) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Change Summary / Revision Note (for version history)</label>
                    <input type="text" name="change_summary" value="{{ old('change_summary') }}" placeholder="e.g. Updated Section 3 with 2026 refund guidelines" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <!-- Version History Audit Table (Phase 8) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                    <span>📜 Version History & 1-Click Rollback</span>
                    <span class="text-xs text-gray-400">{{ $policy->versions->count() }} revisions logged</span>
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 uppercase text-gray-500 font-semibold border-b">
                            <tr>
                                <th class="px-4 py-2">Version</th>
                                <th class="px-4 py-2">Summary Note</th>
                                <th class="px-4 py-2">Author</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($policy->versions->sortByDesc('version_number') as $ver)
                                <tr class="hover:bg-gray-50/75 {{ $ver->version_number == $policy->current_version ? 'bg-blue-50/40 font-semibold' : '' }}">
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded font-bold {{ $ver->version_number == $policy->current_version ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                            v{{ $ver->version_number }}.0
                                        </span>
                                        @if($ver->version_number == $policy->current_version)
                                            <span class="text-[10px] text-blue-600 ml-1">(Active)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $ver->change_summary }}</td>
                                    <td class="px-4 py-3">{{ $ver->author ? $ver->author->name : 'System' }}</td>
                                    <td class="px-4 py-3 text-gray-400">{{ $ver->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        @if($ver->version_number != $policy->current_version)
                                            <form action="{{ route('admin.policies.restore', [$policy, $ver->version_number]) }}" method="POST" class="inline" onsubmit="return confirm('Restore this policy page to version {{ $ver->version_number }}.0?');">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-primary bg-primary/10 hover:bg-primary/20 rounded transition">
                                                    Restore
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-400">No previous versions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Dynamic Placeholders Helper Box (Phase 8) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Dynamic Placeholder Tags</h3>
                <p class="text-xs text-gray-500">Insert placeholders anywhere; they get substituted automatically when displayed:</p>

                <div class="space-y-1.5 text-xs font-mono">
                    <div class="p-1.5 bg-gray-50 rounded border flex justify-between">
                        <span class="text-primary font-bold">{store_name}</span>
                        <span class="text-gray-400">Store Name</span>
                    </div>
                    <div class="p-1.5 bg-gray-50 rounded border flex justify-between">
                        <span class="text-primary font-bold">{admin_email}</span>
                        <span class="text-gray-400">Email</span>
                    </div>
                    <div class="p-1.5 bg-gray-50 rounded border flex justify-between">
                        <span class="text-primary font-bold">{admin_phone}</span>
                        <span class="text-gray-400">Phone</span>
                    </div>
                    <div class="p-1.5 bg-gray-50 rounded border flex justify-between">
                        <span class="text-primary font-bold">{free_delivery_radius}</span>
                        <span class="text-gray-400">Free KM</span>
                    </div>
                    <div class="p-1.5 bg-gray-50 rounded border flex justify-between">
                        <span class="text-primary font-bold">{paid_delivery_charge}</span>
                        <span class="text-gray-400">Paid Fee</span>
                    </div>
                    <div class="p-1.5 bg-gray-50 rounded border flex justify-between">
                        <span class="text-primary font-bold">{advance_percentage}</span>
                        <span class="text-gray-400">Advance %</span>
                    </div>
                </div>
            </div>

            <!-- Settings & Visibility -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Placement</h3>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $policy->is_published) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Published (Publicly Viewable)</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer', $policy->show_in_footer) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Display in Footer Legal Links</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="show_at_checkout" value="1" {{ old('show_at_checkout', $policy->show_at_checkout) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Display on Checkout Page</span>
                </label>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $policy->sort_order) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
