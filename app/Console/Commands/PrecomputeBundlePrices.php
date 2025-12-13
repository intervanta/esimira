<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bundle;

class PrecomputeBundlePrices extends Command
{
    protected $signature = 'bundles:precompute-prices';
    protected $description = 'Precompute cached cheapest prices for all bundles';

    public function handle()
    {
        $this->info('Starting to precompute bundle prices...');
        
        $bundles = Bundle::with(['refills' => function($q) {
            $q->active()->select('bundle_id', 'sale_price');
        }])->get();

        $bar = $this->output->createProgressBar(count($bundles));
        $updatedCount = 0;
        
        foreach ($bundles as $bundle) {
            $cheapestPrice = $bundle->refills->min('sale_price');
            
            // Update only if different or null
            if ($bundle->cached_cheapest_price != $cheapestPrice) {
                $bundle->cached_cheapest_price = $cheapestPrice;
                $bundle->saveQuietly(); // Save without events
                $updatedCount++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->info("\n✅ Prices precomputed for {$updatedCount} bundles.");
        
        // Clear cache to ensure fresh data
        \Cache::flush();
        $this->info('🔄 Cache cleared.');
        
        return Command::SUCCESS;
    }
}