<?php

namespace App\Traits;

use App\Models\Badge;

trait HasGamification
{
    // ==========================================
    // 1. ACCESSORS (Data Pintar)
    // ==========================================

    /**
     * Mengambil Label Rank (Title)
     * Panggil: $user->rank_label
     */
    public function getRankLabelAttribute()
    {
        // Prioritas: Badge yang dipakai
        if ($this->activeBadge) {
            return $this->activeBadge->name;
        }

        // Fallback: Level
        $level = $this->level;
        if ($level >= 80)
            return 'Legendary Vanguard';
        if ($level >= 60)
            return 'Epic Pathfinder';
        if ($level >= 40)
            return 'Elite Explorer';
        if ($level >= 20)
            return 'Senior Traveler';
        if ($level >= 10)
            return 'Amateur Rover';

        return 'New Traveler';
    }

    /**
     * Menghitung Persentase XP untuk Progress Bar
     * Panggil: $user->xp_percentage
     */
    public function getXpPercentageAttribute()
    {
        if ($this->next_level_xp <= 0)
            return 0;
        return ($this->current_xp / $this->next_level_xp) * 100;
    }

    // ==========================================
    // 2. RELASI (Khusus Gamifikasi)
    // ==========================================

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_user')->withTimestamps();
    }

    public function activeBadge()
    {
        return $this->belongsTo(Badge::class, 'equipped_badge_id');
    }

    // ==========================================
    // 3. LOGIC (XP & Level Up)
    // ==========================================

    public function addXp($amount)
    {
        $this->current_xp += $amount;

        // Loop Level Up
        while ($this->current_xp >= $this->next_level_xp) {
            $this->current_xp -= $this->next_level_xp;
            $this->level++;
            $this->next_level_xp = $this->calculateNextXp($this->level);

            // Simpan flash session untuk notifikasi
            session()->flash('level_up', "LEVEL UP! Welcome to Level $this->level.");
        }

        $this->updateStreak();
        $this->save();
    }

    private function calculateNextXp($currentLevel)
    {
        $baseXP = 100;
        return floor(($baseXP * pow($currentLevel, 1.5)) + ($currentLevel * 50));
    }

    private function updateStreak()
    {
        $today = now()->startOfDay();
        $lastDate = $this->last_activity_date ? $this->last_activity_date->startOfDay() : null;

        if (!$lastDate) {
            $this->current_streak = 1;
        } elseif ($lastDate->isYesterday()) {
            $this->current_streak += 1;
        } elseif ($lastDate->isBefore($today) && !$lastDate->isToday()) {
            $this->current_streak = 1;
        }

        $this->last_activity_date = now();
    }
}