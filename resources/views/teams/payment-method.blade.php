<x-form-section submit="">
    <x-slot name="title">
        <div class="label-with-tooltip">
            {{ __('Payment Method') }}<span class="tooltip">
                <span class="tooltip-text">
                    {{ __('This specifically determines from which "wallet" (bank) the money will be disbursed. This is a tool to ensure that the difference between the date of purchase and the date of posting is properly tracked and managed, especially when the date of purchase and the date of posting are different, as is the case with credit cards.') }}
                </span>
            </span>
        </div>
    </x-slot>

    <x-slot name="description">
        {{ __('Can edit the payment method used by your team') }}
    </x-slot>

    <x-slot name="form">
        <div style="right:10px;">
            <a href="{{ route('payment-method.index') }}" class="ml-2 text-sm text-gray-400 underline whitespace-nowrap">
                {{ __('Payment Method') }}
            </a>
        </div>
    </x-slot>
</x-form-section>
