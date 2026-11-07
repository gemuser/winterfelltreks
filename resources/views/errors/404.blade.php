@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-background-alt">
    <div class="max-w-xl w-full text-center">
        <div class="mb-8" data-gsap="fade-up">
            <span class="text-accent-primary font-mono text-sm tracking-widest uppercase block mb-2 font-bold">Error 404 · Trail Not Found</span>
            <h1 class="font-display text-8xl sm:text-9xl font-bold text-text-primary tracking-tight leading-none">404</h1>
            <p class="font-display text-2xl sm:text-3xl font-bold text-text-primary mt-4">You've Wandered Off The Trail</p>
        </div>
        
        <div class="bg-white border border-gray-200 p-8 sm:p-10 mb-8 shadow-sm" data-gsap="fade-up" data-gsap-delay="0.1">
            <p class="text-text-secondary leading-relaxed mb-8 text-base">
                The Himalayan ridge or page you are searching for does not exist or has been relocated. Let us navigate you safely back to base camp or our premier expeditions.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/') }}" class="btn-primary w-full sm:w-auto px-8 py-3.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Return to Base Camp
                </a>
                
                <a href="{{ url('/packages') }}" class="btn-secondary w-full sm:w-auto px-8 py-3.5">
                    Explore Tour Packages
                </a>
            </div>
        </div>
        
        <div class="text-xs sm:text-sm text-text-muted" data-gsap="fade-up" data-gsap-delay="0.2">
            <p>Need assistance finding a specific destination? <a href="{{ url('/contact') }}" class="text-accent-primary font-semibold hover:underline">Contact our mountain guides</a>.</p>
        </div>
    </div>
</div>
@endsection
