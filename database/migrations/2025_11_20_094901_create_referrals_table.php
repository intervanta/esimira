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
        Schema::create('referrals', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('referrer_id')->nullable();
        $table->unsignedBigInteger('referred_user_id')->nullable(); 
        $table->string('referral_code', 50);
        $table->string('referral_source', 50)->nullable();
        $table->string('event')->default('signup'); 
        $table->decimal('reward_amount', 10, 2)->nullable();
        $table->json('meta')->nullable();
        $table->enum('status', ['pending', 'completed', 'cancelled', 'expired'])
                  ->default('pending');
        $table->timestamps();

        $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
        $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
