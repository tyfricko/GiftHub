<x-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="text-center max-w-2xl mx-auto">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-6">
                <i class="fa fa-envelope-open-text text-primary-600 dark:text-primary-400 text-2xl"></i>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-neutral-100 mb-4">
                You've received a Gift Exchange invitation
            </h1>

            <div class="text-lg text-neutral-600 dark:text-neutral-400 mb-8 space-y-2">
                <p>This invitation is for <strong class="text-neutral-900 dark:text-neutral-200">{{ $invitation->email }}</strong>.</p>
                <p>To accept the invitation you need to create an account or log in with that email address.</p>
            </div>

            <x-ui.card class="max-w-md mx-auto">
                <div class="space-y-4">
                    <div class="text-sm text-neutral-700 dark:text-neutral-300 space-y-2">
                        <p><strong>Event:</strong> {{ $event->name }}</p>
                        @if($event->description)
                            <p class="text-neutral-500 dark:text-neutral-400">{{ $event->description }}</p>
                        @endif
                        <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400 pt-2">
                            <i class="fa fa-calendar-alt"></i>
                            <span>Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</span>
                        </div>
                        @if($event->budget_max)
                            <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                                <i class="fa fa-money-bill-wave"></i>
                                <span>Budget: up to €{{ number_format($event->budget_max, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <x-ui.button as="a" href="{{ route('login', ['invitation' => $invitation->token]) }}" class="flex-1" min-h-[48px]">
                            <i class="fa fa-sign-in-alt mr-2"></i> Log in to accept
                        </x-ui.button>

                        <x-ui.button as="a" href="{{ route('register', ['invitation' => $invitation->token]) }}" variant="secondary" class="flex-1" min-h-[48px]">
                            <i class="fa fa-user-plus mr-2"></i> Create an account
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>

            <div class="mt-6 text-sm text-neutral-500 dark:text-neutral-400 max-w-md mx-auto">
                <p>If you already have an account with a different email, please sign out and sign up with the invited email, or contact the event organizer.</p>
            </div>

            <div class="mt-8 text-sm text-neutral-500 dark:text-neutral-400">
                <p>Questions? Reach out to the event organizer for help.</p>
            </div>
        </div>
    </div>
</x-layout>
