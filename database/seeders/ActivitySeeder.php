<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Lari Pagi 30 Menit', 'exp_reward' => 50, 'icon' => '🏃'],
            ['name' => 'Baca Buku 1 Bab', 'exp_reward' => 30, 'icon' => '📚'],
            ['name' => 'Coding Latihan Laravel', 'exp_reward' => 100, 'icon' => '💻'],
            ['name' => 'Minum Air 2 Liter', 'exp_reward' => 10, 'icon' => '💧'],
            ['name' => 'Tidur Sebelum Jam 10 Malam', 'exp_reward' => 40, 'icon' => '😴'],
        ];

        foreach ($data as $item) {
            Activity::create($item);
        }
    }
}