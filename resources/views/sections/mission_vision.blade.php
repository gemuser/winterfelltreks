@php
    $missionTitle = $elements['mission_title']?->content ?? 'Our Mission';
    $missionText = $elements['mission_text']?->content ?? 'To orchestrate profound, sustainable, and transformative journeys into the heart of the Himalayas, preserving native heritage and mountains while providing unprecedented expedition safety and luxury.';
    $visionTitle = $elements['vision_title']?->content ?? 'Our Vision';
    $visionText = $elements['vision_text']?->content ?? 'To stand as the global benchmark for responsible high-altitude alpine travel—bridging authentic sherpa mastery, low-impact exploration, and bespoke hospitality for generations to come.';
@endphp

<section class="py-20 lg:py-32 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Mission Card --}}
            <div class="bg-background-alt border border-gray-100 p-8 sm:p-12 relative overflow-hidden group hover:border-accent-primary/40 transition-colors duration-300" data-gsap="fade-right">
                <div class="w-12 h-1 bg-accent-primary mb-6"></div>
                <span class="text-accent-primary font-semibold text-xs tracking-widest uppercase block mb-3">Guiding Purpose</span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary mb-6 tracking-tight">
                    {{ $missionTitle }}
                </h2>
                <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                    {!! $missionText !!}
                </div>
            </div>

            {{-- Vision Card --}}
            <div class="bg-background-alt border border-gray-100 p-8 sm:p-12 relative overflow-hidden group hover:border-accent-primary/40 transition-colors duration-300" data-gsap="fade-left">
                <div class="w-12 h-1 bg-accent-primary mb-6"></div>
                <span class="text-accent-primary font-semibold text-xs tracking-widest uppercase block mb-3">Future Horizon</span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary mb-6 tracking-tight">
                    {{ $visionTitle }}
                </h2>
                <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                    {!! $visionText !!}
                </div>
            </div>
        </div>
    </div>
</section>
