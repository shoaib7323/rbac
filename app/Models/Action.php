<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = ['feature_id', 'name', 'slug'];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_actions')
                    ->withTimestamps();
    }
}
