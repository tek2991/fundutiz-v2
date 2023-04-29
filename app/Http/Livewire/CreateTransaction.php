<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Approver;
use App\Models\FinancialYear;
use App\Models\TransactionType;
use Illuminate\Validation\Rule;

class CreateTransaction extends Component
{
    // Variables
    public $transactionTypes;
    public $activeFinancialYear;
    public $currentOffice;
    public $funds;
    public $approvers;

    // Transaction type ids
    public $debitTypeId;
    public $creditTypeId;

    // Dynamic Fund Variables
    public $currentBalance;
    public $balanceAfterTransaction;

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
    public $approverId;
    public $incurred = 1; // Required for Debit // Default: Yes
    public $item; // Required for Debit
    public $vendorName; // Required for Debit
    public $gemContractNumber; // Required for Debit
    public $gemNonAvailabilityCertificateNumber; // Optional for Debit if empty gemContractNumber
    public $notGemRemarks; // Required for Debit if empty gemContractNumber and gemNonAvailabilityCertificateNumber
    public $confirmDeficitTransaction = false; // Required for Debit if balanceAfterTransaction < 0
    
    // Confirm transaction
    public $confirmTransaction = false; // Required to submit the form

    public function mount()
    {
        // Get the variables
        $this->transactionTypes = TransactionType::all();
        $this->activeFinancialYear = FinancialYear::where('is_active', true)->first();
        $this->currentOffice = auth()->user()->office;
        $this->funds = $this->currentOffice->funds;
        $this->approvers = Approver::all();

        // Set the transaction type ids
        $this->debitTypeId = $this->transactionTypes->where('name', 'Debit')->first()->id;
        $this->creditTypeId = $this->transactionTypes->where('name', 'Credit')->first()->id;

        // Set the form data for the current financial year, current user and office
        $this->financialYearId = $this->activeFinancialYear->id;
        $this->officeId = $this->currentOffice->id;
        $this->createdBy = auth()->user()->id;

        // Set the default transaction type
        $this->transactionTypeId = $this->debitTypeId;
    }

    public function setCurrentBalance()
    {
        $this->currentBalance = $this->funds->find($this->fundId)->getFyBalance();
    }

    public function calcBalanceAfterTransaction()
    {
        if($this->transactionTypeId == $this->debitTypeId)
        {
            $this->balanceAfterTransaction = $this->currentBalance - $this->amount;
        }
        else
        {
            $this->balanceAfterTransaction = $this->currentBalance + $this->amount;
        }
    }

    public function updatedFundId($value)
    {
        $this->setCurrentBalance();
        $this->calcBalanceAfterTransaction();
    }

    public function updatedTransactionTypeId($value)
    {
        $this->showDebitFields = $this->transactionTypeId == $this->debitTypeId;
        $this->calcBalanceAfterTransaction();
    }

    public function updatedAmount($value)
    {
        $this->calcBalanceAfterTransaction();
    }

    public function rules(){
        return [
            'transactionTypeId' => 'required|exists:transaction_types,id',
            'financialYearId' => 'required|exists:financial_years,id',
            'officeId' => 'required|exists:offices,id',
            'createdBy' => 'required|exists:users,id',
            'fundId' => 'required|exists:funds,id',
            'amount' => 'required|numeric|min:1',
            'fileNumber' => 'required|string',
            'approved_at' => 'required|date',
            'approverId' => 'required|exists:approvers,id',
            'incurred' => 'nullable|required_if:transactionTypeId,' . $this->debitTypeId . '|boolean',
            'item' => 'nullable|required_if:transactionTypeId,' . $this->debitTypeId . '|string',
            'vendorName' => 'nullable|required_if:transactionTypeId,' . $this->debitTypeId . '|string',
            'gemContractNumber' => [
                'nullable',
                'string',
                // Required if transaction type is debit and gemNonAvailabilityCertificateNumber is empty and notGemRemarks is empty
                Rule::requiredIf(function () {
                    return $this->transactionTypeId == $this->debitTypeId && empty($this->gemNonAvailabilityCertificateNumber) && empty($this->notGemRemarks);
                }),
            ],
            'gemNonAvailabilityCertificateNumber' => 'nullable',
            'notGemRemarks' => [
                'nullable',
                'string',
                // Required if transaction type is debit and if gemContractNumber is empty and gemNonAvailabilityCertificateNumber is empty
                Rule::requiredIf(function () {
                    return $this->transactionTypeId == $this->debitTypeId && empty($this->gemContractNumber) && empty($this->gemNonAvailabilityCertificateNumber);
                }),
            ],
            'confirmDeficitTransaction' => 'nullable|required_if:balanceAfterTransaction,<,0|boolean',
            'confirmTransaction' => 'required|boolean',
        ];
    }

    public function submit()
    {
        $this->validate();

        // Create the transaction
        $transaction = $this->currentOffice->transactions()->create([
            'transaction_type_id' => $this->transactionTypeId,
            'financial_year_id' => $this->financialYearId,
            'office_id' => $this->officeId,
            'created_by' => $this->createdBy,
            'fund_id' => $this->fundId,
            'amount' => $this->amount,
            'file_number' => $this->fileNumber,
            'approved_at' => $this->approved_at,
            'approver_id' => $this->approverId,
            'incurred' => $this->transactionTypeId == $this->debitTypeId ? $this->incurred : null,
            'item' => $this->transactionTypeId == $this->debitTypeId ? $this->item : null,
            'vendor_name' => $this->transactionTypeId == $this->debitTypeId ? $this->vendorName : null,
            'gem_contract_number' => $this->transactionTypeId == $this->debitTypeId ? $this->gemContractNumber : null,
            'gem_non_availability_certificate_number' => $this->transactionTypeId == $this->debitTypeId ? $this->gemNonAvailabilityCertificateNumber : null,
            'not_gem_remarks' => $this->transactionTypeId == $this->debitTypeId ? $this->notGemRemarks : null,
            'is_deficit' => $this->transactionTypeId == $this->debitTypeId ? $this->confirmDeficitTransaction : null,
        ]);

        // Flash success message
        session()->flash('flash.banner', 'Transaction created successfully.');

        // Redirect to the transaction show page
        return redirect()->route('transaction.index');
    }

    public function render()
    {
        return view('livewire.create-transaction');
    }
}
