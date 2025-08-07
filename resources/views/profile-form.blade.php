@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <x-ui.card>
        <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Picture -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-4">
                        <img src="{{ auth()->user()->avatar ? '/storage/' . auth()->user()->avatar : '/fallback-avatar.jpg' }}" alt="Current Avatar" class="w-20 h-20 rounded-full object-cover">
                        <div>
                            <x-ui.form-group label="Profile Picture" id="avatar">
                                <input type="file" name="avatar" id="avatar" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary file:text-white
                                    hover:file:bg-primary-dark">
                            </x-ui.form-group>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <x-ui.form-group label="Full Name" id="name" error="{{ $errors->first('name') }}">
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                        class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray" required>
                </x-ui.form-group>

                <x-ui.form-group label="Email" id="email" error="{{ $errors->first('email') }}">
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                        class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray" required>
                </x-ui.form-group>

                <x-ui.form-group label="Username" id="username" error="{{ $errors->first('username') }}">
                    <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}"
                        class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray" required>
                </x-ui.form-group>

                <x-ui.form-group label="Address" id="address" error="{{ $errors->first('address') }}">
                    <textarea name="address" id="address" class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray">{{ old('address', auth()->user()->address) }}</textarea>
                </x-ui.form-group>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-ui.button variant="secondary" href="{{ route('profile.show', auth()->user()) }}">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    Save Changes
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection