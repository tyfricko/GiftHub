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
        'class' => 'w-full px-4 py-3 border border-neutral-200 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-accent-600 focus:border-accent-600 transition duration-150 text-body text-neutral-700'
    ]) }}
/>