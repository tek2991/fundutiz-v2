<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Creating ransaction') }}
            </h2>
            @if (auth()->user()->hasRole('administrator'))
                <p class="text-gray-500">Creating with administrator privileges.</p>
            @else
                <p class="text-gray-500">Creating with limited privileges. [{{ auth()->user()->office->name }}]</p>
            @endif
        </div>
    </x-slot>

    @livewire('create-transaction')
</x-app-layout>