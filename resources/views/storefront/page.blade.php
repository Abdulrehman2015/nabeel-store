@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

    {{-- Universal Hero Section --}}
    <x-storefront-hero 
        :title="$page->title"
        :description="'Official store policies, transparent compliance documents, and customer service guidelines for store purchases.'"
        badge="Legal & Compliance"
        :subBadge="'Version ' . $page->current_version . '.0'"
        :breadcrumbs="[$page->title => null]"
    >
        <x-slot:icon>
            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </x-slot:icon>
    </x-storefront-hero>

    {{-- Page Body Card --}}
    <div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex items-center justify-between text-xs text-slate-400 pb-3 border-b border-slate-100">
            <span>Official Policy Document</span>
            <span>Last Updated: {{ $page->updated_at->format('F d, Y') }}</span>
        </div>

        {{-- Render parsed content with replaced dynamic placeholders --}}
        <div class="prose prose-sm sm:prose-base max-w-none text-slate-700 leading-relaxed space-y-4 pt-2">
            {!! $page->parsed_content !!}
        </div>
    </div>

</div>
@endsection
