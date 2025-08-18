@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50">
  <div class="max-w-md w-full">
    <div class="mx-auto w-full">
      <x-ui.card class="p-8 rounded-lg">
        <h2 class="text-center text-2xl lg:text-3xl font-extrabold text-neutral-900 mb-6">Sign in to your account</h2>

        <form action="/login" method="POST" class="space-y-6">
          @csrf

          <x-ui.form-group label="Username" for="loginusername" error="{{ $errors->first('loginusername') }}">
            <x-ui.input
              type="text"
              name="loginusername"
              id="loginusername"
              value="{{ old('loginusername') }}"
              required
              autofocus
              placeholder="Enter your username"
            />
          </x-ui.form-group>

          <x-ui.form-group label="Password" for="loginpassword" error="{{ $errors->first('loginpassword') }}">
            <x-ui.input
              type="password"
              name="loginpassword"
              id="loginpassword"
              required
              placeholder="Enter your password"
            />
          </x-ui.form-group>

          <div>
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
              Sign In
            </x-ui.button>
          </div>
        </form>

        <div class="text-center mt-4">
          <p class="text-sm text-neutral-600">Don't have an account? <a href="/register" class="text-primary-600 font-medium">Sign up here</a></p>
        </div>
      </x-ui.card>
    </div>
  </div>
</div>
@endsection