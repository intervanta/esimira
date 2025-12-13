<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use App\Models\Bundle;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Handle search queries (only for actual search, not popular bundles)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
       
        // If no query or short query, return empty (popular bundles are handled globally)
        if (empty($query) || strlen($query) < 1) {
            return response()->json([
                'bundles' => [],
                'type' => 'empty',
                'message' => 'Use global $popularBundles for popular results'
            ]);
        }

        // ⚡ Search in cached data first (much faster than database)
        $searchResults = $this->searchInCachedData($query);
        
        if (!empty($searchResults)) {
            return response()->json([
                'bundles' => $searchResults,
                'type' => 'search',
                'loaded_from_cache' => true,
                'query' => $query
            ]);
        }

        // Fallback to database search if cache miss
        $bundles = Bundle::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('title', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function($bundle) {
                return [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'slug' => $bundle->slug ?: Str::slug($bundle->name),
                    'image' => $bundle->image ? Storage::url($bundle->image) : $this->getDefaultImage($bundle),
                ];
            });

        return response()->json([
            'bundles' => $bundles,
            'type' => 'search',
            'loaded_from_cache' => false,
            'query' => $query
        ]);
    }

    /**
     * ⚡ Search in cached Redis/JSON data (ultra-fast)
     */
    private function searchInCachedData($query)
    {
        // ⚡ 1. FIRST CHECK REDIS
        $allPlans = $this->getAllFromRedis();
        
        // ⚡ 2. FALLBACK TO JSON FILE
        if (empty($allPlans)) {
            $allPlans = $this->getAllFromJson();
        }

        if (empty($allPlans)) {
            return [];
        }

        $searchResults = [];
        $query = strtolower(trim($query));

        // Search across all plan types
        foreach ($allPlans as $type => $bundles) {
            foreach ($bundles as $bundle) {
                $bundleName = strtolower($bundle['name']);
                
                // Simple string matching
                if (str_contains($bundleName, $query)) {
                    $searchResults[] = [
                        'id' => $bundle['id'],
                        'name' => $bundle['name'],
                        'slug' => $bundle['slug'],
                        'image' => $bundle['image'],
                    ];

                    // Limit results to 10
                    if (count($searchResults) >= 10) {
                        break 2;
                    }
                }
            }
        }

        return $searchResults;
    }

    /**
     * ⚡ Get all plans data from Redis
     */
    private function getAllFromRedis()
    {
        try {
            $redisData = Redis::get('all_plans_data');
            if ($redisData) {
                return json_decode($redisData, true);
            }
        } catch (\Exception $e) {
            \Log::warning('Redis connection failed: ' . $e->getMessage());
        }
        
        return [];
    }

    /**
     * 📁 Get all plans data from JSON file
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
}