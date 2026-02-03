<x-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

        @php
            $participant = null;
            if (auth()->check()) {
                $participant = $participants->firstWhere('user_id', auth()->id());
            }
        @endphp

        {{-- Shipping address banner for participants who need to provide address --}}
        @if(isset($event) && isset($participant))
            <div class="mt-4">
                <x-ui.shipping-address-banner :event="$event" :participant="$participant" />
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $event->name }}</h1>
                @if($event->description)
                    <p class="mt-1 text-neutral-600 dark:text-neutral-400">{{ $event->description }}</p>
                @endif
                <div class="mt-2 text-sm text-neutral-500 dark:text-neutral-400 flex flex-wrap gap-3">
                    <span>Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</span>
                    @if($event->budget_max)
                        <span aria-hidden="true">•</span>
                        <span>Budget: up to €{{ number_format($event->budget_max, 2) }}</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 shrink-0 items-center flex-wrap">
                <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary" size="sm">
                    <i class="fa fa-arrow-left mr-2"></i> Back to My Events
                </x-ui.button>
                <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary" size="sm">
                    <i class="fa fa-th mr-2"></i> All Events
                </x-ui.button>
                @if(auth()->check() && auth()->id() === $event->created_by)
                    <div class="flex items-center gap-2">
                        <x-ui.button as="a" href="{{ route('gift-exchange.edit', $event->id) }}" size="sm" variant="primary" ariaLabel="Edit event {{ $event->name }}" min-h-[44px]">
                            <i class="fa fa-edit mr-2"></i> Edit Event
                        </x-ui.button>

                        <form method="POST" action="{{ route('gift-exchange.destroy', $event->id) }}" class="inline-flex items-center" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone and will remove all participants, invitations, and assignments.')">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" variant="danger" size="sm" ariaLabel="Delete event {{ $event->name }}" min-h-[44px]}>
                                Delete
                            </x-ui.button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-ui.card>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">Participants</div>
                        <div class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $participants->count() }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <i class="fa fa-users text-primary-600 dark:text-primary-400"></i>
                    </div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">Invitations</div>
                        <div class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $invitations->count() }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-accent-100 dark:bg-accent-900/30 flex items-center justify-center">
                        <i class="fa fa-envelope text-accent-600 dark:text-accent-400"></i>
                    </div>
                </div>
            </x-ui.card>
            <div>
                @if(isset($event) && isset($participants))
                    <x-ui.shipping-progress :event="$event" :participants="$participants" />
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <section>
                <h2 class="text-lg font-semibold mb-3 text-neutral-900 dark:text-neutral-100">Participants</h2>
                @if($participants->isEmpty())
                    <x-ui.card class="text-center text-neutral-600 dark:text-neutral-400">
                        <i class="fa fa-users text-4xl text-neutral-300 dark:text-neutral-600 mb-2"></i>
                        <p>No participants yet.</p>
                    </x-ui.card>
                @else
                    <div class="space-y-2">
                        @foreach($participants as $p)
                            <x-ui.card>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <x-ui.avatar :src="$p->user->avatar ? asset('storage/' . $p->user->avatar) : null" :name="$p->user->name ?? $p->user->username ?? ('User #'.$p->user_id)" size="md" />
                                        <div>
                                            <div class="font-medium">
                                                <a href="{{ route('profile.show', ['user' => $p->user->username]) }}" class="text-accent-600 hover:underline dark:text-accent-400 dark:hover:text-accent-300">
                                                    {{ $p->user->username ?? $p->user->name ?? ('User #' . $p->user_id) }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-neutral-500 dark:text-neutral-400 flex items-center gap-2">
                                                <span>Status:</span>
                                                @switch($p->status)
                                                    @case('accepted')
                                                        <x-ui.badge variant="success" size="sm" dot>Accepted</x-ui.badge>
                                                        @break
                                                    @case('declined')
                                                        <x-ui.badge variant="error" size="sm" dot>Declined</x-ui.badge>
                                                        @break
                                                    @case('pending')
                                                        <x-ui.badge variant="warning" size="sm" dot>Pending</x-ui.badge>
                                                        @break
                                                    @default
                                                        <x-ui.badge variant="default" size="sm">{{ ucfirst($p->status ?? 'unknown') }}</x-ui.badge>
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.card>
                        @endforeach
                    </div>
                @endif
            </section>

            @if(auth()->check() && auth()->id() === $event->created_by)
                <section>
                    <h2 class="text-lg font-semibold mb-3 text-neutral-900 dark:text-neutral-100">Invite Participants</h2>
                    <x-ui.card>
                        <form method="POST" action="{{ route('gift-exchange.invite', $event->id) }}" class="space-y-4" id="invitation-form">
                            @csrf
                            <div id="email-inputs" class="space-y-3">
                                <x-ui.input type="email" name="emails[]" placeholder="email@example.com" required />
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-ui.button type="button" variant="secondary" size="sm" onclick="addEmailInput()" min-h-[44px]">
                                    <i class="fa fa-plus mr-2"></i> Add Email
                                </x-ui.button>
                                <x-ui.button type="submit" size="sm" min-h-[44px]">
                                    <i class="fa fa-paper-plane mr-2"></i> Send Invitations
                                </x-ui.button>
                            </div>
                        </form>
                    </x-ui.card>
                </section>

                <script>
                    function addEmailInput() {
                        const container = document.getElementById('email-inputs');
                        const newInput = document.createElement('input');
                        newInput.type = 'email';
                        newInput.name = 'emails[]';
                        newInput.placeholder = 'email@example.com';
                        newInput.required = true;
                        newInput.className = 'w-full px-4 py-3 border border-neutral-200 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-accent-600 focus:border-accent-600 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-600 dark:focus:ring-accent-500 dark:focus:border-accent-500';
                        container.appendChild(newInput);
                    }
                </script>
            @endif

            <section>
                <h2 class="text-lg font-semibold mb-3 text-neutral-900 dark:text-neutral-100">Invitations</h2>
                @if($invitations->isEmpty())
                    <x-ui.card class="text-center text-neutral-600 dark:text-neutral-400">
                        <i class="fa fa-envelope-open text-4xl text-neutral-300 dark:text-neutral-600 mb-2"></i>
                        <p>No invitations sent yet.</p>
                    </x-ui.card>
                @else
                    <div class="space-y-2">
                        @foreach($invitations as $inv)
                            <x-ui.card>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-neutral-900 dark:text-neutral-100">{{ $inv->email }}</div>
                                        <div class="text-sm text-neutral-500 dark:text-neutral-400 flex items-center gap-2">
                                            <span>Status:</span>
                                            @switch($inv->status)
                                                @case('accepted')
                                                    <x-ui.badge variant="success" size="sm" dot>Accepted</x-ui.badge>
                                                    @break
                                                @case('declined')
                                                    <x-ui.badge variant="error" size="sm" dot>Declined</x-ui.badge>
                                                    @break
                                                @case('pending')
                                                    <x-ui.badge variant="warning" size="sm" dot>Pending</x-ui.badge>
                                                    @break
                                                @default
                                                    <x-ui.badge variant="default" size="sm">{{ ucfirst($inv->status) }}</x-ui.badge>
                                            @endswitch
                                        </div>
                                    </div>
                                    @if($inv->responded_at)
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ $inv->responded_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </x-ui.card>
                        @endforeach
                    </div>
                @endif
            </section>

            <section>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Assignments</h2>

                    {{-- Show Assign Gifts button to event owner when no assignments exist --}}
                    @if(auth()->check() && auth()->id() === $event->created_by && ($assignments ?? collect())->isEmpty())
                        <form method="POST" action="{{ route('gift-exchange.assign-gifts', $event->id) }}" class="inline">
                            @csrf
                            <x-ui.button type="submit" size="sm" min-h-[44px]
                                onclick="return confirm('This will randomly assign Secret Santa pairs and notify participants. Continue?')">
                                <i class="fa fa-random mr-2"></i> Assign Gifts
                            </x-ui.button>
                        </form>
                    @endif
                </div>

                @if(($assignments ?? collect())->isEmpty())
                    <x-ui.card class="text-center text-neutral-600 dark:text-neutral-400">
                        <i class="fa fa-random text-4xl text-neutral-300 dark:text-neutral-600 mb-2"></i>
                        <p>No assignments yet.</p>
                        @if(auth()->check() && auth()->id() === $event->created_by)
                            <br><small class="text-neutral-500 dark:text-neutral-400">Click "Assign Gifts" to create Secret Santa pairs.</small>
                        @endif
                    </x-ui.card>
                @else
                    <div class="space-y-2">
                        @foreach($assignments as $a)
                            <x-ui.card>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2 text-sm">
                                            <x-ui.avatar :src="$a->giver->user->avatar ? asset('storage/' . $a->giver->user->avatar) : null" :name="$a->giver->user->name ?? $a->giver->user->username ?? ('User #'.$a->giver_id)" size="sm" />
                                            <span class="font-medium text-neutral-700 dark:text-neutral-300">
                                                {{ $a->giver->user->username ?? $a->giver->user->name ?? ('User #' . $a->giver_id) }}
                                            </span>
                                        </div>
                                        <span class="text-neutral-400"><i class="fa fa-arrow-right"></i></span>
                                        <div class="flex items-center gap-2 text-sm">
                                            <x-ui.avatar :src="$a->recipient->user->avatar ? asset('storage/' . $a->recipient->user->avatar) : null" :name="$a->recipient->user->name ?? $a->recipient->user->username ?? ('User #'.$a->recipient_id)" size="sm" />
                                            <span class="font-medium text-neutral-700 dark:text-neutral-300">
                                                {{ $a->recipient->user->username ?? $a->recipient->user->name ?? ('User #' . $a->recipient_id) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
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
