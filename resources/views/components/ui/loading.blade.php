@props([
    'size' => 'md',
    'color' => 'primary',
    'label' => null,
])

@php
    $sizeClasses = match($size) {
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'lg' => 'w-8 h-8',
        'xl' => 'w-12 h-12',
        default => 'w-6 h-6',
    };
    $colorClasses = match($color) {
        'white' => 'text-white',
        'neutral' => 'text-neutral-400',
        default => 'text-accent-600',
    };
@endphp

<div class="inline-flex items-center justify-center" role="status" aria-label="{{ $label ?? 'Loading' }}">
    <svg class="animate-spin {{ $sizeClasses }} {{ $colorClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
    @if ($label)
        <span class="sr-only">{{ $label }}</span>
    @endif
</div>
