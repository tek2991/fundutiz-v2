<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'name',
        // 'manager_id',
    ];

    public function managers()
    {
        return $this->belongsToMany(User::class, 'office_managers');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function funds()
    {
        return $this->belongsToMany(Fund::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
