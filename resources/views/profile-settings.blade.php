@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <x-ui.card>
      <h1 class="text-xl sm:text-2xl font-semibold mb-2 text-neutral-900 dark:text-white">Settings</h1>
      <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 mb-4">Manage your account settings and profile information.</p>
      <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
        <x-ui.button as="a" href="{{ route('profile.manage') }}" class="w-full sm:w-auto min-h-[44px]">Manage Profile</x-ui.button>
        <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary" class="w-full sm:w-auto min-h-[44px]">Go to My Events</x-ui.button>
        <x-ui.button as="a" href="{{ route('profile.wishlist') }}" variant="secondary" class="w-full sm:w-auto min-h-[44px]">Go to My Wishlist</x-ui.button>
      </div>
    </x-ui.card>
</div>
@endsection