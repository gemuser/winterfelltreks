@extends('layouts.app')

@section('content')
<article class="package-detail min-h-screen">
    {{-- Hero Section --}}
    <section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-gray-900">
        @if($package->featured_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $package->featured_image }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/50 to-transparent"></div>
        </div>
        @endif

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <div class="flex flex-wrap items-center justify-center gap-2 mb-4">
                @if($package->region)
                <span class="px-3 py-1 bg-accent-primary text-white text-xs font-semibold uppercase tracking-wider">{{ $package->region }}</span>
                @endif
                @if($package->duration_days)
                <span class="px-3 py-1 bg-white/20 backdrop-blur text-white text-xs font-medium">{{ $package->duration_days }} Days</span>
                @endif
                @if($package->max_elevation)
                <span class="px-3 py-1 bg-white/20 backdrop-blur text-white text-xs font-medium">{{ $package->max_elevation }}</span>
                @endif
            </div>

            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-4 leading-tight">
                {{ $package->title }}
            </h1>

            @if($package->route_overview)
            <div class="max-w-3xl mx-auto text-gray-200 text-lg leading-relaxed [&>p]:leading-relaxed [&>p]:text-gray-200">
                {!! $package->route_overview !!}
            </div>
            @endif

            <nav class="mt-8" aria-label="Breadcrumb">
                <ol class="flex items-center justify-center space-x-2 text-sm text-gray-300">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="{{ url('/packages') }}" class="hover:text-white transition-colors">Packages</a></li>
                    <li class="text-gray-500">/</li>
                    <li class="text-white font-medium" aria-current="page">{{ $package->title }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- Main Package Details --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                {{-- Main Column --}}
                <div class="lg:col-span-8 space-y-12">
                    {{-- Key Stats --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-6 bg-background-alt border border-gray-100 text-center">
                        <div>
                            <span class="text-xs uppercase text-text-secondary tracking-wider block mb-1">Duration</span>
                            <span class="font-display text-xl font-bold text-text-primary">{{ $package->duration_days ?? 'N/A' }} Days</span>
                        </div>
                        <div>
                            <span class="text-xs uppercase text-text-secondary tracking-wider block mb-1">Max Altitude</span>
                            <span class="font-display text-xl font-bold text-text-primary">{{ $package->max_elevation ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs uppercase text-text-secondary tracking-wider block mb-1">Technical Grade</span>
                            <span class="font-display text-xl font-bold text-text-primary">{{ $package->technical_grade ?? 'Moderate' }}</span>
                        </div>
                        <div>
                            <span class="text-xs uppercase text-text-secondary tracking-wider block mb-1">Region</span>
                            <span class="font-display text-xl font-bold text-text-primary">{{ $package->region ?? 'Nepal' }}</span>
                        </div>
                    </div>

                    {{-- Day by Day Itinerary --}}
                    @if(!empty($package->itinerary) && is_array($package->itinerary))
                    <div>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary mb-6">Detailed Itinerary</h2>
                        <div class="space-y-4">
                            @foreach($package->itinerary as $day)
                            <div class="border border-gray-200 p-6 bg-white">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2.5 py-1 bg-accent-primary text-white text-xs font-bold uppercase">
                                        Day {{ $day['day'] ?? $loop->iteration }}
                                    </span>
                                    <h3 class="font-display text-lg font-bold text-text-primary">
                                        {{ $day['title'] ?? 'Trek Itinerary' }}
                                    </h3>
                                </div>
                                @if(isset($day['description']))
                                <p class="text-text-secondary text-sm leading-relaxed mt-2">{{ $day['description'] }}</p>
                                @endif
                                @if(isset($day['altitude']) || isset($day['hours']))
                                <div class="flex gap-4 mt-3 text-xs text-text-secondary font-medium">
                                    @if(isset($day['altitude']))
                                    <span>Altitude: {{ $day['altitude'] }}</span>
                                    @endif
                                    @if(isset($day['hours']))
                                    <span>Walking: {{ $day['hours'] }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Inclusions and Exclusions --}}
                    @if(!empty($package->included_services) || !empty($package->excluded_services))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        @if(!empty($package->included_services))
                        <div class="p-6 bg-white border border-gray-200">
                            <h3 class="font-display text-xl font-bold text-text-primary mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                What's Included
                            </h3>
                            <ul class="space-y-2 text-sm text-text-secondary">
                                @foreach((array)$package->included_services as $inc)
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 font-bold">•</span>
                                    <span>{{ is_array($inc) ? ($inc['title'] ?? json_encode($inc)) : $inc }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($package->excluded_services))
                        <div class="p-6 bg-white border border-gray-200">
                            <h3 class="font-display text-xl font-bold text-text-primary mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                What's Excluded
                            </h3>
                            <ul class="space-y-2 text-sm text-text-secondary">
                                @foreach((array)$package->excluded_services as $exc)
                                <li class="flex items-start gap-2">
                                    <span class="text-red-500 font-bold">•</span>
                                    <span>{{ is_array($exc) ? ($exc['title'] ?? json_encode($exc)) : $exc }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Gear Checklist --}}
                    @if(!empty($package->gear_checklist))
                    <div class="p-8 bg-background-alt border border-gray-100">
                        <h2 class="font-display text-2xl font-bold text-text-primary mb-4">Gear & Equipment Checklist</h2>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-text-secondary">
                            @foreach((array)$package->gear_checklist as $gear)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ is_array($gear) ? ($gear['item'] ?? json_encode($gear)) : $gear }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-4 space-y-8">
                    {{-- Enquiry Card --}}
                    <div class="bg-white border-2 border-accent-primary p-8 shadow-sm">
                        <h3 class="font-display text-2xl font-bold text-text-primary mb-2">Book Your Enquiry</h3>
                        <p class="text-text-secondary text-sm leading-relaxed mb-6">
                            Tailor this itinerary to your schedule, group size, and personal preferences.
                        </p>
                        <a href="{{ url('/enquiry?package_id=' . $package->id . '&package_slug=' . $package->slug) }}" class="btn-primary w-full text-center py-4 block font-semibold text-base">
                            Enquire About This Package
                        </a>
                    </div>

                    {{-- Related Expeditions --}}
                    @if($relatedPackages->isNotEmpty())
                    <div class="bg-white border border-gray-200 p-8 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-text-primary mb-6">Other Expeditions</h3>
                        <div class="space-y-4">
                            @foreach($relatedPackages as $rel)
                            <div class="flex gap-4 items-center border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                @if($rel->featured_image)
                                <img src="{{ $rel->featured_image }}" alt="{{ $rel->title }}" class="w-20 h-16 object-cover flex-shrink-0">
                                @endif
                                <div>
                                    <h4 class="font-display text-sm font-bold text-text-primary hover:text-accent-primary transition-colors">
                                        <a href="{{ url('/packages/' . $rel->slug) }}">{{ $rel->title }}</a>
                                    </h4>
                                    <span class="text-xs text-text-secondary">{{ $rel->duration_days }} Days · {{ $rel->region }}</span>
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

