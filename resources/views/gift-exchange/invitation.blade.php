@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <x-ui.card class="max-w-lg mx-auto">
        <h2 class="text-2xl font-bold mb-4">Gift Exchange Invitation</h2>
        
        @if($invitation->status === 'pending')
            <div class="mb-6">
                <p class="mb-4">You've been invited to participate in the gift exchange:</p>
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h3 class="font-semibold">{{ $event->name }}</h3>
                    @if($event->description)
                        <p class="text-gray-600 mt-1">{{ $event->description }}</p>
                    @endif
                    <p class="text-sm text-gray-500 mt-2">Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</p>
                </div>

                <form method="POST" action="{{ route('gift-exchange.respond', $invitation->id) }}" class="space-y-4">
                    @csrf
                    <div class="flex space-x-4">
                        <x-ui.button type="submit" name="response" value="accept" variant="primary" class="flex-1">
                            Accept Invitation
                        </x-ui.button>
                        <x-ui.button type="submit" name="response" value="decline" variant="secondary" class="flex-1">
                            Decline
                        </x-ui.button>
                    </div>
                </form>
            </div>
        @elseif($invitation->status === 'accepted')
            <div class="alert alert-success mb-4">
                You've already accepted this invitation.
            </div>
        @else
            <div class="alert alert-info mb-4">
                You've declined this invitation.
            </div>
        @endif

        <div class="mt-6 text-sm text-gray-500">
            <p>Invited by: {{ $invitation->inviter->name }}</p>
            <p>Sent: {{ $invitation->created_at->diffForHumans() }}</p>
        </div>
    </x-ui.card>
</div>
@endsection