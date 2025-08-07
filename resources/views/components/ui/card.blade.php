@props([
    'title' => null,
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'bg-neutral-white rounded-lg shadow-card p-6 ' . $class]) }}>
    @if ($title)
        <h3 class="text-headline font-semibold mb-4">{{ $title }}</h3>
    @endif
    <div class="text-body text-neutral-gray">
        {{ $slot }}
    </div>
</div>