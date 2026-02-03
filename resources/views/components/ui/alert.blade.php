@props([
    'type' => 'success',
    'dismissible' => false,
    'title' => null,
])

@php
    $baseClasses = 'rounded-lg p-4 mb-4 text-sm font-medium flex items-start gap-3';
    $typeClasses = match($type) {
        'success' => 'bg-primary-50 text-primary-800 border border-primary-200 dark:bg-primary-900/20 dark:text-primary-200 dark:border-primary-800',
        'error' => 'bg-red-50 text-red-800 border border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800',
        'warning' => 'bg-amber-50 text-amber-800 border border-amber-200 dark:bg-amber-900/20 dark:text-amber-200 dark:border-amber-800',
        'info' => 'bg-accent-50 text-accent-800 border border-accent-200 dark:bg-accent-900/20 dark:text-accent-200 dark:border-accent-800',
        default => 'bg-primary-50 text-primary-800 border border-primary-200 dark:bg-primary-900/20 dark:text-primary-200 dark:border-primary-800',
    };
    $iconClasses = match($type) {
        'success' => 'text-primary-600 dark:text-primary-400',
        'error' => 'text-red-600 dark:text-red-400',
        'warning' => 'text-amber-600 dark:text-amber-400',
        'info' => 'text-accent-600 dark:text-accent-400',
        default => 'text-primary-600 dark:text-primary-400',
    };
    $iconSvg = match($type) {
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    };
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeClasses]) }} role="alert" aria-live="polite">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $iconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        {!! $iconSvg !!}
    </svg>
    <div class="flex-1">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div>{{ $slot }}</div>
    </div>

    @if ($dismissible)
        <button type="button" class="ml-2 p-1 rounded hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-current" aria-label="Dismiss alert" onclick="this.parentElement.style.display='none'">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>