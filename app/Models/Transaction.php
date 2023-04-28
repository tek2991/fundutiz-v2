<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_type_id',
        'financial_year_id',
        'office_id',
        'fund_id',
        'file_number',
        'amount_in_cents',
        'approver_id',
        'approved_at',
        'incurred',
        'item',
        'vendor_name',
        'gem_contract_number',
        'gem_non_availability_certificate_number',
        'not_gem_remarks',
        'created_by',
        'is_deficit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'amount' => 'integer',
        'incurred' => 'boolean',
        'is_deficit' => 'boolean',
    ];

    /**
     * Append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'amount',
    ];

        /**
     * Interact with the amount_in_cents column.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function amount(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_in_cents'] / 100,

            set: fn ($value) => [
                'amount_in_cents' => $value * 100,
            ]
        );
    }

    /**
     * Get the transaction type that owns the transaction.
     */
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class);
    }

    /**
     * Get the financial year that owns the transaction.
     */
    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    /**
     * Get the office that owns the transaction.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the fund that owns the transaction.
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    /**
     * Get the approver of the transaction.
     */
    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }

    /**
     * Get the user that created the transaction.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }
}
