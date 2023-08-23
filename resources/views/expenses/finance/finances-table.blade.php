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
                @if ($hasFinancePermission)
                    <th class="py-2 px-6 text-left">{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @if ($finances->count() > 0)
                @foreach ($finances as $finance)
                    <tr
                        class="border-b border-gray-200 hover:bg-gray-100 {{ !$this->isPastReflectedDate($finance->reflected_date) ? 'opacity-50' : '' }}">
                        <td class="py-2 px-6">{{ $finance->date->format('Y-m-d') }}</td>
                        <td class="py-2 px-6">{{ optional($finance->expense_category)->name }}</td>
                        <td
                            class="py-2 px-6 {{ is_null($finance->payment_method) ? 'text-green-700 font-bold"' : '' }}">
                            {{ number_format($finance->amount) }}{{ __('yen') }}
                        </td>
                        <td class="py-2 px-6">{{ $finance->description }}</td>
                        <td class="py-2 px-6">{{ optional($finance->payment_method)->name }}</td>
                        <td class="py-2 px-6">
                            {{ \Carbon\Carbon::parse($finance->reflected_date)->format('Y-m-d') }}
                        </td>
                        @if ($hasFinancePermission)
                            <td class="py-2 px-6">
                                <a href="{{ route('finance.edit', $finance->id) }}" title="{{ __('Edit') }}"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button
                                    onclick="showFinanceDeleteModal('{{ route('finance.destroy', $finance->id) }}')"
                                    class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">{{ __('Delete') }}</button>
                        @endif
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

    <div class="my-4">
        <table class="min-w-full bg-white table-auto mt-8">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-2 px-6 text-left">{{ __('Item') }}</th>
                    <th class="py-2 px-6 text-left">{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <tr>
                    <td class="py-2 px-6 flex items-center">
                        {{ __('Total income for the month') }}
                    </td>
                    <td class="py-2 px-6 text-green-700 font-bold">
                        {{ number_format($this->getTotalIncome()) }}{{ __('yen') }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-6 flex items-center">
                        {{ __('Total Expenditures for the Month') }}
                    </td>
                    <td class="py-2 px-6 text-red-700 font-bold">
                        {{ number_format($this->getTotalExpense()) }}{{ __('yen') }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-6">{{ __('Planned Expenditures for the Month') }}</td>
                    <td class="py-2 px-6">{{ number_format($this->getScheduledExpense()) }}{{ __('yen') }}</td>
                </tr>
                <tr class="font-semibold">
                    <td class="py-2 px-6">{{ __('Entire total') }}</td>
                    <td class="py-2 px-6">{{ number_format($this->getOverallTotal()) }}{{ __('yen') }}</td>
                </tr>
            </tbody>

        </table>
    </div>


</div>
