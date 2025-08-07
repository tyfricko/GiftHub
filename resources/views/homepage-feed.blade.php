@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content -->
        <div class="md:w-2/3">
            <x-ui.card>
                <h2 class="text-2xl font-bold mb-4">Recent Activity</h2>
                
                <div class="space-y-4">
                    @foreach($activities as $activity)
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex items-start gap-3">
                            <img src="{{ $activity->user->avatar_url }}" alt="{{ $activity->user->name }}" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium">{{ $activity->user->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $activity->description }}</p>
                                <p class="text-gray-400 text-xs mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar -->
        <div class="md:w-1/3">
            <x-ui.card>
                <h3 class="text-lg font-semibold mb-3">Upcoming Exchanges</h3>
                @if($upcomingExchanges->isEmpty())
                    <p class="text-gray-500">No upcoming exchanges</p>
                @else
                    <ul class="space-y-2">
                        @foreach($upcomingExchanges as $exchange)
                        <li class="flex items-center justify-between py-2 border-b last:border-b-0">
                            <div>
                                <p class="font-medium">{{ $exchange->name }}</p>
                                <p class="text-sm text-gray-500">{{ $exchange->end_date->diffForHumans() }}</p>
                            </div>
                            <a href="{{ route('gift-exchange.show', $exchange->id) }}" class="text-primary hover:underline">View</a>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>

            <x-ui.card class="mt-4">
                <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <x-ui.button variant="primary" class="w-full" href="{{ route('gift-exchange.create') }}">
                        Create New Exchange
                    </x-ui.button>
                    <x-ui.button variant="secondary" class="w-full" href="{{ route('wishlist.index') }}">
                        Manage Wishlist
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection
