<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallet(bank)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                {{ __('Name') }}
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                {{ __('Initial Balance') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $wallet)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $wallet->name }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $wallet->balance }}
                                    @if ($permissions['canUpdate'])
                                        <a href="{{ route('wallet.edit', $wallet->id) }}"
                                            class="px-4 py-2 ml-4 text-white bg-blue-600 rounded hover:bg-blue-700">{{ __('Edit') }}</a>
                                    @else
                                        <span class="px-4 py-2 ml-4 bg-gray-400 rounded">{{ __('Edit') }}</span>
                                    @endif

                                    @if ($permissions['canDelete'])
                                        <button onclick="showDeleteModal('{{ route('wallet.destroy', $wallet->id) }}')"
                                            class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">{{ __('Delete') }}</button>
                                    @else
                                        <span class="px-4 py-2 ml-4 bg-gray-400 rounded">{{ __('Delete') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- FABボタンの追加 -->
    @if ($permissions['canCreate'])
        <a href="{{ route('wallet.create') }}" class="create_fab">+</a>
    @else
        <span class="create_fab bg-gray-400">+</span>
    @endif

    <!-- Wallet Deletion Confirmation Modal -->
    <div id="walletDeleteModal"
        class="opacity-0 hidden fixed inset-0 z-40 w-full h-full transition-opacity duration-300 bg-black bg-opacity-50">
        <div class="relative p-6 mx-auto mt-20 text-left bg-white border-0 rounded-lg w-96 bottom-0">
            <!-- Modal Content -->
            <div>
                <h3 class="text-xl font-bold">{{ __('Delete Wallet') }}</h3>
            </div>
            <div class="mt-3">
                <p class="text-sm text-gray-500">
                    {{ __('Are you sure you want to delete this wallet? This action cannot be undone.') }}
                </p>
            </div>
            <div class="flex justify-between mt-5">
                <button onclick="toggleWalletDeleteModal()"
                    class="px-4 py-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-100">{{ __('Cancel') }}</button>
                <form id="walletDeleteForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleWalletDeleteModal() {
            const modal = document.getElementById('walletDeleteModal');
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
            document.getElementById('walletDeleteForm').action = route;
            toggleWalletDeleteModal();
        }
    </script>

</x-app-layout>
