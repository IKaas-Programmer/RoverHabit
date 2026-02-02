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
            // menyimpan ID badge yang sedang DIPAKAI
            // Boleh NULL (artinya user sedang tidak memakai badge apapun / pakai rank default)
            $table->foreignId('equipped_badge_id')
                ->nullable()
                ->after('password') // Opsional: letak kolom
                ->constrained('badges')
                ->onDelete('set null'); // Jika badge dihapus admin, user jadi tidak pakai badge (aman)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['equipped_badge_id']);
            $table->dropColumn('equipped_badge_id');
        });
    }
};