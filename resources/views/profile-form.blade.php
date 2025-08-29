@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <x-ui.card>
        <h1 class="text-2xl font-bold mb-6">Manage Profile</h1>

        <form method="POST" action="{{ route('profile.manage') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
                <div class="space-y-6">
                    <!-- Profile Picture -->
                    <div>
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

                    <!-- Username (Read-only with gray background) -->
                    <x-ui.form-group label="Username" id="username">
                        <input type="text" name="username" id="username" value="{{ auth()->user()->username }}"
                            class="w-full px-4 py-2 border border-neutral-gray rounded-md bg-gray-100 text-body text-neutral-gray cursor-not-allowed" readonly>
                        <p class="text-sm text-gray-500 mt-1">Username cannot be changed as it's used for login and public profile</p>
                    </x-ui.form-group>

                    <!-- Full Name -->
                    <x-ui.form-group label="Full Name" id="name" error="{{ $errors->first('name') }}">
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray" required>
                    </x-ui.form-group>

                    <!-- Email -->
                    <x-ui.form-group label="Email" id="email" error="{{ $errors->first('email') }}">
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray" required>
                    </x-ui.form-group>
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                <p class="text-sm text-gray-600 mb-4">Leave blank if you don't want to change your password</p>
                <div class="space-y-6">
                    <!-- Current Password -->
                    <x-ui.form-group label="Current Password" id="current_password" error="{{ $errors->first('current_password') }}">
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray">
                    </x-ui.form-group>

                    <!-- New Password -->
                    <x-ui.form-group label="New Password" id="password" error="{{ $errors->first('password') }}">
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray">
                    </x-ui.form-group>

                    <!-- Confirm New Password -->
                    <x-ui.form-group label="Confirm New Password" id="password_confirmation" error="{{ $errors->first('password_confirmation') }}">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border border-neutral-gray rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 text-body text-neutral-gray">
                    </x-ui.form-group>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-ui.button as="a" variant="secondary" href="{{ route('profile.show', auth()->user()) }}">
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