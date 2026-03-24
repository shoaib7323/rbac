<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'name'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}
