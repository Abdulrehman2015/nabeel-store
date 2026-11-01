@extends('layouts.admin')

@section('title', 'Add Legal Policy Page')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.policies.index') }}" class="hover:text-primary">Policies</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Create New</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Add Legal Policy Document</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.policies.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
            <button type="submit" form="policyForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Policy</button>
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

    <form id="policyForm" action="{{ route('admin.policies.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Document Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Terms of Service, Privacy Policy" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="auto-generated-from-title" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-semibold text-gray-700 uppercase">Policy Body (HTML or Plain Text) *</label>
                    </div>
                    <textarea name="content" rows="18" required class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('content') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Change Summary / Audit Log Note</label>
                    <input type="text" name="change_summary" value="{{ old('change_summary') }}" placeholder="e.g. Initial creation of bulk return terms" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Dynamic Placeholders Helper Box (Phase 8) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Dynamic Placeholder Tags</h3>
                <p class="text-xs text-gray-500">Insert any placeholder in your content; they get replaced with live store settings dynamically!</p>

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
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Published (Publicly Viewable)</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer', true) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Display in Footer Legal Links</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="show_at_checkout" value="1" {{ old('show_at_checkout') ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-medium text-gray-700">Display on Checkout Page</span>
                </label>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
