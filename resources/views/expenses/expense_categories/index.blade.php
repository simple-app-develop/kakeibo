<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Item category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-category-tabs>
                    <x-slot name="expense">
                        @if ($categories->where('type', 'expense')->isEmpty())
                            <div>{{ __('No expense categories found.') }}</div>
                        @else
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($categories->where('type', 'expense') as $category)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $category->type }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $category->name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $category->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </x-slot>

                    <x-slot name="income">
                        @if ($categories->where('type', 'income')->isEmpty())
                            <div>{{ __('No income categories found.') }}</div>
                        @else
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($categories->where('type', 'income') as $category)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $category->type }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $category->name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap">{{ $category->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </x-slot>
                </x-category-tabs>
            </div>
        </div>
    </div>
</x-app-layout>