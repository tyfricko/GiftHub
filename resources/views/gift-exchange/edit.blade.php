<x-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Edit Event</h1>
        <p class="mt-1 text-gray-600">Update details for your gift exchange event.</p>
      </div>
      <div class="flex gap-2 shrink-0">
        <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" variant="secondary" size="sm">
          <i class="fa fa-arrow-left mr-2"></i> Back to Event
        </x-ui.button>
        <x-ui.button as="a" href="{{ route('gift-exchange.dashboard') }}" variant="secondary" size="sm">
          <i class="fa fa-th mr-2"></i> All Events
        </x-ui.button>
      </div>
    </div>

    <script>
      window.__initialNotifications = window.__initialNotifications || [];
      @if(session('success'))
        window.__initialNotifications.push({ type: 'success', message: {!! json_encode(session('success')) !!} });
      @endif

      @if($errors->any())
        window.__initialNotifications.push({ type: 'error', message: {!! json_encode(implode('<br/>', $errors->all())) !!} });
      @endif
    </script>

    <x-ui.card>
      <form method="POST" action="{{ route('gift-exchange.update', $event->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <x-ui.form-group label="Event Name" for="name" required>
          <x-ui.input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required />
        </x-ui.form-group>

        <x-ui.form-group label="Description" for="description">
          <textarea name="description" id="description" class="w-full border rounded p-2" rows="3">{{ old('description', $event->description) }}</textarea>
        </x-ui.form-group>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <x-ui.form-group label="End Date" for="end_date" required>
            <x-ui.input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\\TH:i')) }}" required />
          </x-ui.form-group>
          <x-ui.form-group label="Budget Limit (€)" for="budget_max">
            <x-ui.input type="number" name="budget_max" id="budget_max" min="0" step="0.01" value="{{ old('budget_max', $event->budget_max) }}" />
          </x-ui.form-group>
        </div>

        <div class="flex justify-end gap-2">
          <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" variant="secondary">Cancel</x-ui.button>
          <x-ui.button type="submit"><i class="fa fa-save mr-2"></i> Save Changes</x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>
</x-layout>