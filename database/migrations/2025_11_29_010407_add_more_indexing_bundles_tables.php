<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add only critical missing indexes without checking existing ones
        Schema::table('bundles', function (Blueprint $table) {
            // Composite index for main queries - will be ignored if exists
            $table->index([
                'status', 
                'is_country', 
                'is_region', 
                'is_global', 
                'is_gcc', 
                'is_lifetime'
            ], 'bundles_main_composite');
            
            // Index for pricing with types
            $table->index([
                'status',
                'cached_cheapest_price',
                'is_country',
                'is_region',
                'is_global'
            ], 'bundles_pricing_types');
            
            // Covering index for list queries
            $table->index([
                'status',
                'is_country',
                'name',
                'slug',
                'cached_cheapest_price'
            ], 'bundles_list_covering');
        });

        Schema::table('refills', function (Blueprint $table) {
            $table->index([
                'bundle_id',
                'status',
                'sale_price'
            ], 'refills_active_pricing');
        });
    }

    public function down()
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropIndexIfExists('bundles_main_composite');
            $table->dropIndexIfExists('bundles_pricing_types');
            $table->dropIndexIfExists('bundles_list_covering');
        });

        Schema::table('refills', function (Blueprint $table) {
            $table->dropIndexIfExists('refills_active_pricing');
        });
    }
};