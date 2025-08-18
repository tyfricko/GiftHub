@props([
    'title' => null,
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-level-2 p-6 ' . $class]) }}>
    @if ($title)
        <h3 class="text-headline font-semibold mb-4">{{ $title }}</h3>
    @endif
    <div class="text-body text-neutral-600">
        {{ $slot }}
    </div>
</div>