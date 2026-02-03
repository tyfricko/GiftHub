{{-- resources/views/components/patterns/action-bar.blade.php --}}
{{-- Reusable action bar with button groups --}}
{{-- Usage: 
    @include('components.patterns.action-bar', [
        'title' => 'Section Title',
        'actions' => [
            ['label' => 'Add New', 'route' => route('items.create'), 'variant' => 'primary'],
            ['label' => 'Export', 'route' => '#', 'variant' => 'secondary'],
        ]
    ])
--}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    @isset($title)
    <h2 class="text-xl font-semibold text-neutral-900">{{ $title }}</h2>
    @endisset
    
    @if(isset($actions) && is_array($actions))
    <div class="flex flex-wrap gap-2">
        @foreach($actions as $action)
            <x-ui.button 
                variant="{{ $action['variant'] ?? 'primary' }}" 
                href="{{ $action['route'] ?? '#' }}"
                as="a"
            >
                {{ $action['label'] }}
            </x-ui.button>
        @endforeach
    </div>
    @endif
    
    {{ $slot ?? '' }}
</div>
