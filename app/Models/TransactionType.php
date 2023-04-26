<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $fillable = [
        'name',
    ];

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
