<x-form-section submit="">
    <x-slot name="title">
        <div class="label-with-tooltip">
            {{ __('Wallet(bank)') }}<span class="tooltip">
                <span class="tooltip-text">
                    {{ __('This refers to where the money is actually kept. Specifically, we maintain bank accounts, wallets on hand, and all other places where money comes in and out of the bank.') }}
                </span>
            </span>
        </div>
    </x-slot>

    <x-slot name="description">
        {{ __('You can edit the wallets (bank) used by your team.') }}
    </x-slot>

    <x-slot name="form">
        <div style="right:10px;">
            <a href="{{ route('wallet.index') }}" class="ml-2 text-sm text-gray-400 underline whitespace-nowrap">
                {{ __('Wallet(bank)') }}
            </a>
        </div>
    </x-slot>
</x-form-section>
