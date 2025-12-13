@extends('layouts.app')
<style>
    .order-confirm-box {
        max-width: 700px;
        margin: 60px auto 60px auto;
        /* top auto bottom */
        background: #fff;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 6px 26px rgba(0, 0, 0, 0.08);
    }

    .order-confirm-header {
        text-align: center;
        margin-bottom: 30px;
        padding-top: 20px;
        /* Push success icon downward */
    }


    .success-icon {
        width: 70px;
        margin-bottom: 16px;
    }

    .confirm-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .confirm-subtitle {
        font-size: 15px;
        color: #6b7280;
    }

    .order-details-card,
    .activation-card,
    .qr-card {
        background: #fafafa;
        padding: 22px 24px;
        border-radius: 14px;
        border: 1px solid #eee;
        margin-bottom: 24px;
    }

    .details-title {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 16px;
    }

    .details-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #4b5563;
        font-size: 15px;
    }

    .details-row strong {
        color: #111827;
    }

    .status-paid {
        color: #10b981;
        font-weight: 700;
    }

    .qr-wrapper {
        padding: 20px;
        background: #fff;
        display: inline-block;
        border-radius: 12px;
        border: 1px solid #ddd;
    }

    .qr-image {
        width: 200px;
        height: 200px;
    }

    .qr-note {
        margin-top: 12px;
        font-size: 14px;
        color: #6b7280;
        text-align: center;
    }

    .btn-manage-esim {
        display: block;
        text-align: center;
        margin-top: 10px;
        background: #ef7f50;
        color: white;
        padding: 14px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
    }

    .btn-manage-esim:hover {
        background: #d96d45;
    }

    .success-icon {
        width: 60px;
        height: 60px;
        object-fit: contain;
        display: block;
        margin: 0 auto 16px auto;
    }
</style>

@section('content')
    <div class="order-confirm-box">

        {{-- SUCCESS HEADER --}}
        <div class="order-confirm-header">
            <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" class="success-icon" alt="Success">

            <h2 class="confirm-title">Order Successful!</h2>
            <p class="confirm-subtitle">Thank you, {{ auth()->user()->name }} — your order has been placed.</p>
        </div>

        {{-- ORDER SUMMARY --}}
        <div class="order-details-card">
            <h3 class="details-title">Order Summary</h3>

            <div class="details-row">
                <span>Order Number</span>
                <strong>{{ $order->order_number }}</strong>
            </div>

            <div class="details-row">
                <span>Amount Paid</span>
                <strong>₹{{ number_format($order->amount, 2) }}</strong>
            </div>

            <div class="details-row">
                <span>Payment Status</span>
                <strong class="status-paid">Paid</strong>
            </div>

            <div class="details-row">
                <span>Completed On</span>
                <strong>{{ $order->completed_at->format('d M Y, h:i A') }}</strong>
            </div>
        </div>

        {{-- ACTIVATION DETAILS --}}
        <div class="activation-card">
            <h3 class="details-title">eSIM Details</h3>

            <div class="details-row">
                <span>ICCID</span>
                <strong>{{ $order->iccid ?? 'Pending' }}</strong>
            </div>

            <div class="details-row">
                <span>SM-DP+ Address</span>
                <strong>{{ $order->smdp_plus ?? 'Pending' }}</strong>
            </div>

            <div class="details-row">
                <span>Activation Code</span>
                <strong>{{ $order->activation_code ?? 'Pending' }}</strong>
            </div>
        </div>

        {{-- QR CODE BLOCK --}}
        @if (!empty($order->qr_code_url))
            <div class="qr-card">
                <h3 class="details-title">Scan Your eSIM QR Code</h3>

                <div class="qr-wrapper">
                    <img src="{{ $order->qr_code_url }}" class="qr-image" alt="eSIM QR Code">
                </div>

                <p class="qr-note">
                    Scan this QR code using your phone’s camera to install your eSIM instantly.
                    <br>Keep this code safe and do not share it.
                </p>
            </div>
        @endif

        <a href="{{ route('dashboard.index') }}" class="btn-manage-esim">Manage My eSIM</a>

    </div>
@endsection
