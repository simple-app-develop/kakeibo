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
                                        <a href="{{ route('expense-category-edit', $category->id) }}"
                                            class="px-4 py-2 ml-4 text-white bg-blue-600 rounded hover:bg-blue-700">{{ __('Edit') }}</a>

                                        <button
                                            onclick="showDeleteModal('{{ route('expense-category-destroy', $category->id) }}')"
                                            class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">{{ __('Delete') }}</button>
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
                                        <a href="{{ route('expense-category-edit', $category->id) }}"
                                            class="px-4 py-2 ml-4 text-white bg-blue-600 rounded hover:bg-blue-700">{{ __('Edit') }}</a>

                                        <button
                                            onclick="showDeleteModal('{{ route('expense-category-destroy', $category->id) }}')"
                                            class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">{{ __('Delete') }}</button>
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
        class="opacity-0 hidden fixed inset-0 z-40 w-full h-full transition-opacity duration-300 bg-black bg-opacity-50">
        <div class="relative p-6 mx-auto mt-20 text-left bg-white border-0 rounded-lg w-96 bottom-0">
            <!-- モーダルの内容 -->
            <div>
                <h3 class="text-xl font-bold">{{ __('Delete Category') }}</h3>
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-500">
                    {{ __('Are you sure you want to delete this category? Once deleted, any data set to this category will be changed to [Uncategorized]. This action cannot be undone.') }}
                </p>
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
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                }, 100);
            } else {
                modal.classList.add('opacity-0');
                modal.addEventListener('transitionend', () => {
                    if (modal.classList.contains('opacity-0')) {
                        modal.classList.add('hidden');
                    }
                }, {
                    once: true
                });
            }
        }

        function showDeleteModal(route) {
            document.getElementById('deleteForm').action = route;
            toggleModal();
        }
    </script>

</x-app-layout>
