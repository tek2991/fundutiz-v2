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

    // Load the user
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->financialYears = FinancialYear::all();

        $this->loadFunds();
        $this->loadOffices();

        // Prepare data for chart
        $this->prepareFundArray();
    }

    public function loadFunds()
    {
        // If user is administrator, show all funds
        // If user is manager, show only his/her funds
        // If user is user, show only his/her fund

        $this->funds = $this->user->office->funds;

        if ($this->user->hasRole('manager')) {
            $offices = $this->user->managerOfOffices;
            $this_office = null;
            $fund_ids = [];
            foreach ($offices as $office) {
                $fund_ids  = array_merge($fund_ids, $office->funds->pluck('id')->toArray());
                $this_office = $office;
            }
            dd($fund_ids);
            dd($this_office);
            $this->funds = Fund::whereIn('id', $fund_ids)->get();
        }

        if ($this->user->hasRole('administrator')) {
            $this->funds = Fund::all();
        }
    }

    public function loadOffices()
    {
        // If user is administrator, show all offices
        // If user is manager, show only his/her offices
        // If user is user, show only his/her office

        if ($this->user->hasRole('administrator')) {
            $this->offices = Office::all();
        } else if ($this->user->hasRole('manager')) {
            $this->offices = $this->user->managerOfOffices;
        } else {
            $this->offices = [$this->user->office];
        }
    }

    public function updatedOfficeId()
    {
        if ($this->office_id == null) {
            $this->loadFunds();
        } else {
            $this->funds = Office::find($this->office_id)->funds;
        }

        $this->prepareFundArray();
    }

    public function updatedFyId()
    {
        $this->prepareFundArray();
    }

    public function prepareFundArray()
    {
        $fy_ids = [];
        if ($this->fy_id == null) {
            $fy_ids = FinancialYear::where('is_active', true)->pluck('id');
        } else {
            $fy_ids = [$this->fy_id];
        }

        $fundArray = [];

        foreach ($this->funds as $fund) {
            $sum_of_debit_transactions = $fund->getDebitTransactions()->whereIn('financial_year_id', $fy_ids)->sum('amount_in_cents') / 100;
            $sum_of_credit_transactions = $fund->getCreditTransactions()->whereIn('financial_year_id', $fy_ids)->sum('amount_in_cents') / 100;
            $sum_of_credit_transactions = $sum_of_credit_transactions == 0 ? 1 : $sum_of_credit_transactions;
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
