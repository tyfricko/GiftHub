@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-display font-bold text-neutral-900 mb-4">About GiftHub</h1>
            <p class="text-body-large text-neutral-600 max-w-2xl mx-auto">
                Simplify your gift-giving with organized Secret Santa exchanges, shareable wishlists, and stress-free celebrations.
            </p>
        </div>

        <div class="prose prose-lg mx-auto text-neutral-700">
            <h2 class="text-headline font-semibold text-neutral-900 mb-6">Our Mission</h2>
            <p class="text-body mb-6">
                At GiftHub, we believe that the best gifts come from the heart. Our platform is designed to take the stress out of gift-giving by providing tools that help you understand what your loved ones truly want, while making the process of organizing group gifts seamless and enjoyable.
            </p>

            <h2 class="text-headline font-semibold text-neutral-900 mb-6">What We Offer</h2>
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Personal Wishlists</h3>
                    <p class="text-body-sm text-neutral-600">
                        Create detailed wishlists with links, prices, and descriptions. Share them with friends and family for perfect gift suggestions.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Gift Exchanges</h3>
                    <p class="text-body-sm text-neutral-600">
                        Organize Secret Santa events with ease. Our platform handles the random assignments and keeps everything organized.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Smart Features</h3>
                    <p class="text-body-sm text-neutral-600">
                        Automatic metadata scraping, URL shortening, and intelligent wishlist management make the experience smooth.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Community Focus</h3>
                    <p class="text-body-sm text-neutral-600">
                        Built for teams, families, and friend groups who want to strengthen their relationships through thoughtful giving.
                    </p>
                </div>
            </div>

            <h2 class="text-headline font-semibold text-neutral-900 mb-6">Our Story</h2>
            <p class="text-body mb-6">
                GiftHub was born from the frustration of countless awkward gift exchanges and missed opportunities to show appreciation. We saw how technology could transform this fundamental human experience, making it more meaningful and less stressful.
            </p>
            <p class="text-body mb-6">
                Today, thousands of users trust GiftHub to help them create memorable gift-giving experiences. Whether it's a family holiday tradition or a team appreciation event, we're here to make every occasion special.
            </p>

            <div class="bg-primary-50 p-8 rounded-lg text-center mt-12">
                <h3 class="text-headline font-semibold text-neutral-900 mb-4">Ready to Get Started?</h3>
                <p class="text-body text-neutral-600 mb-6">
                    Join the GiftHub community and discover a better way to give and receive gifts.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200">
                    Create Your Account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection