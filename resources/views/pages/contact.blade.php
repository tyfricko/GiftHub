@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-display font-bold text-neutral-900 mb-4">Contact Us</h1>
            <p class="text-body-large text-neutral-600 max-w-2xl mx-auto">
                Have a question or need help? We're here to assist you with anything related to GiftHub.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div>
                <h2 class="text-headline font-semibold text-neutral-900 mb-6">Get in Touch</h2>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-title font-semibold text-neutral-900 mb-1">Email Support</h3>
                            <p class="text-body text-neutral-600 mb-2">Send us an email and we'll get back to you within 24 hours.</p>
                            <a href="mailto:support@gifthub.test" class="text-primary-600 hover:text-primary-700 font-medium">
                                support@gifthub.test
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-accent-100 text-accent-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-title font-semibold text-neutral-900 mb-1">FAQ</h3>
                            <p class="text-body text-neutral-600 mb-2">Check our frequently asked questions for quick answers.</p>
                            <a href="{{ route('pages.how-it-works') }}" class="text-accent-600 hover:text-accent-700 font-medium">
                                View FAQ →
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-neutral-100 text-neutral-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-title font-semibold text-neutral-900 mb-1">Response Time</h3>
                            <p class="text-body text-neutral-600">
                                We typically respond to all inquiries within 24 hours during business days.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="mt-8">
                    <h3 class="text-title font-semibold text-neutral-900 mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://twitter.com" class="w-10 h-10 bg-neutral-100 text-neutral-600 rounded-lg flex items-center justify-center hover:bg-primary-100 hover:text-primary-600 transition-colors duration-200" aria-label="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 4.01c-.77.34-1.6.57-2.46.67a4.3 4.3 0 001.88-2.37 8.59 8.59 0 01-2.72 1.04 4.28 4.28 0 00-7.3 3.9A12.13 12.13 0 013 3.8a4.28 4.28 0 001.33 5.71c-.66-.02-1.28-.2-1.82-.5v.05c0 2.06 1.46 3.78 3.4 4.17-.36.1-.74.14-1.13.14-.28 0-.55-.02-.82-.08a4.28 4.28 0 003.99 2.97A8.58 8.58 0 012 19.54 12.11 12.11 0 008.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.53A8.36 8.36 0 0022 4.01z"/>
                            </svg>
                        </a>
                        <!-- Add more social links as needed -->
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h2 class="text-headline font-semibold text-neutral-900 mb-6">Send us a Message</h2>

                <form class="space-y-6">
                    <div>
                        <label for="name" class="block text-body-sm font-medium text-neutral-700 mb-2">Name</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" placeholder="Your full name">
                    </div>

                    <div>
                        <label for="email" class="block text-body-sm font-medium text-neutral-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" placeholder="your.email@example.com">
                    </div>

                    <div>
                        <label for="subject" class="block text-body-sm font-medium text-neutral-700 mb-2">Subject</label>
                        <select id="subject" name="subject" class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                            <option value="">Select a topic</option>
                            <option value="general">General Inquiry</option>
                            <option value="technical">Technical Support</option>
                            <option value="account">Account Issues</option>
                            <option value="feature">Feature Request</option>
                            <option value="bug">Bug Report</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-body-sm font-medium text-neutral-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="6" class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" placeholder="Tell us how we can help you..."></textarea>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200">
                        Send Message
                    </button>
                </form>

                <p class="text-body-sm text-neutral-500 mt-4">
                    We respect your privacy and will never share your information. See our <a href="{{ route('pages.privacy') }}" class="text-primary-600 hover:text-primary-700">Privacy Policy</a> for more details.
                </p>
            </div>
        </div>

        <!-- Additional Help Section -->
        <div class="bg-neutral-50 p-8 rounded-lg mt-16 text-center">
            <h3 class="text-headline font-semibold text-neutral-900 mb-4">Need Immediate Help?</h3>
            <p class="text-body text-neutral-600 mb-6">
                Check out our comprehensive guides and tutorials to get the most out of GiftHub.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('pages.how-it-works') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200">
                    How It Works
                </a>
                <a href="{{ route('pages.about') }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-medium rounded-lg border border-primary-600 hover:bg-primary-50 transition-colors duration-200">
                    About GiftHub
                </a>
            </div>
        </div>
    </div>
</div>
@endsection