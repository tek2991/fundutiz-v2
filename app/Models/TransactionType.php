<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $fillable = [
        'name',
    ];

    const CREDIT = 1;
    const DEBIT = 2;

    public static function defaultvalues()
    {
        return [
            'Credit',
            'Debit',
        ];
    }

    /**
     * Get the transactions for the transaction type.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
