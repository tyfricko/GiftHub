@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-neutral-gray">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-neutral-gray">
                Or
                <a href="/login" class="font-medium text-primary hover:text-primary-dark">
                    sign in to your existing account
                </a>
            </p>
        </div>
        
        <x-ui.card class="mt-8">
            <form action="/register" method="POST" class="space-y-6">
                @csrf
                
                <x-ui.form-group label="Username" id="username" error="{{ $errors->first('username') }}">
                    <x-ui.input 
                        type="text" 
                        name="username" 
                        id="username" 
                        value="{{ old('username') }}"
                        required 
                        autofocus
                        placeholder="Choose a username (3-20 characters)"
                        minlength="3"
                        maxlength="20"
                    />
                </x-ui.form-group>

                <x-ui.form-group label="Email Address" id="email" error="{{ $errors->first('email') }}">
                    <x-ui.input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        placeholder="Enter your email address"
                    />
                </x-ui.form-group>

                <x-ui.form-group label="Password" id="password" error="{{ $errors->first('password') }}">
                    <x-ui.input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        placeholder="Enter your password (minimum 6 characters)"
                        minlength="6"
                    />
                </x-ui.form-group>

                <x-ui.form-group label="Confirm Password" id="password_confirmation" error="{{ $errors->first('password_confirmation') }}">
                    <x-ui.input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required
                        placeholder="Confirm your password"
                        minlength="6"
                    />
                </x-ui.form-group>

                <div>
                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                        Create Account
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <div class="text-center">
            <p class="text-sm text-neutral-gray">
                Already have an account?
                <a href="/login" class="font-medium text-primary hover:text-primary-dark">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection