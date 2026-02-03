{{-- resources/views/components/patterns/page-container.blade.php --}}
{{-- Reusable page container with consistent structure --}}
{{-- Usage: @include('components.patterns.page-container', ['title' => 'Page Title']) --}}

<div class="container mx-auto px-4 py-6">
    {{ $slot }}
</div>
