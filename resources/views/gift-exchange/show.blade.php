<x-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">{{ $event->name }}</h1>
        @if($event->description)
          <p class="mt-1 text-gray-600">{{ $event->description }}</p>
        @endif
        <div class="mt-2 text-sm text-gray-500 space-x-3">
          <span>Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</span>
          @if($event->budget_max)
            <span aria-hidden="true">•</span>
            <span>Budget: up to €{{ number_format($event->budget_max, 2) }}</span>
          @endif
        </div>
      </div>
      <div class="flex gap-2 shrink-0">
        <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary" size="sm">
          <i class="fa fa-arrow-left mr-2"></i> Back to My Events
        </x-ui.button>
        <x-ui.button as="a" href="{{ route('gift-exchange.dashboard') }}" variant="secondary" size="sm">
          <i class="fa fa-th mr-2"></i> All Events
        </x-ui.button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <x-ui.card>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Participants</div>
            <div class="text-2xl font-semibold">{{ $participants->count() }}</div>
          </div>
        </div>
      </x-ui.card>
      <x-ui.card>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-gray-500">Invitations</div>
            <div class="text-2xl font-semibold">{{ $invitations->count() }}</div>
          </div>
        </div>
      </x-ui.card>
    </div>

    <div class="space-y-6">
      <section>
        <h2 class="text-lg font-semibold mb-3">Participants</h2>
        @if($participants->isEmpty())
          <x-ui.card class="text-center text-gray-600">No participants yet.</x-ui.card>
        @else
          <div class="space-y-2">
            @foreach($participants as $p)
              <x-ui.card>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <x-ui.avatar :src="$p->user->avatar ?? null" :name="$p->user->name ?? $p->user->username ?? ('User #'.$p->user_id)" size="sm" />
                    <div>
                      <div class="font-medium">
                        {{ $p->user->username ?? $p->user->name ?? ('User #' . $p->user_id) }}
                      </div>
                      <div class="text-sm text-gray-500">Status: {{ ucfirst($p->status ?? 'unknown') }}</div>
                    </div>
                  </div>
                </div>
              </x-ui.card>
            @endforeach
          </div>
        @endif
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-3">Invitations</h2>
        @if($invitations->isEmpty())
          <x-ui.card class="text-center text-gray-600">No invitations sent yet.</x-ui.card>
        @else
          <div class="space-y-2">
            @foreach($invitations as $inv)
              <x-ui.card>
                <div class="flex items-center justify-between">
                  <div>
                    <div class="font-medium">{{ $inv->email }}</div>
                    <div class="text-sm text-gray-500">Status: {{ ucfirst($inv->status) }}</div>
                  </div>
                </div>
              </x-ui.card>
            @endforeach
          </div>
        @endif
      </section>

      <section>
        <h2 class="text-lg font-semibold mb-3">Assignments</h2>
        @if(($assignments ?? collect())->isEmpty())
          <x-ui.card class="text-center text-gray-600">No assignments yet.</x-ui.card>
        @else
          <div class="space-y-2">
            @foreach($assignments as $a)
              <x-ui.card>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div class="flex items-center gap-3">
                    <div class="text-sm">
                      <span class="font-medium">Giver:</span>
                      {{ $a->giver->user->username ?? $a->giver->user->name ?? ('User #' . $a->giver_id) }}
                    </div>
                    <span class="text-gray-400">→</span>
                    <div class="text-sm">
                      <span class="font-medium">Recipient:</span>
                      {{ $a->recipient->user->username ?? $a->recipient->user->name ?? ('User #' . $a->recipient_id) }}
                    </div>
                  </div>
                  <div class="text-xs text-gray-500">
                    Assigned: {{ optional($a->assigned_at)->format('Y-m-d H:i') ?? '—' }}
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