<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Bundle;

class HomeController extends Controller
{
    public function index()
    {
        // ⚡ Get initial data for home page pricing section
        $initialType = 'local';
        
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
        
        // Get initial bundles for home page - ONLY POPULAR COUNTRIES (is_popular = true)
        $initialBundles = collect($allPlans[$initialType] ?? [])
            ->filter(function($bundle) {
                return $bundle['is_popular'] === true; // Only show popular countries
            })
            ->take(28) // Limit to 28 popular countries
            ->values();
        
        // ⚡ 3. FINAL FALLBACK TO DATABASE
        if ($initialBundles->isEmpty()) {
            $initialBundles = $this->getPopularFromDatabase($initialType);
        }

        return view('home', [
            'initialType' => $initialType,
            'initialBundles' => $initialBundles,
            'allPlans' => $allPlans // Pass all plans for instant tab switching
        ]);
    }

    /**
     * Get popular plans data for home page pricing section (AJAX endpoint)
     */
    public function getPopularPlansData(Request $request)
    {
        $type = $request->get('type', 'local');
        
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
        
        // Get popular bundles for current tab - ONLY POPULAR COUNTRIES
        $bundles = collect($allPlans[$type] ?? [])
            ->filter(function($bundle) {
                return $bundle['is_popular'] === true; // Only show popular countries
            })
            ->take(28) // Limit to 28 popular countries
            ->values();
        
        // ⚡ 3. FINAL FALLBACK TO DATABASE
        if ($bundles->isEmpty()) {
            $bundles = $this->getPopularFromDatabase($type);
        }

        return response()->json([
            'bundles' => $bundles,
            'count' => $bundles->count(),
            'type' => $type,
            'loaded_instantly' => true
        ]);
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
     * 🗄️ Get popular plans from Database - ONLY POPULAR COUNTRIES
     */
    private function getPopularFromDatabase($type)
    {
        $conditions = [
            'local' => ['is_country' => true],
            'regional' => ['is_region' => true],
            'global' => ['is_global' => true],
            'gcc' => ['is_gcc' => true, 'is_country' => true],
            'monthly' => ['is_lifetime' => true]
        ][$type];

        return Bundle::where('status', true)
            ->where($conditions)
            ->where('is_popular', true) // ✅ ONLY POPULAR COUNTRIES
            ->select([
                'id', 'name', 'slug', 'image', 'currency_sign',
                'cached_cheapest_price', 'base_price', 'is_global',
                'description', 'data_validity', 'coverage', 'is_popular'
            ])
            ->with(['refills' => function($q) {
                $q->active()->select('bundle_id', 'sale_price')->orderBy('sale_price')->limit(1);
            }])
            ->orderBy('name', 'asc')
            ->orderBy('cached_cheapest_price', 'asc')
            ->limit(40)
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

    public function whyChooseEsimira()
    {
        return view('pages.why-choose-esimira');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function help()
    {
        return view('pages.help');
    }

    public function resellerBusiness()
    {
        return view('pages.reseller-business');
    }
}