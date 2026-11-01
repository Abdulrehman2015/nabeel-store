@extends('layouts.admin')

@section('title', 'Customer Profile: ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-primary">Customers</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $user->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $user->name }}</h1>
        </div>
        <form action="{{ route('admin.customers.toggle-status', $user) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg shadow-sm {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                {{ $user->is_active ? 'Suspend Account' : 'Activate Account' }}
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Customer Information</h3>

            <div class="space-y-2 text-sm">
                <div>
                    <span class="text-xs text-gray-400 block uppercase">Email</span>
                    <span class="font-medium text-gray-800">{{ $user->email }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block uppercase">Phone</span>
                    <span class="font-medium text-gray-800">{{ $user->phone ?? 'Not provided' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block uppercase">Member Since</span>
                    <span class="font-medium text-gray-800">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block uppercase">Account Status</span>
                    <span class="font-semibold {{ $user->is_active ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </div>
            </div>

            <!-- Saved Addresses -->
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-xs font-bold text-gray-700 uppercase mb-2">Saved Addresses ({{ $user->addresses->count() }})</h4>
                <div class="space-y-2">
                    @forelse($user->addresses as $addr)
                        <div class="p-2.5 bg-gray-50 rounded-lg text-xs space-y-0.5 border">
                            <div class="font-bold text-gray-800">{{ $addr->label ?? 'Address' }}</div>
                            <div class="text-gray-600">{{ $addr->full_address }}</div>
                            <div class="text-gray-400">Floor: {{ $addr->floor_number }}, Lift: {{ $addr->has_lift ? 'Yes' : 'No' }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No saved addresses.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                <span>Order History ({{ $user->orders->count() }})</span>
                <span class="text-xs font-normal text-gray-500">Total Spent: {{ format_currency($user->orders->where('payment_status', 'verified')->sum('total_amount')) }}</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 uppercase text-gray-500 font-semibold border-b">
                        <tr>
                            <th class="px-4 py-2">Order #</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Cartons</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Payment</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2 text-right">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($user->orders as $o)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono font-bold text-primary">{{ $o->order_number }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $o->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $o->items->sum('quantity') }} pkgs</td>
                                <td class="px-4 py-3 font-bold text-gray-900">{{ format_currency($o->total_amount) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $o->payment_status === 'verified' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($o->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700">
                                        {{ ucfirst($o->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $o) }}" class="text-primary hover:underline font-bold">Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-400">No orders placed yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
