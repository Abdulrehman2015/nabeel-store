@extends('layouts.admin')

@section('title', 'Brands Management')

@section('content')
<div class="space-y-6" x-data="{ deleteAllModal: false, confirmInput: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Brands</h1>
            <p class="text-xs text-gray-500 mt-1">Manage manufacturer brands and supplier labels for bulk catalog filtering.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button 
                type="button" 
                @click="deleteAllModal = true; confirmInput = ''" 
                class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg text-xs font-bold text-rose-700 shadow-xs hover:border-rose-400 transition flex items-center gap-1.5 cursor-pointer"
                title="Delete all brands from store database"
            >
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Delete All</span>
            </button>
            <a href="{{ route('admin.brands.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Brand
            </a>
        </div>
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

    {{-- Delete All Brands Confirmation Modal --}}
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
            
            <h3 class="text-lg font-black text-slate-900 text-center mb-1">Delete All Brands?</h3>
            <p class="text-xs text-slate-500 text-center leading-relaxed mb-4">
                This will permanently delete <strong class="text-rose-600">ALL {{ number_format($brands->total()) }} brands</strong>. Any products linked to these brands will remain, but their brand affiliation will be cleared. This action cannot be undone.
            </p>

            <form method="POST" action="{{ route('admin.brands.delete-all') }}" class="space-y-4">
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

