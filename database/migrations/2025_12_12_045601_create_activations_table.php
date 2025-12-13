<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activations', function (Blueprint $table) {
            $table->id();

            // Order + Customer reference
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            // eSIM details
            $table->string('iccid')->nullable();       // SIM ICCID
            $table->string('smdp_plus')->nullable();   // SM-DP+ Address
            $table->string('activation_code')->nullable(); // eSIM Activation Code
            $table->string('qr_code_url')->nullable(); // URL to QR Code

            // Device info (optional)
            $table->string('device_model')->nullable();
            $table->string('device_os')->nullable();

            $table->enum('status', [
                'pending',
                'processing',
                'activated',
                'failed'
            ])->default('pending');

            $table->timestamp('activated_at')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activations');
    }
};
