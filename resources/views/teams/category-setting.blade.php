<x-form-section submit="">
    <x-slot name="title">
        {{ __('Item category') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Can edit item categories used by the team') }}
    </x-slot>

    <x-slot name="form">
        <div style="right:10px;">
            <a href="{{ route('expense-category-index') }}"
                class="ml-2 text-sm text-gray-400 underline whitespace-nowrap">
                {{ __('Edit Item Category') }}
            </a>
        </div>
    </x-slot>
</x-form-section>
