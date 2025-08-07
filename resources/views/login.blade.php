@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-neutral-gray">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-neutral-gray">
                Or
                <a href="/register" class="font-medium text-primary hover:text-primary-dark">
                    create a new account
                </a>
            </p>
        </div>
        
        <x-ui.card class="mt-8">
            <form action="/login" method="POST" class="space-y-6">
                @csrf
                
                <x-ui.form-group label="Username" id="loginusername" error="{{ $errors->first('loginusername') }}">
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

                <x-ui.form-group label="Password" id="loginpassword" error="{{ $errors->first('loginpassword') }}">
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
        </x-ui.card>

        <div class="text-center">
            <p class="text-sm text-neutral-gray">
                Don't have an account?
                <a href="/register" class="font-medium text-primary hover:text-primary-dark">
                    Sign up here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection