<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom status gamifikasi
            $table->integer('level')->default(1);
            $table->integer('current_xp')->default(0);
            $table->integer('next_level_xp')->default(100);

            // Kolom streak
            $table->integer('current_streak')->default(0);
            $table->date('last_activity_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn([
                'level',
                'current_exp',
                'next_level_exp',
                'current_streak',
                'last_activity_date',
            ]);
        });
    }
};