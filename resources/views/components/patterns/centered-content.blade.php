{{-- resources/views/components/patterns/centered-content.blade.php --}}
{{-- Reusable centered content container --}}
{{-- Usage: @include('components.patterns.centered-content', ['maxWidth' => 'max-w-2xl']) --}}

<div class="{{ $maxWidth ?? 'max-w-2xl' }} mx-auto">
    {{ $slot }}
</div>
