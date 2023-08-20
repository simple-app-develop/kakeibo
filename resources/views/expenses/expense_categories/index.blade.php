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
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $category->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $category->description }}
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
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $category->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $category->description }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-slot>
                </x-category-tabs>

                <script>
                    document.addEventListener("DOMContentLoaded", () => {

                        const updateCategoryOrder = (elementId, reorderUrl) => {
                            const categoryList = document.getElementById(elementId);
                            if (!categoryList) return;

                            Sortable.create(categoryList, {
                                animation: 150,
                                onEnd: function() {
                                    const order = [];
                                    categoryList.querySelectorAll('[data-id]').forEach(function(item) {
                                        order.push(item.dataset.id);
                                    });

                                    // サーバーに新しい順序を送信
                                    fetch(reorderUrl, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]').getAttribute('content')
                                            },
                                            body: JSON.stringify({
                                                order: order
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.message === 'Order updated successfully') {
                                                console.log('Order updated on backend');
                                            } else {
                                                console.error('Error updating order on backend:', data
                                                    .message);
                                            }
                                        });
                                }
                            });
                        };

                        updateCategoryOrder('expenseCategoryList', '/expense-category/reorder');
                        updateCategoryOrder('incomeCategoryList', '/expense-category/reorder');
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
