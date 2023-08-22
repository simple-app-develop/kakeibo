<x-form-section submit="">
    <x-slot name="title">
        {{ __('Payment Method') }}
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
