<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redis;
use App\Models\Bundle;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share popular bundles with ALL views
        View::composer('*', function ($view) {
            $popularBundles = $this->getPopularBundles();
            $view->with('popularBundles', $popularBundles);
        });
    }

    /**
     * ⚡ Get popular bundles for search
     */
    private function getPopularBundles()
    {
        // ⚡ 1. FIRST CHECK REDIS
        $popularBundles = $this->getPopularFromRedis();
        
        if (!empty($popularBundles)) {
            return $popularBundles;
        }

        // ⚡ 2. FALLBACK TO DATABASE
        $popularBundles = Bundle::active()
            ->where('is_popular', true)
            ->where('is_country', true)
            ->limit(9)
            ->get()
            ->map(function($bundle) {
                return [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'slug' => $bundle->slug,
                    'image' => $bundle->image ? asset('storage/' . $bundle->image) : '/assets/images/default-country.svg',
                ];
            })
            ->toArray();

        // Store in Redis for next time
        $this->storePopularInRedis($popularBundles);

        return $popularBundles;
    }

    /**
     * ⚡ Get popular bundles from Redis
     */
    private function getPopularFromRedis()
    {
        try {
            $redisData = Redis::get('popular_bundles_data');
            if ($redisData) {
                return json_decode($redisData, true);
            }
        } catch (\Exception $e) {
            // Redis might be down
        }
        
        return [];
    }

    /**
     * ⚡ Store popular bundles in Redis
     */
    private function storePopularInRedis($popularBundles)
    {
        try {
            Redis::set('popular_bundles_data', json_encode($popularBundles));
            Redis::expire('popular_bundles_data', 86400); // 24 hours
        } catch (\Exception $e) {
            // Silently fail
        }
    }
}