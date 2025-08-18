# Events Page Redesign Specification

## Overview
Redesign the events page to match the provided screenshots with a clean, modern layout consistent with the established design system.

## Screenshots Analysis

### Empty State (Screenshot 1)
- Header: "Welcome, Demo User!" with subtitle
- Tab navigation: "My Wishlist" and "My Events" (Events tab active)
- Section header: "My Events" with green "Create Event" button aligned right
- Centered empty state with:
  - Calendar/gift icon (gray/neutral color)
  - "No events yet!" heading
  - "Click 'Create Event' to start a new gift exchange." subtitle

### With Events (Screenshot 2)
- Same header and navigation structure
- Event card displaying:
  - Event title: "Test event"
  - Draw date: "Draw Date: 31/12/2025"
  - Description: "test description"
  - Budget: "Budget: $25.00" (in teal/blue color)
  - Participants section: "Participants (3)" with bulleted list
  - Status badge: "Pending Draw" (yellow/amber background)

## Components to Create

### 1. Event Card Component (`event-card.blade.php`)
```php
@props([
    'event',
    'showActions' => true
])

<div class="bg-white rounded-lg border border-neutral-200 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex justify-between items-start mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-neutral-900 mb-1">{{ $event->name }}</h3>
            <p class="text-sm text-neutral-600 mb-2">Draw Date: {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}</p>
            @if($event->description)
                <p class="text-sm text-neutral-700 mb-3">{{ $event->description }}</p>
            @endif
            @if($event->budget_max)
                <p class="text-sm font-medium text-accent-600 mb-3">Budget: ${{ number_format($event->budget_max, 2) }}</p>
            @endif
        </div>
        <div class="ml-4">
            @php
                $status = 'Pending Draw'; // Default status
                $statusColor = 'bg-amber-100 text-amber-800'; // Yellow for pending
                
                // Determine status based on event state
                if($event->assignments && $event->assignments->count() > 0) {
                    $status = 'Assignments Made';
                    $statusColor = 'bg-primary-100 text-primary-800';
                } elseif(\Carbon\Carbon::parse($event->end_date)->isPast()) {
                    $status = 'Expired';
                    $statusColor = 'bg-neutral-100 text-neutral-600';
                }
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                {{ $status }}
            </span>
        </div>
    </div>
    
    @if($event->participants && $event->participants->count() > 0)
        <div class="border-t border-neutral-200 pt-4">
            <h4 class="text-sm font-medium text-neutral-900 mb-2">Participants ({{ $event->participants->count() }})</h4>
            <ul class="text-sm text-neutral-600 space-y-1">
                @foreach($event->participants as $participant)
                    <li class="flex items-center">
                        <span class="w-1.5 h-1.5 bg-neutral-400 rounded-full mr-2"></span>
                        {{ $participant->user->name ?? 'Unknown User' }} ({{ $participant->user->email ?? 'No email' }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if($showActions)
        <div class="border-t border-neutral-200 pt-4 mt-4">
            <div class="flex gap-2">
                <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" size="sm" variant="secondary">
                    View Dashboard
                </x-ui.button>
                @if(auth()->user() && auth()->user()->id === $event->created_by)
                    <x-ui.button as="a" href="{{ route('gift-exchange.edit', $event->id) }}" size="sm" variant="ghost">
                        Edit
                    </x-ui.button>
                @endif
            </div>
        </div>
    @endif
</div>
```

### 2. Empty State Component (`empty-state.blade.php`)
```php
@props([
    'icon' => 'calendar',
    'title' => 'No items yet!',
    'description' => '',
    'actionText' => '',
    'actionUrl' => '#'
])

<div class="text-center py-12">
    <div class="mx-auto w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
        @if($icon === 'calendar')
            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        @else
            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        @endif
    </div>
    <h3 class="text-lg font-medium text-neutral-900 mb-2">{{ $title }}</h3>
    @if($description)
        <p class="text-neutral-600 mb-6 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    @if($actionText && $actionUrl !== '#')
        <x-ui.button as="a" href="{{ $actionUrl }}">{{ $actionText }}</x-ui.button>
    @endif
</div>
```

## Updated Events Page Layout

### File: `resources/views/profile-events.blade.php`
```php
<x-layout>
    <div class="container mx-auto px-6 py-6 space-y-6">
        <!-- Profile Header -->
        <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

        <!-- Tab Navigation -->
        @php
            $profileTabs = [
                ['key' => 'wishlists', 'label' => 'My Wishlist', 'url' => route('profile.wishlist')],
                ['key' => 'events',    'label' => 'My Events',   'url' => route('profile.events')],
                ['key' => 'friends',   'label' => 'My Friends',  'url' => route('profile.friends')],
                ['key' => 'settings',  'label' => 'Settings',    'url' => route('profile.settings')],
            ];
        @endphp
        <x-ui.tabs :tabs="$profileTabs" :active="$activeTab" />

        <!-- Events Section -->
        <div class="space-y-6">
            <!-- Section Header -->
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-neutral-900">My Events</h2>
                <x-ui.button as="a" href="{{ route('gift-exchange.dashboard') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Event
                </x-ui.button>
            </div>

            <!-- Events Content -->
            @if(($createdEvents ?? collect())->isEmpty() && ($participatingEvents ?? collect())->isEmpty())
                <!-- Empty State -->
                <x-ui.empty-state 
                    icon="calendar"
                    title="No events yet!"
                    description="Click 'Create Event' to start a new gift exchange."
                />
            @else
                <!-- Events List -->
                <div class="space-y-4">
                    @foreach(($createdEvents ?? collect())->merge($participatingEvents ?? collect()) as $event)
                        <x-ui.event-card :event="$event" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout>
```

## Design System Consistency

### Colors Used
- **Primary Green**: `primary-600` for Create Event button
- **Accent Blue**: `accent-600` for budget text
- **Neutral Grays**: `neutral-100`, `neutral-200`, `neutral-400`, `neutral-600`, `neutral-900` for various text and borders
- **Status Colors**: 
  - Amber (`amber-100`, `amber-800`) for "Pending Draw"
  - Primary (`primary-100`, `primary-800`) for "Assignments Made"
  - Neutral for expired events

### Typography
- **Section Header**: `text-2xl font-semibold` (32px, 600 weight)
- **Event Title**: `text-lg font-semibold` (18px, 600 weight)
- **Body Text**: `text-sm` (14px) for descriptions and details
- **Empty State Title**: `text-lg font-medium` (18px, 500 weight)

### Spacing & Layout
- **Container**: Uses `container mx-auto px-6` for consistent width with header/footer
- **Section Spacing**: `space-y-6` (24px) between major sections
- **Card Padding**: `p-6` (24px) internal padding
- **Card Spacing**: `space-y-4` (16px) between event cards

### Interactive Elements
- **Hover Effects**: `hover:shadow-md transition-shadow duration-200` on cards
- **Button Variants**: Primary for "Create Event", secondary for "View Dashboard", ghost for "Edit"
- **Status Badges**: Rounded full badges with appropriate semantic colors

## Implementation Notes

1. **Data Structure**: The component expects event objects with relationships loaded (participants, assignments)
2. **Status Logic**: Status is determined by checking if assignments exist and if the end_date has passed
3. **Permissions**: Edit button only shows for event creators (created_by matches current user)
4. **Responsive**: Layout works on mobile and desktop with appropriate spacing
5. **Accessibility**: Proper semantic HTML, ARIA labels where needed, keyboard navigation support

## Next Steps

1. Switch to Code mode to implement these components
2. Create the `event-card.blade.php` component
3. Create the `empty-state.blade.php` component  
4. Update the `profile-events.blade.php` page layout
5. Test the implementation with sample data
6. Verify responsive behavior and accessibility compliance