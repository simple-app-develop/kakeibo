<div>
    <button wire:click="decrementMonth">前の月</button>
    <span>{{ $this->getCurrentMonthYear() }}</span>
    <button wire:click="incrementMonth">次の月</button>

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
                    <td>{{ optional($finance->expense_category)->name }}</td>
                    <td>{{ number_format($finance->amount) }}円</td>
                    <td>{{ $finance->description }}</td>
                    <td>{{ optional($finance->payment_method)->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($finance->reflected_date)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
