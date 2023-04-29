<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Fund') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h2 class="text-xl font-regular pt-2 pb-4">Fund details</h2>
                <x-validation-errors class="mb-4" />
                <form action="{{ route('fund.update', $fund) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" required name="name"
                                value="{{ $fund->name }}" />
                        </div>
                        <div class="md:flex md:justify-end">
                            <div>
                                @php
                                    $currentFyBalance = $fund->getFyBalance();
                                @endphp
                                <p class="text-sm text-gray-500 py-1">
                                    Current FY balance: ₹{{ $currentFyBalance }}
                                    @if ($currentFyBalance < 0)
                                        <span class="text-red-600">[Deficit]</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-start-1">
                            <x-label for="head_of_account" :value="__('Head of Account')" />
                            <x-input id="head_of_account" class="block mt-1 w-full" type="text" required
                                name="head_of_account" value="{{ $fund->head_of_account }}" />
                        </div>

                        <div class="col-start-1">
                            <x-label for="description" :value="__('Description')" />
                            <x-textarea id="description" class="block mt-1 w-full" name="description" required
                                rows="4">{{ $fund->description }}</x-textarea>
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

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <span class="flex justify-between items-center mt-2 mb-4">
                    <h2 class="text-xl font-regular">Assigned Offices</h2>
                    <button
                        onclick="Livewire.emit('openModal', 'attach-modal', {{ json_encode(['route' => 'fund.attachOffice', 'model_id' => $fund->id, 'model_name' => 'Fund', 'attaching_model_name' => 'Office']) }})"
                        class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Add Office</span>
                    </button>
                </span>
                <livewire:fund-offices-table :fund_id="$fund->id" />
            </div>
        </div>
    </div>
</x-app-layout>
