@props([
    'tabs' => [],
    'active' => null,
])

<div class="border-b border-neutral-gray mb-6">
    <nav class="flex space-x-4" aria-label="Tabs">
        @foreach ($tabs as $tab)
            @php
                $isActive = $tab['key'] === $active;
                $baseClasses = 'px-3 py-2 font-semibold text-sm rounded-t-md focus:outline-none focus:ring-2 focus:ring-accent';
                $activeClasses = $isActive
                    ? 'bg-neutral-white border border-b-0 border-neutral-gray text-primary'
                    : 'text-neutral-gray hover:text-primary hover:bg-neutral-light';
            @endphp
            <a href="{{ $tab['url'] ?? '#' }}"
               class="{{ $baseClasses }} {{ $activeClasses }}"
               aria-current="{{ $isActive ? 'page' : false }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>