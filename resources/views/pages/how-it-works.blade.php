@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-display font-bold text-neutral-900 mb-4">How GiftHub Works</h1>
            <p class="text-body-large text-neutral-600 max-w-2xl mx-auto">
                Get started in minutes with our simple, step-by-step guide to creating wishlists and organizing gift exchanges.
            </p>
        </div>

        <div class="space-y-12">
            <!-- Step 1 -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-shrink-0 w-16 h-16 bg-primary-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                    1
                </div>
                <div class="flex-grow">
                    <h2 class="text-headline font-semibold text-neutral-900 mb-3">Create Your Account</h2>
                    <p class="text-body text-neutral-600 mb-4">
                        Sign up for a free GiftHub account. It's quick and easy - just provide your email and create a password.
                    </p>
                    <div class="bg-neutral-50 p-4 rounded-lg">
                        <p class="text-body-sm text-neutral-500">
                            <strong>Pro tip:</strong> Use an email address that your friends and family can easily recognize.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-shrink-0 w-16 h-16 bg-primary-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                    2
                </div>
                <div class="flex-grow">
                    <h2 class="text-headline font-semibold text-neutral-900 mb-3">Build Your Wishlist</h2>
                    <p class="text-body text-neutral-600 mb-4">
                        Add items to your wishlist by pasting product URLs. GiftHub automatically scrapes details like the item name, price, and image.
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-neutral-200">
                            <h4 class="font-medium text-neutral-900 mb-2">What you add:</h4>
                            <ul class="text-body-sm text-neutral-600 space-y-1">
                                <li>• Product URL</li>
                                <li>• Personal notes</li>
                                <li>• Priority level</li>
                            </ul>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-neutral-200">
                            <h4 class="font-medium text-neutral-900 mb-2">What we handle:</h4>
                            <ul class="text-body-sm text-neutral-600 space-y-1">
                                <li>• Item details</li>
                                <li>• Price tracking</li>
                                <li>• Image optimization</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-shrink-0 w-16 h-16 bg-primary-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                    3
                </div>
                <div class="flex-grow">
                    <h2 class="text-headline font-semibold text-neutral-900 mb-3">Share Your Profile</h2>
                    <p class="text-body text-neutral-600 mb-4">
                        Share your profile link with friends and family. They can view your wishlist and see what you'd love to receive.
                    </p>
                    <div class="bg-primary-50 p-4 rounded-lg">
                        <p class="text-body-sm text-primary-700">
                            Your profile URL will look like: <code class="bg-white px-2 py-1 rounded">gifthub.test/profile/yourusername</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-shrink-0 w-16 h-16 bg-primary-600 text-white rounded-full flex items-center justify-center text-xl font-bold">
                    4
                </div>
                <div class="flex-grow">
                    <h2 class="text-headline font-semibold text-neutral-900 mb-3">Organize Gift Exchanges</h2>
                    <p class="text-body text-neutral-600 mb-4">
                        Create Secret Santa events, invite participants, and let GiftHub handle the random assignments automatically.
                    </p>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-accent-100 text-accent-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-medium text-neutral-900 mb-1">Invite Friends</h4>
                            <p class="text-body-sm text-neutral-600">Send invitations via email</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-accent-100 text-accent-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h4 class="font-medium text-neutral-900 mb-1">Random Assignment</h4>
                            <p class="text-body-sm text-neutral-600">Fair and anonymous</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-accent-100 text-accent-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <h4 class="font-medium text-neutral-900 mb-1">Track Progress</h4>
                            <p class="text-body-sm text-neutral-600">Monitor event status</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-neutral-50 p-8 rounded-lg text-center mt-16">
            <h3 class="text-headline font-semibold text-neutral-900 mb-4">Questions?</h3>
            <p class="text-body text-neutral-600 mb-6">
                Check out our FAQ or contact us if you need help getting started.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('pages.contact') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200">
                    Contact Support
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-medium rounded-lg border border-primary-600 hover:bg-primary-50 transition-colors duration-200">
                    Start Building Your Wishlist
                </a>
            </div>
        </div>
    </div>
</div>
@endsection