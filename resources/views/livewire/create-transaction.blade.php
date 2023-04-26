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
                    {{-- Financial Year --}}
                    <div>
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