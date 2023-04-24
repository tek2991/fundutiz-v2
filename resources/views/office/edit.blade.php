<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Office') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h2 class="text-xl font-regular pt-2 pb-4">Office details</h2>
                <x-validation-errors class="mb-4" />
                <form action="{{ route('office.update', $office) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:w-1/2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" required name="name"
                                value="{{ $office->name }}" />
                        </div>
                        <div>
                            <x-label for="manager_id" :value="__('Manager')" />
                            <x-input-select id="manager_id" class="block mt-1 w-full" required name="manager_id">
                                <option value="">Select Manager</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}"
                                        {{ $office->manager_id == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}</option>
                                @endforeach
                            </x-input-select>
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
