<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finances') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="table">
                    <thead>
                        <tr>
                            <th>日付</th>
                            <th>カテゴリ</th>
                            <th>金額</th>
                            <th>説明</th>
                            <th>支払方法</th>
                            <th>計上日</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($finances as $finance)
                            <tr>
                                <td>{{ $finance->date->format('Y-m-d') }}</td>
                                <td>{{ optional($finance->expenseCategory)->name }}</td>
                                <td>{{ number_format($finance->amount) }}円</td>
                                <td>{{ $finance->description }}</td>
                                <td>{{ optional($finance->paymentMethod)->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($finance->reflected_date)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
