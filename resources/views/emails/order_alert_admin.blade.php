<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Alert</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f1f5f9; padding: 20px; color: #334155; }
        .card { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 24px; }
        .alert-bar { background: #fef08a; color: #854d0e; padding: 12px; border-radius: 6px; font-weight: bold; margin-bottom: 20px; }
        .btn { display: inline-block; background: #2563eb; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="alert-bar">
            🚨 New Order Received: #{{ $order->order_number }}
        </div>
        <p>A new bulk order has been submitted with advance payment proof requiring verification.</p>
        
        <ul>
            <li><strong>Customer:</strong> {{ $order->customer_name }}</li>
            <li><strong>Phone:</strong> {{ $order->customer_phone }}</li>
            <li><strong>Email:</strong> {{ $order->customer_email }}</li>
            <li><strong>Total Amount:</strong> {{ format_currency($order->grand_total) }}</li>
            <li><strong>Advance Paid:</strong> {{ format_currency($order->advance_amount) }}</li>
            <li><strong>Transaction Reference:</strong> <code>{{ $order->payment_reference }}</code></li>
            <li><strong>Delivery:</strong> Floor {{ $order->floor }}, {{ $order->delivery_city }} ({{ $order->distance_km }} km, Zone: {{ strtoupper($order->delivery_zone) }})</li>
        </ul>

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/admin/orders/' . $order->id) }}" class="btn">Review Order & Verify Payment</a>
        </p>
    </div>
</body>
</html>

