<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Daftar Badge/Title Keren
        $badges = [
            [
                'name' => 'Dawn Chaser',
                'description' => 'Menyelesaikan tugas sebelum jam 6 pagi.',
                'image_path' => null, // Bisa diisi path gambar nanti
            ],
            [
                'name' => 'Consistency King',
                'description' => 'Mempertahankan streak selama 7 hari berturut-turut.',
                'image_path' => null,
            ],
            [
                'name' => 'Elite Resonator',
                'description' => 'Gelar khusus bagi pengembara yang mencapai Level 20.',
                'image_path' => null,
            ],
            [
                'name' => 'Code Architect',
                'description' => 'Menyelesaikan 50 tugas kategori pemrograman.',
                'image_path' => null,
            ],
            [
                'name' => 'Early Supporter',
                'description' => 'Pengguna generasi pertama RoverHabit.',
                'image_path' => null,
            ],
        ];

        foreach ($badges as $data) {
            Badge::updateOrCreate(['name' => $data['name']], $data);
        }

        // 2. OTOMATIS BERIKAN KE USER PERTAMA (Untuk Testing)
        // Agar modal inventory Anda tidak kosong saat dites
        $user = User::find(2);

        if ($user) {
            $allBadgeIds = Badge::pluck('id')->toArray();
            // Berikan semua badge di atas ke user ID 1
            $user->badges()->syncWithoutDetaching($allBadgeIds);
        }
    }
}