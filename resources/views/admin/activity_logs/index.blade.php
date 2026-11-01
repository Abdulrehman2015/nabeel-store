@extends('layouts.admin')

@section('title', 'Admin Activity & Security Audit Log')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Security & Activity Audit Log</h1>
        <p class="text-xs text-gray-500 mt-1">Immutable audit trail of administrative actions, staff approvals, and system configuration updates.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Timestamp</th>
                        <th class="px-6 py-3">Administrator</th>
                        <th class="px-6 py-3">Action Event</th>
                        <th class="px-6 py-3">Details / Target</th>
                        <th class="px-6 py-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4 text-xs text-gray-500 font-mono">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <span class="font-bold text-gray-900 block text-xs">{{ $log->user->name }}</span>
                                    <span class="text-[11px] text-gray-400">{{ $log->user->email }}</span>
                                @else
                                    <span class="text-xs text-gray-400 italic">System / Anonymous</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-gray-100 text-gray-800">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-700 max-w-sm">
                                @if($log->subject_type)
                                    <span class="text-gray-400 font-mono text-[11px]">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</span>
                                @endif
                                @if($log->payload)
                                    <pre class="mt-1 text-[11px] bg-gray-50 p-1.5 rounded border border-gray-200 font-mono overflow-x-auto">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-400">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No activity recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

