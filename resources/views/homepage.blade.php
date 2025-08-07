@extends('components.layout')

@section('content')
<main class="container mx-auto px-6 py-12">
  <!-- Hero Section -->
  <section class="text-center max-w-3xl mx-auto mb-16">
    <h1 class="text-display font-extrabold mb-4 text-neutral-gray">Simplify Your Gift-Giving</h1>
    <p class="text-body text-neutral-gray mb-8">
      Organize Secret Santa exchanges, create shareable wishlists, and make every occasion special.
    </p>
    <x-ui.button variant="primary" size="lg" href="/register">
      Get Started for Free
    </x-ui.button>
  </section>

  <!-- How It Works Section -->
  <section class="max-w-5xl mx-auto mb-16">
    <h2 class="text-headline font-bold text-neutral-gray mb-8 text-center">How It Works</h2>
    <div class="flex flex-col md:flex-row justify-center gap-8">
      <x-ui.card class="flex-1 text-center">
        <div class="text-5xl mb-4">🎁</div>
        <h3 class="text-xl font-semibold mb-2">Create Wishlists</h3>
        <p class="text-neutral-gray">Add gifts from any store.</p>
      </x-ui.card>
      <x-ui.card class="flex-1 text-center">
        <div class="text-5xl mb-4">🧑‍🤝‍🧑</div>
        <h3 class="text-xl font-semibold mb-2">Run Secret Santa</h3>
        <p class="text-neutral-gray">Invite people, draw names, notify everyone.</p>
      </x-ui.card>
      <x-ui.card class="flex-1 text-center">
        <div class="text-5xl mb-4">🎉</div>
        <h3 class="text-xl font-semibold mb-2">Stress-Free Fun</h3>
        <p class="text-neutral-gray">No more guessing or duplicate gifts.</p>
      </x-ui.card>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="text-center max-w-3xl mx-auto mb-16">
    <h2 class="text-headline font-semibold mb-4">Ready to make gifting easy?</h2>
    <x-ui.button variant="primary" size="lg" href="/register">
      Create an Account
    </x-ui.button>
  </section>
</main>

<footer class="bg-neutral-light py-6 mt-auto">
  <div class="container mx-auto px-6 text-center text-neutral-gray text-sm space-x-4">
    <a href="/about" class="hover:underline">About</a>
    <a href="/contact" class="hover:underline">Contact</a>
    <a href="/privacy" class="hover:underline">Privacy Policy</a>
    <a href="/terms" class="hover:underline">Terms of Service</a>
  </div>
  <div class="container mx-auto px-6 text-center text-neutral-gray text-xs mt-2">
    © 2025 GiftHub. All rights reserved.
  </div>
</footer>
@endsection