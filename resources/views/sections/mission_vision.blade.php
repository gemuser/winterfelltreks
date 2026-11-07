@php
    $missionTitleEl = $elements['mission_title'] ?? $elements['headline'] ?? $elements['heading'] ?? null;
    $missionTitle = $missionTitleEl?->content ?? 'Our Mission';
    $missionTextEl = $elements['mission_text'] ?? $elements['body'] ?? null;
    $missionText = $missionTextEl?->content ?? 'To orchestrate profound, sustainable, and transformative journeys into the heart of the Himalayas, preserving native heritage and mountains while providing unprecedented expedition safety and luxury.';
    $missionBadgeEl = $elements['mission_badge'] ?? $elements['badge'] ?? null;
    $missionBadge = $missionBadgeEl?->content ?? 'Guiding Purpose';

    $visionTitleEl = $elements['vision_title'] ?? null;
    $visionTitle = $visionTitleEl?->content ?? 'Our Vision';
    $visionTextEl = $elements['vision_text'] ?? null;
    $visionText = $visionTextEl?->content ?? 'To stand as the global benchmark for responsible high-altitude alpine travel—bridging authentic sherpa mastery, low-impact exploration, and bespoke hospitality for generations to come.';
    $visionBadgeEl = $elements['vision_badge'] ?? null;
    $visionBadge = $visionBadgeEl?->content ?? 'Future Horizon';
@endphp

<section class="py-20 lg:py-32 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Mission Card --}}
            <div class="bg-background-alt border border-gray-100 p-8 sm:p-12 relative overflow-hidden group hover:border-accent-primary/40 transition-colors duration-300" data-gsap="fade-right">
                <div class="w-12 h-1 bg-accent-primary mb-6"></div>
                @if($missionBadge)
                <span data-key="mission_badge" @if($missionBadgeEl) data-element-id="{{ $missionBadgeEl->id }}" @endif class="text-accent-primary font-semibold text-xs tracking-widest uppercase block mb-3">{{ $missionBadge }}</span>
                @endif
                <h2 data-key="mission_title" data-element-id="{{ $missionTitleEl?->id ?? '' }}" class="font-display text-2xl sm:text-3xl font-bold text-text-primary mb-6 tracking-tight">
                    {{ $missionTitle }}
                </h2>
                <div data-key="mission_text" data-element-id="{{ $missionTextEl?->id ?? '' }}" class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                    {!! $missionText !!}
                </div>
            </div>

            {{-- Vision Card --}}
            <div class="bg-background-alt border border-gray-100 p-8 sm:p-12 relative overflow-hidden group hover:border-accent-primary/40 transition-colors duration-300" data-gsap="fade-left">
                <div class="w-12 h-1 bg-accent-primary mb-6"></div>
                @if($visionBadge)
                <span data-key="vision_badge" @if($visionBadgeEl) data-element-id="{{ $visionBadgeEl->id }}" @endif class="text-accent-primary font-semibold text-xs tracking-widest uppercase block mb-3">{{ $visionBadge }}</span>
                @endif
                <h2 data-key="vision_title" data-element-id="{{ $visionTitleEl?->id ?? '' }}" class="font-display text-2xl sm:text-3xl font-bold text-text-primary mb-6 tracking-tight">
                    {{ $visionTitle }}
                </h2>
                <div data-key="vision_text" data-element-id="{{ $visionTextEl?->id ?? '' }}" class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                    {!! $visionText !!}
                </div>
            </div>
        </div>
    </div>
</section>
