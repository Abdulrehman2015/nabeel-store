@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_number)

@section('content')
<div class="space-y-8" x-data="{
    verifyModal: false,
    rejectModal: false,
    statusModal: false,
    zoomProof: false
}">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-sky-600 hover:underline">&larr; Back to Orders</a>
                <span class="text-slate-300">/</span>
                <span class="font-mono text-xs text-slate-500">{{ $order->order_number }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-mono mt-1">{{ $order->order_number }}</h1>
            <span class="text-xs text-slate-400">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</span>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            {{-- Print Invoice Button --}}
            <a 
                href="{{ route('admin.orders.invoice', $order->id) }}" 
                target="_blank" 
                class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2"
            >
                <span>📄</span>
                <span>Print Invoice (PDF)</span>
            </a>

            {{-- WhatsApp Contact Button --}}
            @if($whatsAppUrl)
                <a 
                    href="{{ $whatsAppUrl }}" 
                    target="_blank" 
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2"
                >
                    <span>💬</span>
                    <span>Chat on WhatsApp</span>
                </a>
            @endif

            {{-- Status Badge --}}
            <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    {{-- Urgent Payment Verification Card (if pending) --}}
    @if($order->order_status === 'pending_payment_verification')
        <div class="bg-amber-50 rounded-3xl p-6 sm:p-8 border border-amber-300 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <h2 class="text-base font-black text-amber-900">Action Required: Verify Advance Payment Proof</h2>
                        <p class="text-xs text-amber-800">Customer has submitted bank transfer receipt for {{ format_currency($order->advance_amount) }}. Verify details below.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="verifyModal = true" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition"
                    >
                        ✓ Verify & Confirm Order
                    </button>
                    <button 
                        type="button" 
                        @click="rejectModal = true" 
                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition"
                    >
                        ✕ Reject Payment
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Main 2-Column Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left Column (8 cols): Items, Payment Screenshot, Internal Notes --}}
        <div class="lg:col-span-8 space-y-8">
            
            {{-- Cartons Line Items Table --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                    Carton Items ({{ $order->items->sum('quantity') }} Total)
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                                <th class="py-2.5 px-3">Product Name</th>
                                <th class="py-2.5 px-3">SKU</th>
                                <th class="py-2.5 px-3">Pack Size</th>
                                <th class="py-2.5 px-3 text-right">Unit Price</th>
                                <th class="py-2.5 px-3 text-center">Qty</th>
                                <th class="py-2.5 px-3 text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-3 font-bold text-slate-900">{{ $item->product_name }}</td>
                                    <td class="py-3 px-3 font-mono text-slate-400">{{ $item->sku }}</td>
                                    <td class="py-3 px-3 text-slate-600">{{ $item->pack_size }}</td>
                                    <td class="py-3 px-3 text-right">{{ format_currency($item->unit_price) }}</td>
                                    <td class="py-3 px-3 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                                    <td class="py-3 px-3 text-right font-black text-slate-900">{{ format_currency($item->line_total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Advance Payment Proof Screenshot (Phase 3.6 & 7.1) --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Payment Proof Screenshot</h3>
                        <p class="text-xs text-slate-400">Transaction Reference: <strong class="font-mono text-slate-800">{{ $order->payment_reference }}</strong></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $order->payment_status === 'verified' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($order->payment_status === 'rejected' ? 'bg-rose-100 text-rose-800 border-rose-300' : 'bg-amber-100 text-amber-800 border-amber-300') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

                @if($order->payment_proof_path)
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center">
                        <img 
                            src="{{ route('admin.orders.payment-proof', $order->id) }}" 
                            alt="Payment Proof" 
                            class="max-h-96 mx-auto rounded-xl shadow-md border cursor-pointer hover:opacity-90 transition"
                            @click="zoomProof = true"
                        >
                        <p class="text-[11px] text-slate-500 mt-2">Click image to enlarge receipt</p>
                    </div>
                @else
                    <div class="p-8 text-center text-xs text-slate-400">
                        No payment proof uploaded.
                    </div>
                @endif
            </div>

            {{-- Status Change History Log --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                    Status History & Audit Timeline
                </h3>
                <div class="space-y-3 text-xs">
                    @forelse($order->histories as $hist)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="font-mono text-[11px] text-slate-400">{{ $hist->created_at->format('M d, H:i') }}</span>
                            <div class="flex-1">
                                <span class="font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $hist->status_to) }}</span>
                                <p class="text-slate-600 mt-0.5">{{ $hist->comment }}</p>
                                @if($hist->user)
                                    <span class="text-[10px] text-slate-400">By {{ $hist->user->name }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-slate-400 text-center py-4">No status changes recorded.</div>
                    @endforelse
                </div>
            </div>

            {{-- Internal Staff Notes --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                    Internal Staff Notes
                </h3>
                
                @if($order->internal_notes)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-700 whitespace-pre-line font-mono">
                        {{ $order->internal_notes }}
                    </div>
                @endif

                <form action="{{ route('admin.orders.internal-note', $order->id) }}" method="POST" class="space-y-2">
                    @csrf
                    <textarea 
                        name="internal_note" 
                        rows="2" 
                        required 
                        placeholder="Add internal dispatch note (e.g. customer called to confirm driver timing)..." 
                        class="w-full text-xs px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-sky-500"
                    ></textarea>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition">
                        Add Internal Note
                    </button>
                </form>
            </div>

        </div>

        {{-- Right Column (4 cols): Customer, Destination, Financial Breakdown, Operational Status --}}
        <div class="lg:col-span-4 space-y-8">
            
            {{-- Update Operational Status Card --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">
                    Change Operational Status
                </h3>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">New Status:</label>
                        <select name="status" class="w-full text-xs py-2 px-3 border border-slate-300 rounded-xl focus:ring-sky-500 bg-slate-50">
                            <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed (Payment Verified)</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing (Packing Cartons)</option>
                            <option value="out_for_delivery" {{ $order->order_status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered (Completed)</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Note / Driver Name:</label>
                        <input type="text" name="comment" placeholder="e.g. Assigned to Driver Ali (Lorry WX 1234)" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Customer Destination Card --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4 text-xs">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">
                    Delivery Destination
                </h3>

                <div class="space-y-2 text-slate-700">
                    <strong class="text-slate-900 text-sm block">{{ $order->customer_name }}</strong>
                    <p class="text-slate-500">📞 {{ $order->customer_phone }}</p>
                    <p class="text-slate-500">✉️ {{ $order->customer_email }}</p>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                        <p class="font-bold text-slate-900">{{ $order->delivery_address }}</p>
                        <p>{{ $order->delivery_city }}</p>
                        <p class="font-bold text-amber-800">🏢 Floor: Floor {{ $order->floor }}</p>
                        @if($order->landmark)
                            <p class="text-slate-500">Landmark: {{ $order->landmark }}</p>
                        @endif
                        @if($order->order_notes)
                            <p class="text-sky-700 italic">Notes: "{{ $order->order_notes }}"</p>
                        @endif
                    </div>
                    <div class="pt-2">
                        <a 
                            href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" 
                            target="_blank" 
                            class="inline-flex items-center gap-1.5 text-sky-600 font-bold hover:underline"
                        >
                            <span>📍</span>
                            <span>Open in Google Maps ({{ $order->distance_km }} km, Zone: {{ strtoupper($order->delivery_zone) }})</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Financial Breakdown Card --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4 text-xs">
                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">
                    Financial Summary
                </h3>

                <div class="space-y-2">
                    <div class="flex justify-between text-slate-600">
                        <span>Cart Subtotal:</span>
                        <strong class="text-slate-900">{{ format_currency($order->subtotal) }}</strong>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Delivery Charge:</span>
                        <strong class="{{ $order->delivery_charge > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                            {{ $order->delivery_charge > 0 ? format_currency($order->delivery_charge) : 'FREE' }}
                        </strong>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Floor Charge (Floor {{ $order->floor }}):</span>
                        <strong class="{{ $order->floor_charge > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                            {{ $order->floor_charge > 0 ? format_currency($order->floor_charge) : 'FREE' }}
                        </strong>
                    </div>
                    <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-200">
                        <span>Grand Total:</span>
                        <span class="text-sky-600">{{ format_currency($order->grand_total) }}</span>
                    </div>

                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-emerald-900 font-bold flex justify-between">
                        <span>Advance Paid ({{ $order->advance_percentage }}%):</span>
                        <span>{{ format_currency($order->advance_amount) }}</span>
                    </div>

                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 font-bold flex justify-between">
                        <span>Balance on Delivery:</span>
                        <span>{{ format_currency($order->balance_amount) }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Verify Payment Modal --}}
    <div x-show="verifyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60" @click="verifyModal = false"></div>
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full relative z-10 shadow-2xl space-y-4">
            <h3 class="text-lg font-black text-slate-900">Confirm Payment Verification</h3>
            <p class="text-xs text-slate-500">
                Are you sure you have verified the receipt for <strong>{{ format_currency($order->advance_amount) }}</strong> (Ref: <code>{{ $order->payment_reference }}</code>)?
                This will transition the order to <strong>Confirmed</strong> and officially reserve stock.
            </p>

            <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Optional Note:</label>
                    <input type="text" name="note" placeholder="e.g. Verified on Maybank portal" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="verifyModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow">Approve & Confirm</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject Payment Modal --}}
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/60" @click="rejectModal = false"></div>
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full relative z-10 shadow-2xl space-y-4">
            <h3 class="text-lg font-black text-rose-900">Reject Payment Proof</h3>
            <p class="text-xs text-slate-500">Please provide a clear reason for rejecting this payment screenshot. The customer will be informed.</p>

            <form action="{{ route('admin.orders.reject-payment', $order->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Rejection Reason * (REQUIRED):</label>
                    <textarea name="reason" rows="3" required placeholder="e.g. Transaction amount does not match advance deposit, or unreadable screenshot..." class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="rejectModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow">Reject Payment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Zoom Proof Modal --}}
    <div x-show="zoomProof" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80" @click="zoomProof = false" style="display: none;">
        <div class="max-w-4xl max-h-[90vh] overflow-auto bg-white p-3 rounded-2xl shadow-2xl" @click.stop>
            @if($order->payment_proof_path)
                <img src="{{ route('admin.orders.payment-proof', $order->id) }}" class="max-w-full h-auto">
            @endif
            <div class="text-center pt-2">
                <button type="button" @click="zoomProof = false" class="px-4 py-1.5 bg-slate-900 text-white text-xs font-bold rounded-lg">Close Preview</button>
            </div>
        </div>
    </div>

</div>
@endsection

