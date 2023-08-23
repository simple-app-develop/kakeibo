<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('wallet.store') }}">
                    @csrf

                    <!-- Wallet Name -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="name" value="{{ __('Wallet Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" name="name" :value="old('name')"
                            required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Initial Balance -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="balance" value="{{ __('Initial Balance') }}" />
                        <x-input id="balance" type="number" class="mt-1 block w-full" name="balance"
                            :value="old('balance')" required />
                        <x-input-error for="balance" class="mt-2" />
                    </div>

                    <div class="p-6">
                        <x-button type="submit">
                            {{ __('Register') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
