<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h2 class="text-xl font-regular pt-2 pb-4">User details</h2>
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="grid grid-cols-1 md:w-1/2 gap-6">
                        <div>
                            <x-jet-label for="name" :value="__('Name')" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" required name="name"
                                value="{{ old('name') }}" />
                        </div>
                        <div>
                            <x-jet-label for="email" :value="__('Email')" />
                            <x-jet-input id="email" class="block mt-1 w-full" type="email" required name="email"
                                value="{{ old('email') }}" />
                        </div>

                        <div>
                            <x-jet-label for="phone" :value="__('Phone')" />
                            <x-jet-input id="phone" class="block mt-1 w-full" type="text" name="phone_1"
                                value="{{ old('phone_1') }}" required />
                        </div>
                        <div>
                            <x-jet-label for="phone_2" :value="__('Phone (Alternate)')" />
                            <x-jet-input id="phone_2" class="block mt-1 w-full" type="text" name="phone_2"
                                value="{{ old('phone_2') }}" />
                        </div>
                        <div>
                            <x-jet-label for="role_ids" :value="__('Roles')" />
                            <x-input-select id="role_ids" name="role_ids[]" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </x-input-select>
                        </div>
                        <div>
                            <x-jet-label for="password" :value="__('Password')" />
                            <x-jet-input id="password" class="block mt-1 w-full" type="password" required name="password"
                                value="{{ old('password') }}" />
                        </div>
                        <div>
                            <x-jet-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" required name="password_confirmation"
                                value="{{ old('password_confirmation') }}" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-jet-button class="ml-4">
                            {{ __('Save') }}
                        </x-jet-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
