@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-background-alt">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center" data-gsap="fade-up">
            <a href="{{ url('/') }}" class="inline-block mb-6 group">
                <img src="/images/winterfell-nobg.png" alt="Winterfell Treks & Expedition" class="h-16 w-auto mx-auto object-contain transition-transform group-hover:scale-105">
            </a>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary tracking-tight">
                Staff & Guide Portal
            </h2>
            <p class="mt-2 text-sm text-text-secondary">
                Sign in to manage itineraries, reservations, and dispatch logs.
            </p>
        </div>
        
        <form class="space-y-6" method="POST" action="{{ route('login') }}" data-gsap="fade-up" data-gsap-delay="0.1">
            @csrf

            @if(session('status'))
            <div class="p-3.5 bg-green-50 border border-green-200 text-green-800 text-xs sm:text-sm rounded-sm">
                {{ session('status') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-sm">
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
            @endif
            
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
                
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase text-text-muted mb-2 tracking-wider">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="form-input w-full" 
                           placeholder="••••••••">
                    @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="form-checkbox h-4 w-4 text-accent-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-xs sm:text-sm text-text-secondary">Remember me</label>
                    </div>
                    
                    <div class="text-xs sm:text-sm">
                        <a href="{{ route('password.request') }}" class="font-semibold text-accent-primary hover:underline">Forgot password?</a>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="btn-primary w-full py-3.5 text-base font-bold shadow-md">
                        Sign In to Dispatch
                    </button>
                </div>
            </div>
        </form>

        <div class="text-center text-xs text-text-muted" data-gsap="fade-up" data-gsap-delay="0.2">
            <p>Protected staff console · Winterfell Treks & Expedition Nepal</p>
        </div>
    </div>
</div>
@endsection
