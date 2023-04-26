<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\FinancialYear;
use App\Models\TransactionType;

class CreateTransaction extends Component
{
    // Variables
    public $transactionTypes;
    public $activeFinancialYear;
    public $currentOffice;
    public $funds;
    public $approvers;

    // Form Data
    public $transactionTypeId; // Default: Debit
    public $financialYearId; // Current Financial Year
    public $officeId; // Current Office
    public $createdBy; // Current User
    public $fundId;
    public $fileNumber;
    public $amount;
    public $approverId; // Required for Debit
    public $incurred; // Required for Debit
    public $item; // Required for Debit
    public $vendorName; // Required for Debit
    public $gemContractNumber; // Required for Debit
    public $gemNonAvailabilityCertificateNumber; // Optional for Debit if empty gemContractNumber
    public $notGemRemarks; // Required for Debit if empty gemContractNumber and gemNonAvailabilityCertificateNumber

    public function mount()
    {
        // Get the variables
        $this->transactionTypes = TransactionType::all();
        $this->activeFinancialYear = FinancialYear::where('is_active', true)->first();
        $this->currentOffice = auth()->user()->office;
        $this->funds = $this->currentOffice->funds;
        $this->approvers = User::where('role_id', 2)->get();

        // Set the form data for the current financial year, current user and office
        $this->financialYearId = $this->activeFinancialYear->id;
        $this->officeId = $this->currentOffice->id;
        $this->createdBy = auth()->user()->id;

        // Set the default transaction type
        $this->transactionTypeId = $this->transactionTypes->where('name', 'Debit')->first()->id;
    }

    public function updatedTransactionTypeId($value)
    {
        $this->transactionTypeId = $value;
    }

    public function submit()
    {
    }

    public function render()
    {
        return view('livewire.create-transaction');
    }
}
