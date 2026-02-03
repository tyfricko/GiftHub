@props([
    'variant' => 'default',
    'size' => 'md',
    'dot' => false,
])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300',
        'error' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
        'info' => 'bg-accent-100 text-accent-800 dark:bg-accent-900/30 dark:text-accent-300',
        default => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-300',
    };
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'lg' => 'px-3 py-1 text-base',
        default => 'px-2.5 py-0.5 text-sm',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 font-medium rounded-full ' . $variantClasses . ' ' . $sizeClasses]) }}>
    @if($dot)
        <span class="w-2 h-2 rounded-full bg-current"></span>
    @endif
    {{ $slot }}
</span>
