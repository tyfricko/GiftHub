<footer class="bg-neutral-50 border-t border-neutral-200 py-12 mt-auto" role="contentinfo">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="md:col-span-2">
                <div class="flex items-center space-x-2 text-primary-600 mb-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.95 9 11 5.16-1.05 9-5.45 9-11V7l-10-5z"/>
                        <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <span class="text-xl font-bold">{{ __('messages.gift_hub') }}</span>
                </div>
                <p class="text-neutral-600 text-body-sm max-w-md leading-relaxed">
                    {{ __('messages.footer_description') }}
                </p>
            </div>

            <div>
                <h3 class="text-title font-semibold text-neutral-900 mb-4">{{ __('messages.footer_quick_links') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('pages.about') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_about') }}</a></li>
                    <li><a href="{{ route('pages.how-it-works') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_how_it_works') }}</a></li>
                    <li><a href="{{ route('pages.pricing') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_pricing') }}</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_contact') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-title font-semibold text-neutral-900 mb-4">{{ __('messages.footer_legal') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('pages.privacy') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_privacy_policy') }}</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_terms_of_service') }}</a></li>
                    <li><a href="{{ route('pages.cookies') }}" class="text-neutral-600 hover:text-primary-600 text-body-sm transition-colors duration-fast">{{ __('messages.footer_cookie_policy') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-neutral-200">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-neutral-500 text-body-sm">
                    {{ __('messages.footer_copyright', ['year' => date('Y')]) }}
                </p>

                <div class="flex space-x-4">
                    <a href="https://twitter.com" class="text-neutral-500 hover:text-primary-600" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M22 4.01c-.77.34-1.6.57-2.46.67a4.3 4.3 0 001.88-2.37 8.59 8.59 0 01-2.72 1.04 4.28 4.28 0 00-7.3 3.9A12.13 12.13 0 013 3.8a4.28 4.28 0 001.33 5.71c-.66-.02-1.28-.2-1.82-.5v.05c0 2.06 1.46 3.78 3.4 4.17-.36.1-.74.14-1.13.14-.28 0-.55-.02-.82-.08a4.28 4.28 0 003.99 2.97A8.58 8.58 0 012 19.54 12.11 12.11 0 008.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.53A8.36 8.36 0 0022 4.01z"/>
                        </svg>
                    </a>
                    <!-- Social icons placeholder -->
                </div>
            </div>
        </div>
    </div>
</footer>