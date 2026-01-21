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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->enum('type', ['credit', 'debit']);
            $table->string('source');
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
