@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto flex justify-center">
                <img src="/images/winterfell-nobg.png" alt="{{ \App\Models\SiteSetting::getValue('site_name') ?? 'Winterfell Treks & Expedition' }}" class="h-16 w-auto object-contain">
            </div>
            <h2 class="mt-6 font-display text-3xl font-bold text-text-primary">
                Verify Your Email Address
            </h2>
            <p class="mt-2 text-sm text-text-secondary">
                Thanks for registering! Before getting started, please verify your email address by clicking the link we sent to your email.
            </p>
        </div>
        
        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary w-full py-3">
                    Resend Verification Email
                </button>
            </form>
            
            <p class="text-sm text-text-secondary">
                Didn't receive the email? Check your spam folder or request another one.
            </p>
        </div>
        
        <div class="text-center">
            <a href="{{ route('logout') }}" class="text-sm text-accent-primary hover:underline">
                Logout
            </a>
        </div>
    </div>
</div>
@endsection