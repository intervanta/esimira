<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Order Identification
            $table->string('order_number')->unique();
            $table->string('invoice_number')->unique()->nullable();
            
            // Customer & Product Information
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('bundle_id')->constrained()->onDelete('cascade');
            $table->foreignId('refill_id')->constrained()->onDelete('cascade');
            
            // Pricing Information
            $table->decimal('plan_amount', 10, 2);
            $table->decimal('convenience_fee', 10, 2);
            $table->decimal('gst_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('miravault_used', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            
            // Coupon Information
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
            
            // Order Status
            $table->tinyInteger('status')->default(0)->comment('0=Pending, 1=Confirmed, 2=Processing, 3=Completed, 4=Cancelled, 5=Refunded, 6=Failed');
            $table->tinyInteger('payment_status')->default(0)->comment('0=Pending, 1=Paid, 2=Failed, 3=Refunded, 4=Partially Refunded');
            
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['order_number']);
            $table->index(['status', 'payment_status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};