@props([
    'type' => 'success', // success or error
    'dismissible' => false,
])

@php
    $baseClasses = 'rounded-md p-4 mb-4 text-sm font-semibold';
    $typeClasses = $type === 'error'
        ? 'bg-error text-white'
        : 'bg-success text-white';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeClasses]) }} role="alert">
    {{ $slot }}

    @if ($dismissible)
        <button type="button" class="ml-4 text-white hover:text-gray-200 focus:outline-none" aria-label="Dismiss alert" onclick="this.parentElement.style.display='none'">
            &times;
        </button>
    @endif
</div>