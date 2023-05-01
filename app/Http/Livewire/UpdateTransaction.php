<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Approver;
use App\Models\FinancialYear;
use App\Models\TransactionType;
use Illuminate\Validation\Rule;

class UpdateTransaction extends Component
{
    // Transaction
    public $transaction;

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
    public $balanceAfterUpdate;

    // Show debit fields
    public $showDebitFields;

    // Form Data
    public $transactionTypeId;
    public $financialYearId;
    public $officeId;
    public $createdBy;
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
    public $confirmDeficitTransaction = false; // Required for Debit if balanceAfterUpdate < 0
    
    // Confirm transaction
    public $confirmTransaction = false; // Required to submit the form

    public function mount($transaction)
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
        
        // set showDebitFields
        $this->showDebitFields = $transaction->transaction_type_id == $this->debitTypeId;

        // Set the form data from the transaction
        $this->transactionTypeId = $this->transaction->transaction_type_id;
        $this->financialYearId = $this->transaction->financial_year_id;
        $this->officeId = $this->transaction->office_id;
        $this->createdBy = $this->transaction->created_by;

        $this->fundId = $this->transaction->fund_id;
        $this->amount = $this->transaction->amount;
        $this->fileNumber = $this->transaction->file_number;
        $this->approved_at = $this->transaction->approved_at->format('Y-m-d');
        $this->approverId = $this->transaction->approver_id;

        $this->incurred = $this->transaction->incurred;
        $this->item = $this->transaction->item;
        $this->vendorName = $this->transaction->vendor_name;
        $this->gemContractNumber = $this->transaction->gem_contract_number;
        $this->gemNonAvailabilityCertificateNumber = $this->transaction->gem_non_availability_certificate_number;
        $this->notGemRemarks = $this->transaction->not_gem_remarks;

        // Set the dynamic fund variables
        $this->setCurrentBalance();
        $this->calcBalanceAfterUpdate();
    }

    public function setCurrentBalance()
    {
        $this->currentBalance = $this->funds->find($this->fundId)->getFyBalance($this->financialYearId);
    }

    public function calcBalanceAfterUpdate()
    {
        $recorded_amount = $this->transaction->amount == null ? 0 : $this->transaction->amount;
        $current_amount = $this->amount == null ? 0 : $this->amount;
        $diff_amount = $current_amount - $recorded_amount;
        if($this->transactionTypeId == $this->debitTypeId)
        {
            $this->balanceAfterUpdate = $this->currentBalance - $diff_amount;
        }
        else
        {
            $this->balanceAfterUpdate = $this->currentBalance + $diff_amount;
        }
    }

    public function updatedFundId($value)
    {
        $this->setCurrentBalance();
        $this->calcBalanceAfterUpdate();
    }

    public function updatedTransactionTypeId($value)
    {
        $this->showDebitFields = $this->transactionTypeId == $this->debitTypeId;
        $this->calcBalanceAfterUpdate();
    }

    public function updatedAmount($value)
    {
        $this->calcBalanceAfterUpdate();
    }

    public function rules(){
        return [
            'transactionTypeId' => 'required|exists:transaction_types,id',
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
            'confirmDeficitTransaction' => 'nullable|required_if:balanceAfterUpdate,<,0|boolean',
            'confirmTransaction' => 'required|boolean',
        ];
    }

    public function submit()
    {
        $this->validate();

        // Update the transaction. Financial year, office and created by are not updated
        $transaction = $this->transaction->update([
            'transaction_type_id' => $this->transactionTypeId,
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
            'is_deficit' => $this->transactionTypeId == $this->debitTypeId ? $this->confirmDeficitTransaction : false,
        ]);

        // Flash success message
        session()->flash('flash.banner', 'Transaction updated successfully.');

        // Redirect to the transaction show page
        return redirect()->route('transaction.index');
    }

    public function render()
    {
        return view('livewire.update-transaction');
    }
}
