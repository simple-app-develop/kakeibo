<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    @livewire('finances-table')
                </div>
            </div>
        </div>
    </div>

    @if ($permissions['canCreate'])
        <a href="{{ route('finance.create') }}" class="create_fab">+</a>
    @else
        <span class="create_fab bg-gray-400">+</span>
    @endif

    <!-- 削除確認モーダル -->
    <div id="financeDeleteModal"
        class="opacity-0 hidden fixed inset-0 z-40 w-full h-full transition-opacity duration-300 bg-black bg-opacity-50">
        <div class="relative p-6 mx-auto mt-20 text-left bg-white border-0 rounded-lg w-96 bottom-0">
            <!-- モーダルの内容 -->
            <div>
                <h3 class="text-xl font-bold">{{ __('Delete Finance Data') }}</h3>
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-500">
                    {{ __('Are you sure you want to delete this finance data? This action cannot be undone.') }}
                </p>
            </div>

            <div class="flex justify-between mt-5">
                <button onclick="toggleFinanceDeleteModal()"
                    class="px-4 py-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-100">{{ __('Cancel') }}</button>

                <form id="financeDeleteForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 設定案内モーダル -->
    <div id="settingsGuideModal"
        class="opacity-0 hidden fixed inset-0 z-50 w-full h-full transition-opacity duration-300 bg-black bg-opacity-50">
        <div class="relative p-6 mx-auto mt-20 text-left bg-white border-0 rounded-lg w-96 bottom-0">
            <!-- モーダルの内容 -->
            <div>
                <h3 class="text-xl font-bold">{{ __('Settings Guidance') }}</h3>
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-500">
                    {{ __('It seems you haven\'t set up all the necessary settings. Please proceed to the settings page and complete the setup.') }}
                </p>
                <ul class="text-sm text-gray-500 mt-2">
                    <li>・{{ __('Categories to set up') }}: <span
                            id="categoriesCountDisplay">{{ $settingCounts['categoriesCount'] }}</span></li>

                    <li>・{{ __('Payment Methods to set up') }}: <span
                            id="paymentMethodsCountDisplay">{{ $settingCounts['paymentMethodsCount'] }}</span></li>
                    @if ($settingCounts['paymentMethodsCount'] < 1)
                        <span class="text-red-500">{{ __('(At least one payment method is required)') }}</span>
                    @endif

                    <li>・{{ __('Wallets to set up') }}: <span
                            id="walletsCountDisplay">{{ $settingCounts['walletsCount'] }}</span></li>
                    @if ($settingCounts['walletsCount'] < 1)
                        <span class="text-red-500">{{ __('(At least one wallet is required)') }}</span>
                    @endif

                </ul>
            </div>

            <div class="flex justify-between mt-5">
                <a href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                    class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">{{ __('Go to Settings') }}</a>
                <button onclick="toggleSettingsGuideModal()"
                    class="px-4 py-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-100">{{ __('Not Now') }}</button>
            </div>
        </div>
    </div>


    <script>
        function toggleFinanceDeleteModal() {
            const modal = document.getElementById('financeDeleteModal');
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

        function showFinanceDeleteModal(route) {
            document.getElementById('financeDeleteForm').action = route;
            toggleFinanceDeleteModal();
        }

        function toggleSettingsGuideModal() {
            const modal = document.getElementById('settingsGuideModal');
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

        const categoriesCount = @json($settingCounts['categoriesCount']);
        const paymentMethodsCount = @json($settingCounts['paymentMethodsCount']);
        const walletsCount = @json($settingCounts['walletsCount']);

        if (paymentMethodsCount === 0 || walletsCount === 0) {
            toggleSettingsGuideModal();
        }
    </script>
</x-app-layout>
