<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'exp_reward', // Konsisten pakai 'exp'
    ];

    // Relasi: Satu aktivitas bisa dikerjakan berkali-kali (masuk ke banyak log)
    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}