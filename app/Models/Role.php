<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_predefined'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'role_modules')
                    ->withPivot('full_access')
                    ->withTimestamps();
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'role_actions')
                    ->withTimestamps();
    }

    public function permissions()
    {
        // Keep this for backward compatibility if needed, but point to actions
        return $this->actions();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
