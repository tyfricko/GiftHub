<x-layout>

    <div class="container py-md-5">
      <div class="row align-items-center">
        <div class="col-lg-7 py-3 py-md-5">
          <h1 class="display-3">GiftHub: Darila, ki Povezujejo</h1>
          <p class="lead text-muted">Odkrijte veselje ob darovanju z GiftHub platformo, oblikovano za nemoteno in prijetno izmenjavo daril v poslovnem okolju. Okrepite vezi in vzbudite duh ekipe s premišljenimi presenečenji.</p>
        </div>
        <div class="col-lg-5 pl-lg-5 pb-3 py-lg-5">
          <form action="/register" method="POST" id="registration-form">
            @csrf
            <!--
            <div class="form-group">
              <label for="fullname-register" class="text-muted mb-1"><small>Ime</small></label>
              <input value="{{old('fullname')}}" name="fullname" id="fullname-register" class="form-control" type="text" placeholder="Vpišite vaše ime" autocomplete="off" />
            </div>

            <div class="form-group">
              <label for="surname-register" class="text-muted mb-1"><small>Priimek</small></label>
              <input value="{{old('surname')}}" name="surname" id="surname-register" class="form-control" type="text" placeholder="Vpišite vaš priimek" autocomplete="off" />
            </div>

            <div class="form-group">
              <label for="address-register" class="text-muted mb-1"><small>Naslov</small></label>
              <input value="{{old('address')}}" name="address" id="address-register" class="form-control" type="text" placeholder="Moj naslov 22, 1000 Ljubljana " autocomplete="off" />
            </div>
          -->

            <div class="form-group">
              <label for="username-register" class="text-muted mb-1"><small>Uporabniško ime</small></label>
              <input value="{{old('username')}}" name="username" id="username-register" class="form-control" type="text" placeholder="Izberite uporabniško ime" autocomplete="off" />
              @error('username')
                  <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="email-register" class="text-muted mb-1"><small>Email</small></label>
              <input value="{{old('email')}}" name="email" id="email-register" class="form-control" type="text" placeholder="mojemail@gmail.com" autocomplete="off" />
              @error('email')
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="password-register" class="text-muted mb-1"><small>Geslo</small></label>
              <input name="password" id="password-register" class="form-control" type="password" placeholder="Ustvarite geslo" />
              @error('password')
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="password-register-confirm" class="text-muted mb-1"><small>Ponovite geslo</small></label>
              <input name="password_confirmation" id="password-register-confirm" class="form-control" type="password" placeholder="Potrdite geslo" />
              @error('password_confirmation')
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <button type="submit" class="py-3 mt-4 btn btn-lg btn-success btn-block">Prijavite se in širite veselje</button>
          </form>
        </div>
      </div>
    </div>

  </x-layout>