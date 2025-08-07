@extends('components.layout')

@section('content')
<div class="container">
    <h1>Gift Exchange Events</h1>

    {{-- Event Creation Form --}}
    <section>
        <h2>Create New Event</h2>
        <form id="create-event-form" method="POST" action="{{ route('gift-exchange.create') }}">
            @csrf
            <div>
                <label for="name">Event Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description"></textarea>
            </div>
            <div>
                <label for="end_date">End Date:</label>
                <input type="datetime-local" name="end_date" id="end_date" required>
            </div>
            <div>
                <label for="budget_max">Budget Limit (€):</label>
                <input type="number" name="budget_max" id="budget_max" min="0" step="0.01">
            </div>
            <button type="submit">Create Event</button>
        </form>
    </section>

    {{-- List of User's Events --}}
    <section>
        <h2>Your Events</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div id="events-list">
            @if($events->isEmpty())
                <p>You have not created any gift exchange events yet.</p>
            @else
                <ul>
                    @foreach($events as $event)
                        <li style="margin-bottom:2em;">
                            <strong>{{ $event->name }}</strong>
                            <br>
                            @if($event->description)
                                <em>{{ $event->description }}</em><br>
                            @endif
                            <span>Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</span>
                            @if($event->budget_max)
                                <br><span>Budget: up to €{{ number_format($event->budget_max, 2) }}</span>
                            @endif

                            {{-- Invitation Form --}}
                            <form method="POST" action="{{ route('gift-exchange.invite', $event->id) }}" style="margin-top:1em;">
                                @csrf
                                <label for="emails_{{ $event->id }}">Invite participants (comma-separated emails):</label>
                                <input type="text" name="emails[]" id="emails_{{ $event->id }}" placeholder="email1@example.com, email2@example.com" oninput="this.setCustomValidity('')" required>
                                <button type="submit" class="btn btn-sm btn-primary">Send Invites</button>
                            </form>

                            {{-- Invitations/Participants List --}}
                            <div style="margin-top:1em;">
                                <strong>Invitations/Participants:</strong>
                                @php
                                    $invitations = $event->invitations ?? \App\Models\GiftExchangeInvitation::where('event_id', $event->id)->get();
                                @endphp
                                @if($invitations->isEmpty())
                                    <div>No invitations sent yet.</div>
                                @else
                                    <ul>
                                        @foreach($invitations as $inv)
                                            <li>
                                                {{ $inv->email }} - <span>{{ ucfirst($inv->status) }}</span>
                                                @if($inv->status === 'pending')
                                                    <span class="text-warning">(Pending)</span>
                                                @elseif($inv->status === 'accepted')
                                                    <span class="text-success">(Accepted)</span>
                                                @elseif($inv->status === 'declined')
                                                    <span class="text-danger">(Declined)</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</div>
@endsection