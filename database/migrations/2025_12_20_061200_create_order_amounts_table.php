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
        Schema::create('order_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Original amounts
            $table->decimal('plan_amount', 10, 2);
            $table->decimal('convenience_fee', 10, 2)->default(0);
            $table->decimal('gst_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('currency', 10);

            // Converted amounts (snapshot)
            $table->decimal('converted_plan_amount', 10, 2)->default(0);
            $table->decimal('converted_convenience_fee', 10, 2)->default(0);
            $table->decimal('converted_gst_amount', 10, 2)->default(0);
            $table->decimal('converted_discount_amount', 10, 2)->default(0);
            $table->decimal('converted_final_amount', 10, 2)->default(0);
            $table->string('converted_currency', 10)->nullable();

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_amounts');
    }
};
