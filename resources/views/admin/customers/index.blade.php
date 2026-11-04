@extends('layouts.admin')

@section('title', 'Registered Customers')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="text-xs text-gray-500 mt-1">Manage registered customers, accounts, and purchase history.</p>
        </div>
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer, email, phone..." class="text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary w-64">
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Total Orders</th>
                        <th class="px-6 py-3">Registered</th>
                        <th class="px-6 py-3">Account Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customers as $c)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block">{{ $c->name }}</span>
                                <span class="text-xs text-gray-400">{{ $c->email }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                {{ $c->phone ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold">
                                    {{ $c->orders_count }} orders
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $c->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($c->is_active)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-red-100 text-red-800">Suspended</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2.5 whitespace-nowrap">
                                <a href="{{ route('admin.customers.show', $c) }}" class="text-sky-600 hover:text-sky-700 font-semibold text-xs">View Profile</a>
                                <form action="{{ route('admin.customers.toggle-status', $c) }}" method="POST" class="inline" onsubmit="return confirm('{{ $c->is_active ? 'Suspend this customer account?' : 'Unlock customer ' . addslashes($c->name) . '? A new login password will be generated and emailed to them immediately.' }}');">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold {{ $c->is_active ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700 font-bold' }}">
                                        {{ $c->is_active ? 'Suspend' : 'Unlock Account' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.customers.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete customer {{ addslashes($c->name) }}? All customer addresses will be removed. Past orders will be retained for bookkeeping.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

