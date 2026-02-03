@props([
    'id' => null,
    'open' => false,
    'size' => 'md',
    'closeOnBackdrop' => true,
    'closeOnEscape' => true,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-full mx-4',
        default => 'max-w-lg',
    };
@endphp

<div
    id="{{ $id }}"
    {{ $attributes->merge([
        'class' => 'fixed inset-0 z-50 overflow-y-auto hidden',
        'role' => 'dialog',
        'aria-modal' => 'true',
        'x-show' => $open ? 'true' : 'false',
        'x-transition:enter' => 'transition ease-out duration-300',
        'x-transition:enter-start' => 'opacity-0',
        'x-transition:enter-end' => 'opacity-100',
        'x-transition:leave' => 'transition ease-in duration-200',
        'x-transition:leave-start' => 'opacity-100',
        'x-transition:leave-end' => 'opacity-0',
        'x-cloak' => ''
    ]) }}
>
    <!-- Backdrop -->
    <div
        class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
        @if($closeOnBackdrop) x-on:click="$dispatch('close-modal', { id: '{{ $id }}' })" @endif
    ></div>

    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            class="relative w-full {{ $sizeClasses }} bg-white rounded-xl shadow-level-5 overflow-hidden transform transition-all dark:bg-neutral-800"
            x-on:keydown.escape.window="{{ $closeOnEscape ? '$dispatch(\"close-modal\", { id: \"' . $id . '\" })' : '' }}"
        >
            <!-- Close Button -->
            <button
                type="button"
                class="absolute top-4 right-4 p-2 text-neutral-400 hover:text-neutral-600 focus:outline-none focus:ring-2 focus:ring-accent-600 rounded-lg dark:text-neutral-400 dark:hover:text-neutral-200"
                aria-label="Close modal"
                x-on:click="$dispatch('close-modal', { id: '{{ $id }}' })"
            >
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Content -->
            {{ $slot }}
        </div>
    </div>
</div>
