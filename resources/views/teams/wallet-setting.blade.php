<x-form-section submit="">
    <x-slot name="title">
        {{ __('Wallet(bank)') }}
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
