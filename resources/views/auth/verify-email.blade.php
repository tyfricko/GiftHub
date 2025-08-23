@extends('components.layout')

@section('content')
<div class="h-screen flex items-center justify-center bg-neutral-50">
  <div class="w-full max-w-md px-4 sm:px-6 lg:px-8">
    <div class="mx-auto w-full text-center">
      <x-ui.card class="p-8 rounded-lg">
        <div class="text-center mb-6">
          <div class="text-6xl mb-4">📧</div>
          <h2 class="text-2xl lg:text-3xl font-extrabold text-neutral-900 mb-2">Potrdite svoj e-poštni naslov</h2>
          <p class="text-neutral-600">Poslali smo vam povezavo za potrditev na vaš e-poštni naslov.</p>
        </div>

        @if (session('message'))
          <x-ui.alert type="success" class="mb-6">
            {{ session('message') }}
          </x-ui.alert>
        @endif

        @if (session('warning'))
          <x-ui.alert type="warning" class="mb-6">
            {{ session('warning') }}
          </x-ui.alert>
        @endif

        <div class="space-y-4">
          <p class="text-sm text-neutral-600 text-center">
            Niste prejeli e-pošte? Kliknite spodnji gumb za ponovno pošiljanje.
          </p>

          <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
              Ponovno pošlji povezavo za potrditev
            </x-ui.button>
          </form>

          <div class="text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="text-sm text-neutral-600 hover:text-neutral-800 underline">
                Odjavi se
              </button>
            </form>
          </div>
        </div>
      </x-ui.card>
    </div>
  </div>
</div>
@endsection