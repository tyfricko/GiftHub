@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50 dark:bg-neutral-900">
  <div class="max-w-md w-full">
    <div class="mx-auto w-full">
      <x-ui.card class="p-6 sm:p-8 rounded-lg bg-white dark:bg-neutral-800">
        <h2 class="text-center text-xl sm:text-2xl lg:text-3xl font-extrabold text-neutral-900 dark:text-white mb-6">{{ __('messages.create_account') }}</h2>

        <form action="{{ route('register') }}" method="POST" class="space-y-4 sm:space-y-6">
          @csrf

          <x-ui.form-group label="{{ __('messages.name') }}" for="name" error="{{ $errors->first('name') }}">
            <x-ui.input
              type="text"
              name="name"
              id="name"
              value="{{ old('name') }}"
              required
              placeholder="{{ __('messages.full_name_placeholder') }}"
            />
          </x-ui.form-group>

          <x-ui.form-group label="{{ __('messages.username') }}" for="username" error="{{ $errors->first('username') }}">
            <x-ui.input
              type="text"
              name="username"
              id="username"
              value="{{ old('username') }}"
              required
              placeholder="{{ __('messages.username_placeholder') }}"
            />
          </x-ui.form-group>

          <x-ui.form-group label="{{ __('messages.email') }}" for="email" error="{{ $errors->first('email') }}">
            <x-ui.input
              type="email"
              name="email"
              id="email"
              value="{{ old('email') }}"
              required
              placeholder="{{ __('messages.email_placeholder') }}"
            />
          </x-ui.form-group>

          <x-ui.form-group label="{{ __('messages.password') }}" for="password" error="{{ $errors->first('password') }}">
            <x-ui.input
              type="password"
              name="password"
              id="password"
              required
              placeholder="{{ __('messages.password_placeholder') }}"
            />
          </x-ui.form-group>

          <x-ui.form-group label="{{ __('messages.confirm_password') }}" for="password_confirmation" error="{{ $errors->first('password_confirmation') }}">
            <x-ui.input
              type="password"
              name="password_confirmation"
              id="password_confirmation"
              required
              placeholder="{{ __('messages.confirm_password_placeholder') }}"
            />
          </x-ui.form-group>

          <div>
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full min-h-[44px]">
              {{ __('messages.register') }}
            </x-ui.button>
          </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-700">
          <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('messages.already_have_account') }} <a href="{{ route('login') }}" class="text-primary-600 dark:text-primary-400 font-medium hover:underline">{{ __('messages.sign_in') }}</a></p>
        </div>
      </x-ui.card>
    </div>
  </div>
</div>
@endsection