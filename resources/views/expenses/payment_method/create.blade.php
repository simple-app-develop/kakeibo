<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('payment-method.store') }}">
                    @csrf

                    <div x-data="{ isCreditCard: '{{ old('isCreditCard') }}' === '1' ? true : false }">

                        <!-- Payment Method Name -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="name" value="{{ __('Payment Method Name') }}" />
                            <x-input id="name" type="text" class="mt-1 block w-full" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="wallet" value="{{ __('Wallet') }}" />
                            <select id="wallet" name="wallet_id">
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="wallet_id" class="mt-2" />
                        </div>


                        <!-- Payment Type Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('Payment Type') }}" />
                            <input type="checkbox" x-model="isCreditCard"
                                :checked="'{{ old('isCreditCard') }}' === '1'">
                            {{ __('Credit Card') }}
                            <x-input-error for="isCreditCard" class="mt-2" />
                        </div>
                        <input type="hidden" name="isCreditCard" :value="isCreditCard ? '1' : '0'">

                        <div x-show="isCreditCard">
                            <!-- Closing Date -->
                            <div class="col-span-6 sm:col-span-4 p-6">
                                <x-label for="closing_date" value="{{ __('Closing Date') }}" />
                                <x-input id="closing_date" type="number" min="1" max="31"
                                    class="mt-1 block w-full" name="closing_date" :value="old('closing_date')" />
                                <x-input-error for="closing_date" class="mt-2" />
                            </div>

                            <!-- select Payment Month Offset -->
                            <div class="col-span-6 sm:col-span-4 p-6">
                                <x-label for="month_offset" :value="__('Select Payment Month Offset')" />

                                <x-select-input id="month_offset" name="month_offset">
                                    <option value="0" {{ old('month_offset') == '0' ? 'selected' : '' }}>
                                        {{ __('This Month') }}</option>
                                    <option value="1" {{ old('month_offset') == '1' ? 'selected' : '' }}>
                                        {{ __('Next Month') }}</option>
                                    <option value="2" {{ old('month_offset') == '2' ? 'selected' : '' }}>
                                        {{ __('Month after Next') }}</option>
                                    <option value="3" {{ old('month_offset') == '3' ? 'selected' : '' }}>
                                        {{ __('3 Months Later') }}</option>
                                </x-select-input>


                                <x-input-error for="month_offset" class="mt-2" />
                            </div>

                            <!-- Payment Date -->
                            <div class="col-span-6 sm:col-span-4 p-6">
                                <x-label for="payment_date" value="{{ __('Payment Date') }}" />
                                <x-input id="payment_date" type="number" min="1" max="31"
                                    class="mt-1 block w-full" name="payment_date" :value="old('payment_date')" />
                                <x-input-error for="payment_date" class="mt-2" />
                            </div>

                        </div>


                        <div class="p-6">
                            <x-button type="submit">
                                {{ __('Register') }}
                            </x-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
