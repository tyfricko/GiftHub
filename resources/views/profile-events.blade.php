<x-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

    @php
      $profileTabs = [
        ['key' => 'wishlists', 'label' => 'My Wishlist', 'url' => route('profile.wishlist')],
        ['key' => 'events',    'label' => 'My Events',   'url' => route('profile.events')],
        ['key' => 'friends',   'label' => 'My Friends',  'url' => route('profile.friends')],
        ['key' => 'settings',  'label' => 'Settings',    'url' => route('profile.settings')],
      ];
    @endphp
    <x-ui.tabs :tabs="$profileTabs" :active="$activeTab" />

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <x-ui.card>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Created Events</div>
            <div class="text-2xl font-semibold">{{ $counts['created'] ?? 0 }}</div>
          </div>
        </div>
      </x-ui.card>

      <x-ui.card>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Participating</div>
            <div class="text-2xl font-semibold">{{ $counts['participating'] ?? 0 }}</div>
          </div>
        </div>
      </x-ui.card>
    </div>

    <div class="flex justify-end">
      <x-ui.button as="a" href="{{ $links['createEvent'] ?? ($links['dashboard'] ?? '#') }}">
        <i class="fa fa-plus mr-2"></i> Create Event
      </x-ui.button>
    </div>

    <div class="space-y-6">
      <section>
        <h2 class="text-lg font-semibold mb-3">Created Events</h2>

        @if(($createdEvents ?? collect())->isEmpty())
          <x-ui.card class="text-center text-gray-600">You haven't created any events yet.</x-ui.card>
        @else
          <div class="space-y-3">
            @foreach($createdEvents as $event)
              <x-ui.card>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div>
                    <div class="font-medium">{{ $event->name ?? $event->title ?? ('Event #'.$event->id) }}</div>
                    <div class="text-sm text-gray-500">{{ $event->end_date ?? $event->date ?? '' }}</div>
                  </div>
                  <div class="flex gap-2">
                    <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" size="sm" variant="secondary">View Dashboard</x-ui.button>
                  </div>
                </div>
              </x-ui.card>
            @endforeach
          </div>
        @endif
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-3">Participating Events</h2>

        @if(($participatingEvents ?? collect())->isEmpty())
          <x-ui.card class="text-center text-gray-600">You are not participating in any events yet.</x-ui.card>
        @else
          <div class="space-y-3">
            @foreach($participatingEvents as $event)
              <x-ui.card>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div>
                    <div class="font-medium">{{ $event->name ?? $event->title ?? ('Event #'.$event->id) }}</div>
                    <div class="text-sm text-gray-500">{{ $event->end_date ?? $event->date ?? '' }}</div>
                  </div>
                  <div class="flex gap-2">
                    <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" size="sm" variant="secondary">View Dashboard</x-ui.button>
                  </div>
                </div>
              </x-ui.card>
            @endforeach
          </div>
        @endif
      </section>
    </div>
  </div>
</x-layout>