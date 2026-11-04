@extends('layouts.admin')

@section('title', 'Customer Profile: ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-sky-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Customers</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700 truncate max-w-xs">{{ $user->name }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    <span>{{ $user->is_active ? 'Active Customer' : 'Account Suspended' }}</span>
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Shopper ID #{{ $user->id }} &bull; Registered {{ $user->created_at->format('F d, Y') }} ({{ $user->created_at->diffForHumans() }})</p>
        </div>

        <div class="flex items-center gap-2.5">
            {{-- Toggle Suspend / Unlock --}}
            <form action="{{ route('admin.customers.toggle-status', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_active ? 'Suspend customer account for ' . addslashes($user->name) . '?' : 'Unlock customer ' . addslashes($user->name) . '? A new login password will be generated and emailed to them immediately.' }}');">
                @csrf
                @if($user->is_active)
                    <button type="submit" class="px-4 py-2 bg-white hover:bg-amber-50 border border-slate-300 hover:border-amber-400 text-amber-700 rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m12-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Suspend Account</span>
                    </button>
                @else
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        <span>Unlock & Email Password</span>
                    </button>
                @endif
            </form>

            {{-- Delete Customer --}}
            <form action="{{ route('admin.customers.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete customer {{ addslashes($user->name) }}? All customer addresses will be removed. Past orders will be retained for bookkeeping.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-rose-600 rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    @php
        $initials = collect(explode(' ', trim($user->name)))
            ->filter()
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->join('') ?: 'CU';
        $totalSpent = $user->orders->where('payment_status', 'verified')->sum('total_amount');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Col: Profile & Address Details -->
        <div class="space-y-6">
            
            <!-- Customer Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-md shadow-sky-500/20 uppercase tracking-wider">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base font-black text-slate-900 leading-tight truncate">{{ $user->name }}</h3>
                        <p class="text-xs text-slate-400 font-mono truncate mt-0.5">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Email Address</span>
                        <a href="mailto:{{ $user->email }}" class="font-bold text-sky-600 hover:underline flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="truncate">{{ $user->email }}</span>
                        </a>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Contact Phone</span>
                        @if($user->phone)
                            <a href="tel:{{ $user->phone }}" class="font-bold text-slate-800 hover:text-sky-600 flex items-center gap-1.5 font-mono">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $user->phone }}</span>
                            </a>
                        @else
                            <span class="text-slate-400 font-medium">Not provided</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Registration Date</span>
                        <span class="font-bold text-slate-800">{{ $user->created_at->format('M d, Y • h:i A') }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Account Status</span>
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active (Can Login & Place Orders)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Account Locked / Suspended
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Saved Delivery Addresses Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Saved Addresses ({{ $user->addresses->count() }})</h4>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($user->addresses as $addr)
                        <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/70 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-slate-900">{{ $addr->label ?? 'Home / Mart Dropoff' }}</span>
                                @if($addr->is_default)
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-sky-100 text-sky-800">DEFAULT</span>
                                @endif
                            </div>
                            <p class="text-slate-600 leading-relaxed">{{ $addr->full_address }}</p>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400 pt-1 border-t border-slate-200/40 font-medium">
                                <span>Floor: <strong>{{ $addr->floor_number ?? 'Ground' }}</strong></span>
                                <span>&bull;</span>
                                <span>Elevator: <strong>{{ $addr->has_lift ? 'Yes' : 'No Lift' }}</strong></span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 bg-slate-50 rounded-xl text-center text-slate-400 text-xs">
                            <span>No delivery addresses saved by customer.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Col: Lifetime Stats & Order History -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Lifetime Spending Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Orders</span>
                    <div class="text-2xl font-black text-slate-900">{{ $user->orders->count() }}</div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Lifetime store purchases</span>
                </div>

                <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block mb-1">Total Spent</span>
                    <div class="text-2xl font-black text-emerald-700">{{ format_currency($totalSpent) }}</div>
                    <span class="text-[10px] text-emerald-600/80 mt-1 block">Verified order payments</span>
                </div>

                <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Average Order Value</span>
                    <div class="text-2xl font-black text-slate-900">
                        {{ $user->orders->count() > 0 ? format_currency($totalSpent / max(1, $user->orders->where('payment_status', 'verified')->count())) : 'RM 0.00' }}
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Per verified purchase</span>
                </div>
            </div>

            <!-- Orders History Table -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Purchase History</h3>
                        <p class="text-[11px] text-slate-400">Complete log of orders placed by this customer</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                        {{ $user->orders->count() }} Orders
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50/80 uppercase text-[10px] text-slate-500 font-bold tracking-wider border-b border-slate-200/80">
                            <tr>
                                <th class="px-5 py-3">Order Number</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Items</th>
                                <th class="px-5 py-3">Total Amount</th>
                                <th class="px-5 py-3">Payment</th>
                                <th class="px-5 py-3">Fulfillment</th>
                                <th class="px-5 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($user->orders as $o)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-3.5 font-mono font-bold text-sky-600">
                                        <a href="{{ route('admin.orders.show', $o) }}" class="hover:underline">
                                            {{ $o->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-500">
                                        {{ $o->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 font-semibold text-slate-800">
                                        {{ $o->items->sum('quantity') }} items
                                    </td>
                                    <td class="px-5 py-3.5 font-black text-slate-900">
                                        {{ format_currency($o->total_amount) }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($o->payment_status === 'verified')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Verified
                                            </span>
                                        @elseif($o->payment_status === 'pending_verification')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Pending Review
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                                {{ ucfirst($o->payment_status ?? 'Unpaid') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 capitalize">
                                            {{ str_replace('_', ' ', $o->status ?? $o->order_status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('admin.orders.show', $o) }}" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:text-sky-600 transition shadow-2xs">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                        <span>No purchase history recorded for this customer yet.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
