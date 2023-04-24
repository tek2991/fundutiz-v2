<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = [
        'name',
        'head_of_account',
        'description',
    ];

    public function offices()
    {
        return $this->belongsToMany(Office::class);
    }
}
