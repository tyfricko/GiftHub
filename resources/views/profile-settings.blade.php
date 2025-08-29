<x-layout>
  <div class="container mx-auto px-6 py-12">
    <x-ui.card>
      <h1 class="text-xl font-semibold mb-2">Settings</h1>
      <p class="text-neutral-600 mb-4">Manage your account settings and profile information.</p>
      <div class="flex gap-2">
        <x-ui.button as="a" href="{{ route('profile.manage') }}">Manage Profile</x-ui.button>
        <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary">Go to My Events</x-ui.button>
        <x-ui.button as="a" href="{{ route('profile.wishlist') }}" variant="secondary">Go to My Wishlist</x-ui.button>
      </div>
    </x-ui.card>
  </div>
</x-layout>