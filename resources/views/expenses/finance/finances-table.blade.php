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
                <th class="py-2 px-6 text-left fixed-width">{{ __('Date') }}</th>
                <th class="py-2 px-6 text-left">{{ __('Category') }}</th>
                <th class="py-2 px-6 text-right fixed-width">{{ __('Amount') }}</th>
                <th class="py-2 px-6 text-left hidden md:table-cell">{{ __('Description') }}</th>
                <th class="py-2 px-6 text-left fixed-width hidden md:table-cell">{{ __('Payment Method') }}</th>
                <th class="py-2 px-6 text-left fixed-width hidden md:table-cell">{{ __('Reflected Date') }}</th>
                <th class="py-2 px-6 text-left @if (!$hasFinancePermission) md:hidden @endif">
                    {{ __('Action') }}
                </th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach ($finances as $finance)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <!-- The rest of your table cells for each record -->
                    <td class="py-2 px-6 text-left">{{ $finance->date->format('Y-m-d') }}</td>
                    <td class="py-2 px-6 text-left">{{ optional($finance->expense_category)->name }}</td>
                    <td class="py-2 px-6 text-right">{{ number_format($finance->amount) }}{{ __('yen') }}</td>
                    <td class="py-2 px-6 text-left hidden md:table-cell">{{ $finance->description }}</td>
                    <td class="py-2 px-6 text-left fixed-width hidden md:table-cell">
                        {{ optional($finance->payment_method)->name }}</td>
                    <td class="py-2 px-6 text-left fixed-width hidden md:table-cell">
                        {{ \Carbon\Carbon::parse($finance->reflected_date)->format('Y-m-d') }}</td>

                    <!-- Action Button -->
                    <td class="py-2 px-6 text-left @if (!$hasFinancePermission) md:hidden @endif">
                        <div class="md:hidden">
                            <button class="text-blue-500 hover:text-blue-700" onclick="toggleDetails(this)">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                        @if ($hasFinancePermission)
                            <!-- This will be visible only on desktop -->
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('finance.edit', $finance->id) }}"
                                    class="text-blue-500 hover:text-blue-700 md:block hidden">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button
                                    onclick="showFinanceDeleteModal('{{ route('finance.destroy', $finance->id) }}')"
                                    class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700 md:block hidden">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        @endif
                    </td>
                </tr>
                <!-- Details Section for Mobile -->
                <tr class="details-section hidden md:hidden bg-gray-100">
                    <td colspan="8">
                        <ul class="px-4 py-2 border rounded-md shadow-sm">
                            <li>{{ __('Description') }}: {{ $finance->description }}</li>
                            <li>{{ __('Payment Method') }}: {{ optional($finance->payment_method)->name }}</li>
                            <li>{{ __('Reflected Date') }}:
                                {{ \Carbon\Carbon::parse($finance->reflected_date)->format('Y-m-d') }}</li>
                            @if ($hasFinancePermission)
                                <li>
                                    <a href="{{ route('finance.edit', $finance->id) }}"
                                        class="text-blue-500 hover:text-blue-700">
                                        Edit
                                    </a>
                                    <button
                                        onclick="showFinanceDeleteModal('{{ route('finance.destroy', $finance->id) }}')"
                                        class="px-4 py-2 ml-4 text-white bg-red-600 rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="my-4">
        <table class="min-w-full bg-white table-auto mt-8">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-2 px-6 text-left">{{ __('Item') }}</th>
                    <th class="py-2 px-6 text-right fixed-width">{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <tr>
                    <td class="py-2 px-6 text-left">{{ __('Total income for the month') }}</td>
                    <td class="py-2 px-6 text-right fixed-width">
                        {{ number_format($this->getTotalIncome()) }}{{ __('yen') }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-6 text-left">{{ __('Total Expenditures for the Month') }}</td>
                    <td class="py-2 px-6 text-right fixed-width">
                        {{ number_format($this->getTotalExpense()) }}{{ __('yen') }}</td>
                </tr>
                @if ($this->getScheduledExpense() > 0)
                    <tr>
                        <td class="py-2 px-6 text-left">{{ __('Planned Expenditures for the Month') }}</td>
                        <td class="py-2 px-6 text-right fixed-width">
                            {{ number_format($this->getScheduledExpense()) }}{{ __('yen') }}
                            <button wire:click="toggleScheduledExpenseDetails">{{ __('See more...') }}</button>
                        </td>
                    </tr>
                    @if ($showScheduledExpenseDetails)
                        <tr>
                            <td class="py-2 px-6" colspan="2">
                                @foreach ($scheduledExpenseDetails as $detail)
                                    <ul>
                                        <li>
                                            {{ $detail->date->format('Y-m-d') }}
                                            {{ optional($detail->expense_category)->name }}:
                                            {{ number_format($detail->amount) }}{{ __('yen') }}
                                            {{ $detail->reflected_date }}
                                        </li>
                                    </ul>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endif
                <tr class="font-semibold">
                    <td class="py-2 px-6 text-left">{{ __('Entire total') }}</td>
                    <td class="py-2 px-6 text-right fixed-width">
                        {{ number_format($this->getOverallTotal()) }}{{ __('yen') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function toggleDetails(button) {
            const detailsSection = button.closest('tr').nextElementSibling;
            if (detailsSection.classList.contains('details-section')) {
                if (detailsSection.style.display === 'none' || detailsSection.style.display === '') {
                    detailsSection.style.display = 'table-row';
                } else {
                    detailsSection.style.display = 'none';
                }
            }
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                document.querySelectorAll('.details-section').forEach(section => {
                    section.style.display = 'none';
                });
            }
        });
    </script>

</div>
