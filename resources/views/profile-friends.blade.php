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

    <x-ui.alert type="info">
      Social features (follow, requests) are planned in Phase 3. This page is a safe placeholder.
    </x-ui.alert>

    <div class="space-y-6">
      <section>
        <h2 class="text-lg font-semibold mb-3">Following ({{ $counts['following'] ?? 0 }})</h2>
        @if(($following ?? collect())->isEmpty())
          <x-ui.card class="text-gray-600">You are not following anyone yet.</x-ui.card>
        @else
          <div class="space-y-3">
            @foreach($following as $f)<x-ui.card>{{ $f->name ?? 'User' }}</x-ui.card>@endforeach
          </div>
        @endif
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-3">Followers ({{ $counts['followers'] ?? 0 }})</h2>
        @if(($followers ?? collect())->isEmpty())
          <x-ui.card class="text-gray-600">No followers yet.</x-ui.card>
        @else
          <div class="space-y-3">
            @foreach($followers as $f)<x-ui.card>{{ $f->name ?? 'User' }}</x-ui.card>@endforeach
          </div>
        @endif
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-3">Friend Requests ({{ $counts['requests'] ?? 0 }})</h2>
        @if(($requests ?? collect())->isEmpty())
          <x-ui.card class="text-gray-600">No friend requests.</x-ui.card>
        @else
          <div class="space-y-3">
            @foreach($requests as $r)<x-ui.card>{{ $r->name ?? 'User' }}</x-ui.card>@endforeach
          </div>
        @endif
      </section>
    </div>
  </div>
</x-layout>