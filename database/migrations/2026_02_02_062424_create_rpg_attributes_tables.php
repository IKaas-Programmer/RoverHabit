<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tabel Master Atribut (STR, INT, VIT, dll)
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // STR, INT
            $table->string('name'); // Strength, Intelligence, dll
            $table->string('color')->default('#6366f1'); // Warna Hex untuk Chart
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Tabel User Stats (Menyimpan XP User per Atribut)
        Schema::create('user_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->bigInteger('current_xp')->default(0); // Akumulasi XP atribut 
            $table->timestamps();

            // Mencegah duplikasi: Satu user hanya punya satu row per atribut
            $table->unique(['user_id', 'attribute_id']);
        });

        // 3. Tabel Pivot Activity (Menyimpan Bobot Mapping)
        Schema::create('activity_attribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');

            // Bobot: 0.1 sampai 1.0 (Total harus 1.0 idealnya)
            // Menggunakan decimal (3,2) memungkinkan angka seperti 0.55
            $table->decimal('weight', 3, 2)->default(1.00);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_attribute');
        Schema::dropIfExists('user_attributes');
        Schema::dropIfExists('attributes');
    }
};