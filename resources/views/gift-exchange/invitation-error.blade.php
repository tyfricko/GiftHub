<x-layout>
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-6">
        <i class="fa fa-exclamation-triangle text-red-600 text-xl"></i>
      </div>
      
      <h1 class="text-3xl font-bold text-gray-900 mb-4">Invitation Not Available</h1>
      
      <div class="text-lg text-gray-600 mb-8 space-y-3">
        <p>This invitation is not intended for your account, or it may have already been used.</p>
        <p>If you believe you should have access to this invitation, please check:</p>
        <ul class="text-left max-w-md mx-auto space-y-2 mt-4">
          <li class="flex items-start">
            <i class="fa fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>You're logged in with the correct email address</span>
          </li>
          <li class="flex items-start">
            <i class="fa fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>The invitation link is complete and hasn't been modified</span>
          </li>
          <li class="flex items-start">
            <i class="fa fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>The invitation hasn't already been accepted or declined</span>
          </li>
        </ul>
      </div>

      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        @auth
          <x-ui.button as="a" href="{{ route('profile.events') }}">
            <i class="fa fa-user mr-2"></i> Go to My Profile
          </x-ui.button>
          <x-ui.button as="a" href="{{ route('gift-exchange.dashboard') }}" variant="secondary">
            <i class="fa fa-gift mr-2"></i> View My Events
          </x-ui.button>
        @else
          <x-ui.button as="a" href="{{ route('login') }}">
            <i class="fa fa-sign-in-alt mr-2"></i> Login
          </x-ui.button>
          <x-ui.button as="a" href="{{ route('register') }}" variant="secondary">
            <i class="fa fa-user-plus mr-2"></i> Create Account
          </x-ui.button>
        @endauth
      </div>

      <div class="mt-8 text-sm text-gray-500">
        <p>Need help? Contact the person who sent you this invitation.</p>
      </div>
    </div>
  </div>
</x-layout>