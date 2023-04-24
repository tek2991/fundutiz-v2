<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'name',
        'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function funds()
    {
        return $this->belongsToMany(Fund::class);
    }
}
