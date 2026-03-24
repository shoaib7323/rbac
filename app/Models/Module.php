<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_modules')
                    ->withPivot('full_access')
                    ->withTimestamps();
    }
}
