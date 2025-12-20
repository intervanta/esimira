<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UnsplashService
{
    protected string $key;
    protected int $remainingRequests = 50;
    protected bool $rateLimitReached = false;
    protected ?int $resetTimestamp = null;

    public function __construct()
    {
        $this->key = config('services.unsplash.key');
        $this->loadRateLimitFromCache();
    }

    public function getHeroImage(string $name, string $type = 'country'): ?array
    {
        if ($this->rateLimitReached || $this->remainingRequests <= 0) {
            Log::info('Unsplash rate limit reached');
            return null;
        }

        $query = $this->buildSearchQuery($name, $type);

        try {
            $response = Http::timeout(30)
                ->retry(2, 1000)
                ->get('https://api.unsplash.com/search/photos', [
                    'query' => $query,
                    'orientation' => 'landscape',
                    'per_page' => 1,
                    'order_by' => 'relevant',
                    'client_id' => $this->key,
                ]);

            $this->updateRateLimitFromHeaders($response->headers());

            if ($response->status() === 403 || $response->status() === 429) {
                $this->handleRateLimit($response);
                return null;
            }

            if ($response->successful()) {
                $result = $response->json('results.0');
                
                if (!$result) {
                    return null;
                }

                return $this->formatImageResponse($result);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Unsplash API error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function buildSearchQuery(string $name, string $type): string
    {
        $queries = [
            'country' => $name . ' landmark cityscape tourism',
            'region' => $name . ' landscape nature region',
            'global' => 'world earth planet space',
            'lifetime' => 'famous landmark cityscape tourism'
        ];

        return $queries[$type] ?? $name . ' travel destination';
    }

    protected function formatImageResponse(array $result): array
    {
        return [
            'id' => $result['id'],
            'description' => $result['description'] ?? $result['alt_description'] ?? '',
            'url_full' => $result['urls']['full'],
            'url_regular' => $result['urls']['regular'],
            'user_name' => $result['user']['name'] ?? '',
            'user_link' => $result['user']['links']['html'] ?? ''
        ];
    }

    protected function updateRateLimitFromHeaders(array $headers): void
    {
        if (isset($headers['X-Ratelimit-Remaining'][0])) {
            $this->remainingRequests = (int)$headers['X-Ratelimit-Remaining'][0];
        }

        if (isset($headers['X-Ratelimit-Reset'][0])) {
            $this->resetTimestamp = (int)$headers['X-Ratelimit-Reset'][0];
        }

        if ($this->remainingRequests <= 0) {
            $this->rateLimitReached = true;
        }

        $this->saveRateLimitToCache();
    }

    protected function handleRateLimit($response): void
    {
        $this->rateLimitReached = true;
        $this->remainingRequests = 0;
        $this->resetTimestamp = time() + 3600;

        $headers = $response->headers();
        if (isset($headers['X-Ratelimit-Reset'][0])) {
            $this->resetTimestamp = (int)$headers['X-Ratelimit-Reset'][0];
        }

        $this->saveRateLimitToCache();
        Log::warning('Unsplash rate limit exceeded', ['reset_at' => date('Y-m-d H:i:s', $this->resetTimestamp)]);
    }

    protected function saveRateLimitToCache(): void
    {
        Cache::put('unsplash_rate_limit', [
            'remaining' => $this->remainingRequests,
            'reset' => $this->resetTimestamp,
            'rate_limit_reached' => $this->rateLimitReached
        ], 86400);
    }

    protected function loadRateLimitFromCache(): void
    {
        $data = Cache::get('unsplash_rate_limit', [
            'remaining' => 50,
            'reset' => null,
            'rate_limit_reached' => false
        ]);

        $this->remainingRequests = $data['remaining'];
        $this->resetTimestamp = $data['reset'];
        $this->rateLimitReached = $data['rate_limit_reached'];

        if ($this->rateLimitReached && $this->resetTimestamp && time() > $this->resetTimestamp) {
            $this->rateLimitReached = false;
            $this->remainingRequests = 50;
            $this->saveRateLimitToCache();
        }
    }

    public function canMakeRequest(): bool
    {
        return !$this->rateLimitReached && $this->remainingRequests > 0;
    }

    public function getRemainingRequests(): int
    {
        return $this->remainingRequests;
    }

    public function getResetTime(): ?int
    {
        return $this->resetTimestamp;
    }
}