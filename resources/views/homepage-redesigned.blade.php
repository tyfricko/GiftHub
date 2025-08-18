@extends('components.layout')

@section('content')
<!-- Hero -->
<x-ui.hero 
    title="Simplify Your Gift-Giving"
    subtitle="Organize Secret Santa exchanges, create shareable wishlists, and make every occasion special. All in one place."
    ctaText="Get Started for Free"
    ctaHref="/register"
    backgroundClass="bg-primary-600"
/>

<!-- How It Works -->
<section class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-headline font-bold text-neutral-900 mb-4">How It Works</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
      <div class="bg-white rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-medium p-8 text-center group">
        <div class="mb-6">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 group-hover:bg-primary-100 transition-colors duration-medium">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-title font-semibold text-neutral-900 mb-3">Create Wishlists</h3>
        <p class="text-body text-neutral-600 leading-relaxed">Easily add gifts you want from any store. Share your list with family and friends.</p>
      </div>

      <div class="bg-white rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-medium p-8 text-center group">
        <div class="mb-6">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 group-hover:bg-primary-100 transition-colors duration-medium">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-title font-semibold text-neutral-900 mb-3">Run Secret Santa</h3>
        <p class="text-body text-neutral-600 leading-relaxed">Invite people to a gift exchange. We'll draw names and notify everyone secretly.</p>
      </div>

      <div class="bg-white rounded-lg shadow-level-2 hover:shadow-level-3 transition-shadow duration-medium p-8 text-center group">
        <div class="mb-6">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 group-hover:bg-primary-100 transition-colors duration-medium">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <h3 class="text-title font-semibold text-neutral-900 mb-3">Stress-Free Fun</h3>
        <p class="text-body text-neutral-600 leading-relaxed">No more guessing games or duplicate gifts. Just joyful giving and receiving!</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-20 bg-neutral-50">
  <div class="container mx-auto px-6 text-center">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-headline font-semibold text-neutral-900 mb-4">Ready to make gifting easy?</h2>
      <p class="text-body-lg text-neutral-600 mb-8">Join thousands of happy users and create your first event today.</p>
      <x-ui.button variant="primary" size="lg" as="a" href="/register">Create an Account</x-ui.button>
    </div>
  </div>
</section>

@endsection