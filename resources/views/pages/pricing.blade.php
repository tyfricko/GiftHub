@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-display font-bold text-neutral-900 mb-4">Simple, Transparent Pricing</h1>
            <p class="text-body-large text-neutral-600 max-w-2xl mx-auto">
                GiftHub is completely free to use. Create unlimited wishlists, organize gift exchanges, and enjoy all features without any hidden costs.
            </p>
        </div>

        <!-- Pricing Card -->
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl shadow-lg border border-neutral-200 overflow-hidden">
                <div class="bg-primary-600 px-6 py-8 text-center text-white">
                    <h2 class="text-headline font-bold mb-2">GiftHub Free</h2>
                    <div class="text-4xl font-bold mb-1">$0</div>
                    <div class="text-primary-100">Forever Free</div>
                </div>

                <div class="px-6 py-8">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">Unlimited wishlists</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">Unlimited gift exchanges</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">URL metadata scraping</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">Email invitations</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">Profile sharing</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">Mobile responsive</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-body text-neutral-700">Community support</span>
                        </li>
                    </ul>

                    <div class="mt-8">
                        <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200">
                            Get Started Free
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20">
            <h2 class="text-headline font-semibold text-neutral-900 text-center mb-12">Frequently Asked Questions</h2>

            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Is GiftHub really free?</h3>
                    <p class="text-body text-neutral-600">
                        Yes! GiftHub is completely free to use. We believe in making gift-giving accessible to everyone without any barriers or hidden costs.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Are there any limitations?</h3>
                    <p class="text-body text-neutral-600">
                        There are no artificial limitations on the number of wishlists, items, or gift exchanges you can create. However, we do ask that you use the platform responsibly and respect our community guidelines.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">How do you make money?</h3>
                    <p class="text-body text-neutral-600">
                        GiftHub is currently supported by our development team. In the future, we may explore optional premium features or partnerships, but the core functionality will always remain free.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg border border-neutral-200">
                    <h3 class="text-title font-semibold text-neutral-900 mb-3">Is my data safe?</h3>
                    <p class="text-body text-neutral-600">
                        Absolutely. We take privacy seriously and never sell your data. Your wishlists and personal information are kept secure and private. Check out our <a href="{{ route('pages.privacy') }}" class="text-primary-600 hover:text-primary-700">Privacy Policy</a> for more details.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-primary-600 text-white rounded-xl p-8 text-center mt-16">
            <h3 class="text-headline font-bold mb-4">Ready to Start Gift-Giving?</h3>
            <p class="text-body-large mb-6 opacity-90">
                Join thousands of users who are already using GiftHub to make their gift exchanges more meaningful.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-lg hover:bg-neutral-50 transition-colors duration-200">
                Create Your Free Account
            </a>
        </div>
    </div>
</div>
@endsection