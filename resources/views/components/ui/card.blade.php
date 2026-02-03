@props([
    'title' => null,
    'variant' => 'basic',
    'class' => '',
])

@php
    $variantClasses = match($variant) {
        'elevated' => 'shadow-level-3 hover:shadow-level-4 transition-shadow duration-300',
        'interactive' => 'shadow-level-2 hover:shadow-level-3 transition-shadow duration-300 cursor-pointer hover:border-primary-300',
        default => 'shadow-level-2',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-neutral-200 p-6 ' . $variantClasses . ' ' . $class . ' dark:bg-neutral-800 dark:border-neutral-700']) }}>
    @if ($title)
        <h3 class="text-headline font-semibold mb-4 text-neutral-900 dark:text-neutral-100">{{ $title }}</h3>
    @endif
    <div class="text-body text-neutral-600 dark:text-neutral-300">
        {{ $slot }}
    </div>
</div>