<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Expense Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('expense-category-update', $category->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <!-- Category Type - This is disabled so the user cannot edit it. -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="type" value="{{ __('Category Type') }}" />
                        <x-input id="type" type="text" class="mt-1 block w-full" name="type"
                            value="{{ $category->type }}" disabled />
                        <x-input-error for="type" class="mt-2" />
                    </div>

                    <!-- Category Name -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="name" value="{{ __('Category Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" name="name"
                            value="{{ old('name', $category->name) }}" required />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Category Description -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="description" value="{{ __('Description') }}" />
                        <x-textarea id="description" name="description"
                            class="mt-1 block w-full">{{ old('description', $category->description) }}</x-textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="p-6">
                        <x-button type="submit">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
