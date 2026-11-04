@extends('layouts.admin')

@section('title', 'Registered Customers')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Store Management</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Customers</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Customer Directory</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage registered shopper accounts, account statuses, contact records and order activity</p>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-bold text-slate-700 shadow-2xs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $stats['total'] ?? $customers->total() }} Registered Shoppers</span>
            </span>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Customers --}}
        <a href="{{ route('admin.customers.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:border-sky-400 hover:shadow-md transition group {{ !request('status') ? 'ring-2 ring-sky-500/20 border-sky-400' : '' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Customers</span>
                <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($stats['total'] ?? 0) }}</div>
            <span class="text-[10px] text-slate-400 mt-1 block">All registered user accounts</span>
        </a>

        {{-- Active Accounts --}}
        <a href="{{ route('admin.customers.index', ['status' => 'active']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition group {{ request('status') === 'active' ? 'ring-2 ring-emerald-500/20 border-emerald-400' : '' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Active Shoppers</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-emerald-700">{{ number_format($stats['active'] ?? 0) }}</div>
            <span class="text-[10px] text-emerald-600/80 mt-1 block">Can log in and place orders</span>
        </a>

        {{-- Suspended Accounts --}}
        <a href="{{ route('admin.customers.index', ['status' => 'suspended']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:border-rose-400 hover:shadow-md transition group {{ request('status') === 'suspended' ? 'ring-2 ring-rose-500/20 border-rose-400' : '' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Locked / Suspended</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m12-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-rose-700">{{ number_format($stats['suspended'] ?? 0) }}</div>
            <span class="text-[10px] text-rose-600/80 mt-1 block">Account locked & login disabled</span>
        </a>

        {{-- With Orders --}}
        <a href="{{ route('admin.customers.index', ['status' => 'with_orders']) }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:border-violet-400 hover:shadow-md transition group {{ request('status') === 'with_orders' ? 'ring-2 ring-violet-500/20 border-violet-400' : '' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-violet-700 uppercase tracking-wider">Repeat Buyers</span>
                <span class="w-8 h-8 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-violet-700">{{ number_format($stats['with_orders'] ?? 0) }}</div>
            <span class="text-[10px] text-violet-600/80 mt-1 block">Placed 1 or more orders</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-3.5 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        {{-- Quick Filter Tabs --}}
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <a href="{{ route('admin.customers.index', request()->only('search')) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('status') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                All ({{ $stats['total'] ?? 0 }})
            </a>
            <a href="{{ route('admin.customers.index', array_merge(request()->only('search'), ['status' => 'active'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('status') === 'active' ? 'bg-emerald-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Active ({{ $stats['active'] ?? 0 }})
            </a>
            <a href="{{ route('admin.customers.index', array_merge(request()->only('search'), ['status' => 'suspended'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('status') === 'suspended' ? 'bg-rose-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Suspended ({{ $stats['suspended'] ?? 0 }})
            </a>
            <a href="{{ route('admin.customers.index', array_merge(request()->only('search'), ['status' => 'with_orders'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('status') === 'with_orders' ? 'bg-violet-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                With Orders ({{ $stats['with_orders'] ?? 0 }})
            </a>
        </div>

        {{-- Search Input --}}
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2 flex-1 md:max-w-md">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer by name, email, or phone..." class="w-full text-xs font-medium pl-9 pr-8 py-2 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                @if(request('search'))
                    <a href="{{ route('admin.customers.index', request()->only('status')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">&times;</a>
                @endif
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-2xs transition cursor-pointer">
                Search
            </button>
        </form>
    </div>

    <!-- Customers Table Card -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-bold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-6 py-3.5">Customer Profile</th>
                        <th class="px-6 py-3.5">Phone Contact</th>
                        <th class="px-6 py-3.5">Order History</th>
                        <th class="px-6 py-3.5">Member Since</th>
                        <th class="px-6 py-3.5">Account Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $avatarGradients = [
                            'from-sky-500 to-indigo-600',
                            'from-emerald-500 to-teal-600',
                            'from-amber-500 to-orange-600',
                            'from-violet-500 to-purple-600',
                            'from-rose-500 to-pink-600',
                        ];
                    @endphp

                    @forelse($customers as $c)
                        @php
                            $initials = collect(explode(' ', trim($c->name)))
                                ->filter()
                                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                ->take(2)
                                ->join('') ?: 'CU';
                            $gradient = $avatarGradients[$c->id % count($avatarGradients)];
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition group">
                            {{-- Customer Profile with Avatar --}}
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr {{ $gradient }} text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs uppercase tracking-wider group-hover:scale-105 transition-transform">
                                    {{ $initials }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.customers.show', $c) }}" class="font-bold text-slate-900 group-hover:text-sky-600 transition block text-sm">
                                        {{ $c->name }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-mono mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="truncate">{{ $c->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Phone Contact --}}
                            <td class="px-6 py-4 text-xs font-mono">
                                @if($c->phone)
                                    <a href="tel:{{ $c->phone }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200/80 text-slate-700 font-bold transition">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span>{{ $c->phone }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">— Not provided —</span>
                                @endif
                            </td>

                            {{-- Order History --}}
                            <td class="px-6 py-4 text-xs">
                                @if($c->orders_count > 0)
                                    <a href="{{ route('admin.customers.show', $c) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 hover:bg-violet-100 font-bold border border-violet-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span>{{ $c->orders_count }} {{ Str::plural('order', $c->orders_count) }}</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-medium text-[11px]">
                                        <span>0 orders</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Member Since --}}
                            <td class="px-6 py-4 text-xs text-slate-700">
                                <span class="font-bold block text-slate-800">{{ $c->created_at->format('M d, Y') }}</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $c->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Account Status --}}
                            <td class="px-6 py-4">
                                @if($c->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Suspended
                                    </span>
                                @endif
                            </td>

                            {{-- Action Buttons --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- View Profile --}}
                                    <a href="{{ route('admin.customers.show', $c) }}" class="px-3 py-1 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:text-sky-600 hover:border-sky-300 transition shadow-2xs">
                                        Profile
                                    </a>

                                    {{-- Unlock / Suspend --}}
                                    <form action="{{ route('admin.customers.toggle-status', $c) }}" method="POST" class="inline" onsubmit="return confirm('{{ $c->is_active ? 'Suspend customer account for ' . addslashes($c->name) . '?' : 'Unlock customer ' . addslashes($c->name) . '? A new login password will be generated and emailed to them immediately.' }}');">
                                        @csrf
                                        @if($c->is_active)
                                            <button type="submit" class="px-2.5 py-1 bg-white hover:bg-amber-50 border border-slate-300 hover:border-amber-400 rounded-lg text-xs font-bold text-amber-700 transition cursor-pointer shadow-2xs">
                                                Suspend
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-xs transition cursor-pointer flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                <span>Unlock</span>
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.customers.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete customer {{ addslashes($c->name) }}? All customer addresses will be removed. Past orders will be retained for bookkeeping.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Delete customer record">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span class="font-bold text-slate-600 block">No customers matching your search</span>
                                <span class="text-xs text-slate-400 mt-1 block">Try adjusting your filters or search keywords</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
