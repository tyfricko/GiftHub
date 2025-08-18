
@extends('components.layout')

@section('content')
<div class="container mx-auto px-6">
    <!-- Profile Header -->
    <x-ui.profile-header
        :user="auth()->user()"
        :isOwnProfile="true"
        :avatarSize="'lg'"
    />
    
    <!-- Page Title and Intro -->
    <div class="mt-6 mb-6">
        <h1 class="text-3xl lg:text-4xl font-extrabold text-neutral-900">Welcome, {{ auth()->user()->fullname ?? auth()->user()->username }}!</h1>
        <p class="text-body text-neutral-600 mt-2">This is your personal space to manage your wishlists and gift exchanges.</p>
    </div>

    <!-- Profile Tabs -->
    @php
        $profileTabs = [
            ['key' => 'wishlists', 'label' => 'My Wishlist', 'url' => route('profile.wishlist')],
            ['key' => 'events',    'label' => 'My Events',   'url' => route('profile.events')],
        ];
    @endphp
    <x-ui.tabs :tabs="$profileTabs" :active="'events'" />

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-neutral-900">My Events</h2>
        <div class="flex items-center space-x-3">
            <button class="inline-flex items-center px-4 py-2 rounded-md bg-neutral-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 focus:outline-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                Get Event Ideas
            </button>
            <a href="{{ $links['createEvent'] ?? ($links['dashboard'] ?? '#') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:outline-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Event
            </a>
        </div>
    </div>

    <!-- Events Items -->
    <div class="space-y-6">
        @php
            $hasCreated = !($createdEvents ?? collect())->isEmpty();
            $hasParticipating = !($participatingEvents ?? collect())->isEmpty();
        @endphp

        @if(!$hasCreated && !$hasParticipating)
            <x-ui.card class="text-center py-12">
                <div class="text-neutral-600">
                    <i class="fa fa-calendar text-4xl mb-4 block" aria-hidden="true"></i>
                    <h3 class="text-subheadline font-semibold mb-2">No events yet!</h3>
                    <p class="text-body mb-4">Click 'Create Event' to start a new gift exchange.</p>
                    <a href="{{ $links['createEvent'] ?? ($links['dashboard'] ?? '#') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white">Create Event</a>
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