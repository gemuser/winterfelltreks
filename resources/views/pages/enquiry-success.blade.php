@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-background-alt">
    <div class="max-w-2xl w-full text-center">
        <div class="mb-8" data-gsap="fade-up">
            <div class="w-20 h-20 mx-auto mb-6 bg-accent-primary/10 border-2 border-accent-primary rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="text-accent-primary font-semibold text-xs sm:text-sm tracking-widest uppercase block mb-2">Request Confirmed</span>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                Expedition Enquiry Received
            </h1>
        </div>
        
        <div class="bg-white border border-gray-200 p-8 sm:p-10 mb-8 shadow-sm text-left" data-gsap="fade-up" data-gsap-delay="0.1">
            <div class="mb-6 p-6 bg-background-alt border border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold text-text-muted uppercase tracking-wider">Your Reference Code</p>
                    <p class="font-display text-2xl sm:text-3xl font-bold text-accent-primary font-mono mt-1">
                        {{ $enquiry->reference_number ?? 'WT-' . strtoupper(Str::random(8)) }}
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-bold uppercase tracking-wide">
                    Under Review
                </span>
            </div>
            
            <p class="text-text-secondary leading-relaxed mb-6 text-base">
                Thank you for reaching out to <strong>Winterfell Treks & Expedition</strong>. One of our Kathmandu-based mountain specialists has received your itinerary preferences and will formulate a personalized route overview, acclimatization schedule, and transparent quotation within 24 hours.
            </p>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-gray-100 text-sm">
                <div>
                    <dt class="text-xs text-text-muted uppercase">Guest Name</dt>
                    <dd class="font-semibold text-text-primary mt-0.5">{{ $enquiry->full_name ?? 'Valued Guest' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-text-muted uppercase">Email Address</dt>
                    <dd class="font-semibold text-text-primary mt-0.5">{{ $enquiry->email ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-text-muted uppercase">Target Region</dt>
                    <dd class="font-semibold text-text-primary mt-0.5">{{ $enquiry->destination ?? 'Himalayan Range' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-text-muted uppercase">Party Size</dt>
                    <dd class="font-semibold text-text-primary mt-0.5">{{ $enquiry->number_of_travellers ?? 1 }} Traveller(s)</dd>
                </div>
            </dl>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" data-gsap="fade-up" data-gsap-delay="0.2">
            <a href="{{ url('/') }}" class="btn-primary w-full sm:w-auto px-8 py-3.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Return to Homepage
            </a>
            
            <a href="{{ url('/packages') }}" class="btn-secondary w-full sm:w-auto px-8 py-3.5">
                Explore More Packages
            </a>
        </div>
        
        <div class="mt-10 text-xs sm:text-sm text-text-muted" data-gsap="fade-up" data-gsap-delay="0.3">
            <p>Immediate inquiry? Speak directly with our Kathmandu operations desk at 
                <a href="tel:+977-1-4700000" class="text-accent-primary font-semibold hover:underline">+977-1-4700000</a> or email 
                <a href="mailto:info@winterfelltreks.com" class="text-accent-primary font-semibold hover:underline">info@winterfelltreks.com</a>.
            </p>
        </div>
    </div>
</div>
@endsection
