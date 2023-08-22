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

                    <div x-data="{ transactionType: '{{ $finance->transaction_type }}', category: $refs.categorySelect }">

                        <!-- Transaction Type Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('取引のタイプ') }}" />
                            <input type="radio" x-model="transactionType" value="expense" name="transaction_type"
                                x-on:click="setCategory('expense')"
                                {{ $finance->transaction_type == 'expense' ? 'checked' : '' }}>
                            {{ __('支出') }}
                            <input type="radio" x-model="transactionType" value="income" name="transaction_type"
                                x-on:click="setCategory('income')"
                                {{ $finance->transaction_type == 'income' ? 'checked' : '' }}>
                            {{ __('収入') }}
                            <x-input-error for="transaction_type" class="mt-2" />
                        </div>

                        <!-- Category Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="category" value="{{ __('カテゴリ') }}" />
                            <select id="category" x-ref="categorySelect" class="mt-1 block w-full" name="category">
                                @foreach ($expenseCategories as $category)
                                    <option x-bind:disabled="transactionType !== 'expense'"
                                        x-bind:class="transactionType !== 'expense' ? 'hidden' : 'block'"
                                        value="{{ $category->id }}"
                                        {{ $finance->expense_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                                @foreach ($incomeCategories as $category)
                                    <option x-bind:disabled="transactionType !== 'income'"
                                        x-bind:class="transactionType !== 'income' ? 'hidden' : 'block'"
                                        value="{{ $category->id }}"
                                        {{ $finance->expense_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="category" class="mt-2" />
                        </div>

                        <!-- Payment Method Selection for Expenses -->
                        <div x-show="transactionType === 'expense'" class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="payment_method" value="{{ __('支払い方法') }}" />
                            <select id="payment_method" class="mt-1 block w-full" name="payment_method">
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ $finance->payment_method_id == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="payment_method" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="amount" value="{{ __('金額') }}" />
                            <x-input id="amount" type="number" class="mt-1 block w-full" name="amount" required
                                value="{{ $finance->amount }}" />
                            <x-input-error for="amount" class="mt-2" />
                        </div>

                        <!-- Date Input -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="date" value="{{ __('日付') }}" />
                            <x-input id="date" type="date" class="mt-1 block w-full" name="date"
                                value="{{ $finance->date->format('Y-m-d') }}" required />
                            <x-input-error for="date" class="mt-2" />
                        </div>

                        <div class="p-6">
                            <x-button type="submit">
                                {{ __('更新') }}
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

            if (type === 'income') {
                categorySelect.selectedIndex = @json(count($expenseCategories));
            } else {
                categorySelect.selectedIndex = 0;
            }
        }
    </script>
</x-app-layout>
