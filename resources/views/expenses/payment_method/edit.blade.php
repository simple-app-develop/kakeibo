<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('payment-method.update', $paymentMethod->id) }}">
                    @csrf
                    @method('PATCH')

                    <!-- Payment Method Name -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label for="name" value="{{ __('Payment Method Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" name="name"
                            value="{{ $paymentMethod->name }}" required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Payment Type Display -->
                    <div class="col-span-6 sm:col-span-4 p-6">
                        <x-label value="{{ __('Payment Type') }}" />
                        <div>
                            {{ $paymentMethod->closing_date !== null ? __('Credit Card') : __('Other') }}
                        </div>
                    </div>

                    @if ($paymentMethod->closing_date !== null)
                        <!-- Closing Date Display -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('Closing Date') }}" />
                            <div>
                                {{ $paymentMethod->closing_date }}
                            </div>
                        </div>

                        <!-- Payment Month Offset Display -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label :value="__('Select Payment Month Offset')" />
                            <div>
                                @switch($paymentMethod->month_offset)
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

                                    @default
                                        {{ __('Unknown') }}
                                @endswitch
                            </div>
                        </div>

                        <!-- Payment Date Display -->
                        <div class="col-span-6 sm:col-span-4 p-6">
                            <x-label value="{{ __('Payment Date') }}" />
                            <div>
                                {{ $paymentMethod->payment_date }}
                            </div>
                        </div>
                    @endif

                    <div class="p-6">
                        <x-button type="submit">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
