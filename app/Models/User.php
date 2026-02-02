<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGamification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasGamification;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'status',
        'last_active_at',

        // Kolom Gamifikasi
        'level',
        'current_xp',
        'next_level_xp',
        'current_streak',
        'last_activity_date',

        // Kolom untuk Menyimpan Badge yang di pakai
        'equipped_badge_id',
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
}