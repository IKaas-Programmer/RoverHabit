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
            // 1. Tambah Role (jika belum ada di database Anda)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('member')->after('password');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'non-aktif'])->default('aktif')->after('role');
            }

            // Cek Last Active
            if (!Schema::hasColumn('users', 'last_active_at')) {
                $table->timestamp('last_active_at')->nullable()->after('status');
            }

            // Cek Soft Deletes
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['role', 'status', 'last_active_at']);
            $table->dropSoftDeletes();
        });
    }
};