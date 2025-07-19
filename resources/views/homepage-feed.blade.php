<x-layout>

  <div class="container py-md-5 container--narrow">
    <div class="text-center">
      <h2>Pozdravljeni  <strong>{{auth()->user()->username}}</strong>, vaš seznam želja je prazen.</h2>
      <p class="lead text-muted">Če želite lahko svojo prvo željo dodate <a href="/add-wish"><strong>TUKAJ</strong></a> </p>
    </div>
  </div>
  
</x-layout>
