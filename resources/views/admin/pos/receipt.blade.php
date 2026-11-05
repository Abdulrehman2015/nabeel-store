@php
    $paperWidth = request('paper', '80mm');
    if (!in_array($paperWidth, ['80mm', '58mm'])) {
        $paperWidth = '80mm';
    }
    $is58mm = ($paperWidth === '58mm');
    $copies = (int) request('copies', 1);
    if ($copies < 1 || $copies > 2) {
        $copies = 1;
    }
    $showLogo = request()->has('show_logo') ? (request('show_logo') == '1' || request('show_logo') === 'true') : true;
    $showBarcode = request()->has('show_barcode') ? (request('show_barcode') == '1' || request('show_barcode') === 'true') : true;
    $showCashier = request()->has('show_cashier') ? (request('show_cashier') == '1' || request('show_cashier') === 'true') : true;
    $showCustomer = request()->has('show_customer') ? (request('show_customer') == '1' || request('show_customer') === 'true') : true;

    $storeName = request('header_name') ?: setting('site_name', 'N&J MINI MART');
    $storeTagline = request('tagline') ?: 'Retail Mart & Everyday Essentials';
    $storeAddress = request('address') ?: 'Puchong Central Distribution Hub, Selangor';
    $storePhone = request('phone') ?: '+60 12-859 9900';
    $regNo = request('reg_no') ?: 'SSM: 202401023456 (1549876-X)';
    $footerMessage = request('footer') ?: 'Thank you for shopping at N&J Mini Mart! Goods sold are non-refundable. Exchange allowed within 3 days with original receipt.';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt #{{ $order->order_number }} - {{ $storeName }}</title>
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --paper-width: {{ $paperWidth }};
        }
        @page {
            size: {{ $paperWidth }} auto;
            margin: 0mm;
        }
        @media print {
            html, body {
                width: {{ $paperWidth }} !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Courier New', Courier, monospace, 'Lucida Console' !important;
                font-size: {{ $is58mm ? '10px' : '11px' }} !important;
                line-height: 1.25 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-sheet {
                width: 100% !important;
                max-width: {{ $paperWidth }} !important;
                margin: 0 auto !important;
                padding: 3mm 2.5mm !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always;
            }
            .receipt-sheet:last-child {
                page-break-after: avoid;
            }
            .paper-cut-feed {
                height: 18mm !important;
                display: block !important;
            }
        }
        @media screen {
            body {
                background-color: #f1f5f9;
            }
            .receipt-sheet {
                width: {{ $paperWidth }};
                max-width: {{ $paperWidth }};
                margin-left: auto;
                margin-right: auto;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            }
            .paper-cut-feed {
                height: 8mm;
                display: block;
            }
        }
    </style>
</head>
<body class="min-h-screen py-4 sm:py-8 text-slate-900 font-mono text-xs antialiased">

    {{-- Top Action Controls Toolbar (Hidden on Print) --}}
    <div class="no-print max-w-md mx-auto mb-5 px-3">
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <a 
                    href="{{ route('admin.pos.index') }}" 
                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-sans font-bold text-xs rounded-xl flex items-center gap-1.5 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back to POS</span>
                </a>

                <div class="flex items-center gap-2">
                    {{-- Quick Paper Width Switcher --}}
                    <div class="inline-flex p-0.5 bg-slate-100 rounded-lg text-[11px] font-sans font-bold">
                        <a 
                            href="?paper=80mm{{ request()->has('print') ? '&print=1' : '' }}" 
                            class="px-2 py-1 rounded-md transition {{ $paperWidth === '80mm' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}"
                        >
                            80mm
                        </a>
                        <a 
                            href="?paper=58mm{{ request()->has('print') ? '&print=1' : '' }}" 
                            class="px-2 py-1 rounded-md transition {{ $paperWidth === '58mm' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}"
                        >
                            58mm
                        </a>
                    </div>

                    <button 
                        type="button" 
                        onclick="triggerThermalPrint()" 
                        class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-sans font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 transition cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print Slip</span>
                    </button>
                </div>
            </div>

            @if(isset($isTest) && $isTest)
                <div class="p-2 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-900 font-sans flex items-center gap-2">
                    <span class="text-base">🧪</span>
                    <span><strong>Test Print Mode:</strong> This is a sample receipt slip to verify your physical thermal printer alignment & cutter.</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Thermal Receipt Sheets (Iterates for Number of Copies) --}}
    @for($copyIndex = 1; $copyIndex <= $copies; $copyIndex++)
        <div class="receipt-sheet bg-white p-4 sm:p-5 mb-6 text-black border border-slate-200 leading-tight">
            
            {{-- Copy Header Flag (if multiple copies) --}}
            @if($copies > 1)
                <div class="text-center pb-1 text-[9px] font-bold uppercase tracking-widest text-slate-500">
                    {{ $copyIndex === 1 ? '*** CUSTOMER COPY ***' : '*** MERCHANT / STORE COPY ***' }}
                </div>
            @endif

            {{-- Store Branding & Header --}}
            <div class="text-center pb-2.5 border-b border-dashed border-black">
                @if($showLogo)
                    <div class="flex items-center justify-center gap-2 mb-1.5">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ $storeName }}" class="h-8 max-w-[120px] object-contain mx-auto filter grayscale contrast-200">
                    </div>
                @endif
                <h1 class="{{ $is58mm ? 'text-xs' : 'text-sm' }} font-black tracking-wider uppercase text-black">{{ $storeName }}</h1>
                @if($storeTagline)
                    <p class="text-[9px] text-black mt-0.5">{{ $storeTagline }}</p>
                @endif
                @if($storeAddress)
                    <p class="text-[9px] text-black">{{ $storeAddress }}</p>
                @endif
                @if($storePhone)
                    <p class="text-[9px] text-black">Tel: {{ $storePhone }}</p>
                @endif
                @if($regNo)
                    <p class="text-[8px] text-black font-semibold">{{ $regNo }}</p>
                @endif
            </div>

            {{-- Transaction Meta Info --}}
            <div class="py-2 text-[10px] space-y-0.5 border-b border-dashed border-black">
                <div class="flex justify-between">
                    <span>Receipt #:</span>
                    <span class="font-bold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Date/Time:</span>
                    <span>{{ $order->created_at ? $order->created_at->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}</span>
                </div>
                @if($showCashier)
                    <div class="flex justify-between">
                        <span>Cashier:</span>
                        <span>{{ auth()->user()->name ?? 'Counter Cashier' }}</span>
                    </div>
                @endif
                @if($showCustomer && $order->customer_name)
                    <div class="flex justify-between">
                        <span>Customer:</span>
                        <span class="font-semibold truncate max-w-[150px]">{{ $order->customer_name }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Channel:</span>
                    <span class="font-bold uppercase">POS COUNTER SALE</span>
                </div>
            </div>

            {{-- Itemized Products Table --}}
            <div class="py-2 border-b border-dashed border-black">
                <table class="w-full text-[10px] leading-tight">
                    <thead>
                        <tr class="text-[9px] uppercase tracking-wider border-b border-black text-black">
                            <th class="text-left pb-1 font-bold">Item</th>
                            <th class="text-center pb-1 font-bold">Qty</th>
                            <th class="text-right pb-1 font-bold">Price</th>
                            <th class="text-right pb-1 font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dotted divide-slate-300">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-1 pr-1 font-bold align-top">
                                    <div class="leading-tight">{{ $item->product_name }}</div>
                                    @if($item->sku)
                                        <div class="text-[8px] text-slate-600 font-mono">#{{ $item->sku }}</div>
                                    @endif
                                </td>
                                <td class="py-1 text-center font-bold align-top">{{ $item->quantity }}</td>
                                <td class="py-1 text-right align-top">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-1 text-right font-bold align-top">{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Financials & Payment Breakdown --}}
            <div class="py-2 text-[10px] space-y-1 border-b border-dashed border-black">
                <div class="flex justify-between text-black">
                    <span>Items Subtotal:</span>
                    <span>RM {{ number_format($order->subtotal, 2) }}</span>
                </div>

                @if($order->subtotal > $order->grand_total || ($order->discount_amount && $order->discount_amount > 0))
                    @php
                        $calcDiscount = ($order->discount_amount && $order->discount_amount > 0) 
                            ? $order->discount_amount 
                            : ($order->subtotal - $order->grand_total);
                    @endphp
                    <div class="flex justify-between text-black font-semibold">
                        <span>Discount Savings:</span>
                        <span>-RM {{ number_format($calcDiscount, 2) }}</span>
                    </div>
                @endif

                {{-- Bold Prominent Total --}}
                <div class="flex justify-between font-black {{ $is58mm ? 'text-xs' : 'text-sm' }} pt-1 border-t border-black text-black">
                    <span>TOTAL PAID:</span>
                    <span>RM {{ number_format($order->grand_total, 2) }}</span>
                </div>

                {{-- Payment Method & Tender Details --}}
                <div class="pt-1.5 space-y-0.5 text-[9px] text-black">
                    <div class="flex justify-between">
                        <span class="uppercase">Payment Method:</span>
                        <span class="font-bold uppercase">{{ str_replace('_', ' ', $order->payment_method ?? 'CASH') }}</span>
                    </div>

                    @if($order->internal_notes && str_contains($order->internal_notes, 'Tendered:'))
                        <div class="flex justify-between">
                            <span>Cash Tendered:</span>
                            <span class="font-bold">
                                {{ Str::between($order->internal_notes, 'Tendered: ', ', Change:') }}
                            </span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Change Returned:</span>
                            <span>
                                {{ Str::after($order->internal_notes, 'Change: ') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Barcode & Thermal Footer --}}
            <div class="pt-2.5 text-center space-y-1">
                @if($showBarcode)
                    {{-- High Contrast Simulated Thermal Barcode --}}
                    <div class="py-1 tracking-widest text-[15px] font-black text-black select-none leading-none">
                        |||||| | ||||| ||| |||||| || |||| |||||
                    </div>
                    <div class="text-[8px] font-mono font-bold">{{ $order->order_number }}</div>
                @endif

                <div class="text-[9px] font-bold pt-1 text-black">
                    {{ $footerMessage }}
                </div>
            </div>

            {{-- Thermal Paper Auto-Cutter Margin --}}
            <div class="paper-cut-feed"></div>

        </div>
    @endfor

    <script>
        function triggerThermalPrint() {
            window.focus();
            window.print();
        }

        // Auto print trigger for iframe or popup calls
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1' || window.self !== window.top) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    triggerThermalPrint();
                    // Close popup automatically if invoked as a popup window
                    if (urlParams.get('popup') === '1' && window.opener) {
                        window.onafterprint = () => {
                            setTimeout(() => window.close(), 300);
                        };
                    }
                }, 250);
            });
        }
    </script>
</body>
</html>
