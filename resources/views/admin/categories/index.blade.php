@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="space-y-6" x-data="{ deleteAllModal: false, confirmInput: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Catalog</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Categories</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mart Categories</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage store aisles, mega-menu display order and category SEO meta</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('category.index') }}" target="_blank" class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-sky-400 hover:text-sky-600 transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Storefront View</span>
            </a>
            <button 
                type="button" 
                @click="deleteAllModal = true; confirmInput = ''" 
                class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl text-xs font-bold text-rose-700 shadow-2xs hover:border-rose-400 transition flex items-center gap-1.5 cursor-pointer"
                title="Delete all categories from store database"
            >
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Delete All</span>
            </button>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 flex items-center gap-2 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-bold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-6 py-3.5">Category & Store Hyperlink</th>
                        <th class="px-6 py-3.5">Parent Department</th>
                        <th class="px-6 py-3.5">Sort Order</th>
                        <th class="px-6 py-3.5">Products Count</th>
                        <th class="px-6 py-3.5">Visibility</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/60 transition group">
                            {{-- Category with Icon & Clickable Hyperlink --}}
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl overflow-hidden flex items-center justify-center p-1 shrink-0 border border-slate-200 bg-white shadow-2xs group-hover:border-sky-300 transition">
                                    {!! $category->renderIcon('w-full h-full object-cover rounded-lg') !!}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="font-bold text-slate-900 group-hover:text-sky-600 transition block text-sm">
                                        {{ $category->name }}
                                    </a>
                                    <a href="{{ route('category.show', $category->slug) }}" target="_blank" class="text-[11px] text-sky-600 hover:text-sky-800 hover:underline font-mono inline-flex items-center gap-1 mt-0.5" title="Open storefront category page">
                                        <span>/category/{{ $category->slug }}</span>
                                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            </td>

                            {{-- Parent Category --}}
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                                @if($category->parent)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                        {{ $category->parent->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px] font-medium">— Root Aisle —</span>
                                @endif
                            </td>

                            {{-- Sort Order --}}
                            <td class="px-6 py-4 text-xs font-mono font-bold text-slate-700">
                                <span class="px-2 py-1 rounded-md bg-slate-100 border border-slate-200/60">
                                    #{{ $category->sort_order }}
                                </span>
                            </td>

                            {{-- Products Count with Link --}}
                            <td class="px-6 py-4 text-xs">
                                <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 hover:bg-sky-100 font-bold border border-sky-200 transition">
                                    <span>{{ $category->products_count }} products</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Hidden
                                    </span>
                                @endif
                            </td>

                            {{-- Action Buttons --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('category.show', $category->slug) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition" title="View live page">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:text-sky-600 hover:border-sky-300 transition shadow-2xs">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete category \'{{ $category->name }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-300 rounded-lg text-xs font-bold text-rose-600 transition cursor-pointer shadow-2xs">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>No categories found in store database.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    {{-- Delete All Categories Confirmation Modal --}}
    <div 
        x-show="deleteAllModal" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
    >
        <div 
            @click.away="deleteAllModal = false"
            x-show="deleteAllModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-rose-200 relative"
        >
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            
            <h3 class="text-lg font-black text-slate-900 text-center mb-1">Delete All Categories?</h3>
            <p class="text-xs text-slate-500 text-center leading-relaxed mb-4">
                This will permanently delete <strong class="text-rose-600">ALL {{ number_format($categories->total()) }} categories</strong>. Any products tied directly to these categories will also be removed. This cannot be undone.
            </p>

            <form method="POST" action="{{ route('admin.categories.delete-all') }}" class="space-y-4">
                @csrf
                @method('DELETE')

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Type <span class="text-rose-600 font-mono">DELETE</span> to confirm:
                    </label>
                    <input 
                        type="text" 
                        x-model="confirmInput" 
                        placeholder="Type DELETE" 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500"
                        required
                    >
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button 
                        type="button" 
                        @click="deleteAllModal = false" 
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        :disabled="confirmInput.trim().toUpperCase() !== 'DELETE'"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-500 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/30 transition cursor-pointer"
                    >
                        Permanently Delete All
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
