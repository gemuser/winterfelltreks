@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-background-alt">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center" data-gsap="fade-up">
            <a href="{{ url('/') }}" class="inline-block mb-6 group">
                <img src="/images/winterfell-nobg.png" alt="Winterfell Treks & Expedition" class="h-16 w-auto mx-auto object-contain transition-transform group-hover:scale-105">
            </a>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary tracking-tight">
                Reset Password
            </h2>
            <p class="mt-2 text-sm text-text-secondary">
                Enter your registered email address and we'll transmit a secure reset link.
            </p>
        </div>
        
        @if (session('status'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded">
            {{ session('status') }}
        </div>
        @endif

        <form class="space-y-6" method="POST" action="{{ route('password.email') }}" data-gsap="fade-up" data-gsap-delay="0.1">
            @csrf
            
            <div class="bg-white border border-gray-200 p-8 shadow-sm space-y-5">
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase text-text-muted mb-2 tracking-wider">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="form-input w-full" 
                           placeholder="admin@winterfelltreks.com" 
                           value="{{ old('email') }}">
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full py-3.5 text-base font-bold shadow-md">
                        Transmit Password Reset Link
                    </button>
                </div>
            </div>
        </form>

        <div class="text-center text-sm" data-gsap="fade-up" data-gsap-delay="0.2">
            <a href="{{ route('login') }}" class="font-semibold text-accent-primary hover:underline">
                &larr; Return to Sign In
            </a>
        </div>
    </div>
</div>
@endsection
