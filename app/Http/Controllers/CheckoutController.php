<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Refill;
use App\Models\Bundle;
use App\Models\Coupon;
use App\Models\User;
use App\Helpers\PriceHelper;
use App\Models\Customer;
use App\Models\Activation;
use App\Models\WalletTransaction;
use App\Services\RazorpayService;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Services\QrCodeService; 
use App\Services\KeepGoLineService;
use App\Services\WalletService;
class CheckoutController extends Controller
{
    protected $razorpayService;
    protected $paypalService;
    protected $qrCodeService;
    protected $keepGoLineServices;
    protected $walletServices;
    public function __construct()
    {
        $this->middleware('auth');
        $this->razorpayService = new RazorpayService();
        $this->paypalService = new PayPalService();
        $this->qrCodeService = new QrCodeService();
        $this->keepGoLineServices = new KeepGoLineService();
        $this->walletServices = new WalletService();
    }

    public function show(Request $request)
    {
        $refillId = $request->query('refill_id') ?? session('checkout_refill_id');

        if (!$refillId) {
            return redirect()->route('home')->with('error', 'Please select a plan first.');
        }

        $refill = Refill::with('bundle')->active()->findOrFail($refillId);
        $bundle = $refill->bundle;
        $currency = $bundle->currency ?? 'USD';

        // Calculate initial prices
        $priceData = PriceHelper::convenienceFee($refill->sale_price, $currency);

        return view('pages.checkout', [
            'refill' => $refill,
            'bundle' => $bundle,
            'priceData' => $priceData,
            'currencySign' => PriceHelper::getCurrencySymbol($currency),
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'refill_id' => 'required|exists:refills,id',
        ]);

        $refill = Refill::with('bundle')->findOrFail($request->refill_id);
        $currency = $refill->bundle->currency ?? 'USD';

        $couponResult = PriceHelper::applyCoupon(
            $refill->sale_price,
            $request->coupon_code,
            $refill->bundle_id,
            auth()->id()
        );

        if ($couponResult['error']) {
            return response()->json([
                'success' => false,
                'message' => $couponResult['error']
            ]);
        }

        // Calculate final amount with coupon
        $finalPrice = PriceHelper::calculateFinalAmount(
            $refill->sale_price,
            0, // miravault used
            $request->coupon_code,
            $refill->bundle_id,
            $currency
        );

        return response()->json([
            'success' => true,
            'discount' => $couponResult['discount'],
            'coupon_code' => $request->coupon_code,
            'final_amount' => $finalPrice['final_amount_after_discounts'],
            'currency_sign' => PriceHelper::getCurrencySymbol($currency)
        ]);
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'refill_id' => 'required|exists:refills,id',
            'payment_method' => 'required|in:razorpay,paypal',
            'miravault_used' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $refill = Refill::with('bundle')->findOrFail($validated['refill_id']);
            $customer = auth()->user(); // Get customer
            $currency = $refill->bundle->currency ?? 'USD';

            // Calculate final amount
            $priceData = PriceHelper::calculateFinalAmount(
                $refill->sale_price,
                $validated['miravault_used'] ?? 0,
                $validated['coupon_code'] ?? null,
                $refill->bundle_id,
                $currency
            );

            // Create order record
            Log::info('Creating order for customer ID: ' . $customer->id . ' with refill ID: ' . $refill->id);
            Log::info('Customer details: ' . json_encode($customer));
            Log::info('Refill details: ' . json_encode($refill));

            $order = $this->createOrderRecord($refill, $priceData, $validated, $customer);

            // Handle payment based on method
            if ($validated['payment_method'] === 'razorpay') {
                $result = $this->handleRazorpayPayment($order, $priceData);
            } elseif ($validated['payment_method'] === 'paypal') {
                $result = $this->handlePayPalPayment($order, $priceData);
            } else {
                throw new \Exception('Invalid payment method');
            }

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Payment processing failed');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment initiated successfully',
                'data' => $result['data']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment Processing Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please try again.'
            ], 500);
        }
    }


    private function createOrderRecord($refill, $priceData, $validated, $customer)
    {
        return Order::create([
            'order_number' => 'ORD' . time() . rand(1000, 9999),
            'invoice_number' => 'INV' . time() . rand(1000, 9999),
            'customer_id' => $customer->id,
            'bundle_id' => $refill->bundle_id,
            'refill_id' => $refill->id,
            'plan_amount' => $priceData['plan_amount'],
            'convenience_fee' => $priceData['convenience_fee'],
            'gst_amount' => $priceData['gst_on_convenience'],
            'total_amount' => $priceData['final_amount'],
            'discount_amount' => $priceData['coupon_discount'],
            'miravault_used' => $priceData['miravault_used'],
            'final_amount' => $priceData['final_amount_after_discounts'],
            'currency' => $priceData['currency'],
            'coupon_id' => $priceData['applied_coupon']->id ?? null,
            'coupon_code' => $validated['coupon_code'] ?? null,
            'coupon_discount' => $priceData['coupon_discount'],
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
        ]);
    }

    private function createTransactionRecord($order, $gateway, $gatewayOrderId = null)
    {
        return Transaction::create([
            'transaction_id' => 'TXN' . time() . rand(1000, 9999),
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'gateway' => $gateway,
            'gateway_order_id' => $gatewayOrderId,
            'amount' => $order->plan_amount,
            'convenience_fee' => $order->convenience_fee,
            'gst_on_fee' => $order->gst_amount,
            'total_amount' => $order->final_amount,
            'currency' => $order->currency,
            'status' => Transaction::STATUS_INITIATED,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'initiated_at' => now(),
        ]);
    }

    private function handleRazorpayPayment(Order $order, array $priceData)
    {
        // Convert amount to INR for Razorpay
        $amountInINR = PriceHelper::convertCurrency(
            $order->final_amount,
            $order->currency,
            'INR'
        );

        $customer = $order->customer ?? Customer::find($order->customer_id);
        $refill = $order->refill ?? Refill::find($order->refill_id);
        Log::info('Customer details: ' . json_encode($customer));
        Log::info('Refill details: ' . json_encode($refill));

        Log::info('Converted amount for Razorpay: ' . $amountInINR . ' INR');


        $razorpayOrder = $this->razorpayService->createOrder(
            $amountInINR,
            'INR',
            $order->order_number,

        );

        if (!$razorpayOrder['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create Razorpay order'
            ];
        }

        // Create transaction record
        $transaction = $this->createTransactionRecord($order, 'razorpay', $razorpayOrder['order_id']);

        return [
            'success' => true,
            'data' => [
                'order_id' => $razorpayOrder['order_id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => 'INR',
                'key' => config('services.razorpay.key'),
                'order' => [
                    'id' => $order->id,
                    'number' => $order->order_number,
                ]
            ]
        ];
    }

    private function handlePayPalPayment(Order $order, array $priceData)
    {
        $returnUrl = route('payment.paypal.success');
        $cancelUrl = route('payment.paypal.cancel');

        $paypalOrder = $this->paypalService->createOrder(
            $order->final_amount,
            $order->currency,
            $returnUrl,
            $cancelUrl
        );

        if (!$paypalOrder['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create PayPal order'
            ];
        }

        // Create transaction record
        $transaction = $this->createTransactionRecord($order, 'paypal', $paypalOrder['order_id']);

        // Store PayPal order ID in session for verification
        session([
            'paypal_order_id' => $paypalOrder['order_id'],
            'order_id' => $order->id
        ]);

        return [
            'success' => true,
            'data' => [
                'approve_url' => $paypalOrder['approve_url'],
                'order' => [
                    'id' => $order->id,
                    'number' => $order->order_number,
                ]
            ]
        ];
    }

    public function razorpayWebhook(Request $request)
    {
        \Log::info('Razorpay Webhook Received:', $request->all());

        // Verify webhook signature
        $webhookSecret = config('services.razorpay.webhook_secret');
        $webhookSignature = $request->header('X-Razorpay-Signature');

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        if (!hash_equals($expectedSignature, $webhookSignature)) {
            \Log::error('Razorpay Webhook Signature Mismatch');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->event;
        $payment = $request->payload['payment']['entity'] ?? null;

        if ($event === 'payment.captured' && $payment) {
            $this->handleSuccessfulPayment($payment['order_id'], 'razorpay', $payment);
        }

        return response()->json(['status' => 'success']);
    }

    public function paypalSuccess(Request $request)
    {
        $paypalOrderId = $request->token;
        $orderId = session('order_id');

        if (!$paypalOrderId || !$orderId) {
            return redirect()->route('checkout')->with('error', 'Invalid payment response.');
        }

        $captureResult = $this->paypalService->captureOrder($paypalOrderId);

        if ($captureResult['success']) {
            $order = Order::find($orderId);
            $transaction = $order->latestTransaction;

            // Update transaction
            $transaction->markAsCompleted($captureResult['payment']);

            // Update coupon usage
            if ($order->coupon_id) {
                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }

            // Update user's MiraVault balance
            if ($order->miravault_used > 0) {
                Customer::where('id', $order->customer_id)->decrement('wallet_balance', $order->miravault_used);
            }

            session()->forget(['paypal_order_id', 'order_id']);

            return redirect()->route('order.confirmation')->with([
                'success' => 'Payment completed successfully!',
                'order_number' => $order->order_number
            ]);
        }

        return redirect()->route('checkout')->with('error', 'Payment capture failed.');
    }

    public function paypalCancel()
    {
        $orderId = session('order_id');

        if ($orderId) {
            $order = Order::find($orderId);
            $transaction = $order->latestTransaction;
            $transaction->markAsFailed('Payment cancelled by user');
        }

        session()->forget(['paypal_order_id', 'order_id']);

        return redirect()->route('checkout')->with('error', 'Payment was cancelled.');
    }

    private function handleSuccessfulPayment($gatewayOrderId, $gateway, $paymentData)
    {
        $transaction = Transaction::where('gateway_order_id', $gatewayOrderId)
            ->where('gateway', $gateway)
            ->first();

        if ($transaction) {
            $transaction->markAsCompleted($paymentData);

            // Update coupon usage
            $order = $transaction->order;
            if ($order->coupon_id) {
                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }

            // Update user's MiraVault balance
            if ($order->miravault_used > 0) {
                Customer::where('id', $order->customer_id)->decrement('wallet_balance', $order->miravault_used);
            }
        }
    }


public function confirmation(Request $request)
{
    $orderNumber = $request->query('order_number');

    if (! $orderNumber) {
        return redirect()->route('home')->with('error', 'Order not found.');
    }

    $order = Order::with('bundle','refill','latestTransaction','activation')
        ->where('order_number', $orderNumber)
        ->where('customer_id', auth()->id())
        ->first();

    if (! $order) {
        return redirect()->route('home')->with('error', 'Order not found.');
    }

    //  HARD GUARD — NOTHING RUNS AFTER THIS
    if ($order->status === Order::STATUS_COMPLETED) {
        return view('pages.order_confirmation', compact('order'));
    }

    DB::transaction(function () use ($order) {

        //  CREATE LINE
        $createResponse = $this->keepGoLineServices->createLine([
            'refill_mb'   => $order->refill->amount_mb,
            'refill_days' => $order->bundle->type === 'plan'
                                ? $order->refill->amount_days
                                : null,
            'bundle_id'   => $order->bundle->keepgo_bundle_id,
            'count'       => 1,
        ]);

        $iccid = $createResponse['sim_card']['iccid'];

        //  GET LINE DETAILS
        $detailsResponse = $this->keepGoLineServices->getLineDetails($iccid);
        $sim = $detailsResponse['sim_card'];

        // GENERATE QR
        $qrPath = $this->qrCodeService->generateEsimQr(
            $sim['lpa_code'],
            12,
            true
        );

        //  UPDATE ORDER (NOW MARK COMPLETED)
        $order->update([
            'status'         => Order::STATUS_COMPLETED,
            'payment_status' => 1,
            'completed_at'   => now(),
        ]);

        // CREATE ACTIVATION
        Activation::create([
            'bundle_id'        => $order->bundle_id,
            'refill_id'        => $order->refill_id,
            'order_id'         => $order->id,
            'customer_id'      => auth()->id(),

            'iccid'            => $sim['iccid'],
            'msisdn'           => $sim['msisdn'] ?? null,
            'activation_code'  => $sim['lpa_code'] ?? null,
            'lpa_code'         => $sim['lpa_code'] ?? null,
            'bundle_name'      => $sim['bundle'] ?? $order->bundle->name,

            'allowed_usage_mb' => isset($sim['allowed_usage_kb'])
                                    ? (int) ($sim['allowed_usage_kb'] / 1024)
                                    : null,
            'remaining_usage_mb' => isset($sim['remaining_usage_kb'])
                                    ? (int) ($sim['remaining_usage_kb'] / 1024)
                                    : null,

            'remaining_days'   => $sim['remaining_days'] ?? null,
            'deactivation_date'=> $sim['deactivation_date'] ?: null,

            'qr_code_url'      => $qrPath,
            'status'           => strtolower($sim['status']) === 'activated'
                                    ? 'activated'
                                    : 'processing',
            'is_refill'        => false,
            'notes'            => $sim['notes'] ?? null,
        ]);

        //WALLET CREDIT
        $walletAmount = config('constant.order_confirmation_credit');

        if ($walletAmount > 0) {
            $this->walletServices->credit(
                auth()->user(),
                $walletAmount,
                'order_confirmation',
                'Order confirmation bonus',
                $order->id
            );
        }
    });

    return view('pages.order_confirmation', compact('order'));
}


}
