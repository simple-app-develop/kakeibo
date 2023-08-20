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
                            <div class="min-w-full divide-y divide-gray-200" id="expenseCategoryList">
                                @foreach ($categories->where('type', 'expense') as $category)
                                    <div class="category-item" data-id="{{ $category->id }}">
                                        <span class="px-6 py-4 whitespace-no-wrap category-name">
                                            {{ $category->name }}
                                        </span>
                                        <span class="px-6 py-4 whitespace-no-wrap category-description">
                                            {{ $category->description }}
                                        </span>
                                        <button
                                            onclick="showDeleteModal('{{ route('expense-category-destroy', $category->id) }}')"
                                            class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">削除</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-slot>

                    <x-slot name="income">
                        @if ($categories->where('type', 'income')->isEmpty())
                            <div>{{ __('No income categories found.') }}</div>
                        @else
                            <div class="min-w-full divide-y divide-gray-200" id="incomeCategoryList">
                                @foreach ($categories->where('type', 'income') as $category)
                                    <div class="category-item" data-id="{{ $category->id }}">
                                        <span class="px-6 py-4 whitespace-no-wrap category-name">
                                            {{ $category->name }}
                                        </span>
                                        <span class="px-6 py-4 whitespace-no-wrap category-description">
                                            {{ $category->description }}
                                        </span>
                                        <button
                                            onclick="showDeleteModal('{{ route('expense-category-destroy', $category->id) }}')"
                                            class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">削除</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-slot>
                </x-category-tabs>
            </div>
        </div>
    </div>

    {{-- FABボタンの追加 --}}
    <a href="{{ route('expense-category-create') }}" class="fab">+</a>

    <!-- 削除確認モーダル -->
    <div id="deleteModal"
        class="hidden fixed inset-0 z-40 flex items-center justify-center w-full h-full text-center lg:p-8 lg:items-end bg-black bg-opacity-50 sm:p-0">
        <div
            class="flex flex-col w-full h-1/3 p-6 bg-white rounded-t-lg shadow-xl sm:m-2 sm:w-1/3 sm:rounded-lg sm:h-auto">
            <div>
                <h3 class="text-xl font-bold">{{ __('Delete Category') }}</h3>
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-500">
                    {{ __('Are you sure you want to delete this category? This action cannot be undone.') }}</p>
            </div>

            <div class="flex justify-between mt-5">
                <button onclick="toggleModal()"
                    class="px-4 py-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-100">{{ __('Cancel') }}</button>

                <form id="deleteForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.toggle('hidden');
        }

        function showDeleteModal(route) {
            document.getElementById('deleteForm').action = route;
            toggleModal();
        }
    </script>

</x-app-layout>
