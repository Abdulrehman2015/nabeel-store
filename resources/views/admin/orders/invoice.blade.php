<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; line-height: 1.4; margin: 0; padding: 20px; }
        .header-table { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #0f172a; margin: 0; }
        .tagline { font-size: 10px; color: #64748b; margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-meta h2 { margin: 0; font-size: 18px; color: #0284c7; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-col { width: 50%; vertical-align: top; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; font-size: 11px; }
        .info-box strong { font-size: 12px; color: #0f172a; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #0f172a; color: #ffffff; text-align: left; padding: 8px 10px; font-size: 11px; text-transform: uppercase; }
        .items-table td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .items-table tr:nth-child(even) { background-color: #f8fafc; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 5px 10px; font-size: 11px; }
        .grand-total { font-size: 14px; font-weight: bold; background: #f1f5f9; color: #0f172a; border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; }
        .advance-paid { font-weight: bold; color: #166534; background: #f0fdf4; }
        .balance-due { font-size: 13px; font-weight: bold; color: #991b1b; background: #fef2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 15px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; background: #e2e8f0; color: #334155; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">{{ setting('site_name', '99 Bulk Sales') }}</h1>
                <div class="tagline">Wholesale Carton Groceries & Household Essentials</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                    {{ setting('store_address') }}<br>
                    Phone: {{ setting('store_phone') }} &bull; Email: {{ setting('store_email') }}<br>
                    SSM Reg: {{ setting('company_registration_no', '202401019988') }}
                </div>
            </td>
            <td class="invoice-meta">
                <h2>TAX INVOICE</h2>
                <div style="margin-top: 4px; font-size: 11px;">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                    <strong>Status:</strong> <span class="badge">{{ $order->status_label }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- Bill To & Delivery Destination --}}
    <table class="info-table">
        <tr>
            <td class="info-col" style="padding-right: 10px;">
                <div class="info-box">
                    <strong style="color: #0284c7;">DELIVERY DESTINATION</strong><br>
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->delivery_address }}<br>
                    {{ $order->delivery_city }}<br>
                    <strong>Floor Level: Floor {{ $order->floor }}</strong><br>
                    @if($order->landmark) Landmark: {{ $order->landmark }}<br> @endif
                    Phone: {{ $order->customer_phone }}
                </div>
            </td>
            <td class="info-col" style="padding-left: 10px;">
                <div class="info-box">
                    <strong style="color: #0284c7;">PAYMENT & LOGISTICS</strong><br>
                    <strong>Payment Method:</strong> DuitNow QR (Advance)<br>
                    <strong>Transaction Ref:</strong> {{ $order->payment_reference }}<br>
                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}<br>
                    <strong>Delivery Zone:</strong> {{ strtoupper($order->delivery_zone) }} ({{ $order->distance_km }} km)<br>
                    @if($order->order_notes) Order Notes: {{ $order->order_notes }}<br> @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Line Items --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Carton Product Description</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 12%; text-align: right;">Unit Price</th>
                <th style="width: 8%; text-align: center;">Qty</th>
                <th style="width: 15%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        <small style="color: #64748b;">{{ $item->pack_size }}</small>
                    </td>
                    <td>{{ $item->sku }}</td>
                    <td style="text-align: right;">{{ format_currency($item->unit_price) }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ format_currency($item->line_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Financial Totals Table --}}
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top; font-size: 10px; color: #64748b; padding-right: 20px;">
                <strong>Payment Instructions:</strong><br>
                Remaining balance is payable upon delivery directly to our driver via Cash or instant DuitNow transfer.<br><br>
                <strong>Return Policy:</strong><br>
                Defective or damaged outer cartons must be inspected at the point of delivery.
            </td>
            <td style="width: 50%; vertical-align: top;">
                <table class="totals-table">
                    <tr>
                        <td style="text-align: right;">Subtotal ({{ $order->items->sum('quantity') }} Cartons):</td>
                        <td style="text-align: right; font-weight: bold; width: 35%;">{{ format_currency($order->subtotal) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Delivery Charges ({{ $order->distance_km }} km):</td>
                        <td style="text-align: right; font-weight: bold;">{{ $order->delivery_charge > 0 ? format_currency($order->delivery_charge) : 'FREE' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Floor Handling (Floor {{ $order->floor }}):</td>
                        <td style="text-align: right; font-weight: bold;">{{ $order->floor_charge > 0 ? format_currency($order->floor_charge) : 'FREE' }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td style="text-align: right;">Grand Total:</td>
                        <td style="text-align: right;">{{ format_currency($order->grand_total) }}</td>
                    </tr>
                    <tr class="advance-paid">
                        <td style="text-align: right;">Advance Paid ({{ $order->advance_percentage }}%):</td>
                        <td style="text-align: right;">{{ format_currency($order->advance_amount) }}</td>
                    </tr>
                    <tr class="balance-due">
                        <td style="text-align: right;">Balance Due on Delivery:</td>
                        <td style="text-align: right;">{{ format_currency($order->balance_amount) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Thank you for choosing {{ setting('site_name', '99 Bulk Sales') }} for your bulk grocery needs.<br>
        This document serves as an official computer-generated wholesale tax invoice.
    </div>

</body>
</html>
