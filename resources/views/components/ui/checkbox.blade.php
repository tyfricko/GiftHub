@props([
    'name' => null,
    'id' => null,
    'checked' => false,
    'disabled' => false,
    'label' => null,
    'description' => null,
    'error' => false,
])

@php
    $inputId = $id ?? $name;
@endphp

<div class="flex items-start">
    <div class="flex items-center h-5">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId }}"
            {{ $checked ? 'checked' : '' }}
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'w-4 h-4 text-accent-600 border-neutral-300 rounded focus:ring-accent-600 focus:ring-offset-2 transition duration-150 ' .
                    ($error ? 'border-red-500 focus:ring-red-500' : '') .
                    ($disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer') .
                    ' dark:border-neutral-600 dark:bg-neutral-800 dark:focus:ring-accent-500'
            ]) }}
        />
    </div>
    @if ($label || $description)
        <div class="ml-3 text-sm">
            @if ($label)
                <label for="{{ $inputId }}" class="font-medium text-neutral-700 dark:text-neutral-300 {{ $disabled ? 'opacity-50' : '' }}">
                    {{ $label }}
                </label>
            @endif
            @if ($description)
                <p class="text-neutral-500 dark:text-neutral-400">{{ $description }}</p>
            @endif
        </div>
    @endif
</div>
