<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Transaction Identification
            $table->string('transaction_id')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            // Payment Gateway Information
            $table->enum('gateway', ['razorpay', 'paypal', 'stripe', 'bank_transfer', 'wallet'])->default('razorpay');
            $table->string('gateway_transaction_id')->nullable()->comment('Payment gateway transaction ID');
            $table->string('gateway_order_id')->nullable()->comment('Payment gateway order ID');
            
            // Amount Information
            $table->decimal('amount', 10, 2);
            $table->decimal('convenience_fee', 10, 2)->default(0);
            $table->decimal('gst_on_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->decimal('exchange_rate', 10, 6)->default(1)->comment('Exchange rate if currency converted');
            
            // Transaction Status
            $table->tinyInteger('status')->default(0)->comment('0=Initiated, 1=Pending, 2=Completed, 3=Failed, 4=Cancelled, 5=Refunded');
            $table->string('failure_reason')->nullable();
            
            // Payment Details
            $table->string('payment_method')->nullable()->comment('card, upi, netbanking, wallet, etc.');
            $table->string('wallet_type')->nullable();
            
            // Gateway Response
            $table->json('gateway_request')->nullable()->comment('Request sent to gateway');
            $table->json('gateway_response')->nullable()->comment('Response received from gateway');
            $table->json('webhook_data')->nullable()->comment('Webhook data from gateway');
            
            // Security & Verification
            $table->string('gateway_signature')->nullable()->comment('Gateway signature for verification');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            // Timestamps
            $table->timestamp('initiated_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['transaction_id']);
            $table->index(['order_id']);
            $table->index(['customer_id']);
            $table->index(['gateway', 'status']);
            $table->index(['gateway_transaction_id']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};