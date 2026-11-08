@extends('layouts.admin')

@section('title', 'Daily Notifications & Alert Center')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="notificationsPage()">

    {{-- Top Header & Action Controls --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Daily Notifications</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base shadow-2xs">
                        🔔
                    </span>
                    <span>Daily Notifications & Alert Center</span>
                </h1>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Live Daily Feed</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200" title="Every notification is kept for 30 days and automatically pruned thereafter">
                    <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>30-Day Auto Retention</span>
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Review all incoming daily store notifications, payment verifications, and inventory threshold alerts. Records older than 30 days are automatically deleted.
            </p>
        </div>

        {{-- Header Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            @if($unreadCount > 0)
                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="scope" value="{{ $dateFilter === 'today' ? 'today' : 'all' }}">
                    <button type="submit" class="px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-300 shadow-2xs hover:border-slate-400 transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Mark {{ $dateFilter === 'today' ? "Today's" : "All" }} Read</span>
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.notifications.purge-old') }}" method="POST" class="inline" onsubmit="return confirm('Clean up notifications older than 30 days?');">
                @csrf
                <button type="submit" class="px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-300 shadow-2xs hover:border-slate-400 transition flex items-center gap-1.5 cursor-pointer" title="Manually purge notifications older than 30 days">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Prune >30 Days</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Daily Metrics Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 1. Today's Alerts --}}
        <a href="{{ route('admin.notifications.index', ['date' => 'today']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:border-sky-300 transition flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today's Alerts</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($todayCount) }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Recorded since midnight</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                📅
            </div>
        </a>

        {{-- 2. Unread Alerts --}}
        <a href="{{ route('admin.notifications.index', array_merge(request()->query(), ['status' => 'unread'])) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:border-amber-300 transition flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unread Alerts</p>
                <h3 class="text-2xl font-black {{ $unreadCount > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1">{{ number_format($unreadCount) }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Pending your review</p>
            </div>
            <div class="w-10 h-10 rounded-xl {{ $unreadCount > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                🔔
            </div>
        </a>

        {{-- 3. Today's Orders & Payments --}}
        <a href="{{ route('admin.notifications.index', ['date' => 'today', 'type' => 'order']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:border-emerald-300 transition flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Orders & Payments</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($ordersCount) }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Today's transactions</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                💳
            </div>
        </a>

        {{-- 4. Inventory Warnings --}}
        <a href="{{ route('admin.notifications.index', ['date' => 'today', 'type' => 'inventory']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:border-rose-300 transition flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Low Stock Warnings</p>
                <h3 class="text-2xl font-black {{ $lowStockCount > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">{{ number_format($lowStockCount) }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Below alert threshold</p>
            </div>
            <div class="w-10 h-10 rounded-xl {{ $lowStockCount > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                ⚠️
            </div>
        </a>
    </div>

    {{-- Interactive Filter & Search Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
        <form method="GET" action="{{ route('admin.notifications.index') }}" id="filterForm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                {{-- Date Pills --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Period:</span>
                    
                    <a href="{{ route('admin.notifications.index', array_merge(request()->except(['date', 'custom_date', 'page']), ['date' => 'today'])) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $dateFilter === 'today' ? 'bg-sky-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Today
                    </a>

                    <a href="{{ route('admin.notifications.index', array_merge(request()->except(['date', 'custom_date', 'page']), ['date' => 'yesterday'])) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $dateFilter === 'yesterday' ? 'bg-sky-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Yesterday
                    </a>

                    <a href="{{ route('admin.notifications.index', array_merge(request()->except(['date', 'custom_date', 'page']), ['date' => 'last_7_days'])) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $dateFilter === 'last_7_days' ? 'bg-sky-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Last 7 Days
                    </a>

                    <a href="{{ route('admin.notifications.index', array_merge(request()->except(['date', 'custom_date', 'page']), ['date' => 'all'])) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $dateFilter === 'all' ? 'bg-sky-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All (Up to 30 Days)
                    </a>
                </div>

                {{-- Custom Date Picker & Quick Actions --}}
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input 
                            type="date" 
                            name="custom_date" 
                            value="{{ $customDate }}" 
                            onchange="document.getElementById('dateInputHidden').value='custom'; document.getElementById('filterForm').submit();"
                            class="text-xs font-semibold py-1.5 px-3 border border-slate-300 rounded-xl bg-slate-50 text-slate-700 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20"
                            title="Pick a specific date to inspect"
                        >
                    </div>
                    <input type="hidden" name="date" id="dateInputHidden" value="{{ $dateFilter }}">
                </div>
            </div>

            {{-- Secondary Filters: Type, Status & Search Bar --}}
            <div class="pt-3 border-t border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Type Filter --}}
                    <select name="type" onchange="this.form.submit()" class="text-xs font-bold py-1.5 px-3 border border-slate-300 rounded-xl bg-slate-50 text-slate-700 focus:bg-white focus:border-sky-500">
                        <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>All Alert Types</option>
                        <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>🛒 Orders</option>
                        <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>💳 Payment Verifications</option>
                        <option value="inventory" {{ request('type') == 'inventory' ? 'selected' : '' }}>⚠️ Low Stock Warnings</option>
                        <option value="cashier" {{ request('type') == 'cashier' ? 'selected' : '' }}>🏪 Cashier & Shifts</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>⚙️ System Alerts</option>
                    </select>

                    {{-- Status Filter --}}
                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold py-1.5 px-3 border border-slate-300 rounded-xl bg-slate-50 text-slate-700 focus:bg-white focus:border-sky-500">
                        <option value="all" {{ !request('status') || request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read Only</option>
                    </select>

                    @if(request()->hasAny(['type', 'status', 'search', 'custom_date']) || $dateFilter !== 'today')
                        <a href="{{ route('admin.notifications.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Reset Filters</span>
                        </a>
                    @endif
                </div>

                {{-- Keyword Search --}}
                <div class="relative w-full md:w-72">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search alerts or order #..." 
                        class="w-full text-xs py-1.5 pl-8 pr-3 border border-slate-300 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-sky-500"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </form>
    </div>

    {{-- Notification Feed List --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Notifications Feed</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $notifications->total() }}</span>
            </div>
            <div class="text-[11px] text-slate-400 font-medium">
                Auto-cleaned after 30 days
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center text-2xl shadow-2xs">
                    🛡️
                </div>
                <h3 class="text-base font-bold text-slate-800">No Notifications Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    @if($dateFilter === 'today')
                        You are completely up to date! No unhandled alerts or orders recorded for today.
                    @else
                        No notifications match the selected period or filters.
                    @endif
                </p>
                @if(request()->hasAny(['type', 'status', 'search', 'custom_date']) || $dateFilter !== 'today')
                    <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 hover:underline pt-2">
                        <span>Show Today's Notifications</span>
                        &rarr;
                    </a>
                @endif
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($notifications as $notif)
                    @php
                        $levelBg = match($notif->level) {
                            'danger' => 'bg-rose-50 text-rose-700 border-rose-200',
                            'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default => 'bg-sky-50 text-sky-700 border-sky-200',
                        };

                        $iconEmoji = match($notif->type) {
                            'order' => '🛒',
                            'payment' => '💳',
                            'inventory' => ($notif->level === 'danger' ? '🚨' : '⚠️'),
                            'cashier' => '🏪',
                            default => '🔔',
                        };
                    @endphp

                    <div class="p-4 sm:p-5 hover:bg-slate-50/80 transition flex items-start gap-3.5 {{ !$notif->is_read ? 'bg-sky-50/20' : '' }}">
                        {{-- Unread Dot Indicator --}}
                        <div class="pt-1 shrink-0">
                            <span class="block w-2.5 h-2.5 rounded-full {{ !$notif->is_read ? 'bg-sky-500 ring-4 ring-sky-100' : 'bg-transparent' }}"></span>
                        </div>

                        {{-- Type Emoji Badge --}}
                        <div class="w-10 h-10 rounded-xl {{ $levelBg }} border flex items-center justify-center text-lg shrink-0 shadow-2xs">
                            {{ $iconEmoji }}
                        </div>

                        {{-- Body Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900 {{ !$notif->is_read ? 'font-black' : '' }}">
                                        {{ $notif->title }}
                                    </h4>
                                    @if(!$notif->is_read)
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider bg-sky-100 text-sky-800">
                                            New
                                        </span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $levelBg }} border">
                                        {{ $notif->type }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium shrink-0 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $notif->created_at->diffForHumans() }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-mono">{{ $notif->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                {{ $notif->message }}
                            </p>

                            {{-- Action Links & Footer --}}
                            <div class="mt-2.5 flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100/60">
                                <div>
                                    @if($notif->url)
                                        <a href="{{ $notif->url }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold shadow-2xs transition">
                                            <span>{{ $notif->action_text ?: 'View Details' }}</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    {{-- Toggle Read/Unread --}}
                                    <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-slate-500 hover:text-sky-600 font-bold px-2 py-1 rounded hover:bg-slate-100 transition cursor-pointer" title="{{ $notif->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                                            {{ $notif->is_read ? 'Mark Unread' : 'Mark Read' }}
                                        </button>
                                    </form>

                                    {{-- Delete Notification --}}
                                    <form action="{{ route('admin.notifications.destroy', $notif->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-slate-400 hover:text-rose-600 p-1 rounded hover:bg-rose-50 transition cursor-pointer" title="Delete notification">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if($notifications->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

<script>
function notificationsPage() {
    return {
        // Alpine data handler if needed for dynamic interactions
    }
}
</script>
@endsection

