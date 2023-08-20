@props(['label', 'id', 'name', 'value', 'checked' => false])

<div class="mt-4 flex items-center">
    <input id="{{ $id }}" type="radio" name="{{ $name }}" value="{{ $value }}"
        {{ $checked ? 'checked' : '' }} {!! $attributes->merge(['class' => 'form-radio h-5 w-5 text-indigo-600']) !!}>
    <x-label :for="$id" :value="$label" class="ml-2" />
</div>
