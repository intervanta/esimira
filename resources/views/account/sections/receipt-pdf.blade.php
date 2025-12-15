<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            margin-bottom: 20px;
        }
        .box {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 15px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Order Receipt</h2>
        <p>Order ID: <strong>#{{ $order->order_number }}</strong></p>
    </div>

    <div class="box">
        <div class="row">
            <span>Order Date</span>
            <span>{{ $order->created_at->format('d M Y, h:i A') }}</span>
        </div>

        <div class="row">
            <span>Status</span>
            <span>
                @php
                    $statuses = [
                        0 => 'Pending',
                        1 => 'Confirmed',
                        2 => 'Processing',
                        3 => 'Completed',
                        4 => 'Cancelled',
                        5 => 'Refunded',
                        6 => 'Failed',
                    ];
                @endphp
                {{ $statuses[$order->status] ?? 'Unknown' }}
            </span>
        </div>
    </div>

    <div class="box">
        <div class="row bold">
            <span>Payment Method</span>
            <span>{{ strtoupper($order->latestTransaction->gateway ?? '-') }}</span>
        </div>

        <div class="row bold">
            <span>Total Amount</span>
            <span>
                {{ number_format($order->latestTransaction->total_amount ?? 0, 2) }}
            </span>
        </div>
    </div>

    <p style="margin-top:30px;">
        Thank you for your purchase.
    </p>

</body>
</html>
