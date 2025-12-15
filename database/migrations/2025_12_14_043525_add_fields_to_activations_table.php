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
        Schema::table('activations', function (Blueprint $table) {
            $table->unsignedBigInteger('bundle_id')
                ->nullable()
                ->after('id');

            $table->unsignedBigInteger('refill_id')
                ->nullable()
                ->after('bundle_id');

            $table->boolean('is_auto_refill')->default(false)->after('activated_at');
            $table->unsignedInteger('remaining_usage_mb')->nullable()->after('is_auto_refill');
            $table->unsignedInteger('remaining_days')->nullable()->after('remaining_usage_mb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activations', function (Blueprint $table) {
         // Drop foreign key constraints first
         $table->foreign('bundle_id')->references('id')->on('bundles')->cascadeOnDelete();
         $table->foreign('refill_id')->references('id')->on('refills')->cascadeOnDelete();

        // Then drop columns
        $table->dropColumn([
            'bundle_id',
            'refill_id',
            'is_auto_refill',
            'remaining_usage_mb',
            'remaining_days',
        ]);
        });
    }
};
