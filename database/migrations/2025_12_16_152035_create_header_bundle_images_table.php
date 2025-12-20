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
        Schema::create('header_bundle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['hero', 'place'])->index();
            $table->string('title')->nullable(); 
            $table->string('image_url');
            $table->string('bundle_type')->nullable(); // 'country', 'region', 'global', 'lifetime'
            $table->integer('position')->default(0);
            $table->string('source')->default('unsplash');
            
            // Photographer attribution
            $table->string('photographer_name')->nullable();
            $table->string('photographer_link')->nullable();
            
            // Sync tracking
            $table->timestamp('synced_at')->nullable();
            
            // Control flags
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            
            // Indexes
            $table->index(['bundle_id', 'type']);
            $table->index(['bundle_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_bundle_images');
    }
};