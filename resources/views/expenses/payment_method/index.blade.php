<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if ($paymentMethods->isEmpty())
                    <div>{{ __('No payment methods found.') }}</div>
                @else
                    <div class="min-w-full divide-y divide-gray-200" id="paymentMethodList">
                        @foreach ($paymentMethods as $method)
                            <div class="payment-method-item flex justify-between items-center"
                                data-id="{{ $method->id }}">
                                <div>
                                    <span class="px-6 py-4 whitespace-no-wrap method-name">
                                        {{ $method->name }}
                                    </span>
                                    @if ($method->closing_date !== null)
                                        <div class="flex flex-col px-6">
                                            <span>{{ __('Closing Date') . $method->closing_date }}:</span>
                                            <span>{{ __('Payment Date') . $method->payment_date }}:</span>
                                            <span>
                                                {{ __('Payment Month Offset') }}:
                                                @switch($method->month_offset)
                                                    @case(0)
                                                        {{ __('This Month') }}
                                                    @break

                                                    @case(1)
                                                        {{ __('Next Month') }}
                                                    @break

                                                    @case(2)
                                                        {{ __('Month after Next') }}
                                                    @break

                                                    @case(3)
                                                        {{ __('3 Months Later') }}
                                                    @break
                                                @endswitch
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                @if ($isPermission)
                                    <div class="actions">
                                        <a href="{{ route('payment-method.edit', $method->id) }}"
                                            class="px-4 py-2 ml-4 text-white bg-blue-600 rounded hover:bg-blue-700">{{ __('Edit') }}</a>

                                        <button
                                            onclick="showDeleteModal('{{ route('payment-method.destroy', $method->id) }}')"
                                            class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">{{ __('Delete') }}</button>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- FABボタンの追加 -->
    @if ($isPermission)
        <a href="{{ route('payment-method.create') }}" class="create_fab">+</a>
    @endif

    <!-- 削除確認モーダル -->
    <div id="deleteModal"
        class="opacity-0 hidden fixed inset-0 z-40 w-full h-full transition-opacity duration-300 bg-black bg-opacity-50">
        <div class="relative p-6 mx-auto mt-20 text-left bg-white border-0 rounded-lg w-96 bottom-0">
            <!-- モーダルの内容 -->
            <div>
                <h3 class="text-xl font-bold">{{ __('Delete') }}</h3>
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-500">
                    {{ __('delete') }}
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
        window.isPermission = @json($isPermission);

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
    <script src="{{ asset('script/payment-method-sortable.js') }}"></script>
</x-app-layout>
