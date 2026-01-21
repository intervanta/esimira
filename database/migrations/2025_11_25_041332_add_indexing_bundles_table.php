<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          Schema::table('bundles', function (Blueprint $table) {
            $table->decimal('cached_cheapest_price', 8, 2)->nullable()->after('currency_sign');
            $table->index(['status', 'is_popular', 'is_country']);
            $table->index(['status', 'is_region']);
            $table->index(['status', 'is_global']);
            $table->index(['status', 'is_gcc']);
            $table->index(['status', 'is_lifetime']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn('cached_cheapest_price');
            $table->dropIndex(['status', 'is_popular', 'is_country']);
            $table->dropIndex(['status', 'is_region']);
            $table->dropIndex(['status', 'is_global']);
            $table->dropIndex(['status', 'is_gcc']);
            $table->dropIndex(['status', 'is_lifetime']);
            $table->dropIndex(['name']);
        });
    }
};
