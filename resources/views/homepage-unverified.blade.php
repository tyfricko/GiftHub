@extends('components.layout', ['hideVerificationBanner' => true])

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="max-w-2xl mx-auto">
        <x-ui.card class="text-center py-8 sm:py-12">
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-semibold text-yellow-800 dark:text-yellow-300 mb-2">{{ __('messages.verify_email_title') }}</h2>
                <p class="text-base sm:text-body text-neutral-600 dark:text-neutral-400">
                    {{ __('messages.verify_email_desc') }}
                </p>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                <a href="{{ route('verification.notice') }}" class="inline-block w-full sm:w-auto">
                    <x-ui.button variant="primary" class="w-full sm:w-auto min-h-[44px]">{{ __('messages.go_to_verify') }}</x-ui.button>
                </a>

                <form method="POST" action="{{ route('verification.send') }}" class="inline-block w-full sm:w-auto">
                    @csrf
                    <x-ui.button type="submit" variant="secondary" class="w-full sm:w-auto min-h-[44px]">{{ __('messages.resend_email') }}</x-ui.button>
                </form>
            </div>

            <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                <p class="text-sm text-neutral-500 dark:text-neutral-500">
                    {{ __('messages.email_tip') }}
                </p>
            </div>
        </x-ui.card>
    </div>
</div>
@endsection