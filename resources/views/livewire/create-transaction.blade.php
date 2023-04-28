<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            <h2 class="text-xl font-regular pt-2 pb-4">Transaction details</h2>
            <x-validation-errors class="mb-4" />
            <form wire:submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Transaction Type --}}
                    <div>
                        <x-label for="transaction_type" :value="__('Transaction Type')" />
                        <select name="transaction_type" id="transaction_type" wire:model="transactionTypeId"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                            @foreach ($transactionTypes as $transactionType)
                                <option value="{{ $transactionType->id }}">{{ $transactionType->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 py-2">
                            <b class="text-orange-600">Debit</b> for recording expenses. <br>
                            <b class="text-blue-600">Credit</b> for recording BE, RE and FG.
                        </p>
                    </div>
                    {{-- Fund Statistics --}}
                    <div class="md:col-start-3">
                        <x-label for="fundBalance" :value="__('Fund Balance')" />
                        <p class="text-sm text-gray-500 py-1">
                            Current balance: ₹{{ $currentBalance }} <br>
                            Balance after transaction: ₹{{ $balanceAfterTransaction }}
                            @if ($balanceAfterTransaction < 0)
                                <span class="text-red-600">[Deficit]</span>
                            @endif
                        </p>
                    </div>
                    {{-- Financial Year --}}
                    <div class="md:col-start-1">
                        <x-label for="financial_year" :value="__('Financial Year')" />
                        <x-input id="financial_year" class="block mt-1 w-full" type="text" name="financial_year"
                            value="{{ $activeFinancialYear->name }}" readonly />
                    </div>
                    {{-- Current Office --}}
                    <div>
                        <x-label for="office" :value="__('Current Office')" />
                        <x-input id="office" class="block mt-1 w-full" type="text" name="office"
                            value="{{ $currentOffice->name }}" readonly />
                    </div>
                    {{-- Fund --}}
                    <div class="col-start-1">
                        <x-label for="fund" :value="__('Fund')" />
                        <select name="fund" id="fund" wire:model="fundId"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                            @foreach ($funds as $fund)
                                <option value="{{ $fund->id }}">{{ $fund->name . ' - ' . $fund->head_of_account }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Amount --}}
                    <div>
                        <x-label for="amount" :value="__('Amount')" />
                        <x-input id="amount" class="block mt-1 w-full" type="number" name="amount"
                            wire:model="amount" required />
                    </div>
                    {{-- Fund Statistics --}}
                    <div class="md:hidden">
                        <x-label for="fundBalance" :value="__('Fund Balance')" />
                        <p class="text-sm text-gray-500 py-1">
                            Current balance: ₹{{ $currentBalance }} <br>
                            Balance after transaction: ₹{{ $balanceAfterTransaction }}
                            @if ($balanceAfterTransaction < 0)
                                <span class="text-red-600">[Deficit]</span>
                            @endif
                        </p>
                    </div>
                    {{-- File Number --}}
                    <div class="md:col-span-2 md:col-start-1">
                        <x-label for="file_number" :value="__('File Number')" />
                        <x-input id="file_number" class="block mt-1 w-full" type="text" name="file_number"
                            wire:model="fileNumber" required />
                    </div>
                    {{-- Approved at --}}
                    <div class="md:col-start-1">
                        <x-label for="approved_at" :value="__('Approved at')" />
                        <x-input id="approved_at" class="block mt-1 w-full" type="date" name="approved_at"
                            wire:model="approvedAt" required />
                    </div>
                    @if ($showDebitFields)
                        <div class="md:col-span-3">
                            <h3 class="text-lg mt-8">
                                Details of the expense.
                            </h3>
                        </div>
                        {{-- Approver ID --}}
                        <div class="md:col-start-1">
                            <x-label for="approver_id" :value="__('Approver')" />
                            <select name="approver_id" id="approver_id" wire:model="approverId"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                @foreach ($approvers as $approver)
                                    <option value="{{ $approver->id }}">{{ $approver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Incurred --}}
                        <div>
                            <x-label for="incurred" :value="__('Incurred')" />
                            <select name="incurred" id="incurred" wire:model="incurred"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        {{-- Item --}}
                        <div class="md:col-span-2 md:col-start-1">
                            <x-label for="item" :value="__('Item & description')" />
                            <x-textarea id="item" class="block mt-1 w-full" type="text" name="item"
                                wire:model="item" required />
                        </div>
                        {{-- Vendor name --}}
                        <div class="md:col-start-1">
                            <x-label for="vendor_name" :value="__('Vendor name')" />
                            <x-input id="vendor_name" class="block mt-1 w-full" type="text" name="vendor_name"
                                wire:model="vendorName" required />
                        </div>
                        {{-- GEM contract number --}}
                        <div class="md:col-start-1">
                            <x-label for="gem_contract_number" :value="__('GEM contract number')" />
                            <x-input id="gem_contract_number" class="block mt-1 w-full" type="text"
                                disabled="{{ $gemNonAvailabilityCertificateNumber || $notGemRemarks }}"
                                name="gem_contract_number" wire:model="gemContractNumber" />
                        </div>
                        {{-- GEM non availability certificate number --}}
                        <div>
                            <x-label for="gem_non_availability_certificate_number" :value="__('GEM non availability certificate number')" />
                            <x-input id="gem_non_availability_certificate_number" class="block mt-1 w-full"
                                type="text" disabled="{{ $gemContractNumber }}"
                                name="gem_non_availability_certificate_number"
                                wire:model="gemNonAvailabilityCertificateNumber" />
                        </div>
                        {{-- Not GEm remarks --}}
                        <div class="md:col-span-2 md:col-start-1">
                            <x-label for="not_gem_remarks" :value="__('Not GEM remarks')" />
                            <x-textarea id="not_gem_remarks" class="block mt-1 w-full" type="text"
                                disabled="{{ $gemContractNumber }}" name="not_gem_remarks"
                                wire:model="notGemRemarks" />
                        </div>
                        {{-- Confirm deficit transaction --}}
                        @if ($balanceAfterTransaction < 0)
                            <div class="md:col-span-2 md:col-start-1">
                                <div class=" inline-flex items-center">
                                    <x-checkbox id="confirm_deficit_transaction" class="block mr-3 bg-blue-100"
                                        type="text" name="confirm_deficit_transaction"
                                        wire:model="confirmDeficitTransaction" />
                                    <p class="text-sm text-blue-600 pt-1">
                                        I confirm that the transaction will result in a deficit.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="flex justify-end mt-4">
                    <x-button class="ml-4">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
