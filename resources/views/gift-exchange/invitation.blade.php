@extends('components.layout')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <x-ui.card class="max-w-2xl mx-auto">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-4">
                <i class="fa fa-gift text-primary-600 dark:text-primary-400 text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Gift Exchange Invitation</h2>
        </div>
        
        @if($invitation->status === 'pending')
            <div class="space-y-6">
                <div class="text-center">
                    <p class="text-neutral-600 dark:text-neutral-400 mb-4">You've been invited to participate in the gift exchange:</p>
                </div>

                <div class="bg-neutral-50 dark:bg-neutral-800/50 p-4 rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <h3 class="font-semibold text-lg text-neutral-900 dark:text-neutral-100">{{ $event->name }}</h3>
                    @if($event->description)
                        <p class="text-neutral-600 dark:text-neutral-400 mt-2">{{ $event->description }}</p>
                    @endif
                    <div class="mt-3 text-sm text-neutral-500 dark:text-neutral-400 flex items-center gap-2">
                        <i class="fa fa-calendar-alt"></i>
                        <span>Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</span>
                    </div>
                    @if($event->budget_max)
                        <div class="mt-2 text-sm text-neutral-500 dark:text-neutral-400 flex items-center gap-2">
                            <i class="fa fa-money-bill-wave"></i>
                            <span>Budget: up to €{{ number_format($event->budget_max, 2) }}</span>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('gift-exchange.respondToInvitationWeb', $invitation->token) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <x-ui.button type="submit" name="response" value="accepted" variant="primary" class="w-full" min-h-[48px]">
                            <i class="fa fa-check mr-2"></i> Accept Invitation
                        </x-ui.button>
                        <x-ui.button type="submit" name="response" value="declined" variant="secondary" class="w-full" min-h-[48px]">
                            <i class="fa fa-times mr-2"></i> Decline
                        </x-ui.button>
                    </div>
                </form>
            </div>
        @elseif($invitation->status === 'accepted')
            <x-ui.alert type="success" title="Invitation Accepted">
                You've already accepted this invitation. Get ready for the gift exchange!
            </x-ui.alert>
        @else
            <x-ui.alert type="info" title="Invitation Declined">
                You've declined this invitation. If this was a mistake, please contact the event organizer.
            </x-ui.alert>
        @endif

        <div class="mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-700 text-center text-sm text-neutral-500 dark:text-neutral-400">
            <p>Invitation sent: {{ $invitation->created_at->diffForHumans() }}</p>
        </div>
    </x-ui.card>
</div>
@endsection
