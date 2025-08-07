@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => '',
    'disabled' => false,
    'required' => false,
    'autocomplete' => null,
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    @if($disabled) disabled @endif
    @if($required) required @endif
    @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray'
    ]) }}
/>