@props([
    'label' => null,
    'for' => null,
    'error' => null,
    'help' => null,
])

<div class="mb-6">
    @if ($label)
        <label for="{{ $for }}" class="block text-sm font-semibold text-neutral-gray mb-1">
            {{ $label }}
        </label>
    @endif

    {{ $slot }}

    @if ($help)
        <p class="text-xs text-neutral-gray mt-1">{{ $help }}</p>
    @endif

    @if ($error)
        <p class="text-xs text-error mt-1 font-semibold" role="alert">{{ $error }}</p>
    @endif
</div>