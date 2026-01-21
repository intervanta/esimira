<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Bundle;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $initialType = $this->validateType($request->get('type', 'local'));
        
        // ⚡ 1. FIRST CHECK REDIS (Ultra-fast)
        $allPlans = $this->getAllFromRedis();
        
        // ⚡ 2. FALLBACK TO JSON FILE (Fast)
        if (empty($allPlans)) {
            $allPlans = $this->getAllFromJson();
            
            // Store in Redis for next time if JSON has data
            if (!empty($allPlans)) {
                $this->storeInRedis($allPlans);
            }
        }
        
        // Get bundles for current tab
        $initialBundles = collect($allPlans[$initialType] ?? []);
        
        // ⚡ 3. FINAL FALLBACK TO DATABASE (Slow - should rarely happen)
        if ($initialBundles->isEmpty()) {
            $initialBundles = $this->getFromDatabase($initialType);
            $allPlans[$initialType] = $initialBundles->toArray();
            
            // Update Redis with new data
            $this->storeInRedis($allPlans);
        }

        return view('pages.plans', compact('initialType', 'initialBundles', 'allPlans'));
    }

    /**
     * ⚡ Get data from Redis (0.1-1ms)
     */
    private function getAllFromRedis()
    {
        try {
            $redisData = Redis::get('all_plans_data');
            if ($redisData) {
                return json_decode($redisData, true);
            }
        } catch (\Exception $e) {
            // Redis might be down, fallback to JSON
            \Log::warning('Redis connection failed: ' . $e->getMessage());
        }
        
        return [];
    }

    /**
     * ⚡ Store data in Redis with expiration
     */
    private function storeInRedis($allPlans)
    {
        try {
            Redis::set('all_plans_data', json_encode($allPlans));
            Redis::expire('all_plans_data', 86400); // 24 hours
            
            // Store metadata
            $totalPlans = array_sum(array_map('count', $allPlans));
            $meta = [
                'synced_at' => now()->toISOString(),
                'total_plans' => $totalPlans,
                'types' => array_keys($allPlans)
            ];
            Redis::set('all_plans_meta', json_encode($meta));
            Redis::expire('all_plans_meta', 86400);
            
            return true;
        } catch (\Exception $e) {
            \Log::warning('Failed to store in Redis: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 📁 Get data from JSON file (1-10ms)
     */
    private function getAllFromJson()
    {
        $filePath = storage_path('app/all_plans.json');
        
        if (File::exists($filePath)) {
            try {
                $data = json_decode(File::get($filePath), true);
                return $data['data'] ?? [];
            } catch (\Exception $e) {
                \Log::error('Failed to read JSON file: ' . $e->getMessage());
            }
        }
        
        return [];
    }

    /**
     * 🗄️ Get data from Database (50-200ms)
     */
    private function getFromDatabase($type)
    {
        $conditions = [
            'local' => ['is_country' => true],
            'regional' => ['is_region' => true],
            'global' => ['is_global' => true],
            'gcc' => ['is_gcc' => true, 'is_country' => true],
            'monthly' => ['is_lifetime' => true]
        ][$type];

        return Bundle::where('status', true)
            ->select([
                'id', 'name', 'slug', 'image', 'currency_sign',
                'cached_cheapest_price', 'base_price', 'is_global',
                'description', 'data_validity', 'coverage', 'is_popular'
            ])
            ->where($conditions)
            ->with(['refills' => function($q) {
                $q->active()->select('bundle_id', 'sale_price')->orderBy('sale_price')->limit(1);
            }])
            ->orderBy('name', 'asc')
            ->orderBy('cached_cheapest_price', 'asc')
            ->get()
            ->map(function($bundle) {
                $cheapestRefill = $bundle->refills->first();
                $minPrice = $cheapestRefill ? ($cheapestRefill->sale_price ?? $cheapestRefill->price) : $bundle->cached_cheapest_price;

                return [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'slug' => $bundle->slug,
                    'image' => $bundle->image ? Storage::url($bundle->image) : $this->getDefaultImage($bundle),
                    'min_price' => $minPrice,
                    'currency_sign' => $bundle->currency_sign,
                    'price_in_usd' => $minPrice,
                    'description' => $bundle->description,
                    'data_validity' => $bundle->data_validity,
                    'coverage' => $bundle->coverage,
                    'is_global' => $bundle->is_global,
                    'is_popular' => $bundle->is_popular,
                ];
            });
    }

    /**
     * 🖼️ Get default image based on bundle type
     */
    private function getDefaultImage($bundle)
    {
        $defaultImages = [
            'is_country' => '/assets/images/default-country.svg',
            'is_region' => '/assets/images/default-region.svg', 
            'is_global' => '/assets/images/default-global.svg',
            'is_gcc' => '/assets/images/default-gcc.svg',
            'is_lifetime' => '/assets/images/default-monthly.svg',
        ];

        foreach ($defaultImages as $field => $image) {
            if ($bundle->$field) return $image;
        }

        return '/assets/images/default-bundle.svg';
    }

    /**
     * 🔄 Clear Redis cache (for debugging)
     */
    public function clearRedisCache()
    {
        try {
            $deleted = Redis::del('all_plans_data');
            Redis::del('all_plans_meta');
            
            return response()->json([
                'message' => 'Redis cache cleared successfully',
                'deleted_keys' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Redis not available: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📊 Check Redis status (for debugging)
     */
    public function getRedisStatus()
    {
        try {
            $dataExists = Redis::exists('all_plans_data');
            $metaData = Redis::get('all_plans_meta');
            $meta = $metaData ? json_decode($metaData, true) : [];
            
            return response()->json([
                'redis_available' => true,
                'data_cached' => (bool)$dataExists,
                'total_plans' => $meta['total_plans'] ?? 0,
                'last_sync' => $meta['synced_at'] ?? null,
                'types_available' => $meta['types'] ?? [],
                'cache_ttl' => Redis::ttl('all_plans_data') // Time until expiration
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'redis_available' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * 🔍 Force refresh from database (admin function)
     */
    public function forceRefresh()
    {
        try {
            // Clear existing cache
            Redis::del('all_plans_data');
            Redis::del('all_plans_meta');
            
            // Trigger sync command
            \Artisan::call('plans:sync-all');
            
            return response()->json([
                'message' => 'Data refresh triggered successfully',
                'output' => \Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Refresh failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Validate tab type
     */
    private function validateType($type)
    {
        $validTypes = ['local', 'regional', 'global', 'gcc', 'monthly'];
        return in_array($type, $validTypes) ? $type : 'local';
    }
}