@props([
    'icon' => 'calendar',
    'title' => 'No items yet!',
    'description' => '',
    'actionText' => '',
    'actionUrl' => '#',
])

<div class="bg-white rounded-lg border border-neutral-200 p-8 text-center shadow-level-1">
    <div class="mx-auto w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
        @if($icon === 'calendar')
            <svg class="w-10 h-10 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        @else
            <svg class="w-10 h-10 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        @endif
    </div>

    <h3 class="text-lg font-medium text-neutral-900 mb-2">{{ $title }}</h3>

    @if($description)
        <p class="text-sm text-neutral-600 mb-6 max-w-md mx-auto">{{ $description }}</p>
    @endif

    @if($actionText && $actionUrl !== '#')
        <div class="mt-2">
            <x-ui.button as="a" href="{{ $actionUrl }}">
                {{ $actionText }}
            </x-ui.button>
        </div>
    @endif
</div>