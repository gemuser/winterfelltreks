@php
    $headline = $elements['headline']?->content ?? $elements['heading']?->content ?? $elements['title']?->content ?? '';
    $headlineEl = $elements['headline'] ?? $elements['heading'] ?? $elements['title'] ?? null;
    $subheadline = $elements['subheadline']?->content ?? $elements['subheading']?->content ?? $elements['subtitle']?->content ?? '';
    $subheadlineEl = $elements['subheadline'] ?? $elements['subheading'] ?? $elements['subtitle'] ?? null;
    
    // Primary CTA
    $primaryCtaElement = $elements['primary_cta'] ?? $elements['cta'] ?? $elements['button'] ?? null;
    $rawPrimary = $primaryCtaElement?->getContent() ?? $primaryCtaElement?->content;
    $primaryText = 'Plan Your Journey';
    $primaryUrl = '/contact';
    if (is_array($rawPrimary)) {
        $primaryText = $rawPrimary['text'] ?? ($rawPrimary['label'] ?? 'Plan Your Journey');
        $primaryUrl = $rawPrimary['url'] ?? ($rawPrimary['href'] ?? '/contact');
    } elseif (is_string($rawPrimary) && !empty($rawPrimary)) {
        $decoded = json_decode($rawPrimary, true);
        if (is_array($decoded)) {
            $primaryText = $decoded['text'] ?? ($decoded['label'] ?? 'Plan Your Journey');
            $primaryUrl = $decoded['url'] ?? ($decoded['href'] ?? '/contact');
        } else {
            $primaryText = $rawPrimary;
        }
    }

    // Secondary CTA
    $secondaryCtaElement = $elements['secondary_cta'] ?? null;
    $rawSecondary = $secondaryCtaElement?->getContent() ?? $secondaryCtaElement?->content;
    $secondaryText = 'Explore Expeditions';
    $secondaryUrl = '/packages';
    if (is_array($rawSecondary)) {
        $secondaryText = $rawSecondary['text'] ?? ($rawSecondary['label'] ?? 'Explore Expeditions');
        $secondaryUrl = $rawSecondary['url'] ?? ($rawSecondary['href'] ?? '/packages');
    } elseif (is_string($rawSecondary) && !empty($rawSecondary)) {
        $decoded = json_decode($rawSecondary, true);
        if (is_array($decoded)) {
            $secondaryText = $decoded['text'] ?? ($decoded['label'] ?? 'Explore Expeditions');
            $secondaryUrl = $decoded['url'] ?? ($decoded['href'] ?? '/packages');
        } else {
            $secondaryText = $rawSecondary;
        }
    }

    $backgroundImage = $elements['background_image'] ?? $elements['image'] ?? null;
    $bgImage = $backgroundImage?->getImageUrl() ?? '/images/hero-everest.jpg';
    $bgAlt = $backgroundImage?->getImageAlt() ?? 'Himalayan Mountain Range';
    $alignment = $settings['content_alignment'] ?? 'center';
    $animation = $settings['animation'] ?? 'fade-up';
@endphp

<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-gray-950" 
         id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    
    @if($bgImage)
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" alt="{{ $bgAlt }}" class="w-full h-full object-cover" loading="eager" fetchpriority="high" data-key="background_image" @if($backgroundImage) data-element-id="{{ $backgroundImage->id }}" @endif>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-black/85"></div>
    </div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center" style="text-align: {{ $alignment === 'left' ? 'left' : ($alignment === 'right' ? 'right' : 'center') }};">
        @if($headline)
        <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight drop-shadow-sm canva-target-headline" data-key="headline" @if($headlineEl) data-element-id="{{ $headlineEl->id }}" @endif data-gsap="fade-up" data-gsap-delay="0.1">
            {{ $headline }}
        </h1>
        @endif

        @if($subheadline)
        <div class="max-w-3xl mx-auto mb-10 text-gray-200 text-lg sm:text-xl font-body leading-relaxed [&>p]:text-gray-200 [&>p]:leading-relaxed canva-target-subheadline" data-key="subheadline" @if($subheadlineEl) data-element-id="{{ $subheadlineEl->id }}" @endif data-gsap="fade-up" data-gsap-delay="0.2">
            {!! $subheadline !!}
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" style="justify-content: {{ $alignment === 'left' ? 'flex-start' : ($alignment === 'right' ? 'flex-end' : 'center') }};" data-gsap="fade-up" data-gsap-delay="0.3">
            @if($primaryText)
            <a href="{{ $primaryUrl }}" data-key="primary_cta" @if($primaryCtaElement) data-element-id="{{ $primaryCtaElement->id }}" @endif class="btn-primary px-8 py-3.5 text-base font-semibold tracking-wide w-full sm:w-auto text-center">
                {{ $primaryText }}
            </a>
            @endif
            
            @if($secondaryText)
            <a href="{{ $secondaryUrl }}" data-key="secondary_cta" @if($secondaryCtaElement) data-element-id="{{ $secondaryCtaElement->id }}" @endif class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-white/10 hover:bg-white/20 border-2 border-white/80 transition-all rounded-none w-full sm:w-auto text-center">
                {{ $secondaryText }}
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
