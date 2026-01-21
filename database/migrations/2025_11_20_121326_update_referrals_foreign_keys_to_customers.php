<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Drop existing foreign keys from REFERRALS table
            $table->dropForeign(['referrer_id']);
            $table->dropForeign(['referred_user_id']);
            
            // Add new foreign keys pointing to customers table
            $table->foreign('referrer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('referred_user_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Drop foreign keys from referrals table
            $table->dropForeign(['referrer_id']);
            $table->dropForeign(['referred_user_id']);
            
            // Revert back to users table
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};