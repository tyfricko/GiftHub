@extends('components.layout', ['hideVerificationBanner' => true])

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <div class="text-center py-8">
                <div class="text-4xl mb-4">⚠️</div>
                <h2 class="text-xl font-semibold text-yellow-800 mb-2">Prosimo, potrdite svoj e-poštni naslov</h2>
                <p class="text-gray-600">
                    Za poln dostop do funkcij (ustvarjanje seznamov želja in dogodkov) potrdite vaš e-poštni naslov. Če niste prejeli e-pošte, jo lahko ponovno pošljete.
                </p>
                
                <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('verification.notice') }}" class="inline-block">
                        <x-ui.button variant="primary">Pojdi na potrditev</x-ui.button>
                    </a>
                    
                    <form method="POST" action="{{ route('verification.send') }}" class="inline-block">
                        @csrf
                        <x-ui.button type="submit" variant="secondary">Ponovno pošlji</x-ui.button>
                    </form>
                </div>
            </div>
        </x-ui.card>
    </div>
</div>
@endsection