@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Top Welcome & Metrics --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Wholesale Operations Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Real-time overview of bulk carton orders, advance payments, and warehouse inventory.</p>
        </div>
        <div class="text-xs font-semibold text-slate-500 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-sm">
            📅 {{ now()->format('l, F d, Y') }}
        </div>
    </div>

    {{-- 5 Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        {{-- 1. Today's Revenue --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Today's Revenue</span>
            <div class="text-2xl font-black text-slate-900">{{ format_currency($todayRevenue) }}</div>
            <span class="text-[11px] text-emerald-600 font-semibold">Confirmed & Delivered</span>
        </div>

        {{-- 2. Today's Orders --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Today's Orders</span>
            <div class="text-2xl font-black text-slate-900">{{ $todayOrdersCount }}</div>
            <span class="text-[11px] text-sky-600 font-semibold">Bulk bookings</span>
        </div>

        {{-- 3. Pending Payments (Urgent) --}}
        <div class="bg-white p-5 rounded-2xl border {{ $pendingPaymentsCount > 0 ? 'border-amber-300 bg-amber-50/30' : 'border-slate-200' }} shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider block">Verify Payment Proof</span>
            <div class="text-2xl font-black text-amber-700">{{ $pendingPaymentsCount }}</div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending_payment_verification']) }}" class="text-[11px] text-amber-800 font-bold hover:underline block">
                Needs verification &rarr;
            </a>
        </div>

        {{-- 4. Pending Fulfillment --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">In Packing / Transit</span>
            <div class="text-2xl font-black text-slate-900">{{ $pendingOrdersCount }}</div>
            <span class="text-[11px] text-purple-600 font-semibold">Confirmed orders</span>
        </div>

        {{-- 5. Low Stock Alert --}}
        <div class="bg-white p-5 rounded-2xl border {{ $lowStockCount > 0 ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200' }} shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-rose-800 uppercase tracking-wider block">Low Stock Alert</span>
            <div class="text-2xl font-black text-rose-700">{{ $lowStockCount }}</div>
            <a href="{{ route('admin.products.index', ['stock_status' => 'low']) }}" class="text-[11px] text-rose-800 font-bold hover:underline block">
                &le; 20 cartons remaining &rarr;
            </a>
        </div>

    </div>

    {{-- Sales Chart & Low Stock Alerts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Sales Trend Chart (2 cols) --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">7-Day Wholesale Revenue Trend</h3>
                    <p class="text-[11px] text-slate-400">Total daily confirmed wholesale carton sales</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Low Stock Products List (1 col) --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Critical Stock Warning</h3>
                <span class="text-[11px] text-slate-400">Alert threshold: 20</span>
            </div>

            @if($lowStockProducts->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($lowStockProducts as $p)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div class="truncate pr-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="font-bold text-slate-900 hover:text-sky-600 block truncate">
                                    {{ $p->name }}
                                </a>
                                <span class="text-slate-400 font-mono text-[10px]">{{ $p->sku }} &bull; {{ $p->pack_size }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-black {{ $p->stock_quantity <= 5 ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $p->stock_quantity }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-xs text-slate-400">
                    ✓ All wholesale products have healthy inventory levels.
                </div>
            @endif
        </div>

    </div>

    {{-- Recent Orders Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-4 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Recent Customer Orders</h3>
                <p class="text-xs text-slate-400">Latest bulk orders placed on the storefront</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-sky-600 hover:underline">
                View All Orders &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Delivery Floor</th>
                        <th class="py-3 px-4">Grand Total</th>
                        <th class="py-3 px-4">Advance Paid</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sky-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="bg-slate-100 px-2 py-0.5 rounded font-semibold text-slate-700">Floor {{ $order->floor }}</span>
                                <span class="text-[11px] text-slate-400 ml-1">({{ $order->delivery_zone }})</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ format_currency($order->grand_total) }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-emerald-600">
                                {{ format_currency($order->advance_amount) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg text-[11px] transition">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No orders received yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Render Sales Chart --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [{
                    label: 'Revenue ({{ setting("currency_symbol", "RM") }})',
                    data: {!! json_encode($chartRevenue) !!},
                    backgroundColor: '#0284c7',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'RM ' + value; }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
