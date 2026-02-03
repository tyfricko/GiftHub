@extends('components.layout')

@section('content')
<div class="h-screen flex items-center justify-center bg-neutral-50 dark:bg-neutral-900 px-4 sm:px-6 lg:px-8 py-12">
  <div class="w-full max-w-md">
    <div class="mx-auto w-full text-center">
      <x-ui.card class="p-6 sm:p-8 rounded-lg bg-white dark:bg-neutral-800">
        <div class="text-center mb-6">
          <div class="mx-auto w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mb-4">
            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
          <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-neutral-900 dark:text-white mb-2">{{ __('messages.verify_email_title', 'Potrdite svoj e-poštni naslov') }}</h2>
          <p class="text-base sm:text-body text-neutral-600 dark:text-neutral-400">{{ __('messages.verify_email_desc', 'Poslali smo vam povezavo za potrditev na vaš e-poštni naslov.') }}</p>
        </div>

        @if (session('message'))
          <x-ui.alert type="success" class="mb-6">
            {{ session('message') }}
          </x-ui.alert>
        @endif

        @if (session('warning'))
          <x-ui.alert type="warning" class="mb-6">
            {{ session('warning') }}
          </x-ui.alert>
        @endif

        <div class="space-y-4">
          <p class="text-sm text-neutral-600 dark:text-neutral-400 text-center">
            {{ __('messages.email_not_received', "Niste prejeli e-pošte? Kliknite spodnji gumb za ponovno pošiljanje.") }}
          </p>

          <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full min-h-[44px]">
              {{ __('messages.resend_verification', 'Ponovno pošlji povezavo za potrditev') }}
            </x-ui.button>
          </form>

          <div class="text-center pt-4 border-t border-neutral-200 dark:border-neutral-700">
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-200 underline min-h-[44px] py-2 px-4">
                {{ __('messages.logout', 'Odjavi se') }}
              </button>
            </form>
          </div>
        </div>
      </x-ui.card>
    </div>
  </div>
</div>
@endsection