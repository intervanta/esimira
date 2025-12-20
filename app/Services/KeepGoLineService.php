<?php

namespace App\Services;

use Http\Client\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KeepGoLineService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $accessToken;

    public function __construct()
    {
        $this->baseUrl     = config('keepgo.base_url');
        $this->apiKey      = config('keepgo.api_key');
        $this->accessToken = config('keepgo.access_token');
    }

    /**
     * Create a new eSIM line
     */
    public function createLine(array $payload): array
    {
        // MOCK RESPONSE FOR NON-PRODUCTION
        if (!app()->environment('production')) {
            return $this->mockCreateResponse($payload);
        }

        $response = Http::withHeaders([
            'apiKey'       => $this->apiKey,
            'accessToken'  => $this->accessToken,
            'Accept'       => 'application/json',
        ])->post($this->baseUrl . '/line/create', $payload);

        if (! $response->successful()) {
            throw new \Exception(
                'KeepGo Line Creation Failed: ' . $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Get line details by ICCID
     * 
     *  MOCK RESPONSE FOR NON-PRODUCTION
     *     if (!app()->environment('production')) {
     *         return $this->mockDetailsResponse($iccid);
     *     }
     */
    public function getLineDetails(string $iccid): array
    {

        Log::info('Fetching KeepGo line details', [
            'iccid' => $iccid,
        ]);

        if (!app()->environment('production')) {
            return $this->mockDetailsResponse($iccid);
        }

        Log::debug('KeepGo Get Line Details Request', [
            'url'     => "{$this->baseUrl}/line/{$iccid}/get_details",
            'headers' => [
                'apiKey'      => $this->apiKey,
                'accessToken' => $this->accessToken,
                'Accept'      => 'application/json',
            ],
        ]);

         
         // PRODUCTION REQUEST

        try {
            $response = Http::withHeaders([
                'apiKey'      => $this->apiKey,
                'accessToken' => $this->accessToken,
                'Accept'      => 'application/json',
            ])
                ->timeout(15)
                ->get("{$this->baseUrl}/line/{$iccid}/get_details");
            
            

            if ($response->failed()) {
                Log::error('KeepGo Get Line Details failed', [
                    'iccid'  => $iccid,
                    'status' => $response->status(),
                    'body'   => json_decode($response->body(), true),
                ]);

                throw new \Exception(
                    'Unable to fetch eSIM line details at the moment.'
                );
            }

            Log::info('KeepGo Get Line Details success', [
                'iccid' => $iccid,
            ]);

            $data = $response->json();

            Log::debug('KeepGo raw response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);


            if (!is_array($data)) {
                Log::error('KeepGo invalid JSON response', [
                    'iccid' => $iccid,
                    'body'  => $response->body(),
                ]);

                throw new \Exception('KeepGo returned invalid data.');
            }

            return $data;
        } catch (RequestException $e) {

            Log::critical('KeepGo API request exception', [
                'iccid' => $iccid,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('KeepGo service is currently unavailable.');
        }
    }

    /* ================= MOCK RESPONSES ================= */

    protected function mockCreateResponse(array $payload): array
    {
        return [
            'ack' => 'success',
            'sim_card' => [
                'iccid'   => '899990000000' . rand(100000, 999999),
                'lpa_code' => 'LPA:1$mock.keepgo.local$' . Str::random(20),
            ],
            'data_bundle_id' => $payload['bundle_id'] ?? rand(100, 999),
        ];
    }

    protected function mockDetailsResponse(string $iccid): array
    {
        return [
            'ack' => 'success',
            'sim_card' => [
                'iccid'               => $iccid,
                'msisdn'              => '34590100123456',
                'lpa_code'            => 'LPA:1$mock.keepgo.local$TN' . Str::random(10),
                'deactivation_date'   => null,
                'allowed_usage_kb'    => 102400,
                'remaining_usage_kb'  => 51200,
                'remaining_days'      => 5,
                'status'              => 'Activated',
                'bundle'              => 'Mock Aquila',
                'auto_refill_turned_on' => false,
                'notes'               => '',
            ],
        ];
    }
}
