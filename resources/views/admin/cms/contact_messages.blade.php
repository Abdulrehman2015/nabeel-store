@extends('layouts.admin')

@section('title', 'Customer Inquiries & Messages')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Customer Inquiries Inbox</h1>
        <p class="text-xs text-gray-500 mt-1">Direct inquiries submitted through the storefront contact form.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Subject & Message</th>
                        <th class="px-6 py-3">Received At</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-gray-50/75 transition {{ !$msg->is_read ? 'bg-amber-50/30 font-medium' : '' }}">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block">{{ $msg->name }}</span>
                                <span class="text-xs text-gray-500">{{ $msg->email }}</span>
                                @if($msg->phone)
                                    <span class="text-xs text-gray-400 block font-mono">{{ $msg->phone }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <span class="font-semibold text-gray-800 block text-xs">{{ $msg->subject }}</span>
                                <p class="text-xs text-gray-600 mt-1 whitespace-pre-line">{{ $msg->message }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $msg->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($msg->is_read)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600">Read</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800">New Unread</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(!$msg->is_read)
                                    <form action="{{ route('admin.contact-messages.mark-read', $msg) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-primary bg-primary/10 hover:bg-primary/20 rounded">Mark Read</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No contact messages received yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

