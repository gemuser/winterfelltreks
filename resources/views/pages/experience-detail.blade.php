@extends('layouts.app')

@section('content')
<article class="experience-detail min-h-screen">
    {{-- Hero Section --}}
    <section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-gray-900">
        @if($experience->hero_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $experience->hero_image }}" alt="{{ $experience->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/50 to-transparent"></div>
        </div>
        @endif

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            @if($experience->category)
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent-primary text-white text-xs font-semibold uppercase tracking-wider mb-4">
                {{ $experience->category }}
            </div>
            @endif

            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-4 leading-tight">
                {{ $experience->name }}
            </h1>

            @if($experience->short_description)
            <p class="max-w-3xl mx-auto text-gray-200 text-lg leading-relaxed">
                {{ strip_tags($experience->short_description) }}
            </p>
            @endif

            <nav class="mt-8" aria-label="Breadcrumb">
                <ol class="flex items-center justify-center space-x-2 text-sm text-gray-300">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="{{ url('/experiences') }}" class="hover:text-white transition-colors">Experiences</a></li>
                    <li class="text-gray-500">/</li>
                    <li class="text-white font-medium" aria-current="page">{{ $experience->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-8 space-y-12">
                    @if($experience->overview)
                    <div>
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-4">Overview</h2>
                        <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                            {!! $experience->overview !!}
                        </div>
                    </div>
                    @endif

                    @if($experience->detailed_description)
                    <div>
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-4">Experience Details</h2>
                        <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                            {!! $experience->detailed_description !!}
                        </div>
                    </div>
                    @endif

                    @if(!empty($experience->activities) && is_array($experience->activities))
                    <div>
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-6">Key Activities</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($experience->activities as $act)
                            <div class="p-4 bg-background-alt border border-gray-100 flex items-start gap-3">
                                <svg class="w-5 h-5 text-accent-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-text-primary font-medium text-sm">{{ is_array($act) ? ($act['title'] ?? json_encode($act)) : $act }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!empty($experience->available_locations) && is_array($experience->available_locations))
                    <div>
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-4">Available Locations</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($experience->available_locations as $loc)
                            <span class="px-3 py-1.5 bg-gray-100 text-text-primary text-sm font-medium">
                                {{ is_array($loc) ? ($loc['name'] ?? json_encode($loc)) : $loc }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($experience->important_information)
                    <div>
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-4">Important Information</h2>
                        <div class="prose prose-sm max-w-none text-text-secondary leading-relaxed bg-background-alt border border-gray-100 p-6 sm:p-8 [&>p]:mb-3">
                            {!! $experience->important_information !!}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-4 space-y-8">
                    <div class="bg-white border-2 border-accent-primary p-8 shadow-sm">
                        <h3 class="font-display text-2xl font-bold text-text-primary mb-2">Join This Experience</h3>
                        @if($experience->recommended_duration)
                        <p class="text-accent-primary font-semibold text-sm mb-4">Duration: {{ $experience->recommended_duration }}</p>
                        @endif
                        <p class="text-text-secondary text-sm leading-relaxed mb-6">
                            Tailor this activity to your trip or incorporate it into a full Himalayan expedition.
                        </p>
                        <a href="{{ url('/enquiry?experience_id=' . $experience->id . '&experience_slug=' . $experience->slug) }}" class="btn-primary w-full text-center py-4 block font-semibold text-base">
                            Enquire Now
                        </a>
                    </div>

                    @if($relatedExpeditions->isNotEmpty())
                    <div class="bg-white border border-gray-200 p-8 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-text-primary mb-6">Associated Expeditions</h3>
                        <div class="space-y-4">
                            @foreach($relatedExpeditions as $pkg)
                            <div class="flex gap-4 items-center border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                @if($pkg->featured_image)
                                <img src="{{ $pkg->featured_image }}" alt="{{ $pkg->title }}" class="w-16 h-14 object-cover flex-shrink-0">
                                @endif
                                <div>
                                    <h4 class="font-display text-sm font-bold text-text-primary hover:text-accent-primary transition-colors">
                                        <a href="{{ url('/packages/' . $pkg->slug) }}">{{ $pkg->title }}</a>
                                    </h4>
                                    <span class="text-xs text-text-secondary">{{ $pkg->duration_days }} Days</span>
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

