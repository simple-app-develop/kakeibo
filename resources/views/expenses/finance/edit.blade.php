<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('家計簿データ更新') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('finance.update', $finance->id) }}">
                    @csrf
                    @method('PUT')

                    <div x-data="{ transactionType: '{{ old('transaction_type', $finance->type) }}' }">

                        <!-- Transaction Type Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('Type') }}" />
                            <x-radio-button label="{{ __('Expense') }}" id="type-expense" name="transaction_type"
                                value="expense" :checked="old(
                                    'transaction_type',
                                    $finance->payment_method_id ? 'expense' : 'income',
                                ) === 'expense'" x-model="transactionType"
                                x-on:click="document.getElementById('category').selectedIndex = 0"></x-radio-button>
                            <x-radio-button label="{{ __('Income') }}" id="type-income" name="transaction_type"
                                value="income" :checked="old(
                                    'transaction_type',
                                    $finance->payment_method_id ? 'expense' : 'income',
                                ) === 'income'" x-model="transactionType"
                                x-on:click="document.getElementById('category').selectedIndex = 0"></x-radio-button>
                            <x-radio-button label="{{ __('Transfer') }}" id="type-transfer" name="transaction_type"
                                value="transfer" :checked="old('transaction_type', $finance->type) === 'transfer'" x-model="transactionType"
                                x-on:click="setCategory('transfer')"></x-radio-button>

                            <x-input-error for="transaction_type" class="mt-2" />
                        </div>

                        <!-- Category Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6" x-show="transactionType !== 'transfer'">
                            <x-label for="category" value="{{ __('Category') }}" />
                            <x-select-input id="category" name="category">
                                <option value=""
                                    {{ old('category', $finance->expense_category_id) == '' ? 'selected' : '' }}>
                                    {{ __('Uncategorized') }}
                                </option>

                                @foreach ($expenseCategories as $index => $category)
                                    <option value="{{ $category->id }}" x-bind:disabled="transactionType !== 'expense'"
                                        x-bind:hidden="transactionType !== 'expense'"
                                        {{ old('category', $finance->expense_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                                @foreach ($incomeCategories as $index => $category)
                                    <option value="{{ $category->id }}" x-bind:disabled="transactionType !== 'income'"
                                        x-bind:hidden="transactionType !== 'income'"
                                        {{ old('category', $finance->expense_category_id) == $category->id ? 'selected' : '' }}>
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
                                        {{ old('payment_method', $finance->payment_method_id) == $method->id ? 'selected' : '' }}>
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
                                        {{ old('wallet_id', $finance->wallet_id) == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error for="wallet_id" class="mt-2" />
                        </div>

                        <!-- Source Wallet Selection -->
                        <div x-show="transactionType === 'transfer'" class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="source_wallet" value="{{ __('Source Wallet') }}" />
                            <x-select-input id="source_wallet" name="wallet_id">
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}"
                                        {{ old('wallet_id', $finance->wallet_id) == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error for="wallet_id" class="mt-2" />
                        </div>

                        <!-- Target Wallet Selection -->
                        <div x-show="transactionType === 'transfer'" class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="target_wallet" value="{{ __('Target Wallet') }}" />
                            <x-select-input id="target_wallet" name="target_wallet_id">
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}"
                                        {{ old('target_wallet_id', $finance->target_wallet_id) == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                            <x-input-error for="target_wallet_id" class="mt-2" />
                        </div>


                        <!-- Amount -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="amount" value="{{ __('Amount') }}" />
                            <x-input id="amount" type="number" class="mt-1 block w-full" name="amount"
                                value="{{ old('amount', $finance->amount) }}" required />
                            <x-input-error for="amount" class="mt-2" />
                        </div>

                        <!-- Date Input -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="date" value="{{ __('Date') }}" />
                            <x-input id="date" type="date" class="mt-1 block w-full" name="date"
                                value="{{ old('date', $finance->date->format('Y-m-d')) }}" required />
                            <x-input-error for="date" class="mt-2" />
                        </div>

                        <!-- Description Textarea -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <x-textarea id="description" name="description"
                                class="mt-1 block w-full">{{ old('description', $finance->description) }}</x-textarea>
                            <x-input-error for="description" class="mt-2" />
                        </div>

                        <div class="p-6">
                            <x-button type="submit">
                                {{ __('Edit') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function setCategory(type) {
            let categorySelect = document.getElementById('category');
            categorySelect.selectedIndex = 0;
        }
    </script>
</x-app-layout>
