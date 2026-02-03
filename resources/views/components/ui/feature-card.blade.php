@props([
    'icon' => null,
    'title' => '',
    'description' => '',
    'iconColor' => 'text-primary-600'
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-300 p-8 text-center group dark:bg-neutral-800']) }}>
    @if($icon)
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 group-hover:bg-primary-100 transition-colors duration-300 dark:bg-primary-900/20 dark:group-hover:bg-primary-900/30">
                <svg class="w-8 h-8 {{ $iconColor }} dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg">
                    {!! $icon !!}
                </svg>
            </div>
        </div>
    @endif

    <h3 class="text-title font-semibold text-neutral-900 mb-3 dark:text-neutral-100">
        {{ $title }}
    </h3>

    <p class="text-body text-neutral-600 leading-relaxed dark:text-neutral-400">
        {{ $description }}
    </p>
</div>