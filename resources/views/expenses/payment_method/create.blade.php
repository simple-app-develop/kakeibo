<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('payment-method-store') }}">
                    @csrf

                    <!-- Payment Method Name -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="name" value="{{ __('Payment Method Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" name="name" :value="old('name')"
                            required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="month_offset" value="{{ __('Select Payment Month Offset') }}" />
                        <select id="month_offset" name="month_offset" class="mt-1 block w-full">
                            <option value="0">This Month</option>
                            <option value="1">Next Month</option>
                            <option value="2">Month after Next</option>
                            <option value="3">3 Months Later</option>
                        </select>
                        <x-input-error for="month_offset" class="mt-2" />
                    </div>


                    <!-- Closing Date -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="closing_date" value="{{ __('Closing Date (Optional for cash)') }}" />
                        <x-input id="closing_date" type="number" min="1" max="31"
                            class="mt-1 block w-full" name="closing_date" :value="old('closing_date')" />
                        <x-input-error for="closing_date" class="mt-2" />
                    </div>

                    <!-- Payment Date -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="payment_date" value="{{ __('Payment Date (Optional for cash)') }}" />
                        <x-input id="payment_date" type="number" min="1" max="31"
                            class="mt-1 block w-full" name="payment_date" :value="old('payment_date')" />
                        <x-input-error for="payment_date" class="mt-2" />
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
