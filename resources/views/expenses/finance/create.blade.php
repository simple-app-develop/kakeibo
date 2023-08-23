<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('家計簿データ登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('finance.store') }}">
                    @csrf

                    <div x-data="{ transactionType: '{{ old('transaction_type', 'expense') }}' }">

                        <!-- Transaction Type Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('Type') }}" />
                            <x-radio-button label="{{ __('Expense') }}" id="type-expense" name="transaction_type"
                                value="expense" :checked="old('transaction_type', 'expense') === 'expense'" x-model="transactionType"
                                x-on:click="document.getElementById('category').selectedIndex = 0"></x-radio-button>
                            <x-radio-button label="{{ __('Income') }}" id="type-income" name="transaction_type"
                                value="income" :checked="old('transaction_type', 'expense') === 'income'" x-model="transactionType"
                                x-on:click="document.getElementById('category').selectedIndex = 0"></x-radio-button>
                            <x-input-error for="transaction_type" class="mt-2" />
                        </div>

                        <!-- Category Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="category" value="{{ __('Category') }}" />
                            <x-select-input id="category" name="category">
                                <option value="" {{ old('category') == '' ? 'selected' : '' }}>
                                    {{ __('Uncategorized') }}
                                </option>

                                @foreach ($expenseCategories as $index => $category)
                                    <option value="{{ $category->id }}" x-bind:disabled="transactionType !== 'expense'"
                                        x-bind:hidden="transactionType !== 'expense'"
                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                                @foreach ($incomeCategories as $index => $category)
                                    <option value="{{ $category->id }}" x-bind:disabled="transactionType !== 'income'"
                                        x-bind:hidden="transactionType !== 'income'"
                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error for="category" class="mt-2" />
                        </div>


                        <!-- Payment Method Selection for Expenses -->
                        <div x-show="transactionType === 'expense'" class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="payment_method" value="{{ __('Payment Method') }}" />
                            <x-select-input id="payment_method" name="payment_method">
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ old('payment_method') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error for="payment_method" class="mt-2" />
                        </div>

                        <!-- Wallet Selection for Income -->
                        <div x-show="transactionType === 'income'" class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="wallet" value="{{ __('Wallet') }}" />
                            <x-select-input id="wallet" name="wallet_id">
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}"
                                        {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error for="wallet_id" class="mt-2" />
                        </div>


                        <!-- Amount -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="amount" value="{{ __('Amount') }}" />
                            <x-input id="amount" type="number" class="mt-1 block w-full" name="amount"
                                value="{{ old('amount') }}" required />
                            <x-input-error for="amount" class="mt-2" />
                        </div>

                        <!-- Date Input -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="date" value="{{ __('Date') }}" />
                            <x-input id="date" type="date" class="mt-1 block w-full" name="date"
                                value="{{ old('date', now()->toDateString()) }}" required />
                            <x-input-error for="date" class="mt-2" />
                        </div>

                        <!-- Description Textarea -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <x-textarea id="description" name="description"
                                class="mt-1 block w-full">{{ old('description') }}</x-textarea>
                            <x-input-error for="description" class="mt-2" />
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
