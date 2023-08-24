<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('wallet.update', $wallet->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Wallet Name -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" name="name" :value="old('name', $wallet->name)"
                            required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Initial Balance (Display Only) -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="balance" value="{{ __('Initial Balance') }}" />
                        <p class="mt-1">{{ $wallet->balance }}</p>
                    </div>

                    <div class="p-6">
                        <x-button type="submit">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
