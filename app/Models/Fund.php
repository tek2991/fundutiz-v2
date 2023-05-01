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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getCreditTransactions()
    {
        $creadit_type_id = TransactionType::where('name', 'Credit')->first()->id;
        return $this->transactions()->where('transaction_type_id', $creadit_type_id);
    }

    public function getDebitTransactions()
    {
        $debit_type_id = TransactionType::where('name', 'Debit')->first()->id;
        return $this->transactions()->where('transaction_type_id', $debit_type_id);
    }

    public function getFyBalance($fy_id = null)
    {
        if ($fy_id == null) {
            $fy_id = FinancialYear::where('is_active', true)->first()->id;
        }
        $debit_transactions = $this->getDebitTransactions()->where('financial_year_id', $fy_id)->sum('amount_in_cents')/100;
        $credit_transactions = $this->getCreditTransactions()->where('financial_year_id', $fy_id)->sum('amount_in_cents')/100;
        return $credit_transactions - $debit_transactions;
    }
}
