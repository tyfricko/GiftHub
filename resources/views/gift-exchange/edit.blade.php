<x-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <x-ui.profile-header :user="auth()->user()" :isOwnProfile="true" />

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">Edit Event</h1>
                <p class="mt-1 text-neutral-600 dark:text-neutral-400">Update details for your gift exchange event.</p>
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
            <form method="POST" action="{{ route('gift-exchange.update', $event->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.form-group label="Event Name" for="name" required>
                    <x-ui.input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required />
                </x-ui.form-group>

                <x-ui.form-group label="Description" for="description">
                    <x-ui.textarea name="description" id="description" rows="4" placeholder="Enter event description">{{ old('description', $event->description) }}</x-ui.textarea>
                </x-ui.form-group>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-ui.form-group label="End Date" for="end_date" required>
                        <x-ui.input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\\TH:i')) }}" required />
                    </x-ui.form-group>
                    <x-ui.form-group label="Budget Limit (€)" for="budget_max">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-500 dark:text-neutral-400">€</span>
                            <x-ui.input type="number" name="budget_max" id="budget_max" min="0" step="0.01" value="{{ old('budget_max', $event->budget_max) }}" class="pl-8" placeholder="0.00" />
                        </div>
                    </x-ui.form-group>
                </div>

                <div class="pt-2">
                    <x-ui.checkbox
                        name="requires_shipping_address"
                        id="requires_shipping_address"
                        :checked="$event->requires_shipping_address"
                        label="Require shipping addresses for this event"
                    />
                </div>

                @if($errors->any())
                    <x-ui.alert type="error">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <div class="flex justify-end gap-3 pt-4">
                    <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" variant="secondary" min-h-[44px]">
                        <i class="fa fa-times mr-2"></i> Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" min-h-[44px]">
                        <i class="fa fa-save mr-2"></i> Save Changes
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-layout>
