@extends('layouts.app')

@section('title', 'Checkout - ' . $bundle->name . ' eSIM Plan')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div
            class="flex flex-col justify-start items-center h-[68vh] w-full bg-[url('../assets/images/checkout-bg.png')] bg-cover bg-center relative">
            <section id="hero"
                class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
                <div class="container hero-content">
                    <h1>Checkout</h1>
                </div>
            </section>
        </div>

        <main class="container max-w-6xl mx-auto px-4 py-10 -mt-16 relative z-20">
            <h2 class="text-3xl font-bold mb-8 text-center lg:text-left">
                My <span class="text-primary-checkout">Cart</span>
            </h2>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Side -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Plan Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold mb-4">Your Plan</h3>
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('assets/images/365_406.svg') }}" alt="Flag"
                                class="w-16 h-12 rounded-lg shadow">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $bundle->name }}</p>
                                <p class="text-lg font-bold text-primary-checkout">
                                    {{ $refill->amount_mb >= 1024 ? number_format($refill->amount_mb / 1024, 1) . ' GB' : $refill->amount_mb . ' MB' }}
                                    <span class="text-gray-600 font-normal">• {{ $refill->amount_days }} Days</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="plan-price text-xl font-bold text-gray-900"
                                    data-price="{{ $priceData['plan_amount'] }}">
                                    {{ $currencySign }}{{ number_format($priceData['plan_amount'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Declaration -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" id="declarationAgree"
                                class="mt-1 w-5 h-5 text-primary-checkout rounded focus:ring-primary-checkout">
                            <span class="text-gray-700">I confirm my device is <strong>eSIM-compatible</strong> and
                                <strong>unlocked</strong>. I agree to the <a href="#"
                                    class="text-primary-checkout underline">Terms</a> & <a href="#"
                                    class="text-primary-checkout underline">Privacy Policy</a>.</span>
                        </label>
                    </div>

                    <!-- Payment Methods -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold mb-6">Choose Payment Method</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <label
                                class="payment-option cursor-pointer border-2 border-gray-200 rounded-xl p-6 hover:border-primary-checkout hover:bg-blue-50 transition group"
                                data-method="razorpay">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100">
                                            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M3 10h18M7 15h10m-9 4h8a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold">Card / UPI / NetBanking</p>
                                            <p class="text-xs text-gray-500">Visa, MasterCard, Amex, Rupay</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">via Razorpay</span>
                                </div>
                                <input type="radio" name="payment-method" value="razorpay" class="hidden">
                            </label>

                            <label
                                class="payment-option cursor-pointer border-2 border-gray-200 rounded-xl p-6 hover:border-primary-checkout hover:bg-blue-50 transition group"
                                data-method="paypal">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                                            P</div>
                                        <p class="font-semibold">PayPal</p>
                                    </div>
                                </div>
                                <input type="radio" name="payment-method" value="paypal" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Order Summary -->
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6 space-y-6">
                        <h2 class="text-2xl font-bold">Order Summary</h2>

                        <div class="space-y-4">
                            <!-- Plan Price -->
                            <div class="flex justify-between">
                                <span>Plan Price</span>
                                <span class="plan-price" data-price="{{ $priceData['plan_amount'] }}">
                                    {{ $currencySign }}{{ number_format($priceData['plan_amount'], 2) }}
                                </span>
                            </div>

                            <!-- Convenience Fee -->
                            <div class="flex justify-between">
                                <div>
                                    <span>Convenience Fee</span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $priceData['description'] ?? '3% + 18% GST' }}
                                    </div>
                                </div>
                                <span class="convenience-fee plan-price"
                                    data-price="{{ $priceData['total_convenience_fee'] }}">
                                    {{ $currencySign }}{{ number_format($priceData['total_convenience_fee'], 2) }}
                                </span>
                            </div>

                            <!-- MiraVault Discount -->
                            <div id="miravaultDiscountRow" class="flex justify-between text-green-600 hidden">
                                <span>MiraVault Applied</span>
                                <span>-<span id="miravaultDiscountAmount"
                                        class="plan-price">{{ $currencySign }}0.00</span></span>
                            </div>

                            <!-- Coupon Discount -->
                            <div id="couponDiscountRow" class="hidden">
                                <div class="flex justify-between text-green-600">
                                    <div class="flex items-center gap-2">
                                        <span>Promo Code</span>
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium"
                                            id="appliedCouponCode"></span>
                                        <button type="button" id="removeCouponBtn"
                                            class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                    </div>
                                    <span>-<span id="couponDiscountAmount"
                                            class="plan-price">{{ $currencySign }}0.00</span></span>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="border-t pt-5">
                                <div class="flex justify-between text-xl font-bold">
                                    <span>Total Amount</span>
                                    <span id="totalAmount" class="plan-price"
                                        data-price="{{ $priceData['final_amount'] }}">
                                        {{ $currencySign }}{{ number_format($priceData['final_amount'], 2) }}
                                    </span>
                                </div>
                                <div class="mt-3 flex justify-between text-2xl font-bold text-primary-checkout">
                                    <span>Amount to Pay</span>
                                    <span id="amountToPay" class="final-price plan-price"
                                        data-price="{{ $priceData['final_amount'] }}">
                                        {{ $currencySign }}{{ number_format($priceData['final_amount'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- MiraVault & Coupons Section -->
                        <div class="border-t pt-6">
                            <h3 class="font-semibold text-gray-800 mb-4">MiraVault & Coupons</h3>

                            <div class="space-y-3">
                                <!-- MiraVault Tile -->
                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors cursor-pointer group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-xs border border-gray-200">
                                                <svg class="w-5 h-5 text-gray-700" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm">MiraVault Balance</p>
                                                <p class="text-xs text-gray-600">
                                                    {{ $currencySign }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                                                    available</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="use-miravault-btn bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:border-primary-checkout hover:text-primary-checkout transition-colors">
                                            Use Balance
                                        </button>
                                    </div>
                                </div>

                                <!-- Coupons Tile -->
                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors cursor-pointer group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-xs border border-gray-200">
                                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm">Promo Code</p>
                                                <p class="text-xs text-gray-600">Apply coupon for discounts</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="use-coupon-btn bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:border-primary-checkout hover:text-primary-checkout transition-colors">
                                            Apply Code
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Applied Discounts -->
                            <div class="space-y-2 mt-4">
                                <!-- MiraVault Applied -->
                                <div id="miravaultApplied"
                                    class="bg-blue-50 border border-blue-200 rounded-lg p-3 hidden">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-blue-800">MiraVault: {{ $currencySign }}<span
                                                    id="appliedMiravaultAmount">0.00</span> applied</span>
                                        </div>
                                        <button type="button" id="removeMiravaultBtn"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <!-- Coupon Applied -->
                                <div id="couponApplied" class="bg-green-50 border border-green-200 rounded-lg p-3 hidden">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <span class="text-sm text-green-800">Promo: <span id="appliedCouponCodeText"
                                                    class="font-medium"></span> applied</span>
                                        </div>
                                        <button type="button" id="removeCouponAppliedBtn"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PAY NOW BUTTON -->
                        <button id="payNowBtn" disabled
                            class="btn btn-primary btn-full w-full py-5 text-xl font-bold shadow-lg mt-6 hover:shadow-xl transition disabled:opacity-60 disabled:cursor-not-allowed">
                            PAY NOW
                        </button>
                    </div>
                </aside>
            </div>
        </main>

        <!-- MiraVault Modal -->
        <div id="miravaultModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Use MiraVault Balance</h3>
                    <button type="button" id="closeMiravaultModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 text-sm">Available Balance</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $currencySign }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="miravaultAmount" class="block text-sm font-medium text-gray-700 mb-3">
                            Amount to use
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">{{ $currencySign }}</span>
                            <input type="number" id="miravaultAmount" step="0.01" min="0"
                                max="{{ min(auth()->user()->wallet_balance ?? 0, $priceData['final_amount']) }}"
                                placeholder="0.00"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Maximum: {{ $currencySign }}<span
                                id="maxUsable">{{ min(auth()->user()->wallet_balance ?? 0, $priceData['final_amount']) }}</span>
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="cancelMiravault"
                            class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="button" id="applyMiravault"
                            class="flex-1 py-3 px-4 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                            Apply Amount
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coupon Modal -->
        <div id="couponModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Apply Promo Code</h3>
                    <button type="button" id="closeCouponModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <label for="couponCodeInput" class="block text-sm font-medium text-gray-700 mb-3">
                            Enter promo code
                        </label>
                        <input type="text" id="couponCodeInput" placeholder="e.g. ESIM50"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase">
                    </div>

                    <div id="couponMessage" class="mb-4 p-3 rounded-lg hidden text-sm"></div>

                    <div class="flex gap-3">
                        <button type="button" id="cancelCoupon"
                            class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="button" id="applyCoupon"
                            class="flex-1 py-3 px-4 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                            Apply Code
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Notification -->
        <div id="globalNotification"
            class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-50 hidden transform transition-all duration-300 ease-in-out">
            <div class="flex items-center gap-3">
                <div id="notificationIcon" class="w-8 h-8 rounded-full flex items-center justify-center">
                    <!-- Icon will be set dynamically -->
                </div>
                <div>
                    <p id="notificationTitle" class="text-sm font-medium text-gray-900"></p>
                    <p id="notificationMessage" class="text-xs text-gray-500"></p>
                </div>
                <button type="button" id="closeNotification" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Razorpay Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Security: CSRF Token for all requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Elements
            const paymentOptions = document.querySelectorAll('.payment-option');
            const payBtn = document.getElementById('payNowBtn');
            const declaration = document.getElementById('declarationAgree');
            const razorpayOption = document.querySelector('[data-method="razorpay"]');

            // MiraVault Elements
            const useMiravaultBtns = document.querySelectorAll('.use-miravault-btn');
            const miravaultModal = document.getElementById('miravaultModal');
            const closeMiravaultModal = document.getElementById('closeMiravaultModal');
            const cancelMiravault = document.getElementById('cancelMiravault');
            const applyMiravault = document.getElementById('applyMiravault');
            const miravaultAmount = document.getElementById('miravaultAmount');
            const maxSpan = document.getElementById('maxUsable');
            const appliedMiravaultRow = document.getElementById('miravaultApplied');
            const appliedMiravaultAmount = document.getElementById('appliedMiravaultAmount');
            const removeMiravaultBtn = document.getElementById('removeMiravaultBtn');

            // Coupon Elements
            const useCouponBtns = document.querySelectorAll('.use-coupon-btn');
            const couponModal = document.getElementById('couponModal');
            const closeCouponModal = document.getElementById('closeCouponModal');
            const cancelCoupon = document.getElementById('cancelCoupon');
            const applyCouponBtn = document.getElementById('applyCoupon');
            const couponCodeInput = document.getElementById('couponCodeInput');
            const couponMessage = document.getElementById('couponMessage');
            const couponApplied = document.getElementById('couponApplied');
            const appliedCouponCodeText = document.getElementById('appliedCouponCodeText');
            const removeCouponBtn = document.getElementById('removeCouponBtn');
            const removeCouponAppliedBtn = document.getElementById('removeCouponAppliedBtn');

            // Notification Elements
            const globalNotification = document.getElementById('globalNotification');
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            const closeNotification = document.getElementById('closeNotification');

            // Price Elements
            const planPrice = {{ $priceData['plan_amount'] }};
            const convenienceFee = {{ $priceData['total_convenience_fee'] }};
            const originalTotal = {{ $priceData['final_amount'] }};
            const currencySign = '{{ $currencySign }}';
            const refillId = {{ $refill->id }};
            const bundleId = {{ $bundle->id }};

            // Security: Rate limiting variables
            let lastRequestTime = 0;
            const REQUEST_DELAY = 1000; // 1 second between requests

            // State
            let selectedPaymentMethod = '';
            let miravaultUsed = 0;
            let couponDiscount = 0;
            let appliedCouponCode = '';

            fetch("https://ipapi.co/json/")
                .then(response => response.json())
                .then(data => {
                    console.log("User Country:", data.country);

                    let forcedCountry = data.country;

                    let country = forcedCountry;

                    if (country !== "IN") {
                        razorpayOption.style.display = "none";

                        document.querySelector('[value="paypal"]').checked = true;
                    }
                })
                .catch(error => {
                    console.error("Geo detection failed:", error);

                    // Fallback: Use browser locale
                    const userLocale = navigator.language || navigator.userLanguage;

                    if (!userLocale.startsWith("en-IN")) {
                        razorpayOption.style.display = "none";
                        document.querySelector('[value="paypal"]').checked = true;
                    }
                });

            // Utility Functions
            function showModal(modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function hideModal(modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            function formatPrice(amount) {
                return currencySign + amount.toFixed(2);
            }

            function calculateFinalAmount() {
                const final = Math.max(0, originalTotal - couponDiscount - miravaultUsed);
                return final;
            }

            // Enhanced Notification System
            function showNotification(title, message, type = 'info') {
                const icons = {
                    success: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>`,
                    error: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>`,
                    info: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
                    warning: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>`
                };

                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    info: 'bg-blue-500',
                    warning: 'bg-yellow-500'
                };

                notificationIcon.innerHTML = icons[type] || icons.info;
                notificationIcon.className =
                    `w-8 h-8 rounded-full flex items-center justify-center ${colors[type] || colors.info}`;
                notificationTitle.textContent = title;
                notificationMessage.textContent = message;

                globalNotification.classList.remove('hidden');
                globalNotification.classList.add('translate-y-0', 'opacity-100');
                globalNotification.classList.remove('translate-y-4', 'opacity-0');

                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideNotification();
                }, 5000);
            }

            function hideNotification() {
                globalNotification.classList.add('translate-y-4', 'opacity-0');
                globalNotification.classList.remove('translate-y-0', 'opacity-100');
                setTimeout(() => {
                    globalNotification.classList.add('hidden');
                }, 300);
            }

            function updateUI() {
                const finalAmount = calculateFinalAmount();

                // Update amount to pay
                document.getElementById('amountToPay').textContent = formatPrice(finalAmount);

                // Update discount displays
                document.getElementById('miravaultDiscountAmount').textContent = formatPrice(miravaultUsed);
                document.getElementById('couponDiscountAmount').textContent = formatPrice(couponDiscount);

                // Show/hide discount rows
                document.getElementById('miravaultDiscountRow').classList.toggle('hidden', miravaultUsed === 0);
                document.getElementById('couponDiscountRow').classList.toggle('hidden', couponDiscount === 0);

                // Update applied amounts display
                appliedMiravaultAmount.textContent = miravaultUsed.toFixed(2);
                appliedMiravaultRow.classList.toggle('hidden', miravaultUsed === 0);
                couponApplied.classList.toggle('hidden', couponDiscount === 0);

                if (appliedCouponCode) {
                    document.getElementById('appliedCouponCode').textContent = appliedCouponCode;
                    appliedCouponCodeText.textContent = appliedCouponCode;
                }

                // Update button state
                const isEnabled = selectedPaymentMethod && declaration.checked && finalAmount >= 0;
                payBtn.disabled = !isEnabled;
                payBtn.textContent = finalAmount === 0 ? 'COMPLETE ORDER' : 'PAY NOW';
            }

            // Security: Rate limiting function
            function canMakeRequest() {
                const now = Date.now();
                if (now - lastRequestTime < REQUEST_DELAY) {
                    showNotification('Slow Down', 'Please wait before making another request', 'warning');
                    return false;
                }
                lastRequestTime = now;
                return true;
            }

            // Payment Method Selection
            paymentOptions.forEach(option => {
                option.addEventListener('click', () => {
                    paymentOptions.forEach(opt => {
                        opt.classList.remove('border-primary-checkout', 'bg-blue-50');
                    });
                    option.classList.add('border-primary-checkout', 'bg-blue-50');
                    selectedPaymentMethod = option.dataset.method;
                    updateUI();
                });
            });

            // Declaration Checkbox
            declaration.addEventListener('change', updateUI);

            // MiraVault Functionality
            useMiravaultBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const maxUsable = Math.min({{ auth()->user()->wallet_balance ?? 0 }},
                        calculateFinalAmount() + miravaultUsed);
                    maxSpan.textContent = maxUsable.toFixed(2);
                    miravaultAmount.value = miravaultUsed || '';
                    miravaultAmount.max = maxUsable;
                    showModal(miravaultModal);
                });
            });

            [closeMiravaultModal, cancelMiravault].forEach(btn => {
                btn.addEventListener('click', () => {
                    hideModal(miravaultModal);
                });
            });

            applyMiravault.addEventListener('click', () => {
                let amount = parseFloat(miravaultAmount.value) || 0;
                const max = parseFloat(miravaultAmount.max);

                if (amount > max) {
                    showNotification('Maximum Limit', `Maximum usable amount is ${formatPrice(max)}`,
                        'warning');
                    return;
                }

                if (amount < 0) amount = 0;

                miravaultUsed = amount;
                hideModal(miravaultModal);
                showNotification('Balance Applied',
                    `${formatPrice(amount)} has been applied from your MiraVault balance`, 'success');
                updateUI();
            });

            removeMiravaultBtn.addEventListener('click', () => {
                miravaultUsed = 0;
                showNotification('Balance Removed', 'MiraVault balance application has been removed',
                    'info');
                updateUI();
            });

            // Coupon Functionality
            useCouponBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    couponCodeInput.value = '';
                    couponMessage.classList.add('hidden');
                    showModal(couponModal);
                });
            });

            [closeCouponModal, cancelCoupon].forEach(btn => {
                btn.addEventListener('click', () => {
                    hideModal(couponModal);
                });
            });

            applyCouponBtn.addEventListener('click', async () => {
                if (!canMakeRequest()) return;

                const couponCode = couponCodeInput.value.trim().toUpperCase();
                if (!couponCode) {
                    couponMessage.textContent = 'Please enter a coupon code';
                    couponMessage.className = 'mb-4 p-3 rounded-lg text-sm bg-red-100 text-red-800';
                    couponMessage.classList.remove('hidden');
                    return;
                }

                applyCouponBtn.disabled = true;
                applyCouponBtn.textContent = 'Applying...';

                try {
                    const response = await fetch('{{ route('checkout.apply-coupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            coupon_code: couponCode,
                            refill_id: refillId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        couponDiscount = data.discount;
                        appliedCouponCode = couponCode;
                        hideModal(couponModal);
                        showNotification('Coupon Applied',
                            `Discount of ${formatPrice(data.discount)} applied successfully!`,
                            'success');
                        updateUI();
                    } else {
                        couponMessage.textContent = data.message;
                        couponMessage.className = 'mb-4 p-3 rounded-lg text-sm bg-red-100 text-red-800';
                        couponMessage.classList.remove('hidden');
                    }
                } catch (error) {
                    couponMessage.textContent = 'Failed to apply coupon. Please try again.';
                    couponMessage.className = 'mb-4 p-3 rounded-lg text-sm bg-red-100 text-red-800';
                    couponMessage.classList.remove('hidden');
                } finally {
                    applyCouponBtn.disabled = false;
                    applyCouponBtn.textContent = 'Apply Code';
                }
            });

            [removeCouponBtn, removeCouponAppliedBtn].forEach(btn => {
                btn.addEventListener('click', () => {
                    couponDiscount = 0;
                    appliedCouponCode = '';
                    showNotification('Coupon Removed', 'Promo code has been removed', 'info');
                    updateUI();
                });
            });

            // Enhanced Razorpay Payment with Better Details
            payBtn.addEventListener('click', async () => {
                if (payBtn.disabled) return;

                if (!canMakeRequest()) return;

                payBtn.disabled = true;
                payBtn.textContent = 'Processing...';

                try {
                    const response = await fetch('{{ route('checkout.process-payment') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            refill_id: refillId,
                            payment_method: selectedPaymentMethod,
                            miravault_used: miravaultUsed,
                            coupon_code: appliedCouponCode
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (selectedPaymentMethod === 'razorpay') {
                            // Enhanced Razorpay options with bundle details
                            const options = {
                                key: data.data.key,
                                amount: data.data.amount,
                                currency: data.data.currency,
                                order_id: data.data.order_id,
                                name: '{{ config('app.name') }}',
                                description: `{{ $bundle->name }} - {{ $refill->amount_mb >= 1024 ? number_format($refill->amount_mb / 1024, 1) . ' GB' : $refill->amount_mb . ' MB' }} for {{ $refill->amount_days }} Days`,
                                image: '{{ asset('assets/images/logo.png') }}',
                                prefill: {
                                    name: '{{ auth()->user()->name }}',
                                    email: '{{ auth()->user()->email }}',
                                    contact: '{{ auth()->user()->phone ?? '' }}'
                                },
                                notes: {
                                    bundle_name: '{{ $bundle->name }}',
                                    data_plan: '{{ $refill->amount_mb >= 1024 ? number_format($refill->amount_mb / 1024, 1) . ' GB' : $refill->amount_mb . ' MB' }}',
                                    validity: '{{ $refill->amount_days }} Days',
                                    order_number: data.data.order.number
                                },
                                theme: {
                                    color: '#4F46E5',
                                    backdrop_color: '#00000066'
                                },
                                modal: {
                                    ondismiss: function() {
                                        showNotification('Payment Cancelled',
                                            'You can try again anytime', 'info');
                                        payBtn.disabled = false;
                                        payBtn.textContent = calculateFinalAmount() === 0 ?
                                            'COMPLETE ORDER' : 'PAY NOW';
                                    },
                                    escape: false,
                                    handleback: false
                                },
                                handler: function(response) {

                                    const orderNumber = data.data.order
                                    .number; // <-- VERY IMPORTANT
                                    console.log("ORDER NUMBER:", orderNumber);

                                    if (!orderNumber) {
                                        showNotification('Order Error', 'Order number missing!',
                                            'error');
                                        resetPayButton();
                                        return;
                                    }

                                    showNotification('Payment Successful', 'Redirecting...',
                                        'success');

                                    setTimeout(() => {
                                        window.location.href =
                                            `/order-confirmation?order_number=${orderNumber}&payment_id=${response.razorpay_payment_id}`;
                                    }, 800);
                                }


                            };

                            const rzp = new Razorpay(options);
                            rzp.open();
                        } else if (selectedPaymentMethod === 'paypal') {
                            showNotification('Redirecting', 'Taking you to PayPal...', 'info');
                            setTimeout(() => {
                                window.location.href = data.data.approve_url;
                            }, 1000);
                        }
                    } else {
                        throw new Error(data.message || 'Payment processing failed');
                    }
                } catch (error) {
                    showNotification('Payment Failed', error.message || 'Please try again', 'error');
                    payBtn.disabled = false;
                    payBtn.textContent = calculateFinalAmount() === 0 ? 'COMPLETE ORDER' : 'PAY NOW';
                }
            });

            // Close modals and notifications
            [miravaultModal, couponModal].forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        hideModal(modal);
                    }
                });
            });

            closeNotification.addEventListener('click', hideNotification);

            // Security: Input sanitization for MiraVault amount
            miravaultAmount.addEventListener('input', function(e) {
                let value = e.target.value;
                // Remove any non-numeric characters except decimal point
                value = value.replace(/[^\d.]/g, '');
                // Ensure only one decimal point
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                // Limit to 2 decimal places
                if (parts.length === 2 && parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }
                e.target.value = value;
            });

            // Initialize
            updateUI();
        });
    </script>
@endsection
