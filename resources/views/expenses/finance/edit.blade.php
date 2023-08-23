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

                    <div x-data="{ transactionType: '{{ old('transaction_type', is_null($finance->payment_method_id) ? 'income' : 'expense') }}', category: $refs.categorySelect }">

                        <!-- Transaction Type Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('取引のタイプ') }}" />
                            <input type="radio" x-model="transactionType" value="expense" name="transaction_type"
                                x-on:click="setCategory('expense')"
                                {{ old('transaction_type', $finance->payment_method_id ? 'expense' : 'income') == 'expense' ? 'checked' : '' }}>
                            {{ __('支出') }}

                            <input type="radio" x-model="transactionType" value="income" name="transaction_type"
                                x-on:click="setCategory('income')"
                                {{ old('transaction_type', $finance->payment_method_id ? 'expense' : 'income') == 'income' ? 'checked' : '' }}>
                            {{ __('収入') }}

                            <x-input-error for="transaction_type" class="mt-2" />
                        </div>

                        <!-- Category Selection -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="category" value="{{ __('カテゴリ') }}" />
                            <select id="category" x-ref="categorySelect" class="mt-1 block w-full" name="category">
                                <option value=""
                                    {{ old('category', $finance->expense_category_id) == '' ? 'selected' : '' }}>
                                    {{ __('未分類') }}
                                </option>
                                @foreach ($expenseCategories as $category)
                                    <option x-bind:disabled="transactionType !== 'expense'"
                                        x-bind:class="transactionType !== 'expense' ? 'hidden' : 'block'"
                                        value="{{ $category->id }}"
                                        {{ old('category', $finance->expense_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                                @foreach ($incomeCategories as $category)
                                    <option x-bind:disabled="transactionType !== 'income'"
                                        x-bind:class="transactionType !== 'income' ? 'hidden' : 'block'"
                                        value="{{ $category->id }}"
                                        {{ old('category', $finance->expense_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
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
                                        {{ old('payment_method', $finance->payment_method_id) == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="payment_method" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="amount" value="{{ __('金額') }}" />
                            <x-input id="amount" type="number" class="mt-1 block w-full" name="amount" required
                                value="{{ old('amount', $finance->amount) }}" />
                            <x-input-error for="amount" class="mt-2" />
                        </div>

                        <!-- Date Input -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="date" value="{{ __('日付') }}" />
                            <x-input id="date" type="date" class="mt-1 block w-full" name="date"
                                value="{{ old('date', $finance->date->format('Y-m-d')) }}" required />
                            <x-input-error for="date" class="mt-2" />
                        </div>

                        <!-- Description Textarea -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label for="description" value="{{ __('詳細') }}" />
                            <textarea id="description" class="mt-1 block w-full form-input rounded-md shadow-sm" name="description">{{ old('description', $finance->description) }}</textarea>
                            <x-input-error for="description" class="mt-2" />
                        </div>

                        <div class="p-6">
                            <x-button type="submit">
                                {{ __('Update') }}
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
