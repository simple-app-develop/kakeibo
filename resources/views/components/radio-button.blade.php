@props(['label', 'id', 'name', 'value', 'checked' => false])

<div class="mt-4 flex items-center">
    <input id="{{ $id }}" type="radio" name="{{ $name }}" value="{{ $value }}"
        {{ $checked ? 'checked' : '' }} {!! $attributes->merge(['class' => 'form-radio h-5 w-5 text-indigo-600']) !!}>
    <label for="{{ $id }}" class="ml-2 cursor-pointer py-2 px-4 block w-full">{{ $label }}</label>
</div>
