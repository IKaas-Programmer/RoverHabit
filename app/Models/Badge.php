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
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}