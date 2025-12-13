<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_trusted_devices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('device_id', 64)->unique(); // Hashed device fingerprint
            $table->string('device_type'); // mobile, tablet, desktop
            $table->string('platform'); // Windows, iOS, Android, etc.
            $table->string('browser'); // Chrome, Firefox, Safari, etc.
            $table->string('ip_address', 45); // Support for IPv6
            $table->string('location')->nullable();
            $table->timestamp('last_login_at');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['customer_id', 'device_id']);
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trusted_devices');
    }
};