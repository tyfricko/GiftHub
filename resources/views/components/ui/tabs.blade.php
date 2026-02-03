@props([
    'tabs' => [],
    'active' => null,
])

<div class="border-b border-neutral-200 mb-6 dark:border-neutral-700">
    <nav class="flex space-x-1" role="tablist" aria-label="Tabs">
        @foreach ($tabs as $tab)
            @php
                $isActive = $tab['key'] === $active;
                $baseClasses = 'px-4 py-3 font-medium text-sm rounded-t-lg focus:outline-none focus:ring-2 focus:ring-accent-600 focus:ring-offset-2 transition-colors duration-150 inline-flex items-center gap-2';
                $activeClasses = $isActive
                    ? 'text-primary-600 border-b-2 border-primary-600 -mb-px dark:text-primary-400 dark:border-primary-400'
                    : 'text-neutral-600 hover:text-neutral-900 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:border-neutral-600';
                $badge = $tab['badge'] ?? 0;
            @endphp
            <a href="{{ $tab['url'] ?? '#' }}"
               class="{{ $baseClasses }} {{ $activeClasses }}"
               role="tab"
               aria-selected="{{ $isActive ? 'true' : 'false' }}"
               aria-controls="{{ $tab['key'] }}-panel"
               id="{{ $tab['key'] }}-tab">
                <span>{{ $tab['label'] }}</span>
                @if(!empty($badge) && $badge > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                        {{ $badge > 99 ? '99+' : $badge }}
                    </span>
                @endif
            </a>
        @endforeach
    </nav>
</div>