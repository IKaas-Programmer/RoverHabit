<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Attribute;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'icon', // sebagai kategori quest
        'exp_reward',
    ];

    /**
     * Accessor untuk Visual Quest (Warna & Ikon)
     * Mengubah isi kolom 'icon' menjadi skema warna Tailwind
     */
    public function getVisualAttribute()
    {
        return match (strtolower($this->icon ?? 'routine')) {
            'main' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'hover:border-amber-400', 'fa' => 'fa-star'],
            'explor' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'hover:border-emerald-400', 'fa' => 'fa-compass'],
            'char' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'hover:border-purple-400', 'fa' => 'fa-user-shield'],
            'event' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'hover:border-rose-400', 'fa' => 'fa-calendar-check'],
            'npc' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'hover:border-blue-400', 'fa' => 'fa-comment-dots'],
            default => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'hover:border-indigo-400', 'fa' => 'fa-check-circle'],
        };
    }

    // Relasi: Satu aktivitas bisa dikerjakan berkali-kali (masuk ke banyak log)
    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relasi Multi-Attribute Mapping
     * Mengambil atribut apa saja yang dilatih quest ini beserta bobotnya.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'activity_attribute')
            ->withPivot('weight')
            ->withTimestamps();
    }

    // activityLogs relation sudah ada di ActivityLog model
    // public function activityLogs()
    // {
    //     return $this->hasMany(ActivityLog::class);
    // }
}