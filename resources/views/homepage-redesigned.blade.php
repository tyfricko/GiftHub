@extends('components.layout')

@section('content')
<!-- Hero -->
<x-ui.hero
    title="{{ __('messages.hero_title') }}"
    subtitle="{{ __('messages.hero_subtitle') }}"
    ctaText="{{ __('messages.hero_cta') }}"
    ctaHref="/register"
    backgroundClass="bg-primary-600"
/>

<!-- How It Works -->
<section class="py-12 sm:py-16 lg:py-20 bg-white dark:bg-neutral-900">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8 sm:mb-12 lg:mb-16">
      <h2 class="text-xl sm:text-2xl lg:text-headline font-bold text-neutral-900 dark:text-white mb-4">{{ __('messages.how_it_works') }}</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-6xl mx-auto">
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-300 p-6 sm:p-8 text-center group">
        <div class="mb-4 sm:mb-6">
          <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/50 transition-colors duration-300">
            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-lg sm:text-title font-semibold text-neutral-900 dark:text-white mb-2 sm:mb-3">{{ __('messages.feature_create_wishlists') }}</h3>
        <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ __('messages.feature_create_desc') }}</p>
      </div>

      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-300 p-6 sm:p-8 text-center group">
        <div class="mb-4 sm:mb-6">
          <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/50 transition-colors duration-300">
            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-lg sm:text-title font-semibold text-neutral-900 dark:text-white mb-2 sm:mb-3">{{ __('messages.feature_secret_santa') }}</h3>
        <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ __('messages.feature_secret_santa_desc') }}</p>
      </div>

      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-300 p-6 sm:p-8 text-center group">
        <div class="mb-4 sm:mb-6">
          <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/50 transition-colors duration-300">
            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-lg sm:text-title font-semibold text-neutral-900 dark:text-white mb-2 sm:mb-3">{{ __('messages.feature_stress_free') }}</h3>
        <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ __('messages.feature_stress_free_desc') }}</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-12 sm:py-16 lg:py-20 bg-neutral-50 dark:bg-neutral-800">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-xl sm:text-2xl lg:text-headline font-semibold text-neutral-900 dark:text-white mb-4">{{ __('messages.cta_title') }}</h2>
      <p class="text-base sm:text-body-lg text-neutral-600 dark:text-neutral-400 mb-6 sm:mb-8">{{ __('messages.cta_subtitle') }}</p>
      <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
        <x-ui.button variant="primary" size="lg" as="a" href="/register" class="w-full sm:w-auto min-h-[44px]">{{ __('messages.cta_button') }}</x-ui.button>
        <x-ui.button variant="secondary" size="lg" as="a" href="/login" class="w-full sm:w-auto min-h-[44px]">{{ __('messages.cta_login') }}</x-ui.button>
      </div>
    </div>
  </div>
</section>

@endsection