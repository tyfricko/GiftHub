
@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Profile Header -->
    <x-ui.profile-header
        :user="auth()->user()"
        :isOwnProfile="true"
        :avatarSize="'lg'"
    />
    
    <!-- Page Title and Intro -->
    <div class="mt-4 sm:mt-6 mb-4 sm:mb-6">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-neutral-900 dark:text-white">Welcome, {{ auth()->user()->fullname ?? auth()->user()->username }}!</h1>
        <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 mt-2">This is your personal space to manage your wishlists and gift exchanges.</p>
    </div>
 
    <!-- Profile Tabs -->
    @php
        $profileTabs = [
            ['key' => 'wishlists', 'label' => 'My Wishlist', 'url' => route('profile.wishlist')],
            // Attach badge to events tab when pending invitations exist (controller provides pendingInvitationsCount)
            ['key' => 'events',    'label' => 'My Events',   'url' => route('profile.events'), 'badge' => $pendingInvitationsCount ?? 0],
        ];
    @endphp
    <x-ui.tabs :tabs="$profileTabs" :active="'events'" />
 
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-white">My Events</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:space-x-3 w-full sm:w-auto">
            <button class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="hidden sm:inline">Get Event Ideas</span>
            </button>
            <a href="{{ $links['createEvent'] ?? ($links['dashboard'] ?? '#') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-600 dark:bg-primary-600 text-white hover:bg-primary-700 dark:hover:bg-primary-700 focus:outline-none min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Event
            </a>
        </div>
    </div>

    <!-- Pending Invitations -->
    @if(!empty($pendingInvitations) && $pendingInvitations->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg sm:text-xl font-semibold mb-3 text-neutral-900 dark:text-white">Pending Invitations</h3>
            <div class="space-y-3">
                @foreach($pendingInvitations as $invitation)
                    <x-ui.card>
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-0">
                            <div class="pr-0 sm:pr-4">
                                <div class="text-sm text-neutral-600 dark:text-neutral-400">From: {{ $invitation->event->creator->fullname ?? $invitation->event->creator->username ?? 'Organizer' }}</div>
                                <div class="text-base sm:text-lg font-semibold text-neutral-900 dark:text-white">{{ $invitation->event->name }}</div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-500 mt-1">{{ Illuminate\Support\Str::limit($invitation->event->description ?? '', 140) }}</div>
                                <div class="text-xs text-neutral-400 dark:text-neutral-500 mt-2">Sent: {{ $invitation->sent_at ? $invitation->sent_at->diffForHumans() : 'N/A' }}</div>
                            </div>
                            <div class="flex items-center space-x-2 w-full sm:w-auto">
                                <form method="POST" action="{{ route('invitations.accept', $invitation->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto px-3 sm:px-4 py-2 sm:py-2 bg-primary-600 text-white rounded-md min-h-[44px]">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('invitations.decline', $invitation->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto px-3 sm:px-4 py-2 sm:py-2 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-md min-h-[44px]">Decline</button>
                                </form>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Events Items -->
    <div class="space-y-6">
        @php
            $hasCreated = !($createdEvents ?? collect())->isEmpty();
            $hasParticipating = !($participatingEvents ?? collect())->isEmpty();
        @endphp

        @if(!$hasCreated && !$hasParticipating)
            <x-ui.card class="text-center py-8 sm:py-12">
                <div class="text-neutral-600 dark:text-neutral-400">
                    <i class="fa fa-calendar text-3xl sm:text-4xl mb-4 block" aria-hidden="true"></i>
                    <h3 class="text-lg sm:text-subheadline font-semibold mb-2 text-neutral-900 dark:text-white">No events yet!</h3>
                    <p class="text-sm sm:text-body mb-4">Click 'Create Event' to start a new gift exchange.</p>
                    <a href="{{ $links['createEvent'] ?? ($links['dashboard'] ?? '#') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-600 dark:bg-primary-600 text-white min-h-[44px]">Create Event</a>
                </div>
            </x-ui.card>
        @else
            @foreach(($createdEvents ?? collect())->merge($participatingEvents ?? collect()) as $event)
                <x-ui.event-card :event="$event" :showImage="false" />
            @endforeach
        @endif
    </div>
</div>
 
 
<script>
    // Notifications are handled by the global NotificationManager.
    // Use showNotification(message, type) or the backward compatible showToast(message, type).
 
    document.addEventListener('DOMContentLoaded', function() {
    });
</script>
 
@endsection