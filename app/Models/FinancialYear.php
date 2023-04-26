<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function setActive()
    {
        // Deactivate all financial years
        self::query()->update(['is_active' => false]);

        // Activate the current financial year
        $this->update(['is_active' => true]);
    }

    public function isActive(): bool
    {
        // return $this->is_active;
        return false;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
