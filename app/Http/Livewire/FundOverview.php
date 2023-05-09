<?php

namespace App\Http\Livewire;

use App\Models\Fund;
use App\Models\Office;
use Livewire\Component;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Auth;

class FundOverview extends Component
{
    // Define variables
    public $funds;
    public $offices;
    public $financialYears;

    // Selected values
    public $office_id;
    public $fy_id;

    // Data for chart
    public $fundArray;

    public function mount()
    {
        $this->funds = Fund::all();
        $this->financialYears = FinancialYear::all();

        // If user is administrator, show all offices
        // Else, show only the user's office
        $this->offices = Auth::user()->hasRole('administrator') ? Office::all() : Office::whereIn('id', [Auth::user()->office->id])->get();

        // Prepare data for chart
        $this->prepareFundArray();
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'fy_id' || $propertyName == 'office_id') {
            $this->prepareFundArray();
        }
    }

    public function prepareFundArray()
    {
        $fy_ids = [];
        if ($this->fy_id == null) {
            $fy_ids = FinancialYear::where('is_active', true)->pluck('id');
        } else {
            $fy_ids = [$this->fy_id];
        }

        $office_ids = [];
        if ($this->office_id == null) {
            $office_ids = Auth::user()->hasRole('administrator') ? Office::pluck('id') : [Auth::user()->office->id];
        } else {
            $office_ids = [$this->office_id];
        }

        $fundArray = [];

        foreach ($this->funds as $fund) {
            $sum_of_debit_transactions = $fund->getDebitTransactions()->whereIn('financial_year_id', $fy_ids)->whereIn('office_id', $office_ids)->sum('amount_in_cents') / 100;
            $sum_of_credit_transactions = $fund->getCreditTransactions()->whereIn('financial_year_id', $fy_ids)->whereIn('office_id', $office_ids)->sum('amount_in_cents') / 100;
            $percentage = round(($sum_of_debit_transactions / $sum_of_credit_transactions) * 100, 2);
            $percentage = $percentage > 100 ? 100 : $percentage;
            $color = "blue-500";

            // Case statement to set color
            switch ($percentage) {
                case $percentage > 100:
                    $color = 'red-500';
                    break;
                case $percentage > 75:
                    $color = 'orange-500';
                    break;
                case $percentage > 50:
                    $color = 'yellow-500';
                    break;
                case $percentage > 25:
                    $color = 'green-500';
                    break;
                default:
                    $color = 'blue-600';
            }

            $fundArray[$fund->id] = [
                'fund_name' => $fund->name,
                'fund_hoa' => $fund->head_of_account,
                'fund_description' => $fund->description,
                'allocation' => "₹" . number_format($sum_of_credit_transactions, 2, '.', ','),
                'expenditure' => "₹" . number_format($sum_of_debit_transactions, 2, '.', ','),
                'balance' => "₹" . number_format($sum_of_credit_transactions - $sum_of_debit_transactions, 2, '.', ','),
                'is_deficit' => $sum_of_credit_transactions - $sum_of_debit_transactions < 0,
                'percentage' => $percentage,
                'color' => $color,
            ];
        }

        $this->fundArray = $fundArray;
    }

    public function render()
    {
        return view('livewire.fund-overview');
    }
}
