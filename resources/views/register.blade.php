@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50">
  <div class="max-w-md w-full">
    <div class="mx-auto w-full">
      <x-ui.card class="p-8 rounded-lg">
        <h2 class="text-center text-2xl lg:text-3xl font-extrabold text-neutral-900 mb-6">Create an Account</h2>

        <form action="/register" method="POST" class="space-y-6">
          @csrf

          <x-ui.form-group label="Name" for="name" error="{{ $errors->first('name') }}">
            <x-ui.input
              type="text"
              name="name"
              id="name"
              value="{{ old('name') }}"
              required
              placeholder="Your full name"
            />
          </x-ui.form-group>

          <x-ui.form-group label="Email" for="email" error="{{ $errors->first('email') }}">
            <x-ui.input
              type="email"
              name="email"
              id="email"
              value="{{ old('email') }}"
              required
              placeholder="you@example.com"
            />
          </x-ui.form-group>

          <x-ui.form-group label="Password" for="password" error="{{ $errors->first('password') }}">
            <x-ui.input
              type="password"
              name="password"
              id="password"
              required
              placeholder="Enter password"
            />
          </x-ui.form-group>

          <x-ui.form-group label="Confirm Password" for="password_confirmation" error="{{ $errors->first('password_confirmation') }}">
            <x-ui.input
              type="password"
              name="password_confirmation"
              id="password_confirmation"
              required
              placeholder="Confirm password"
            />
          </x-ui.form-group>

          <div>
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
              Register
            </x-ui.button>
          </div>
        </form>

        <div class="text-center mt-4">
          <p class="text-sm text-neutral-600">Already have an account? <a href="/login" class="text-primary-600 font-medium">Sign In</a></p>
        </div>
      </x-ui.card>
    </div>
  </div>
</div>
@endsection