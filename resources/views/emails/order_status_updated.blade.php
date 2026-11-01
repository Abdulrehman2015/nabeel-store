<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Status Update</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; padding: 24px; color: #1e293b; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 24px; }
        .header { text-align: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px; }
        .status-box { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: 8px; text-align: center; margin: 20px 0; font-size: 18px; font-weight: bold; }
        .btn { display: inline-block; background: #0284c7; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>{{ setting('site_name', 'Bulk Mart') }}</h2>
            <p>Update for Order #{{ $order->order_number }}</p>
        </div>
        <p>Hello <strong>{{ $order->customer_name }}</strong>,</p>
        <p>The status of your order has been updated:</p>

        <div class="status-box">
            {{ $order->status_label }}
        </div>

        @if($order->order_status === 'payment_rejected' && $order->payment_rejection_reason)
            <p style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 6px; border: 1px solid #fecaca;">
                <strong>Reason:</strong> {{ $order->payment_rejection_reason }}
            </p>
        @endif

        <p>Balance to pay on delivery: <strong>{{ format_currency($order->balance_amount) }}</strong></p>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" class="btn">View Live Tracking</a>
        </div>
    </div>
</body>
</html>
