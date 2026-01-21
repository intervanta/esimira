<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Bundle;

class SyncAllPlans extends Command
{
    protected $signature = 'plans:sync-all {--force}';
    protected $description = 'Sync ALL plans to Redis for ultra-fast loading';

    public function handle()
    {
        $this->info('🔄 Syncing ALL plans to Redis for ultra-fast loading...');

        $allPlans = [];
        $totalCount = 0;

        foreach (['local', 'regional', 'global', 'gcc', 'monthly'] as $type) {
            $plans = $this->getPlansByType($type);
            $allPlans[$type] = $plans->map(fn($bundle) => $this->formatBundleData($bundle))->toArray();
            $totalCount += $plans->count();
            $this->info("✓ {$type}: {$plans->count()} plans");
        }

        // ⚡ ULTRA-FAST: Store in Redis
        Redis::set('all_plans_data', json_encode($allPlans));
        Redis::set('all_plans_meta', json_encode([
            'synced_at' => now()->toISOString(),
            'total_plans' => $totalCount,
            'types' => array_keys($allPlans)
        ]));

        // Set expiration (optional - 24 hours)
        Redis::expire('all_plans_data', 86400);
        Redis::expire('all_plans_meta', 86400);

        // ⚡ ADDED: Cache popular bundles for search functionality
        $this->cachePopularBundlesForSearch();

        // Backup to JSON file (for fallback)
        $this->backupToJson($allPlans);

        $this->info("🎉 Success! {$totalCount} plans synced to Redis");
        $this->info("⚡ Ultra-fast loading activated!");
        $this->info("🔍 Search cache also updated!");
    }

    /**
     * ⚡ Cache popular bundles specifically for search functionality
     */
    private function cachePopularBundlesForSearch()
    {
        $this->info('🔄 Caching popular bundles for search...');

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
                    'image' => $bundle->image ? Storage::url($bundle->image) : $this->getDefaultImage($bundle),
                ];
            })
            ->toArray();

        // Store in Redis for ultra-fast search
        Redis::set('popular_bundles_data', json_encode($popularBundles));
        Redis::expire('popular_bundles_data', 86400); // 24 hours

        $this->info("✓ Popular bundles cached: " . count($popularBundles) . " bundles");
    }

    private function getPlansByType($type)
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
            ->with(['refills' => fn($q) => 
                $q->active()->select('bundle_id', 'sale_price')->orderBy('sale_price')->limit(1)
            ])
            ->orderBy('name', 'asc')
            ->orderBy('cached_cheapest_price', 'asc')
            ->get();
    }

    private function formatBundleData($bundle)
    {
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

    private function backupToJson($allPlans)
    {
        $data = [
            'synced_at' => now()->toISOString(),
            'total_plans' => array_sum(array_map('count', $allPlans)),
            'data' => $allPlans
        ];

        File::put(storage_path('app/all_plans.json'), json_encode($data, JSON_PRETTY_PRINT));
    }
}