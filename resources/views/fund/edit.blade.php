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
                    <div class="grid grid-cols-1 md:w-1/2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" required name="name"
                                value="{{ $fund->name }}" />
                        </div>
                        <div>
                            <x-label for="head_of_account" :value="__('Head of Account')" />
                            <x-input id="head_of_account" class="block mt-1 w-full" type="text" required
                                name="head_of_account" value="{{ $fund->head_of_account }}" />
                        </div>

                        <div>
                            <x-label for="description" :value="__('Description')" />
                            <x-textarea id="description" class="block mt-1 w-full" name="description" required
                                rows="4">{{ $fund->description }}</x-textarea>
                        </div>

                        <div>
                            <x-label for="office_id" :value="__('Office')" />
                            <x-input-select id="office_id" class="block mt-1 w-full" multiple name="office_ids[]"
                                size="{{ count($offices) < 4 ? 4 : count($offices) }}">
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ $fund->offices->contains($office) ? 'selected' : '' }}>
                                        {{ $office->name }}</option>
                                @endforeach
                            </x-input-select>
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
