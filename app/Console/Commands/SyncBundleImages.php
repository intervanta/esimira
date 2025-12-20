<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bundle;
use App\Models\HeaderBundleImage;
use App\Services\UnsplashService;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
class SyncBundleImages extends Command
{
    protected $signature = 'bundles:sync-images 
                            {--type=all : Bundle type to sync}
                            {--limit= : Limit number of bundles}
                            {--skip-existing : Skip bundles with images}
                            {--continue : Continue from last processed}';
    
    protected $description = 'Fetch and save hero images for bundles';
    
    protected int $processed = 0;
    protected int $skipped = 0;

    public function handle(UnsplashService $unsplash)
    {
        $bundles = $this->getBundles();
        
        if ($bundles->isEmpty()) {
            $this->info('No bundles found');
            return;
        }

        $this->info("Processing {$bundles->count()} bundles");

        foreach ($bundles as $bundle) {
            if (!$unsplash->canMakeRequest()) {
                $this->info('Rate limit reached. Stopping.');
                break;
            }

            $this->processBundle($bundle, $unsplash);
        }

        $this->showSummary();
    }

    protected function getBundles()
    {
        $query = Bundle::where('status', true);

        $type = $this->option('type');
        
        if ($type !== 'all') {
            $typeField = [
                'country' => 'is_country',
                'region' => 'is_region',
                'global' => 'is_global',
                'lifetime' => 'is_lifetime'
            ][$type] ?? 'is_country';
            
            $query->where($typeField, true);
        } else {
            $query->where(function($q) {
                $q->where('is_country', true)
                  ->orWhere('is_region', true)
                  ->orWhere('is_global', true)
                  ->orWhere('is_lifetime', true);
            });
        }

        if ($this->option('limit')) {
            $query->limit((int)$this->option('limit'));
        }

        if ($this->option('continue')) {
            $lastProcessedId = Cache::get('last_processed_bundle_id', 0);
            $query->where('id', '>', $lastProcessedId);
        }

        return $query->orderBy('id')->get();
    }

    protected function processBundle(Bundle $bundle, UnsplashService $unsplash): void
    {
        $bundleType = $this->getBundleType($bundle);

        if ($this->option('skip-existing')) {
            $exists = HeaderBundleImage::where('bundle_id', $bundle->id)
                ->where('type', 'hero')
                ->exists();
                
            if ($exists) {
                $this->skipped++;
                return;
            }
        }

        $image = $unsplash->getHeroImage($bundle->name, $bundleType);
        
        if (!$image) {
            $this->info("No image found for {$bundle->name}");
            return;
        }

        $this->saveImage($bundle, $image, $bundleType);
        $this->processed++;

        Cache::put('last_processed_bundle_id', $bundle->id, 86400);
    }

    protected function getBundleType(Bundle $bundle): string
    {
        if ($bundle->is_country) return 'country';
        if ($bundle->is_region) return 'region';
        if ($bundle->is_global) return 'global';
        if ($bundle->is_lifetime) return 'lifetime';
        return 'country';
    }

    protected function saveImage(Bundle $bundle, array $image, string $bundleType): void
    {
        try {
            $imageContent = @file_get_contents($image['url_full']);
            
            if (!$imageContent) {
                throw new \Exception('Failed to download image');
            }

            $dimensions = $this->getDimensions($bundleType);
            
            $resizedImage = Image::make($imageContent)
                ->fit($dimensions['width'], $dimensions['height']);

            $filename = "bundles/hero/{$bundle->id}_{$bundleType}_" . time() . '.jpg';
            
            Storage::disk('public')->put($filename, (string) $resizedImage->encode('jpg', 85));

            HeaderBundleImage::updateOrCreate(
                [
                    'bundle_id' => $bundle->id,
                    'type' => 'hero'
                ],
                [
                    'title' => $image['description'] ?: $bundle->name,
                    'image_url' => $filename,
                    'bundle_type' => $bundleType,
                    'source' => 'unsplash',
                    'photographer_name' => $image['user_name'],
                    'photographer_link' => $image['user_link'],
                    'synced_at' => now()
                ]
            );

        } catch (\Exception $e) {
            $this->error("Failed to save image for {$bundle->name}: " . $e->getMessage());
        }
    }

    protected function getDimensions(string $bundleType): array
    {
        return [
            'country' => ['width' => 548, 'height' => 373],
            'region' => ['width' => 600, 'height' => 400],
            'global' => ['width' => 800, 'height' => 600],
            'lifetime' => ['width' => 700, 'height' => 500]
        ][$bundleType] ?? ['width' => 548, 'height' => 373];
    }

    protected function showSummary(): void
    {
        $this->info("\nSummary:");
        $this->info("Processed: {$this->processed}");
        $this->info("Skipped: {$this->skipped}");
        
        $remaining = app(UnsplashService::class)->getRemainingRequests();
        $resetTime = app(UnsplashService::class)->getResetTime();
        
        if ($resetTime) {
            $this->info("Unsplash requests remaining: {$remaining}");
            $this->info("Rate limit resets at: " . date('Y-m-d H:i:s', $resetTime));
        }
    }
}