<x-layout>
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 mb-6">
        <i class="fa fa-envelope-open-text text-blue-600 text-xl"></i>
      </div>

      <h1 class="text-3xl font-bold text-gray-900 mb-4">You’ve received a Gift Exchange invitation</h1>

      <div class="text-lg text-gray-600 mb-6">
        <p>This invitation is for <strong>{{ $invitation->email }}</strong>.</p>
        <p class="mt-2">To accept the invitation you need to create an account or log in with that email address.</p>
      </div>

      <div class="max-w-md mx-auto space-y-4">
        <x-ui.card>
          <div class="text-sm text-gray-700 mb-4">
            <p><strong>Event:</strong> {{ $event->name }}</p>
            @if($event->description)
              <p class="mt-1">{{ $event->description }}</p>
            @endif
            <p class="mt-2 text-gray-500">Ends: {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}</p>
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <x-ui.button as="a" href="{{ route('login', ['invitation' => $invitation->token]) }}">
              <i class="fa fa-sign-in-alt mr-2"></i> Log in to accept
            </x-ui.button>

            <x-ui.button as="a" href="{{ route('register', ['invitation' => $invitation->token]) }}" variant="secondary">
              <i class="fa fa-user-plus mr-2"></i> Create an account
            </x-ui.button>
          </div>
        </x-ui.card>

        <div class="text-sm text-gray-500">
          <p>If you already have an account with a different email, please sign out and sign up with the invited email, or contact the event organizer.</p>
        </div>
      </div>

      <div class="mt-8 text-sm text-gray-500">
        <p>Questions? Reach out to the event organizer for help.</p>
      </div>
    </div>
  </div>
</x-layout>