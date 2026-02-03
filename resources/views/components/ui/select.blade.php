@props([
    'name' => null,
    'id' => null,
    'selected' => null,
    'placeholder' => 'Select an option',
    'disabled' => false,
    'required' => false,
    'options' => [],
])

@php
    $inputId = $id ?? $name;
@endphp

<div class="relative">
    <select
        name="{{ $name }}"
        id="{{ $inputId }}"
        @if($disabled) disabled @endif
        @if($required) required @endif
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-3 pr-10 border border-neutral-200 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-accent-600 focus:border-accent-600 transition duration-150 text-body text-neutral-700 appearance-none cursor-pointer ' .
                ($disabled ? 'opacity-50 cursor-not-allowed bg-neutral-100' : '') .
                ' dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-600 dark:focus:ring-accent-500 dark:focus:border-accent-500'
        ]) }}
    >
        <option value="" disabled {{ $selected === null ? 'selected' : '' }}>{{ $placeholder }}</option>
        @foreach ($options as $option)
            @php
                $value = is_array($option) ? $option['value'] ?? $option : $option;
                $label = is_array($option) ? $option['label'] ?? $option : $option;
                $isSelected = $selected === $value;
            @endphp
            <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    
    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-neutral-500 dark:text-neutral-400">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
</div>
