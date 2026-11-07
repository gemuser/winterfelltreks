@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto flex justify-center">
                <img src="/images/winterfell-nobg.png" alt="{{ \App\Models\SiteSetting::getValue('site_name') ?? 'Winterfell Treks & Expedition' }}" class="h-16 w-auto object-contain">
            </div>
            <h2 class="mt-6 text-center font-display text-3xl font-bold text-text-primary">
                Reset Password
            </h2>
            <p class="mt-2 text-center text-sm text-text-secondary">
                Enter your new password below.
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="rounded-lg shadow-sm space-y-6 p-6 bg-white border border-gray-200">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="form-input w-full" 
                           placeholder="Email address" 
                           value="{{ $request->input('email') }}">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="form-input w-full" 
                           placeholder="New Password">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="form-input w-full" 
                           placeholder="Confirm Password">
                    @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <button type="submit" class="btn-primary w-full py-3">
                        Reset Password
                    </button>
                </div>
            </div>
        </form>
        
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-accent-primary hover:underline">
                Back to login
            </a>
        </div>
    </div>
</div>
@endsection