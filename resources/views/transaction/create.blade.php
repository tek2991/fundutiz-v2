<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Transaction') }}
        </h2>
        <p>
            Enter the details of the transaction you want to record.
        </p>
    </x-slot>

    @livewire('create-transaction')
</x-app-layout>
