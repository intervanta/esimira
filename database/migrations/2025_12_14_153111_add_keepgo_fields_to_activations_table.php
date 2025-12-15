<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activations', function (Blueprint $table) {

            $table->string('msisdn', 50)
                ->nullable()
                ->after('iccid');

            // KeepGo supports max 191 chars
            $table->string('lpa_code', 191)
                ->nullable()
                ->after('activation_code');

            $table->timestamp('deactivation_date')
                ->nullable()
                ->after('activated_at');

            $table->integer('allowed_usage_mb')
                ->unsigned()
                ->nullable()
                ->after('remaining_usage_mb');

            $table->string('bundle_name')
                ->nullable()
                ->after('bundle_id');

            $table->boolean('is_refill')
                ->default(false)
                ->after('refill_id');

            $table->text('notes')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('activations', function (Blueprint $table) {
            $table->dropColumn([
                'msisdn',
                'lpa_code',
                'deactivation_date',
                'allowed_usage_mb',
                'bundle_name',
                'is_refill',
                'notes',
            ]);
        });
    }
};
