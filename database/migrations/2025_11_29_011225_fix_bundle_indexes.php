<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bundles', function (Blueprint $table) {

            // DROP all old indexing mess
            $indexesToDrop = [
                'bundles_status_is_popular_is_country_index',
                'bundles_status_is_region_index',
                'bundles_status_is_global_index',
                'bundles_status_is_gcc_index',
                'bundles_status_is_lifetime_index',
                'bundles_main_composite',
                'bundles_pricing_types',
                'bundles_list_covering',
                'bundles_name_index',
            ];

            foreach ($indexesToDrop as $index) {
                try { $table->dropIndex($index); } catch (\Exception $e) {}
            }

            // RE-ADD SIMPLE NAME INDEX
            $table->index('name', 'bundles_name_index');

            // ADD THE PERFECT MASTER INDEX
            $table->index(
                [
                    'status',
                    'is_country',
                    'is_region',
                    'is_global',
                    'is_gcc',
                    'is_lifetime',
                    'cached_cheapest_price',
                    'name'
                ],
                'bundles_master_filter_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropIndex('bundles_master_filter_index');
        });
    }
};
