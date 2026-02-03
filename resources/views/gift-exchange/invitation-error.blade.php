<x-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="text-center max-w-2xl mx-auto">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-6">
                <i class="fa fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-neutral-100 mb-4">
                Invitation Not Available
            </h1>
            
            <div class="text-lg text-neutral-600 dark:text-neutral-400 mb-8 space-y-4">
                <x-ui.alert type="warning">
                    This invitation is not intended for your account, or it may have already been used.
                </x-ui.alert>
                
                <p class="text-neutral-500 dark:text-neutral-400">If you believe you should have access to this invitation, please check:</p>
                
                <ul class="text-left max-w-md mx-auto space-y-3 mt-4">
                    <li class="flex items-start">
                        <i class="fa fa-check-circle text-primary-600 dark:text-primary-400 mt-1 mr-3"></i>
                        <span class="text-neutral-700 dark:text-neutral-300">You're logged in with the correct email address</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa fa-check-circle text-primary-600 dark:text-primary-400 mt-1 mr-3"></i>
                        <span class="text-neutral-700 dark:text-neutral-300">The invitation link is complete and hasn't been modified</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa fa-check-circle text-primary-600 dark:text-primary-400 mt-1 mr-3"></i>
                        <span class="text-neutral-700 dark:text-neutral-300">The invitation hasn't already been accepted or declined</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                @auth
                    <x-ui.button as="a" href="{{ route('profile.events') }}" min-h-[48px]">
                        <i class="fa fa-user mr-2"></i> Go to My Profile
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('profile.events') }}" variant="secondary" min-h-[48px]">
                        <i class="fa fa-gift mr-2"></i> View My Events
                    </x-ui.button>
                @else
                    <x-ui.button as="a" href="{{ route('login') }}" min-h-[48px]">
                        <i class="fa fa-sign-in-alt mr-2"></i> Login
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" min-h-[48px]">
                        <i class="fa fa-user-plus mr-2"></i> Create Account
                    </x-ui.button>
                @endauth
            </div>

            <div class="mt-8 text-sm text-neutral-500 dark:text-neutral-400">
                <p>Need help? Contact the person who sent you this invitation.</p>
            </div>
        </div>
    </div>
</x-layout>
