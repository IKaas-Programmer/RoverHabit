<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'last_active_at',

        // Tambahan Kolom Gamifikasi
        'level',
        'current_xp',
        'next_level_xp',
        'current_streak',
        'last_activity_date',
    ];


    // Definition Roles
    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_USER = 'user';

    // Helpers to check roles
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isModerator()
    {
        return $this->role === self::ROLE_MODERATOR;
    }
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity_date' => 'date', // Biar otomatis jadi format Tanggal
        'last_active_at' => 'datetime',
    ];

    // ==========================================
    // RELATIONS (HUBUNGAN ANTAR TABEL)
    // ==========================================

    // User punya banyak riwayat aktivitas
    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function activitylogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    // User punya banyak badge
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_user')->withTimestamps();
    }

    // ==========================================
    // GAMIFICATION LOGIC (OTAKNYA DI SINI)
    // ==========================================

    public function addXp($amount)
    {
        // Tambah XP
        $this->current_xp += $amount;

        // Loop Level Up (Gunakan WHILE agar bisa naik >1 level sekaligus)
        // Dan pastikan ada sisa XP yang terbawa (Carry Over)
        while ($this->current_xp >= $this->next_level_xp) {
            $this->current_xp -= $this->next_level_xp; // Sisa XP tidak hangus
            $this->level++;

            // Terapkan Formula Kompleks (Power Curve)
            $this->next_level_xp = $this->calculateNextXp($this->level);

            // Simpan status untuk notifikasi di UI (optional)
            session()->flash('level_up', "CONGRATULATIONS! LEVEL $this->level UNLOCKED.");
        }

        // Update Streak Konsistensi
        $this->updateStreak();

        $this->save();
    }

    /**
     * Formula Jembatan: Menghitung Target XP Level Selanjutnya
     */
    private function calculateNextXp($currentLevel)
    {
        $baseXP = 100;
        // Formula: (Base * Level ^ 1.5) + (Level * 50)
        return floor(($baseXP * pow($currentLevel, 1.5)) + ($currentLevel * 50));
    }

    private function levelUp()
    {
        $this->level++;             // Naik level
        $this->current_xp = 0;      // Reset XP (atau bisa sistem sisa)

        // Target level selanjutnya makin susah (dikali 1.5)
        $this->next_level_xp = $this->next_level_xp * 1.5;

        $this->save();
    }

    /**
     * Logika Penjagaan Streak Harian
     */
    private function updateStreak()
    {
        $today = now()->startOfDay();
        $lastDate = $this->last_activity_date ? $this->last_activity_date->startOfDay() : null;

        if (!$lastDate) {
            // Pertama kali aktivitas
            $this->current_streak = 1;
        } elseif ($lastDate->isYesterday()) {
            // Beruntun (Kemarin ada, hari ini ada)
            $this->current_streak += 1;
        } elseif ($lastDate->isBefore($today) && !$lastDate->isToday()) {
            // Bolong (Terakhir aktif bukan kemarin/hari ini), Reset ke 1
            $this->current_streak = 1;
        }

        // Update tanggal aktivitas terakhir
        $this->last_activity_date = now();
    }
}