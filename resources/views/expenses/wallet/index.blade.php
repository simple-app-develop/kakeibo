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
                                    <a href="{{ route('wallet.edit', $wallet->id) }}"
                                        class="px-4 py-2 ml-4 text-white bg-blue-600 rounded hover:bg-blue-700">{{ __('Edit') }}</a>
                                    <form action="{{ route('wallet.destroy', $wallet->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this wallet?');">Delete</button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
