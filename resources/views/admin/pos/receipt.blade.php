@php
    $paperWidth = request('paper', '58mm');
    if (!in_array($paperWidth, ['80mm', '58mm'])) {
        $paperWidth = '58mm';
    }
    $is58mm = ($paperWidth === '58mm');
    $copies = (int) request('copies', 1);
    if ($copies < 1 || $copies > 2) {
        $copies = 1;
    }
    $showLogo = request()->has('show_logo') ? (request('show_logo') == '1' || request('show_logo') === 'true') : true;
    $showBarcode = request()->has('show_barcode') ? (request('show_barcode') == '1' || request('show_barcode') === 'true') : true;
    $showCustomer = request()->has('show_customer') ? (request('show_customer') == '1' || request('show_customer') === 'true') : true;

    $storeName = request('header_name') ?: setting('site_name', 'N&J MINI MART');
    $storeTagline = request('tagline') ?: 'Retail Mart & Everyday Essentials';
    $storeAddress = request('address') ?: 'Puchong Central Distribution Hub, Selangor';
    $storePhone = request('phone') ?: '+60 12-859 9900';
    $footerMessage = request('footer') ?: 'Thank you for Shopping at N&J Mini Mart';
    $orderScanUrl = route('checkout.success', $order->order_number);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt #{{ $order->order_number }} - {{ $storeName }}</title>
    @vite(['resources/css/app.css'])
    {{-- High-resolution client barcode and QR code libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root {
            --paper-width: {{ $paperWidth }};
        }
        @page {
            size: {{ $is58mm ? '58mm' : '80mm' }} auto;
            margin: 0;
        }
        @media print {
            * {
                -webkit-box-sizing: border-box !important;
                box-sizing: border-box !important;
            }
            html, body {
                width: {{ $is58mm ? '48mm' : '72mm' }} !important;
                max-width: {{ $is58mm ? '48mm' : '72mm' }} !important;
                margin: 0 auto !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Courier New', Courier, monospace, 'Lucida Console' !important;
                font-size: {{ $is58mm ? '9px' : '11px' }} !important;
                line-height: 1.25 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-sheet {
                width: {{ $is58mm ? '48mm' : '72mm' }} !important;
                max-width: {{ $is58mm ? '48mm' : '72mm' }} !important;
                margin: 0 auto !important;
                padding: 1.5mm 0.5mm !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always;
            }
            .receipt-sheet:last-child {
                page-break-after: avoid;
            }
            .paper-cut-feed {
                height: 14mm !important;
                display: block !important;
            }
            table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
            }
            th, td {
                word-break: break-word !important;
                overflow-wrap: break-word !important;
            }
        }
        @media screen {
            body {
                background-color: #f1f5f9;
            }
            .receipt-sheet {
                width: {{ $is58mm ? '52mm' : '80mm' }};
                max-width: {{ $is58mm ? '52mm' : '80mm' }};
                margin-left: auto;
                margin-right: auto;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            }
            .paper-cut-feed {
                height: 8mm;
                display: block;
            }
            table {
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
            }
            th, td {
                word-break: break-word;
                overflow-wrap: break-word;
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
                            href="?paper=58mm{{ request()->has('print') ? '&print=1' : '' }}" 
                            class="px-2 py-1 rounded-md transition {{ $paperWidth === '58mm' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}"
                        >
                            58mm (Mini Roll)
                        </a>
                        <a 
                            href="?paper=80mm{{ request()->has('print') ? '&print=1' : '' }}" 
                            class="px-2 py-1 rounded-md transition {{ $paperWidth === '80mm' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}"
                        >
                            80mm
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
                    <span><strong>Test Print Mode:</strong> Verifying thermal printer slip margins & layout on {{ $paperWidth }}.</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Thermal Receipt Sheets (Iterates for Number of Copies) --}}
    @for($copyIndex = 1; $copyIndex <= $copies; $copyIndex++)
        <div class="receipt-sheet bg-white p-3 sm:p-4 mb-6 text-black border border-slate-200 leading-tight">
            
            {{-- Copy Header Flag (if multiple copies) --}}
            @if($copies > 1)
                <div class="text-center pb-1 text-[8.5px] font-bold uppercase tracking-widest text-slate-500">
                    {{ $copyIndex === 1 ? '*** CUSTOMER COPY ***' : '*** MERCHANT / STORE COPY ***' }}
                </div>
            @endif

            {{-- Store Branding & Header (High-contrast, Large & Visible) --}}
            <div class="text-center pb-2 border-b border-dashed border-black">
                @if($showLogo)
                    <div class="flex items-center justify-center mb-1.5">
                        <img 
                            src="{{ asset('images/logo.png') }}" 
                            alt="{{ $storeName }}" 
                            class="h-12 sm:h-14 max-w-[150px] object-contain mx-auto"
                            style="filter: grayscale(100%) contrast(350%) brightness(0.65); -webkit-print-color-adjust: exact; print-color-adjust: exact;"
                        >
                    </div>
                @endif
                <h1 
                    class="{{ $is58mm ? 'text-sm sm:text-base' : 'text-base sm:text-lg' }} font-black tracking-wide uppercase text-black"
                    style="font-size: {{ $is58mm ? '14px' : '17px' }} !important; font-weight: 900 !important; color: #000000 !important; line-height: 1.2; letter-spacing: 0.5px;"
                >
                    {{ $storeName }}
                </h1>
                @if($storeTagline)
                    <p class="text-[9px] text-black font-semibold mt-0.5" style="color: #000 !important;">{{ $storeTagline }}</p>
                @endif
                @if($storeAddress)
                    <p class="text-[8.5px] text-black" style="color: #000 !important;">{{ $storeAddress }}</p>
                @endif
                @if($storePhone)
                    <p class="text-[8.5px] text-black font-bold" style="color: #000 !important;">Tel: {{ $storePhone }}</p>
                @endif
            </div>

            {{-- Transaction Meta Info (Clean, No Cashier / Channel) --}}
            <div class="py-1.5 text-[9px] sm:text-[9.5px] space-y-0.5 border-b border-dashed border-black">
                <div class="flex justify-between">
                    <span>Receipt #:</span>
                    <span class="font-bold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Date/Time:</span>
                    <span>{{ $order->created_at ? $order->created_at->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}</span>
                </div>
                @if($showCustomer && $order->customer_name)
                    <div class="flex justify-between">
                        <span>Customer:</span>
                        <span class="font-semibold truncate max-w-[130px]">{{ $order->customer_name }}</span>
                    </div>
                @endif
            </div>

            {{-- Itemized Products Table (Strict Column Widths, Never Crops on 58mm) --}}
            <div class="py-1.5 border-b border-dashed border-black">
                <table class="w-full text-[8.5px] sm:text-[9.5px] leading-tight" style="table-layout: fixed; width: 100%;">
                    <thead>
                        <tr class="text-[8px] sm:text-[8.5px] uppercase tracking-wider border-b border-black text-black">
                            <th style="width: 44%; text-align: left;" class="pb-1 font-bold">Item</th>
                            <th style="width: 12%; text-align: center;" class="pb-1 font-bold">Qty</th>
                            <th style="width: 22%; text-align: right;" class="pb-1 font-bold">Price</th>
                            <th style="width: 22%; text-align: right;" class="pb-1 font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dotted divide-slate-400">
                        @foreach($order->items as $item)
                            <tr>
                                <td style="width: 44%;" class="py-1 pr-1 font-bold align-top">
                                    <div class="leading-tight break-words">{{ $item->product_name }}</div>
                                    @if($item->sku)
                                        <div class="text-[7.5px] text-slate-700 font-mono">#{{ $item->sku }}</div>
                                    @endif
                                </td>
                                <td style="width: 12%;" class="py-1 text-center font-bold align-top">{{ $item->quantity }}</td>
                                <td style="width: 22%;" class="py-1 text-right align-top whitespace-nowrap">{{ number_format($item->unit_price, 2) }}</td>
                                <td style="width: 22%;" class="py-1 text-right font-bold align-top whitespace-nowrap">{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Financials & Payment Breakdown --}}
            <div class="py-1.5 text-[9px] sm:text-[9.5px] space-y-0.5 border-b border-dashed border-black">
                <div class="flex justify-between text-black">
                    <span>Items Subtotal:</span>
                    <span class="font-bold">RM {{ number_format($order->subtotal, 2) }}</span>
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
                <div class="pt-1 space-y-0.5 text-[8.5px] text-black">
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

            {{-- Scannable Order QR Code + Real Barcode --}}
            <div class="pt-2 text-center space-y-1">
                @if($showBarcode)
                    <div class="py-1 flex flex-col items-center justify-center">
                        {{-- High-contrast Thermal Scannable QR Code (Scan with phone to open order) --}}
                        <div id="qrcode-wrap-{{ $copyIndex }}" class="inline-block p-1 bg-white border border-black rounded-xs">
                            <div id="qr-canvas-{{ $copyIndex }}"></div>
                            <img 
                                id="qr-img-{{ $copyIndex }}"
                                src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&margin=0&data={{ urlencode($orderScanUrl) }}" 
                                alt="Order #{{ $order->order_number }}"
                                class="w-[95px] h-[95px] sm:w-[110px] sm:h-[110px] mx-auto block object-contain"
                                style="image-rendering: pixelated; -webkit-print-color-adjust: exact; print-color-adjust: exact;"
                                onerror="this.style.display='none'"
                            />
                        </div>

                        {{-- Code 128 Barcode for Counter Handheld Scanner --}}
                        <div class="w-full flex justify-center pt-1.5 pb-0.5">
                            <svg id="barcode-{{ $copyIndex }}" class="max-w-[95%] h-7"></svg>
                        </div>

                        <div class="text-[10px] font-mono font-black text-black tracking-wider leading-none mt-0.5">
                            {{ $order->order_number }}
                        </div>
                        <div class="text-[7.5px] font-bold text-black uppercase tracking-tight mt-0.5">
                            Scan with camera to open order
                        </div>
                    </div>
                @endif

                {{-- Clean Footer Message --}}
                <div class="text-[9px] font-black pt-1 text-black uppercase tracking-wide leading-tight" style="color: #000 !important; font-weight: 800 !important;">
                    {!! $footerMessage !!}
                </div>
            </div>

            {{-- Thermal Paper Auto-Cutter Margin --}}
            <div class="paper-cut-feed"></div>

        </div>
    @endfor

    <script>
        function renderReceiptCodes() {
            @for($i = 1; $i <= $copies; $i++)
                try {
                    // Render 1D Code-128 Barcode for counter laser/CCD scanner
                    if (typeof JsBarcode !== 'undefined') {
                        JsBarcode("#barcode-{{ $i }}", "{{ $order->order_number }}", {
                            format: "CODE128",
                            width: {{ $is58mm ? 1.25 : 1.65 }},
                            height: {{ $is58mm ? 26 : 34 }},
                            displayValue: false,
                            margin: 0,
                            lineColor: "#000000"
                        });
                    }
                } catch(e) {
                    console.error('Barcode render error:', e);
                }

                try {
                    // Render local client-side QR Code if QRCode library loaded
                    const qrCanvasWrap = document.getElementById("qr-canvas-{{ $i }}");
                    const qrImg = document.getElementById("qr-img-{{ $i }}");
                    if (typeof QRCode !== 'undefined' && qrCanvasWrap) {
                        new QRCode(qrCanvasWrap, {
                            text: "{{ $orderScanUrl }}",
                            width: {{ $is58mm ? 95 : 115 }},
                            height: {{ $is58mm ? 95 : 115 }},
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                        if (qrImg) qrImg.style.display = 'none';
                    }
                } catch(e) {
                    console.error('QR code render error:', e);
                }
            @endfor
        }

        function triggerThermalPrint() {
            renderReceiptCodes();
            window.focus();
            window.print();
        }

        // Auto print trigger for iframe or popup calls
        const urlParams = new URLSearchParams(window.location.search);
        window.addEventListener('load', () => {
            renderReceiptCodes();
            if (urlParams.get('print') === '1' || window.self !== window.top) {
                setTimeout(() => {
                    triggerThermalPrint();
                    if (urlParams.get('popup') === '1' && window.opener) {
                        window.onafterprint = () => {
                            setTimeout(() => window.close(), 300);
                        };
                    }
                }, 300);
            }
        });
    </script>
</body>
</html>
