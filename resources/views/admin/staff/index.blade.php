@extends('layouts.admin')

@section('title', 'Staff & Cashier Management')

@section('content')
<div class="space-y-6">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-200/90 p-5 rounded-2xl shadow-2xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Staff & Cashier Accounts</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $staffMembers->count() }} Members
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Manage POS cashiers, counter operators, and system administrators with role-based permissions.
            </p>
        </div>

        <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold transition shadow-sm shadow-sky-600/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Staff / Cashier</span>
        </a>
    </div>

    {{-- Role Summary KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Admins --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-xs transition flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-200/80 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Administrators</div>
                    <div class="text-xl font-black text-slate-900">{{ $staffMembers->where('role', 'admin')->count() }}</div>
                </div>
            </div>
            <span class="text-[10px] text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200 font-bold">Full Access</span>
        </div>

        {{-- Cashiers --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-xs transition flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/80 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Cashiers / Counter</div>
                    <div class="text-xl font-black text-slate-900">{{ $staffMembers->whereIn('role', ['cashier', 'staff'])->count() }}</div>
                </div>
            </div>
            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 font-bold">POS Only</span>
        </div>

        {{-- Active Shift Staff --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-xs transition flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 border border-sky-200/80 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active Staff</div>
                    <div class="text-xl font-black text-slate-900">{{ $staffMembers->where('is_active', true)->count() }}</div>
                </div>
            </div>
            <span class="text-[10px] text-sky-700 bg-sky-50 px-2 py-0.5 rounded-full border border-sky-200 font-bold">Enabled</span>
        </div>
    </div>

    {{-- Staff Table Card --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3.5 px-4 font-bold">Staff Member</th>
                        <th class="py-3.5 px-4 font-bold">Role & Access</th>
                        <th class="py-3.5 px-4 text-right font-bold">Today's Sales (Shift)</th>
                        <th class="py-3.5 px-4 text-right font-bold">All-Time Sales</th>
                        <th class="py-3.5 px-4 text-center font-bold">Status</th>
                        <th class="py-3.5 px-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($staffMembers as $staff)
                        <tr class="hover:bg-slate-50/70 transition">
                            {{-- User Info --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 {{ $staff->isAdmin() ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 flex items-center gap-2">
                                            <span>{{ $staff->name }}</span>
                                            @if($staff->id === auth()->id())
                                                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500">{{ $staff->email }}</div>
                                        @if($staff->phone)
                                            <div class="text-[10px] text-slate-400">{{ $staff->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="py-3.5 px-4">
                                @if($staff->isAdmin())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Administrator
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">Full Store Financials & Settings</span>
                                @elseif($staff->role === 'cashier')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Cashier / Register
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">POS & Add Products (No Financials)</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Staff Member
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">POS & Catalog Management</span>
                                @endif
                            </td>

                            {{-- Today's Sales --}}
                            <td class="py-3.5 px-4 text-right">
                                <div class="font-extrabold text-slate-900 text-sm">
                                    RM {{ number_format($staff->today_sales_sum ?? 0, 2) }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $staff->today_orders_count ?? 0 }} sales today
                                </div>
                            </td>

                            {{-- All-Time Sales --}}
                            <td class="py-3.5 px-4 text-right">
                                <div class="font-bold text-slate-700">
                                    RM {{ number_format($staff->all_time_sales_sum ?? 0, 2) }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $staff->all_time_orders_count ?? 0 }} total orders
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="py-3.5 px-4 text-center">
                                @if($staff->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Disabled
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.staff.edit', $staff) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-sky-600 hover:bg-slate-100 transition" title="Edit Staff & Permissions">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    @if($staff->id !== auth()->id() && $staff->id !== 1)
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.staff.toggle-status', $staff) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition" title="{{ $staff->is_active ? 'Deactivate Account' : 'Activate Account' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete staff member {{ $staff->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Staff Account">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No staff members configured yet. Click "Add New Staff / Cashier" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
