@extends('components.layout')

@section('title', 'Shipping Address')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                    Provide your shipping address
                </h1>
                <p class="mt-1 text-neutral-600 dark:text-neutral-400">
                    Please enter your shipping details for this gift exchange event.
                </p>
            </div>

            @if(session('info'))
                <x-ui.alert type="info" class="mb-6">
                    {{ session('info') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="error" class="mb-6">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <form method="POST" action="{{ route('gift-exchange.shipping-address.update', $event->id) }}">
                @csrf

                @php
                    $addr = $participant->shipping_address ?? [];
                @endphp

                <div class="space-y-6">
                    <x-ui.form-group label="Full name" for="full_name">
                        <x-ui.input 
                            type="text" 
                            name="full_name" 
                            id="full_name" 
                            :value="old('full_name', $addr['full_name'] ?? $participant->user->name ?? '')"
                            placeholder="Enter your full name"
                        />
                        @error('full_name')
                            <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                        @enderror
                    </x-ui.form-group>

                    <x-ui.form-group label="Address line 1" for="address_line_1" required>
                        <x-ui.input 
                            type="text" 
                            name="address_line_1" 
                            id="address_line_1" 
                            :value="old('address_line_1', $addr['address_line_1'] ?? '')"
                            placeholder="Street address, P.O. box, etc."
                        />
                        @error('address_line_1')
                            <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                        @enderror
                    </x-ui.form-group>

                    <x-ui.form-group label="Address line 2 (optional)" for="address_line_2">
                        <x-ui.input 
                            type="text" 
                            name="address_line_2" 
                            id="address_line_2" 
                            :value="old('address_line_2', $addr['address_line_2'] ?? '')"
                            placeholder="Apartment, suite, unit, building, floor, etc."
                        />
                        @error('address_line_2')
                            <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                        @enderror
                    </x-ui.form-group>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-ui.form-group label="City" for="city" required>
                            <x-ui.input 
                                type="text" 
                                name="city" 
                                id="city" 
                                :value="old('city', $addr['city'] ?? '')"
                                placeholder="City"
                            />
                            @error('city')
                                <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                            @enderror
                        </x-ui.form-group>

                        <x-ui.form-group label="State / Province" for="state_province" required>
                            <x-ui.input 
                                type="text" 
                                name="state_province" 
                                id="state_province" 
                                :value="old('state_province', $addr['state_province'] ?? '')"
                                placeholder="State/Province"
                            />
                            @error('state_province')
                                <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                            @enderror
                        </x-ui.form-group>

                        <x-ui.form-group label="Postal code" for="postal_code" required>
                            <x-ui.input 
                                type="text" 
                                name="postal_code" 
                                id="postal_code" 
                                :value="old('postal_code', $addr['postal_code'] ?? '')"
                                placeholder="Postal code"
                            />
                            @error('postal_code')
                                <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                            @enderror
                        </x-ui.form-group>
                    </div>

                    <x-ui.form-group label="Country" for="country" required>
                        <x-ui.input 
                            type="text" 
                            name="country" 
                            id="country" 
                            :value="old('country', $addr['country'] ?? '')"
                            placeholder="Country"
                        />
                        @error('country')
                            <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                        @enderror
                    </x-ui.form-group>

                    <x-ui.form-group label="Phone (optional)" for="phone">
                        <x-ui.input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            :value="old('phone', $addr['phone'] ?? $participant->user->phone ?? '')"
                            placeholder="Phone number for delivery questions"
                        />
                        @error('phone')
                            <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                        @enderror
                    </x-ui.form-group>

                    <x-ui.form-group label="Delivery notes (optional)" for="delivery_notes">
                        <x-ui.textarea 
                            name="delivery_notes" 
                            id="delivery_notes" 
                            rows="3"
                            placeholder="Special delivery instructions, gate codes, etc."
                        >{{ old('delivery_notes', $addr['delivery_notes'] ?? '') }}</x-ui.textarea>
                        @error('delivery_notes')
                            <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                        @enderror
                    </x-ui.form-group>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                        <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" variant="secondary" min-h-[44px]">
                            <i class="fa fa-arrow-left mr-2"></i> Back to event
                        </x-ui.button>
                        <x-ui.button type="submit" min-h-[44px]">
                            <i class="fa fa-save mr-2"></i> Save shipping address
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>
@endsection
