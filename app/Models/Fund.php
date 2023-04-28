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

    public function getCreaditTransactions()
    {
        $creadit_type_id = TransactionType::where('name', 'Creadit')->first()->id;
        return $this->transactions()->where('transaction_type_id', $creadit_type_id);
    }

    public function getDebitTransactions()
    {
        $debit_type_id = TransactionType::where('name', 'Debit')->first()->id;
        return $this->transactions()->where('transaction_type_id', $debit_type_id);
    }

    public function getFyBalance()
    {
        $current_fy_id = FinancialYear::where('is_active', true)->first()->id;
        $debit_transactions = $this->getDebitTransactions()->where('financial_year_id', $current_fy_id)->sum('amount_in_cents');
        $creadit_transactions = $this->getCreaditTransactions()->where('financial_year_id', $current_fy_id)->sum('amount_in_cents');
        return $creadit_transactions - $debit_transactions;
    }
}
