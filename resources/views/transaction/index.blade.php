<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Transactions') }}
                </h2>
                @if (auth()->user()->hasRole('administrator'))
                    <p class="text-gray-500">Manage all transactions</p>
                @else
                    <p class="text-gray-500">Manage your transactions. [{{ auth()->user()->office->name }}]</p>
                @endif
            </div>
            <x-button-link href="{{ route('transaction.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Expenditure & Allocation (BE/RE/FG)</span>
            </x-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                @livewire('transaction-table')
            </div>
        </div>
    </div>
</x-app-layout>
