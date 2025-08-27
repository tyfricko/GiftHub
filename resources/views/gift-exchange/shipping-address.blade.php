@extends('components.layout')

@section('title', 'Shipping Address')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Provide your shipping address</h1>

    @if(session('info'))
        <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('gift-exchange.shipping-address.update', $event->id) }}">
        @csrf

        @php
            $addr = $participant->shipping_address ?? [];
        @endphp

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Full name</label>
                <input type="text" name="full_name" value="{{ old('full_name', $addr['full_name'] ?? $participant->user->name ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                @error('full_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Address line 1</label>
                <input type="text" name="address_line_1" value="{{ old('address_line_1', $addr['address_line_1'] ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                @error('address_line_1') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Address line 2 (optional)</label>
                <input type="text" name="address_line_2" value="{{ old('address_line_2', $addr['address_line_2'] ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                @error('address_line_2') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="city" value="{{ old('city', $addr['city'] ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                    @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">State / Province</label>
                    <input type="text" name="state_province" value="{{ old('state_province', $addr['state_province'] ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                    @error('state_province') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Postal code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $addr['postal_code'] ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                    @error('postal_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Country</label>
                <input type="text" name="country" value="{{ old('country', $addr['country'] ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                @error('country') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                <input type="text" name="phone" value="{{ old('phone', $addr['phone'] ?? $participant->user->phone ?? '') }}" class="mt-1 block w-full border rounded p-2" />
                @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Delivery notes (optional)</label>
                <textarea name="delivery_notes" rows="3" class="mt-1 block w-full border rounded p-2">{{ old('delivery_notes', $addr['delivery_notes'] ?? '') }}</textarea>
                @error('delivery_notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('gift-exchange.show', $event->id) }}" class="text-sm text-gray-600 hover:underline">Back to event</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Save shipping address</button>
            </div>
        </div>
    </form>
</div>
@endsection