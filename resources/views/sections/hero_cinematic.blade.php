@php
    $headline = $elements->get('headline')?->content ?? '';
    $subheadline = $elements->get('subheadline')?->content ?? '';
    $primaryCtaElement = $elements->get('primary_cta');
    $primaryCta = $primaryCtaElement?->getLinkUrl() ? (is_array($primaryCtaElement->getContent()) ? $primaryCtaElement->getContent() : json_decode($primaryCtaElement->content, true)) : null;
    $secondaryCtaElement = $elements->get('secondary_cta');
    $secondaryCta = $secondaryCtaElement?->getLinkUrl() ? (is_array($secondaryCtaElement->getContent()) ? $secondaryCtaElement->getContent() : json_decode($secondaryCtaElement->content, true)) : null;
    $backgroundImage = $elements->get('background_image');
    $bgImage = $backgroundImage?->getImageUrl() ?? '/images/hero-everest.jpg';
    $bgAlt = $backgroundImage?->getImageAlt() ?? 'Himalayan Mountain Range';
    $alignment = $settings['content_alignment'] ?? 'center';
    $animation = $settings['animation'] ?? 'fade-up';
@endphp

<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-gray-950" 
         id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    
    @if($bgImage)
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" alt="{{ $bgAlt }}" class="w-full h-full object-cover" loading="eager" fetchpriority="high" data-key="background_image">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-black/85"></div>
    </div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center" style="text-align: {{ $alignment === 'left' ? 'left' : ($alignment === 'right' ? 'right' : 'center') }};">
        @if($headline)
        <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight drop-shadow-sm canva-target-headline" data-key="headline" data-gsap="fade-up" data-gsap-delay="0.1">
            {{ $headline }}
        </h1>
        @endif

        @if($subheadline)
        <div class="max-w-3xl mx-auto mb-10 text-gray-200 text-lg sm:text-xl font-body leading-relaxed [&>p]:text-gray-200 [&>p]:leading-relaxed canva-target-subheadline" data-key="subheadline" data-gsap="fade-up" data-gsap-delay="0.2">
            {!! $subheadline !!}
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" style="justify-content: {{ $alignment === 'left' ? 'flex-start' : ($alignment === 'right' ? 'flex-end' : 'center') }};" data-gsap="fade-up" data-gsap-delay="0.3">
            @if($primaryCta)
            <a href="{{ $primaryCta['url'] }}" data-key="primary_cta" class="btn-primary px-8 py-3.5 text-base font-semibold tracking-wide w-full sm:w-auto text-center">
                {{ $primaryCta['text'] }}
            </a>
            @endif
            
            @if($secondaryCta)
            <a href="{{ $secondaryCta['url'] }}" data-key="secondary_cta" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-white/10 hover:bg-white/20 border-2 border-white/80 transition-all rounded-none w-full sm:w-auto text-center">
                {{ $secondaryCta['text'] }}
            </a>
            @endif
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce" aria-hidden="true">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>
