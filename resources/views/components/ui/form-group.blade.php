@props([
    'label' => null,
    'for' => null,
    'error' => null,
    'help' => null,
])

<div class="mb-6">
    @if ($label)
        <label for="{{ $for }}" class="block text-sm font-semibold text-neutral-700 mb-1 dark:text-neutral-300">
            {{ $label }}
        </label>
    @endif

    {{ $slot }}

    @if ($help)
        <p class="text-xs text-neutral-500 mt-1 dark:text-neutral-400">{{ $help }}</p>
    @endif

    @if ($error)
        <p class="text-xs text-red-600 mt-1 font-semibold" role="alert" aria-live="polite">{{ $error }}</p>
    @endif
</div>