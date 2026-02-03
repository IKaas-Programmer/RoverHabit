<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $attributes = [
            ['code' => 'STR', 'name' => 'Strength', 'color' => '#ef4444', 'description' => 'Physical power and willpower.'], // Red
            ['code' => 'INT', 'name' => 'Intelligence', 'color' => '#3b82f6', 'description' => 'Logic, learning, and analysis.'], // Blue
            ['code' => 'VIT', 'name' => 'Vitality', 'color' => '#10b981', 'description' => 'Health, stamina, and recovery.'], // Emerald
            ['code' => 'AGI', 'name' => 'Agility', 'color' => '#f59e0b', 'description' => 'Speed, precision, and efficiency.'], // Amber
            ['code' => 'DEX', 'name' => 'Dexterity', 'color' => '#8b5cf6', 'description' => 'Skill, craft, and coordination.'], // Violet
            ['code' => 'CHA', 'name' => 'Charisma', 'color' => '#ec4899', 'description' => 'Social, leadership, and empathy.'], // Pink
        ];

        DB::table('attributes')->insert($attributes);
    }
}