@extends('layouts.app')

{{-- SEO --}}
@section('title', $order->bundle->name . ' eSIM Plan – Esimira')

@section('meta_description',
    $order->bundle->description
        ?: 'Discover flexible eSIM plans for ' . $order->bundle->name . ' with Esimira.'
)

@section('meta_keywords',
    'esim ' . $order->bundle->name . ', ' . $order->bundle->name . ' data plan, travel data, esimira'
)

@section('content')

<div class="pt-32 pb-16 px-4 min-h-screen">
    <div class="order-wrapper">
        <h2 class="page-title">Thank you for your order</h2>

        {{-- ORDER CARD --}}
        <div class="order-card">
            <p class="email-text">
                We've sent a confirmation receipt to:<br>
                <strong>{{ auth()->user()->email }}</strong>
            </p>

            <div class="order-row">
                <span>Order ID</span>
                <strong>{{ $order->order_number }}</strong>
            </div>

            <div class="order-row">
                <span>Payment method</span>
                <strong>{{ $order->latestTransaction->gateway ?? '—' }}</strong>
            </div>

            <div class="order-row total">
                <span>Total</span>
                <strong 
                    class="plan-price" 
                    data-price="{{ $order->latestTransaction->total_amount }}"
                >
                    ₹{{ number_format($order->latestTransaction->total_amount, 2) }}
                </strong>
            </div>
            @if($order->walletTransaction)
                <div class="reward-box">
                    🎉 You've earned Mira wallet credit from this purchase:
                    <strong 
                        class="plan-price wallet-reward" 
                        data-price="{{ $order->walletTransaction->amount }}"
                    >
                        ₹{{ number_format($order->walletTransaction->amount, 2) }}
                    </strong>
                </div>
            @endif
        </div>

        {{-- NEXT STEPS --}}
        <div class="next-steps">
            <h4>You've got your eSIM. What next?</h4>

            <details>
                <summary>When to install your eSIM</summary>
                <p>Install your eSIM just before or upon arrival at your destination.</p>
            </details>

            <details>
                <summary>How to avoid roaming charges</summary>
                <p>Turn off data roaming on your primary SIM and use only the eSIM.</p>
            </details>
        </div>

        {{-- ESIM DETAILS --}}
        <div class="esim-card flex items-center gap-4 p-4 rounded-2xl bg-white shadow-sm">

            {{-- BUNDLE IMAGE --}}
            <div class="bundle-image w-14 h-14 rounded-xl overflow-hidden bg-gray-100">
                <img
                    src="{{ $order->bundle->image 
                            ? asset('storage/' . $order->bundle->image) 
                            : asset('assets/images/default-esim.png') }}"
                    alt="Bundle Image"
                    class="w-full h-full object-cover"
                >
            </div>

            {{-- ESIM INFO --}}
            <div class="esim-info flex-1">
                <h4 class="text-base font-semibold text-gray-900">
                    {{ $order->bundle->name ?? 'eSIM Plan' }}
                </h4>

                <p class="text-sm text-gray-500">
                    {{ $order->bundle->provider ?? 'Burj Mobile' }}
                </p>

                <div class="esim-meta mt-1">
                    <span class="inline-block text-xs px-2 py-1 rounded-full bg-[#FBF7F2] text-gray-700">
                        {{ $order->refill->title ?? '1 GB' }}
                    </span>
                </div>
            </div>

        </div>

        <a href="{{ route('my-esims') }}" class="manage-btn">
            Manage my eSIM
        </a>
    </div>
</div>

@endsection

<style>
body {
    background: #fbf7f2;
    padding-top: 0 !important;
}

.order-wrapper {
    max-width: 720px;
    margin: 0 auto;
    padding: 0 16px;
}

.page-title {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 40px;
    padding-top: 30px;
    color: #333;
}

.order-card {
    background: #fff;
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.email-text {
    color: #555;
    font-size: 15px;
    margin-bottom: 24px;
    line-height: 1.5;
}

.order-row {
    display: flex;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid #eee;
    font-size: 15px;
    color: #444;
}

.order-row.total {
    font-size: 18px;
    border-bottom: none;
    padding-top: 18px;
    color: #222;
    font-weight: 600;
}

.reward-box {
    background: #fff2cc;
    border-radius: 12px;
    padding: 16px;
    margin-top: 20px;
    font-size: 14px;
    color: #333;
    border: 1px solid #ffd966;
}

.next-steps {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    margin: 30px 0;
    border: 1px solid #ef7f50;
}

.next-steps h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 18px;
    color: #1a5632;
}

details {
    background: white;
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 12px;
    border: 1px solid #d4e8db;
}

summary {
    font-weight: 500;
    color: #2e7d32;
    cursor: pointer;
    font-size: 15px;
}

details p {
    margin-top: 10px;
    color: #555;
    font-size: 14px;
    line-height: 1.5;
}

.esim-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 30px 0;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    border: 1px solid #eee;
}

.esim-card h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #222;
}

.provider {
    color: #777;
    font-size: 14px;
    margin-bottom: 8px;
}

.esim-meta span {
    background: #f0f7ff;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    color: #1a56db;
    font-weight: 500;
}

.qr-image {
    width: 100px;
    height: 100px;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 8px;
    background: white;
}

.manage-btn {
    display: block;
    text-align: center;
    margin-top: 30px;
    padding: 16px;
    background: #ef7f50;
    color: #fff;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 16px;
    border: none;
    cursor: pointer;
    width: 100%;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

.manage-btn:hover {
    background: #e66a3a;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(239, 127, 80, 0.2);
}
.wallet-reward {
    font-size: 1rem; /* small text */
    font-weight: 700;  /* bold */
    color: #ff6600;    /* highlight color */
    margin-left: 0.25rem;
}


</style>