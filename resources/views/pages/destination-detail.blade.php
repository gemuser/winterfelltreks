@extends('layouts.app')

@section('content')
<article class="destination-detail min-h-screen">
    {{-- Hero Section --}}
    <section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-gray-900">
        @if($destination->featured_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $destination->featured_image }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/50 to-transparent"></div>
        </div>
        @endif

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent-primary text-white text-xs font-semibold uppercase tracking-wider mb-4">
                {{ $destination->region ?? $destination->country ?? 'Himalayan Destination' }}
            </div>

            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-4 leading-tight">
                {{ $destination->name }}
            </h1>

            @if($destination->short_description)
            <p class="max-w-3xl mx-auto text-gray-200 text-lg sm:text-xl leading-relaxed">
                {{ strip_tags($destination->short_description) }}
            </p>
            @endif

            <nav class="mt-8" aria-label="Breadcrumb">
                <ol class="flex items-center justify-center space-x-2 text-sm text-gray-300">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="{{ url('/destinations') }}" class="hover:text-white transition-colors">Destinations</a></li>
                    <li class="text-gray-500">/</li>
                    <li class="text-white font-medium" aria-current="page">{{ $destination->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- Main Content & Sidebar --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                {{-- Left Content Column --}}
                <div class="lg:col-span-8">
                    @if($destination->full_description)
                    <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed mb-12">
                        {!! $destination->full_description !!}
                    </div>
                    @endif

                    {{-- Travel Info & Best Time --}}
                    @if($destination->travel_information || $destination->best_time_to_visit)
                    <div class="bg-background-alt border border-gray-100 p-8 sm:p-10 mb-12 shadow-sm">
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-6">Traveler Information</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            @if($destination->best_time_to_visit)
                            <div>
                                <h3 class="text-sm font-semibold uppercase text-accent-primary tracking-wider mb-3">Best Time to Visit</h3>
                                <div class="prose prose-sm max-w-none text-text-secondary leading-relaxed [&>p]:mb-3">
                                    {!! $destination->best_time_to_visit !!}
                                </div>
                            </div>
                            @endif

                            @if($destination->category)
                            <div>
                                <h3 class="text-sm font-semibold uppercase text-accent-primary tracking-wider mb-3">Category</h3>
                                <span class="inline-block px-3 py-1 bg-white border border-gray-200 text-xs font-semibold uppercase tracking-wider text-text-primary shadow-xs">
                                    {{ $destination->category }}
                                </span>
                            </div>
                            @endif
                        </div>

                        @if($destination->travel_information)
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-sm font-semibold uppercase text-accent-primary tracking-wider mb-3">Essential Notes & Logistics</h3>
                            <div class="prose prose-sm max-w-none text-text-secondary leading-relaxed [&>p]:mb-3">
                                {!! $destination->travel_information !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Gallery --}}
                    @if(!empty($destination->gallery) && is_array($destination->gallery))
                    <div class="mb-12">
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-6">Photo Gallery</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($destination->gallery as $img)
                            <div class="aspect-[4/3] overflow-hidden group">
                                <img src="{{ $img }}" alt="{{ $destination->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Right Sidebar --}}
                <aside class="lg:col-span-4 space-y-8">
                    {{-- Quick Action Card --}}
                    <div class="bg-white border border-gray-200 p-8 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-text-primary mb-3">Plan Your Journey</h3>
                        <p class="text-text-secondary text-sm leading-relaxed mb-6">
                            Have questions or wish to tailor a custom itinerary for {{ $destination->name }}? Speak with our Himalayan travel specialists.
                        </p>
                        <a href="{{ url('/enquiry?destination_id=' . $destination->id) }}" class="btn-primary w-full text-center py-3.5 block font-semibold">
                            Enquire About This Destination
                        </a>
                    </div>

                    {{-- Related Expeditions --}}
                    @if($relatedPackages->isNotEmpty())
                    <div class="bg-white border border-gray-200 p-8 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-text-primary mb-6">Packages in this Region</h3>
                        <div class="space-y-4">
                            @foreach($relatedPackages as $pkg)
                            <div class="flex gap-4 items-center border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                @if($pkg->featured_image)
                                <img src="{{ $pkg->featured_image }}" alt="{{ $pkg->title }}" class="w-20 h-16 object-cover flex-shrink-0">
                                @endif
                                <div>
                                    <h4 class="font-display text-sm font-bold text-text-primary hover:text-accent-primary transition-colors">
                                        <a href="{{ url('/packages/' . $pkg->slug) }}">{{ $pkg->title }}</a>
                                    </h4>
                                    <span class="text-xs text-text-secondary">{{ $pkg->duration_days }} Days · {{ $pkg->technical_grade ?? 'Trek' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</article>
@endsection

