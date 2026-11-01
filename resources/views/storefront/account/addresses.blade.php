@extends('layouts.storefront')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ newAddressOpen: false }">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Account Portal</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Saved Delivery Addresses</h1>
        </div>
        <button 
            type="button" 
            @click="newAddressOpen = !newAddressOpen" 
            class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition"
        >
            + Add New Address
        </button>
    </div>

    {{-- Add New Address Form Modal/Panel --}}
    <div x-show="newAddressOpen" x-collapse class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-base font-bold text-slate-900">Add New Delivery Location</h3>

        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Label *</label>
                    <input type="text" name="label" placeholder="e.g. Restaurant HQ, Warehouse" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Recipient Name *</label>
                    <input type="text" name="recipient_name" value="{{ auth()->user()->name }}" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Recipient Phone *</label>
                    <input type="text" name="recipient_phone" value="{{ auth()->user()->phone }}" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Street Address *</label>
                    <input type="text" name="address_line" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City / Area *</label>
                    <input type="text" name="area_city" required placeholder="e.g. Puchong" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Floor Level * (0 = Ground)</label>
                    <input type="number" name="floor" value="0" min="0" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Landmark (Optional)</label>
                    <input type="text" name="landmark" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="newAddressOpen = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-700">Save Address</button>
            </div>
        </form>
    </div>

    {{-- Address Cards --}}
    @if($addresses->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($addresses as $addr)
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="font-bold text-sm text-slate-900">{{ $addr->label }}</span>
                            <span class="text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full font-semibold">Floor {{ $addr->floor }}</span>
                        </div>
                        <div class="text-xs text-slate-600 mt-3 space-y-1">
                            <strong class="text-slate-900 block">{{ $addr->recipient_name }} ({{ $addr->recipient_phone }})</strong>
                            <p>{{ $addr->address_line }}, {{ $addr->area_city }}</p>
                            @if($addr->landmark)
                                <p class="text-slate-400">Landmark: {{ $addr->landmark }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <form action="{{ route('account.addresses.delete', $addr->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this address?')" class="text-xs text-rose-500 hover:underline">
                                Delete Address
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 max-w-md mx-auto shadow-sm">
            <div class="text-3xl mb-2">📍</div>
            <h3 class="text-base font-bold text-slate-900">No saved addresses yet</h3>
            <p class="text-xs text-slate-500 mt-1">Add your delivery location to make future bulk ordering faster.</p>
        </div>
    @endif

</div>
@endsection
