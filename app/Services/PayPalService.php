<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.sandbox') ? 
            'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
    }

    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            throw new \Exception('Failed to get access token');
        } catch (\Exception $e) {
            Log::error('PayPal Access Token Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createOrder($amount, $currency = 'USD', $returnUrl, $cancelUrl)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'amount' => [
                                'currency_code' => $currency,
                                'value' => number_format($amount, 2, '.', '')
                            ]
                        ]
                    ],
                    'application_context' => [
                        'return_url' => $returnUrl,
                        'cancel_url' => $cancelUrl,
                        'brand_name' => config('app.name'),
                        'user_action' => 'PAY_NOW'
                    ]
                ]);

            if ($response->successful()) {
                $order = $response->json();
                return [
                    'success' => true,
                    'order_id' => $order['id'],
                    'approve_url' => collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null
                ];
            }

            throw new \Exception('Failed to create PayPal order');
        } catch (\Exception $e) {
            Log::error('PayPal Order Creation Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function captureOrder($orderId)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment' => $response->json()
                ];
            }

            throw new \Exception('Failed to capture PayPal order');
        } catch (\Exception $e) {
            Log::error('PayPal Order Capture Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}