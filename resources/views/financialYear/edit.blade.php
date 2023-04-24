<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Financial Year') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h2 class="text-xl font-regular pt-2 pb-4">Financial Year details</h2>
                <x-validation-errors class="mb-4" />
                <form action="{{ route('financialYear.update', $financialYear) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:w-1/2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" required name="name"
                                value="{{ $financialYear->name }}" />
                        </div>
                        <div>
                            <x-label for="start_date" :value="__('Start Date')" />
                            <x-input id="start_date" class="block mt-1 w-full" type="date" required name="start_date"
                                value="{{ $financialYear->start_date->format('Y-m-d') }}" />
                        </div>
                        <div>
                            <x-label for="end_date" :value="__('End Date')" />
                            <x-input id="end_date" class="block mt-1 w-full" type="date" required name="end_date"
                                value="{{ $financialYear->end_date->format('Y-m-d') }}" />
                        </div>
                        <div>
                            <x-label for="is_active" :value="__('Set as current')" />
                            <x-checkbox id="is_active" class="block mt-1" required name="is_active" value="1"
                                checked="{{ $financialYear->is_active }}" />
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
</x-app-layout>
