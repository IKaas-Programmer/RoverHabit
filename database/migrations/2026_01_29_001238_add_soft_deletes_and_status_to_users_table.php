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
            //
            $table->softDeletes();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif')->after('password');
            // Penanda kapan terakhir login/klik (Penting untuk hitungan 1 bulan)
            $table->timestamp('last_active_at')->nullable()->after('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropSoftDeletes();
            $table->dropColumn(['status', 'last_active_at']);
        });
    }
};