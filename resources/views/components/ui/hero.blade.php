@props([
    'title' => '',
    'subtitle' => '',
    'ctaText' => '',
    'ctaHref' => '#',
    'backgroundClass' => 'bg-primary-600'
])

<section class="{{ $backgroundClass }} text-white py-16 lg:py-28">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-display lg:text-display-lg font-extrabold mb-4 leading-tight">
                {{ $title }}
            </h1>

            @if($subtitle)
                <p class="text-body-lg lg:text-xl mb-8 text-primary-100 max-w-2xl mx-auto leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif

            @if($ctaText)
                <div class="mt-8">
                    <x-ui.button 
                        variant="secondary" 
                        size="lg" 
                        as="a" 
                        href="{{ $ctaHref }}"
                        class="bg-white text-primary-600 hover:bg-primary-50 shadow-level-2 px-6 py-3 text-lg font-semibold"
                    >
                        {{ $ctaText }}
                    </x-ui.button>
                </div>
            @endif
        </div>
    </div>
</section>