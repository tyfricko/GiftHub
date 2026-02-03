@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50 dark:bg-neutral-900">
  <div class="max-w-md w-full">
    <div class="mx-auto w-full">
      <x-ui.card class="p-6 sm:p-8 rounded-lg bg-white dark:bg-neutral-800">
        <h2 class="text-center text-xl sm:text-2xl lg:text-3xl font-extrabold text-neutral-900 dark:text-white mb-6">{{ __('messages.sign_in_title') }}</h2>

        @if (session('status'))
            <x-ui.alert type="success" class="mb-6">
                {{ session('status') }}
            </x-ui.alert>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
          @csrf

          <x-ui.form-group label="{{ __('messages.username') }}" for="loginusername" error="{{ $errors->first('loginusername') }}">
            <x-ui.input
              type="text"
              name="loginusername"
              id="loginusername"
              value="{{ old('loginusername') }}"
              required
              autofocus
              placeholder="{{ __('messages.enter_username') }}"
            />
          </x-ui.form-group>

          <x-ui.form-group label="{{ __('messages.password') }}" for="loginpassword" error="{{ $errors->first('loginpassword') }}">
            <x-ui.input
              type="password"
              name="loginpassword"
              id="loginpassword"
              required
              placeholder="{{ __('messages.enter_password') }}"
            />
          </x-ui.form-group>

          <div>
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full min-h-[44px]">
              {{ __('messages.sign_in') }}
            </x-ui.button>
          </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-700">
          <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('messages.no_account') }} <a href="{{ route('register') }}" class="text-primary-600 dark:text-primary-400 font-medium hover:underline">{{ __('messages.sign_up') }}</a></p>
        </div>
      </x-ui.card>
    </div>
  </div>
</div>
@endsection