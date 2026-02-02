<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_path'];

    // Relasi: Badge ini dimiliki oleh siapa saja?
    public function users()
    {
        // belongsToMany karena relasinya Many-to-Many
        return $this->belongsToMany(User::class, 'badge_user')->withTimestamps();
    }

    // INVENTORY: Siapa saja yang MEMILIKI badge ini? (Many-to-Many)
    public function owners()
    {
        return $this->belongsToMany(User::class, 'badge_user')->withTimestamps();
    }

    // ACTIVE: Siapa saja yang sedang MEMAKAI badge ini sekarang? (One-to-Many)
    // Relasi ke kolom 'equipped_badge_id' di tabel users
    public function equippedByUsers()
    {
        return $this->hasMany(User::class, 'equipped_badge_id');
    }
}