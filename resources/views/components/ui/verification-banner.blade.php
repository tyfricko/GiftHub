@props([])

@if(auth()->check() && !auth()->user()->hasVerifiedEmail())
<div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl shadow-sm p-4 md:p-6 mb-6">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-start space-x-3">
        <div class="text-yellow-600 text-2xl mt-1">⚠️</div>
        <div>
          <h3 class="text-lg font-semibold text-yellow-800">Prosimo, potrdite svoj e-poštni naslov</h3>
          <p class="text-yellow-700 mt-1">Za poln dostop do funkcij (ustvarjanje seznamov želja in dogodkov) potrdite vaš e-poštni naslov. Če niste prejeli e-pošte, jo lahko ponovno pošljete.</p>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-2 shrink-0">
        <a href="{{ route('verification.notice') }}" class="inline-block">
          <x-ui.button variant="primary" size="sm">Pojdi na potrditev</x-ui.button>
        </a>

        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <x-ui.button type="submit" variant="secondary" size="sm">Ponovno pošlji</x-ui.button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif