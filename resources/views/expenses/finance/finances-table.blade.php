<div class="bg-white p-4 rounded-md shadow-sm">
    <div class="flex justify-between items-center mb-4">
        <button wire:click="decrementMonth"
            class="px-4 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-md shadow-sm">
            {{ __('finances.previous_month') }}
        </button>

        <span class="text-lg font-semibold">{{ $this->getCurrentMonthYear() }}</span>

        <button wire:click="incrementMonth"
            class="px-4 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-md shadow-sm">
            {{ __('finances.next_month') }}
        </button>

    </div>

    <table class="min-w-full bg-white table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-2 px-6 text-left">{{ __('Date') }}</th>
                <th class="py-2 px-6 text-left">{{ __('Category') }}</th>
                <th class="py-2 px-6 text-left">{{ __('Amount') }}</th>
                <th class="py-2 px-6 text-left">{{ __('Description') }}</th>
                <th class="py-2 px-6 text-left">{{ __('Payment Method') }}</th>
                <th class="py-2 px-6 text-left">{{ __('Reflected Date') }}</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @if ($finances->count() > 0)
                @foreach ($finances as $finance)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-2 px-6">{{ $finance->date->format('Y-m-d') }}</td>
                        <td class="py-2 px-6">{{ optional($finance->expense_category)->name }}</td>
                        <td class="py-2 px-6">{{ number_format($finance->amount) }}円</td>
                        <td class="py-2 px-6">{{ $finance->description }}</td>
                        <td class="py-2 px-6">{{ optional($finance->payment_method)->name }}</td>
                        <td class="py-2 px-6">{{ \Carbon\Carbon::parse($finance->reflected_date)->format('Y-m-d') }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="py-2 px-6 text-center">@lang('finances.no_data')</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
