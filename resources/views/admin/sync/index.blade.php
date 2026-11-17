@extends('layouts.admin')

@section('title', 'Database Sync Center - N&I Mini Mart')

@section('content')
<div class="space-y-6" x-data="syncCenterApp()">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Offline & Live Database Sync Center</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Bidirectional synchronization between local Windows workstation and cloud hosting.</p>
                </div>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            <button 
                type="button" 
                @click="triggerManualSync()" 
                :disabled="isSyncing"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-xs transition-colors disabled:opacity-50">
                <svg :class="isSyncing ? 'animate-spin' : ''" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span x-text="isSyncing ? 'Synchronizing...' : 'Sync Now with Cloud'"></span>
            </button>
            <button 
                type="button"
                @click="clearSyncedLogs()"
                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition-colors">
                Clean Completed Logs
            </button>
        </div>
    </div>

    <!-- Alert / Status Notice -->
    <template x-if="syncMessage">
        <div :class="syncSuccess ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' : 'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800'" class="p-4 rounded-xl border text-sm flex items-start justify-between">
            <div class="flex items-center gap-2">
                <span x-text="syncSuccess ? '✓' : '⚠'" class="font-bold"></span>
                <span x-text="syncMessage"></span>
            </div>
            <button @click="syncMessage = ''" class="text-xs underline ml-4">Dismiss</button>
        </div>
    </template>

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <!-- Live Connection Status -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Cloud Server</span>
                <span :class="isOnline ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300'" class="px-2.5 py-0.5 rounded-full text-xs font-medium" x-text="isOnline ? 'ONLINE' : 'UNREACHABLE'"></span>
            </div>
            <div class="text-lg font-bold text-gray-900 dark:text-white truncate" title="{{ config('sync.live_url') }}">
                {{ parse_url(config('sync.live_url'), PHP_URL_HOST) ?? 'Cloud Host' }}
            </div>
            <p class="text-xs text-gray-500 mt-1" x-text="pingMessage"></p>
        </div>

        <!-- Pending Sync Items -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Outbox Queue</span>
                <span :class="pendingCount > 0 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-medium" x-text="pendingCount + ' PENDING'"></span>
            </div>
            <div class="text-2xl font-black text-gray-900 dark:text-white" x-text="pendingCount"></div>
            <p class="text-xs text-gray-500 mt-1">Sales & products waiting for cloud upload</p>
        </div>

        <!-- Last Synced At -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Last Sync Time</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-base font-bold text-gray-900 dark:text-white truncate" x-text="lastSyncedAt"></div>
            <p class="text-xs text-gray-500 mt-1">Automatic interval: every 30s when online</p>
        </div>

        <!-- Offline Protection -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Offline Resilience</span>
                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 px-2.5 py-0.5 rounded-full text-xs font-medium">PROTECTED</span>
            </div>
            <div class="text-sm font-bold text-gray-900 dark:text-white">Zero Downtime</div>
            <p class="text-xs text-gray-500 mt-1">Cashier can continue selling even if internet drops.</p>
        </div>
    </div>

    <!-- Outbox Synchronization Queue Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sync Outbox Activity Log</h2>
            <span class="text-xs text-gray-500">Showing recent 20 events</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 font-semibold text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3">Record Type</th>
                        <th class="px-5 py-3">UUID / Reference</th>
                        <th class="px-5 py-3">Action</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Attempts</th>
                        <th class="px-5 py-3">Time</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($pendingItems as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750">
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">
                                <span class="capitalize px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-xs font-semibold">
                                    {{ str_replace('_', ' ', $item->record_type) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-gray-600 dark:text-gray-300">
                                {{ Str::limit($item->sync_uuid, 16) }}
                                @if(!empty($item->payload['order_number']))
                                    <span class="block text-emerald-600 font-semibold">{{ $item->payload['order_number'] }}</span>
                                @elseif(!empty($item->payload['sku']))
                                    <span class="block text-indigo-600 font-semibold">{{ $item->payload['sku'] }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs uppercase font-semibold text-gray-600 dark:text-gray-300">
                                {{ $item->action }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($item->status === 'synced')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 rounded-full text-xs font-semibold">Synced</span>
                                @elseif($item->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 rounded-full text-xs font-semibold">Pending</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 rounded-full text-xs font-semibold" title="{{ $item->error_message }}">Failed</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">
                                {{ $item->attempts }} / 5
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">
                                {{ $item->created_at?->diffForHumans() }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @if($item->status === 'failed')
                                    <button 
                                        type="button" 
                                        @click="retryItem({{ $item->id }})"
                                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
                                        Retry
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">
                                No sync activity logs found. All changes are currently up to date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingItems->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                {{ $pendingItems->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function syncCenterApp() {
    return {
        isOnline: {{ $ping['online'] ? 'true' : 'false' }},
        pingMessage: '{{ addslashes($ping['message']) }}',
        pendingCount: {{ $status['pending_count'] }},
        lastSyncedAt: '{{ addslashes($status['last_synced_at']) }}',
        isSyncing: false,
        syncMessage: '',
        syncSuccess: true,

        async init() {
            // Poll status every 20 seconds
            setInterval(() => this.checkStatus(), 20000);

            // Listen to browser network changes
            window.addEventListener('online', () => {
                this.isOnline = true;
                this.triggerManualSync();
            });
            window.addEventListener('offline', () => {
                this.isOnline = false;
                this.pingMessage = 'Browser network offline';
            });
        },

        async checkStatus() {
            try {
                const res = await fetch('{{ route("admin.sync.status") }}');
                const data = await res.json();
                if (data.success) {
                    this.isOnline = data.is_online;
                    this.pingMessage = data.ping_message;
                    this.pendingCount = data.pending_count;
                    this.lastSyncedAt = data.last_synced_at;
                }
            } catch (e) {
                this.isOnline = false;
            }
        },

        async triggerManualSync() {
            if (this.isSyncing) return;
            this.isSyncing = true;
            this.syncMessage = 'Syncing sales and catalog with live cloud server...';
            this.syncSuccess = true;

            try {
                const res = await fetch('{{ route("admin.sync.trigger") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                this.syncSuccess = data.success;
                this.syncMessage = data.message || (data.success ? 'Sync completed successfully!' : 'Sync encountered issues.');
                if (data.last_synced_at) {
                    this.lastSyncedAt = data.last_synced_at;
                }
                await this.checkStatus();
            } catch (e) {
                this.syncSuccess = false;
                this.syncMessage = 'Network error: Could not reach live server. Sales remain safely stored locally.';
            } finally {
                this.isSyncing = false;
            }
        },

        async retryItem(id) {
            try {
                const res = await fetch(`{{ url('admin/sync') }}/${id}/retry`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (e) {
                alert('Retry request failed.');
            }
        },

        async clearSyncedLogs() {
            if (!confirm('Clean completed sync history? (Pending items will remain safe)')) return;
            try {
                const res = await fetch('{{ route("admin.sync.clear-synced") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (e) {
                alert('Request failed.');
            }
        }
    };
}
</script>
@endsection
