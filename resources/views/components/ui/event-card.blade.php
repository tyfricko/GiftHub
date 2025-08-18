@props([
    'event',
    'showActions' => true,
    'showImage' => true,
])

<x-ui.card class="p-4">
    <div class="flex items-start">

        <!-- Main content -->
        <div class="flex-1 min-w-0">
            <h3 class="text-subheadline font-semibold text-neutral-900 mb-1 truncate">{{ $event->name ?? ('Event #'.$event->id) }}</h3>
            <p class="text-body text-neutral-600 mb-2">Draw Date: <span class="font-medium text-neutral-800">{{ \Carbon\Carbon::parse($event->end_date ?? $event->date ?? now())->format('d/m/Y') }}</span></p>

            @if(!empty($event->description))
                <p class="text-body text-neutral-700 mb-3 line-clamp-3">{{ $event->description }}</p>
            @endif

            <div class="flex items-center gap-4 text-sm">
                @if(!empty($event->budget_max))
                    <span class="text-accent-600 font-semibold">Budget: ${{ number_format($event->budget_max, 2) }}</span>
                @endif

                <span class="text-neutral-500">Participants: {{ $event->participants->count() ?? 0 }}</span>
            </div>
        </div>

        <!-- Right: status and actions -->
        <div class="flex flex-col items-end gap-3">
            @php
                $status = 'Pending Draw';
                $statusClasses = 'bg-amber-100 text-amber-800';

                if(isset($event->assignments) && $event->assignments->count() > 0) {
                    $status = 'Assignments Made';
                    $statusClasses = 'bg-primary-100 text-primary-800';
                } elseif(\Carbon\Carbon::parse($event->end_date ?? $event->date ?? now())->isPast()) {
                    $status = 'Expired';
                    $statusClasses = 'bg-neutral-100 text-neutral-600';
                }
            @endphp

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses }}" aria-hidden="true">
                {{ $status }}
            </span>

            @if($showActions)
                <div class="flex flex-col items-stretch gap-2">
                    <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" size="sm" variant="secondary" ariaLabel="View event dashboard for {{ $event->name }}">
                        View Dashboard
                    </x-ui.button>

                    @if(auth()->user() && auth()->user()->id === $event->created_by)
                        <x-ui.button as="a" href="{{ route('gift-exchange.edit', $event->id) }}" size="sm" variant="secondary" ariaLabel="Edit event {{ $event->name }}">
                            Edit
                        </x-ui.button>
                        <form method="POST" action="{{ route('gift-exchange.destroy', $event->id) }}" class="w-full" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone and will remove all participants, invitations, and assignments.')">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" size="sm" variant="danger" ariaLabel="Delete event {{ $event->name }}" class="w-full">
                                Delete
                            </x-ui.button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if(isset($event->participants) && $event->participants->count() > 0)
        <div class="border-t border-neutral-100 pt-4 mt-4">
            <h4 class="text-sm font-medium text-neutral-900 mb-2">Participants ({{ $event->participants->count() }})</h4>
            <ul class="text-sm text-neutral-600 space-y-1 list-disc list-inside">
                @foreach($event->participants as $participant)
                    <li class="flex items-start">
                        <span class="mr-2 text-neutral-400">•</span>
                        <div>
                            {{ $participant->user->name ?? 'Unknown User' }}
                            <span class="text-neutral-500">({{ $participant->user->email ?? 'No email' }})</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-ui.card>