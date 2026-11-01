@extends('layouts.admin')

@section('title', 'PDPA / GDPR Privacy Requests')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">PDPA & Privacy Data Requests</h1>
        <p class="text-xs text-gray-500 mt-1">Manage customer personal data access, export, or deletion requests for compliance.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Request Type</th>
                        <th class="px-6 py-3">Details / Reason</th>
                        <th class="px-6 py-3">Submitted</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Process</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block">{{ $req->user ? $req->user->name : 'Guest' }}</span>
                                <span class="text-xs text-gray-500">{{ $req->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $req->request_type === 'deletion' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $req->request_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs text-xs text-gray-600">
                                {{ $req->reason ?? 'No additional details provided.' }}
                                @if($req->admin_notes)
                                    <div class="mt-1 text-[11px] text-gray-400 italic">Note: {{ $req->admin_notes }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $req->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($req->status === 'completed')
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Completed</span>
                                @elseif($req->status === 'rejected')
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($req->status === 'pending')
                                    <form action="{{ route('admin.data-requests.process', $req) }}" method="POST" class="inline-flex gap-1">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 rounded">Approve</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">No privacy data requests submitted.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

