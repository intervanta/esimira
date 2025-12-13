<?php

namespace App\Services;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function createOrder($amount, $currency = 'INR', $receipt = null)
    {
        try {

            $amountInPaise = (int) round($amount * 100);
            $order = $this->razorpay->order->create([
                'receipt' => $receipt ?? 'order_rcptid_' . uniqid(),
                'amount' => $amountInPaise, 
                'currency' => $currency,
                'payment_capture' => 1 ,

            ]);

            return [
                'success' => true,
                'order_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function verifySignature($orderId, $paymentId, $signature)
    {
        try {
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];
            $this->razorpay->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            Log::error('Razorpay Signature Verification Failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getPayment($paymentId)
    {
        try {
            return $this->razorpay->payment->fetch($paymentId);
        } catch (\Exception $e) {
            Log::error('Razorpay Payment Fetch Failed: ' . $e->getMessage());
            return null;
        }
    }
}