<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 24px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background: #0f172a; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0 0 6px 0; font-size: 24px; }
        .header p { margin: 0; color: #94a3b8; font-size: 14px; }
        .content { padding: 24px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; background: #fef3c7; color: #92400e; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th { text-align: left; padding: 10px; background: #f8fafc; font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        td { padding: 12px 10px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        .totals td { padding: 6px 10px; border-bottom: none; }
        .totals .grand-total { font-size: 16px; font-weight: bold; color: #0f172a; border-top: 2px solid #e2e8f0; }
        .footer { background: #f8fafc; padding: 16px 24px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; background: #0284c7; color: #ffffff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>{{ setting('site_name', 'Bulk Mart') }}</h1>
            <p>Thank you for your bulk order!</p>
        </div>
        <div class="content">
            <p>Hi <strong>{{ $order->customer_name }}</strong>,</p>
            <p>We have successfully received your order <strong>#{{ $order->order_number }}</strong>. Your payment proof is under review and will be verified shortly by our team.</p>

            <p><span class="badge">Status: {{ $order->status_label }}</span></p>

            <h3>Order Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong><br>
                                <small style="color: #64748b;">{{ $item->pack_size }}</small>
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">{{ format_currency($item->unit_price) }}</td>
                            <td style="text-align: right;">{{ format_currency($item->line_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totals">
                <tr>
                    <td colspan="3" style="text-align: right;">Subtotal:</td>
                    <td style="text-align: right;"><strong>{{ format_currency($order->subtotal) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Delivery Charges:</td>
                    <td style="text-align: right;"><strong>{{ $order->delivery_charge > 0 ? format_currency($order->delivery_charge) : 'FREE' }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Floor Charges (Floor {{ $order->floor }}):</td>
                    <td style="text-align: right;"><strong>{{ $order->floor_charge > 0 ? format_currency($order->floor_charge) : 'FREE' }}</strong></td>
                </tr>
                <tr class="grand-total">
                    <td colspan="3" style="text-align: right;">Grand Total:</td>
                    <td style="text-align: right; color: #0284c7;">{{ format_currency($order->grand_total) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; color: #16a34a;">Advance Paid ({{ $order->advance_percentage }}%):</td>
                    <td style="text-align: right; color: #16a34a;"><strong>{{ format_currency($order->advance_amount) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; color: #dc2626;">Balance on Delivery:</td>
                    <td style="text-align: right; color: #dc2626;"><strong>{{ format_currency($order->balance_amount) }}</strong></td>
                </tr>
            </table>

            <h3>Delivery Address</h3>
            <p style="background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 14px; border: 1px solid #e2e8f0;">
                {{ $order->delivery_address }}<br>
                {{ $order->delivery_city }} (Floor {{ $order->floor }})<br>
                @if($order->landmark) Landmark: {{ $order->landmark }}<br> @endif
                Phone: {{ $order->customer_phone }}
            </p>

            <div style="text-align: center;">
                <a href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" class="btn">Track Order Status</a>
            </div>
        </div>
        <div class="footer">
            <p>{{ setting('site_name', 'N&J Mini Mart') }} &bull; {{ setting('store_address', 'Puchong, Selangor') }}</p>
            <p>Questions? Call us at {{ setting('store_phone', '+60 12-345 6789') }} or email {{ setting('store_email', 'support@bulkmart.my') }}</p>
        </div>
    </div>
</body>
</html>

