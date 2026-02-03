<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['code', 'name', 'color', 'description'];

    // Relasi ke User (melalui tabel user_attributes)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_attributes')
            ->withPivot('current_xp')
            ->withTimestamps();
    }

    // Relasi ke Activity (Quest)
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_attribute')
            ->withPivot('weight')
            ->withTimestamps();
    }
}