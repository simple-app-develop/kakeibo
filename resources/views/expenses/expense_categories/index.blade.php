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

    <!-- モーダル -->
    <div id="deleteModal"
        class="fixed inset-0 w-full h-full z-20 bg-black bg-opacity-50 duration-300 overflow-y-auto hidden">
        <div class="relative p-6 mx-auto mt-20 text-left bg-white border-0 rounded-lg w-96">
            <span class="block w-full text-xl leading-6 font-medium text-gray-900">
                このカテゴリを削除しますか？
            </span>
            <div class="mt-5">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 text-white bg-red-600 rounded hover:bg-red-700">削除</button>
                    <button type="button"
                        class="px-6 py-2 ml-4 text-red-600 border border-red-600 rounded hover:text-white hover:bg-red-600"
                        onclick="toggleModal()">キャンセル</button>
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
