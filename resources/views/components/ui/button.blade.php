@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'ariaLabel' => null,
    'loading' => false,
    'as' => 'button',      // 'button' or 'a'
    'href' => null,        // used when as === 'a'
])

@php
    $baseClasses = 'rounded-lg font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-600 focus:ring-offset-2 transition-colors duration-150 inline-flex items-center justify-center';
    $variantClasses = match($variant) {
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 disabled:bg-neutral-400 disabled:text-neutral-100 dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700 dark:disabled:bg-neutral-600 dark:disabled:text-neutral-400',
        'secondary' => 'bg-white border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white disabled:border-neutral-300 disabled:text-neutral-400 disabled:bg-transparent dark:bg-neutral-800 dark:border-primary-500 dark:text-primary-400 dark:hover:bg-primary-600 dark:hover:text-white dark:disabled:border-neutral-600 dark:disabled:text-neutral-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 disabled:bg-neutral-400 disabled:text-neutral-100 dark:bg-red-600 dark:text-white dark:hover:bg-red-700 dark:disabled:bg-neutral-600 dark:disabled:text-neutral-400',
        'ghost' => 'bg-transparent text-neutral-700 hover:bg-neutral-100 disabled:text-neutral-400 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:disabled:text-neutral-500',
        default => 'bg-white border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white disabled:border-neutral-300 disabled:text-neutral-400 disabled:bg-transparent dark:bg-neutral-800 dark:border-primary-500 dark:text-primary-400 dark:hover:bg-primary-600 dark:hover:text-white dark:disabled:border-neutral-600 dark:disabled:text-neutral-500'
    };
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1 text-sm',
        'lg' => 'px-6 py-3 text-lg',
        default => 'px-4 py-2 text-base',
    };
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90';
    $classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $disabledClasses;
@endphp

@if($as === 'a')
    <a
        href="{{ $href ?? '#' }}"
        @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if($disabled || $loading) aria-disabled="true" tabindex="-1" @endif
        {{ $attributes->merge(['class' => $classes, 'role' => 'button']) }}
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 {{ in_array($variant, ['primary', 'danger']) ? 'text-white' : 'text-primary-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled || $loading ? 'disabled aria-disabled=true' : '' }}
        @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 {{ in_array($variant, ['primary', 'danger']) ? 'text-white' : 'text-primary-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif