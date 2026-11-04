<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt #{{ $order->order_number }} - {{ setting('site_name', 'N&J Mini Mart') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        @media print {
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto !important;
                padding: 8mm 4mm !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 text-slate-800 font-mono text-xs antialiased">

    {{-- Top Action Buttons (Hidden on Print) --}}
    <div class="no-print max-w-sm mx-auto mb-4 flex items-center justify-between gap-2 px-2">
        <a 
            href="{{ route('admin.pos.index') }}" 
            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-sans font-bold text-xs rounded-xl flex items-center gap-1.5 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to POS</span>
        </a>

        <div class="flex items-center gap-2">
            <button 
                type="button" 
                onclick="window.print()" 
                class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-sans font-bold text-xs rounded-xl shadow-md flex items-center gap-1.5 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Thermal Receipt</span>
            </button>
        </div>
    </div>

    {{-- 80mm Thermal Receipt Slip --}}
    <div class="receipt-container max-w-[80mm] mx-auto bg-white p-5 rounded-2xl shadow-md border border-slate-200 leading-tight">
        
        {{-- Header & Store Branding --}}
        <div class="text-center pb-3 border-b border-dashed border-slate-300">
            <div class="flex items-center justify-center gap-2 mb-1">
                <img src="{{ asset('images/logo.png') }}" alt="N&J Mini Mart" class="h-9 w-auto object-contain mx-auto">
            </div>
            <h1 class="text-sm font-black tracking-wider uppercase text-slate-900">{{ setting('site_name', 'N&J MINI MART') }}</h1>
            <p class="text-[10px] text-slate-500 mt-0.5">Retail Mart & Everyday Essentials</p>
            <p class="text-[10px] text-slate-500">Puchong Central Distribution Hub</p>
            <p class="text-[10px] text-slate-500">Tel: +60 12-859 9900</p>
        </div>

        {{-- Transaction Details --}}
        <div class="py-2.5 text-[10px] space-y-1 border-b border-dashed border-slate-300">
            <div class="flex justify-between">
                <span class="text-slate-500">Receipt #:</span>
                <span class="font-bold text-slate-900">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Date/Time:</span>
                <span class="text-slate-900">{{ $order->created_at->format('d/m/Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Cashier:</span>
                <span class="text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Customer:</span>
                <span class="font-semibold text-slate-900">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Type:</span>
                <span class="font-bold uppercase text-emerald-700 bg-emerald-50 px-1 rounded">WALK-IN POS SALE</span>
            </div>
        </div>

        {{-- Items Sold Table --}}
        <div class="py-2.5 border-b border-dashed border-slate-300">
            <table class="w-full text-[11px]">
                <thead>
                    <tr class="text-[9px] uppercase tracking-wider text-slate-400 border-b border-slate-200">
                        <th class="text-left pb-1">Item</th>
                        <th class="text-center pb-1">Qty</th>
                        <th class="text-right pb-1">Price</th>
                        <th class="text-right pb-1">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-1 pr-1 font-semibold text-slate-900">
                                <div>{{ $item->product_name }}</div>
                                @if($item->sku)
                                    <div class="text-[8px] text-slate-400 font-mono">{{ $item->sku }}</div>
                                @endif
                            </td>
                            <td class="py-1 text-center font-bold text-slate-700 align-top">{{ $item->quantity }}</td>
                            <td class="py-1 text-right text-slate-600 align-top">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-1 text-right font-bold text-slate-900 align-top">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals Calculation --}}
        <div class="py-2.5 text-xs space-y-1.5 border-b border-dashed border-slate-300">
            <div class="flex justify-between text-slate-600 text-[11px]">
                <span>Items Subtotal:</span>
                <span>RM {{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->subtotal > $order->grand_total)
                <div class="flex justify-between text-emerald-600 text-[11px]">
                    <span>Discount:</span>
                    <span>-RM {{ number_format($order->subtotal - $order->grand_total, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between text-slate-900 font-black text-sm pt-1 border-t border-slate-200">
                <span>TOTAL DUE:</span>
                <span class="text-base text-slate-900">RM {{ number_format($order->grand_total, 2) }}</span>
            </div>

            <div class="pt-2 text-[10px] space-y-1 text-slate-700">
                <div class="flex justify-between">
                    <span class="uppercase text-slate-500">Payment Method:</span>
                    <span class="font-bold uppercase text-slate-900">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                @if($order->internal_notes && str_contains($order->internal_notes, 'Tendered:'))
                    <div class="flex justify-between">
                        <span class="text-slate-500">Cash Tendered:</span>
                        <span class="font-bold text-slate-900">
                            {{ Str::between($order->internal_notes, 'Tendered: ', ', Change:') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-emerald-700 font-bold">
                        <span>Change Returned:</span>
                        <span>
                            {{ Str::after($order->internal_notes, 'Change: ') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Barcode Graphic & Footer Note --}}
        <div class="pt-3 text-center space-y-1.5">
            {{-- Simulated Thermal Barcode --}}
            <div class="py-1 tracking-widest text-[16px] font-black text-slate-800 select-none">
                ||||| | |||| ||| ||||| || ||| ||||
            </div>
            <div class="text-[9px] text-slate-400 font-mono">{{ $order->order_number }}</div>

            <div class="text-[10px] text-slate-600 font-semibold pt-1">
                Thank you for shopping at N&J Mini Mart!
            </div>
            <div class="text-[8px] text-slate-400">
                Goods sold are non-refundable. Exchange allowed within 3 days with original receipt.
            </div>
        </div>

    </div>

    <script>
        // Auto print prompt if opened as receipt popup
        if (window.location.search.includes('print=1')) {
            window.addEventListener('load', () => {
                setTimeout(() => window.print(), 300);
            });
        }
    </script>
</body>
</html>

