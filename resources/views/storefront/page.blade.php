@extends('layouts.storefront')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    {{-- Breadcrumbs --}}
    <nav class="flex text-xs text-slate-500 font-medium">
        <ol class="flex items-center gap-2">
            <li><a href="{{ url('/') }}" class="hover:text-sky-600">Home</a></li>
            <li>/</li>
            <li class="text-slate-900 font-bold" aria-current="page">{{ $page->title }}</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between text-xs text-slate-400">
            <span>Version {{ $page->current_version }}.0</span>
            <span>Last Updated: {{ $page->updated_at->format('F d, Y') }}</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
            {{ $page->title }}
        </h1>
        <hr class="border-slate-100 my-4">

        {{-- Render parsed content with replaced dynamic placeholders --}}
        <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed space-y-4">
            {!! $page->parsed_content !!}
        </div>
    </div>

</div>
@endsection

