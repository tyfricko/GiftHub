<x-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Gift Exchange</h1>
        <p class="mt-1 text-gray-600">Create and manage your gift exchange events.</p>
      </div>
      <div class="flex gap-2 shrink-0">
        <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary" size="sm">
          <i class="fa fa-user mr-2"></i> My Events
        </x-ui.button>
      </div>
    </div>

    @if(session('success'))
      <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
    @endif
    @if($errors->any())
      <x-ui.alert type="error">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </x-ui.alert>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <x-ui.card>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Total Events</div>
            <div class="text-2xl font-semibold">{{ $events->count() }}</div>
          </div>
        </div>
      </x-ui.card>
      <x-ui.card>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Upcoming</div>
            <div class="text-2xl font-semibold">
              {{ $events->filter(fn($e) => \Carbon\Carbon::parse($e->end_date)->isFuture())->count() }}
            </div>
          </div>
        </div>
      </x-ui.card>
    </div>

    <div class="space-y-6">
      <section>
        <h2 class="text-lg font-semibold mb-3">Create New Event</h2>
        <x-ui.card>
          <form id="create-event-form" method="POST" action="{{ route('gift-exchange.create') }}" class="space-y-4">
            @csrf
            <x-ui.form-group label="Event Name" for="name" required>
              <x-ui.input type="text" name="name" id="name" required />
            </x-ui.form-group>

            <x-ui.form-group label="Description" for="description">
              <textarea name="description" id="description" class="w-full border rounded p-2" rows="3"></textarea>
            </x-ui.form-group>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <x-ui.form-group label="End Date" for="end_date" required>
                <x-ui.input type="datetime-local" name="end_date" id="end_date" required />
              </x-ui.form-group>
              <x-ui.form-group label="Budget Limit (€)" for="budget_max">
                <x-ui.input type="number" name="budget_max" id="budget_max" min="0" step="0.01" />
              </x-ui.form-group>
            </div>

            <div class="flex justify-end">
              <x-ui.button type="submit">
                <i class="fa fa-plus mr-2"></i> Create Event
              </x-ui.button>
            </div>
          </form>
        </x-ui.card>
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-3">Your Events</h2>

        @if($events->isEmpty())
          <x-ui.card class="text-center text-gray-600">
            You have not created any gift exchange events yet.
          </x-ui.card>
        @else
          <div class="space-y-4">
            @foreach($events as $event)
              <x-ui.card>
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                  <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $event->name }}</div>
                    @if($event->description)
                      <div class="text-sm text-gray-600 mt-1">{{ $event->description }}</div>
                    @endif
                    <div class="text-sm text-gray-500 mt-2 space-x-3">
                      <span>Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</span>
                      @if($event->budget_max)
                        <span aria-hidden="true">•</span>
                        <span>Budget: up to €{{ number_format($event->budget_max, 2) }}</span>
                      @endif
                    </div>
                  </div>
                  <div class="flex flex-col gap-3 shrink-0 w-full sm:w-auto">
                    <div>
                      <div class="text-sm font-medium mb-2">Invite participants</div>
                      <form method="POST" action="{{ route('gift-exchange.invite', $event->id) }}" class="space-y-2" x-data="{ inputs: [''] }">
                        @csrf
                        <template x-for="(v,i) in inputs" :key="i">
                          <x-ui.input type="email" name="emails[]" placeholder="email@example.com" />
                        </template>
                        <div class="flex items-center gap-2">
                          <x-ui.button type="button" variant="secondary" size="sm" x-on:click="inputs.push('')">
                            <i class="fa fa-plus mr-2"></i> Add Email
                          </x-ui.button>
                          <x-ui.button type="submit" size="sm">
                            <i class="fa fa-paper-plane mr-2"></i> Send Invites
                          </x-ui.button>
                        </div>
                      </form>
                    </div>

                    <div>
                      <div class="flex items-center gap-2">
                        <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" variant="secondary" size="sm">
                          <i class="fa fa-eye mr-2"></i> View Dashboard
                        </x-ui.button>
                        @if(auth()->check() && auth()->id() === $event->created_by)
                          <x-ui.button as="a" href="{{ route('gift-exchange.edit', $event->id) }}" size="sm">
                            <i class="fa fa-edit mr-2"></i> Edit
                          </x-ui.button>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-4">
                  <div class="text-sm font-medium mb-2">Invitations</div>
                  @php
                    $invitations = $event->invitations ?? \App\Models\GiftExchangeInvitation::where('event_id', $event->id)->get();
                  @endphp
                  @if($invitations->isEmpty())
                    <div class="text-sm text-gray-500">No invitations sent yet.</div>
                  @else
                    <div class="space-y-1">
                      @foreach($invitations as $inv)
                        <div class="text-sm">
                          <span class="font-medium">{{ $inv->email }}</span>
                          <span class="text-gray-500">— {{ ucfirst($inv->status) }}</span>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              </x-ui.card>
            @endforeach
          </div>
        @endif
      </section>
    </div>
  </div>

  {{-- AlpineJS is used by other components; ensure it’s loaded in layout. --}}
</x-layout>