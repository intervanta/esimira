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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('referral_code', 50)->nullable();
            $table->boolean('promotional_emails')->default(false);
            $table->string('auth_provider_name')->nullable();   // e.g. google, facebook, apple
            $table->string('auth_provider_id')->nullable();     // provider user id
            $table->json('auth_provider_raw')->nullable();      // raw provider response (optional)
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
