@props([
    'src' => '/fallback-avatar.jpg',
    'alt' => 'User Avatar',
    'size' => 'md', // sm, md, lg
])

@php
    $sizeClasses = match($size) {
        'sm' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-10 h-10',
    };
@endphp

<img src="{{ $src }}" alt="{{ $alt }}" class="rounded-full object-cover {{ $sizeClasses }}" loading="lazy" />