@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <x-ui.card>
        <h1 class="text-xl sm:text-2xl font-bold mb-6 text-neutral-900 dark:text-white">{{ __('messages.manage_profile') }}</h1>

        <form method="POST" action="{{ route('profile.manage') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="mb-6 sm:mb-8">
                <h2 class="text-lg sm:text-xl font-semibold mb-4 text-neutral-900 dark:text-white">{{ __('messages.personal_information') }}</h2>
                <div class="space-y-4 sm:space-y-6">
                    <!-- Profile Picture -->
                    <div>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <img src="{{ auth()->user()->avatar ? '/storage/' . auth()->user()->avatar : '/fallback-avatar.jpg' }}" alt="Current Avatar" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover">
                            <div class="flex-1 w-full sm:w-auto">
                                <x-ui.form-group label="{{ __('messages.profile_picture') }}" id="avatar">
                                    <input type="file" name="avatar" id="avatar" class="block w-full text-sm text-gray-500 dark:text-neutral-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-primary-50 dark:file:bg-primary-900/30 file:text-primary-600 dark:file:text-primary-400
                                        hover:file:bg-primary-100 dark:hover:file:bg-primary-900/50">
                                </x-ui.form-group>
                            </div>
                        </div>
                    </div>

                    <!-- Username (Read-only with gray background) -->
                    <x-ui.form-group label="{{ __('messages.username') }}" id="username">
                        <x-ui.input type="text" name="username" id="username" value="{{ auth()->user()->username }}"
                            readonly class="bg-neutral-100 dark:bg-neutral-800 cursor-not-allowed text-neutral-500 dark:text-neutral-500" />
                        <p class="text-sm text-gray-500 dark:text-neutral-500 mt-1">{{ __('messages.username_cannot_change') }}</p>
                    </x-ui.form-group>

                    <!-- Full Name -->
                    <x-ui.form-group label="{{ __('messages.full_name') }}" id="name" error="{{ $errors->first('name') }}">
                        <x-ui.input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required />
                    </x-ui.form-group>

                    <!-- Email -->
                    <x-ui.form-group label="{{ __('messages.email') }}" id="email" error="{{ $errors->first('email') }}">
                        <x-ui.input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required />
                    </x-ui.form-group>
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="mb-6 sm:mb-8">
                <h2 class="text-lg sm:text-xl font-semibold mb-4 text-neutral-900 dark:text-white">{{ __('messages.change_password') }}</h2>
                <p class="text-sm text-gray-600 dark:text-neutral-400 mb-4">{{ __('messages.leave_blank_password') }}</p>
                <div class="space-y-4 sm:space-y-6">
                    <!-- Current Password -->
                    <x-ui.form-group label="{{ __('messages.current_password') }}" id="current_password" error="{{ $errors->first('current_password') }}">
                        <x-ui.input type="password" name="current_password" id="current_password" />
                    </x-ui.form-group>

                    <!-- New Password -->
                    <x-ui.form-group label="{{ __('messages.new_password') }}" id="password" error="{{ $errors->first('password') }}">
                        <x-ui.input type="password" name="password" id="password" />
                    </x-ui.form-group>

                    <!-- Confirm New Password -->
                    <x-ui.form-group label="{{ __('messages.confirm_new_password') }}" id="password_confirmation" error="{{ $errors->first('password_confirmation') }}">
                        <x-ui.input type="password" name="password_confirmation" id="password_confirmation" />
                    </x-ui.form-group>
                </div>
            </div>

            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end gap-3">
                <x-ui.button as="a" variant="secondary" href="{{ route('profile.show', auth()->user()) }}" class="w-full sm:w-auto min-h-[44px]">
                    {{ __('messages.cancel') }}
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto min-h-[44px]">
                    {{ __('messages.save_changes') }}
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection