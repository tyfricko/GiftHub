@props([
    'src' => '/fallback-avatar.jpg',
    'alt' => 'User Avatar',
    'size' => 'md',
    'fallback' => null,
    'class' => '',
])

@php
    $sizeClasses = match($size) {
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16',
        default => 'w-10 h-10',
    };
    
    $fallbackSrc = $fallback ?? '/fallback-avatar.jpg';
    $darkModeFallback = $fallback ?? '/fallback-avatar-white.jpg';
@endphp

<picture>
    <source srcset="{{ $darkModeFallback }}" media="(prefers-color-scheme: dark)" />
    <img 
        src="{{ $src }}"
        onerror="this.onerror=null; this.src='{{ $fallbackSrc }}'"
        alt="{{ $alt }}"
        class="rounded-full object-cover {{ $sizeClasses }} {{ $class }} bg-neutral-200 dark:bg-neutral-700"
        loading="lazy"
    />
</picture>