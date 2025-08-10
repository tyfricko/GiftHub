<x-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <h1 class="text-xl font-semibold mb-4">Settings (debug)</h1>
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

    <div class="space-y-8">
      <section>
        <h2 class="text-lg font-semibold mb-4">Privacy</h2>
        <form onsubmit="return false;" class="space-y-4">
          <x-ui.form-group label="Default wishlist visibility">
            <select class="w-full border rounded px-3 py-2">
              <option value="public" {{ ($settings['privacy']['default_wishlist_visibility'] ?? 'public') === 'public' ? 'selected' : '' }}>Public</option>
              <option value="private" {{ ($settings['privacy']['default_wishlist_visibility'] ?? 'public') === 'private' ? 'selected' : '' }}>Private</option>
            </select>
          </x-ui.form-group>
          <x-ui.button type="button">Save</x-ui.button>
        </form>
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
        <form onsubmit="return false;" class="space-y-4">
          <label class="flex items-center gap-2">
            <input type="checkbox" class="h-4 w-4" {{ !empty($settings['notifications']['email_invitations']) ? 'checked' : '' }} />
            <span>Email me about gift exchange invitations</span>
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" class="h-4 w-4" {{ !empty($settings['notifications']['email_assignments']) ? 'checked' : '' }} />
            <span>Email me when gift assignments occur</span>
          </label>
          <x-ui.button type="button">Save</x-ui.button>
        </form>
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-4">Account</h2>
        <form onsubmit="return false;" class="space-y-4">
          <x-ui.form-group label="Display name">
            <x-ui.input type="text" name="display_name" value="{{ $settings['account']['display_name'] ?? ($user->name ?? '') }}" />
          </x-ui.form-group>

          <x-ui.form-group label="Avatar">
            @if(!empty($settings['account']['avatar']))
              <div class="flex items-center gap-3">
                <img src="{{ $settings['account']['avatar'] }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                <x-ui.button as="a" href="{{ route('profile.settings') }}" variant="secondary">Change Avatar</x-ui.button>
              </div>
            @else
              <div class="text-gray-600">No avatar set.</div>
              <x-ui.button as="a" href="{{ route('profile.settings') }}" variant="secondary">Upload Avatar</x-ui.button>
            @endif
          </x-ui.form-group>

          <x-ui.button type="button">Save</x-ui.button>
        </form>
      </section>
    </div>
  </div>
</x-layout>