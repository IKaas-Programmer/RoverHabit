<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Tambahan Kolom Gamifikasi
        'level',
        'current_xp',
        'next_level_xp',
        'current_streak',
        'last_activity_date',
    ];

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
    ];

    // ==========================================
    // RELATIONS (HUBUNGAN ANTAR TABEL)
    // ==========================================

    // User punya banyak riwayat aktivitas
    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // User punya banyak badge
    public function badges()
    {
        return $this->belongsToMany(Badge::class)->withTimestamps();
    }

    // ==========================================
    // GAMIFICATION LOGIC (OTAKNYA DI SINI)
    // ==========================================

    public function addXp($amount)
    {
        // 1. Tambah XP
        $this->current_xp += $amount;

        // 2. Cek apakah naik level?
        if ($this->current_xp >= $this->next_level_xp) {
            $this->levelUp();
        } else {
            $this->save();
        }
    }

    private function levelUp()
    {
        $this->level++;             // Naik level
        $this->current_xp = 0;      // Reset XP (atau bisa sistem sisa)

        // Target level selanjutnya makin susah (dikali 1.5)
        $this->next_level_xp = $this->next_level_xp * 1.5;

        $this->save();
    }
}