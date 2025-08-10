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
    $baseClasses = 'rounded-lg font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-accent transition-colors duration-150 inline-flex items-center justify-center';
    $variantClasses = $variant === 'primary'
        ? 'bg-primary text-white hover:bg-secondary disabled:bg-neutral-gray disabled:text-neutral-light'
        : 'bg-white border border-primary text-primary hover:bg-primary hover:text-white disabled:border-neutral-gray disabled:text-neutral-gray disabled:bg-transparent';
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
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 {{ $variant === 'primary' ? 'text-white' : 'text-primary' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
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
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 {{ $variant === 'primary' ? 'text-white' : 'text-primary' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif