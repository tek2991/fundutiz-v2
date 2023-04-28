<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Approver;
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

    // Dynamic Fund Variables
    public $currentBalance = 1000;
    public $balanceAfterTransaction = -500;

    // Show debit fields
    public $showDebitFields = true;

    // Form Data
    public $transactionTypeId; // Default: Debit
    public $financialYearId; // Current Financial Year
    public $officeId; // Current Office
    public $createdBy; // Current User
    public $fundId;
    public $amount;
    public $fileNumber;
    public $approved_at;
    public $approverId; // Required for Debit
    public $incurred = 1; // Required for Debit // Default: Yes
    public $item; // Required for Debit
    public $vendorName; // Required for Debit
    public $gemContractNumber; // Required for Debit
    public $gemNonAvailabilityCertificateNumber; // Optional for Debit if empty gemContractNumber
    public $notGemRemarks; // Required for Debit if empty gemContractNumber and gemNonAvailabilityCertificateNumber

    // Confirm deficit transaction
    public $confirmDeficitTransaction = 0; // Default: No

    public function mount()
    {
        // Get the variables
        $this->transactionTypes = TransactionType::all();
        $this->activeFinancialYear = FinancialYear::where('is_active', true)->first();
        $this->currentOffice = auth()->user()->office;
        $this->funds = $this->currentOffice->funds;
        $this->approvers = Approver::all();

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

        $this->showDebitFields = $this->transactionTypeId == $this->transactionTypes->where('name', 'Debit')->first()->id;
    }

    public function submit()
    {
    }

    public function render()
    {
        return view('livewire.create-transaction');
    }
}
