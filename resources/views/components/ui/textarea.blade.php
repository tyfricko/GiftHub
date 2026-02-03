@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => '',
    'disabled' => false,
    'required' => false,
    'rows' => 4,
    'error' => false,
])

<textarea
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    placeholder="{{ $placeholder }}"
    rows="{{ $rows }}"
    @if($disabled) disabled @endif
    @if($required) required @endif
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-3 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-accent-600 focus:border-accent-600 transition duration-150 text-body text-neutral-700 resize-none ' .
            ($error ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-neutral-200') .
            ($disabled ? 'opacity-50 cursor-not-allowed bg-neutral-100' : '') .
            ' dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-600 dark:focus:ring-accent-500 dark:focus:border-accent-500'
    ]) }}
>{{ $value ?? '' }}</textarea>
