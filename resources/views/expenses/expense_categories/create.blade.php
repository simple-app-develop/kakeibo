<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Item category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('expense-category-store') }}">
                    @csrf

                    <!-- Type Selection -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="type" value="{{ __('Type') }}" />
                        <select id="type" name="type"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="income">{{ __('Income') }}</option>
                            <option value="expense">{{ __('Expense') }}</option>
                        </select>
                    </div>

                    <!-- Category Name -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="name" value="{{ __('Category Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" name="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Category Description -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="description" value="{{ __('Description') }}" />
                        <x-textarea id="description" class="mt-1 block w-full" name="description"></x-textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="p-6">
                        <x-button type="submit">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
