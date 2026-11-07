@php
    $headingEl = $elements->get('heading') ?? $elements->get('headline') ?? $elements->get('title');
    $heading = $headingEl?->content ?? 'Begin Your Himalayan Odyssey';
    $bodyEl = $elements->get('body') ?? $elements->get('content');
    $body = $bodyEl?->content ?? 'Contact our expedition architects to craft an unmatched, bespoke journey across the grandest peaks on Earth.';
    $badgeEl = $elements->get('badge') ?? $elements->get('tag');
    $badge = $badgeEl?->content ?? 'Custom Expeditions';
    $noteEl = $elements->get('note') ?? $elements->get('disclaimer');
    $note = $noteEl?->content ?? 'No obligation · 100% bespoke tailor-made itineraries · Direct contact with Himalayan experts';
    
    // Primary CTA
    $primaryElement = $elements->get('primary_cta') ?? $elements->get('cta') ?? $elements->get('button');
    $primaryData = $primaryElement ? (is_array($primaryElement->getContent()) ? $primaryElement->getContent() : (is_string($primaryElement->content) && str_starts_with(trim($primaryElement->content), '{') ? json_decode($primaryElement->content, true) : null)) : null;
    $ctaText = $primaryData['text'] ?? ($primaryElement && is_string($primaryElement->content) && !str_starts_with(trim($primaryElement->content), '{') && !str_starts_with($primaryElement->content, 'http') && !str_starts_with($primaryElement->content, '/') ? $primaryElement->content : ($elements->get('cta_text')?->content ?? 'Plan Your Journey'));
    $ctaUrl = $primaryData['url'] ?? ($primaryElement && is_string($primaryElement->content) && (str_starts_with($primaryElement->content, 'http') || str_starts_with($primaryElement->content, '/')) ? $primaryElement->content : ($primaryElement?->getLinkUrl() ?? ($elements->get('cta_url')?->content ?? url('/enquiry'))));

    // Secondary CTA
    $secondaryElement = $elements->get('secondary_cta');
    $secondaryData = $secondaryElement ? (is_array($secondaryElement->getContent()) ? $secondaryElement->getContent() : (is_string($secondaryElement->content) && str_starts_with(trim($secondaryElement->content), '{') ? json_decode($secondaryElement->content, true) : null)) : null;
    $secondaryCtaText = $secondaryData['text'] ?? ($secondaryElement && is_string($secondaryElement->content) && !str_starts_with(trim($secondaryElement->content), '{') && !str_starts_with($secondaryElement->content, 'http') && !str_starts_with($secondaryElement->content, '/') ? $secondaryElement->content : $secondaryElement?->getLinkText());
    $secondaryCtaUrl = $secondaryData['url'] ?? ($secondaryElement && is_string($secondaryElement->content) && (str_starts_with($secondaryElement->content, 'http') || str_starts_with($secondaryElement->content, '/')) ? $secondaryElement->content : $secondaryElement?->getLinkUrl());

    $background = $settings['background'] ?? 'dark';
    $bgImageEl = $elements->get('background_image');
    $bgImage = $bgImageEl?->getImageUrl();
    $isDark = ($background === 'dark' || $background === 'accent' || !empty($bgImage));
@endphp

<section class="relative overflow-hidden py-20 lg:py-32 {{ $background === 'accent' ? 'bg-accent-primary text-white' : ($isDark ? 'bg-gray-950 text-white' : 'bg-background-alt text-text-primary') }}" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    @if($bgImage)
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" alt="" data-key="background_image" data-element-id="{{ $bgImageEl?->id ?? '' }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gray-950/75 backdrop-blur-[1px]"></div>
    </div>
    @elseif($background === 'dark')
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-900 via-gray-950 to-black opacity-90 z-0"></div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto" data-gsap="fade-up">
            @if($badge)
            <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-light font-medium text-xs sm:text-sm tracking-widest uppercase block mb-3">{{ $badge }}</span>
            @endif
            
            @if($heading)
            <h2 data-key="heading" data-element-id="{{ $headingEl?->id ?? '' }}" class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-6 {{ $isDark ? 'text-white' : 'text-text-primary' }}">
                {{ $heading }}
            </h2>
            @endif

            @if($body)
            <div data-key="body" data-element-id="{{ $bodyEl?->id ?? '' }}" class="prose prose-lg {{ $isDark ? 'text-gray-300' : 'text-text-secondary' }} max-w-2xl mx-auto mb-10 text-base sm:text-lg leading-relaxed">
                {!! $body !!}
            </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" data-gsap="fade-up" data-gsap-delay="0.2">
                @if($ctaText && $ctaUrl)
                <a href="{{ $ctaUrl }}" data-key="primary_cta" data-element-id="{{ $primaryElement?->id ?? '' }}" class="btn-primary px-8 py-4 font-semibold text-base min-w-[200px] text-center shadow-lg">
                    {{ $ctaText }}
                </a>
                @endif

                @if($secondaryCtaText && $secondaryCtaUrl)
                <a href="{{ $secondaryCtaUrl }}" data-key="secondary_cta" data-element-id="{{ $secondaryElement?->id ?? '' }}" class="{{ $isDark ? 'inline-flex items-center justify-center px-8 py-4 font-semibold text-white bg-white/10 hover:bg-white/20 border-2 border-white/30 transition-colors duration-200 min-w-[200px] text-center' : 'btn-secondary' }}">
                    {{ $secondaryCtaText }}
                </a>
                @endif
            </div>

            @if($note)
            <p data-key="note" @if($noteEl) data-element-id="{{ $noteEl->id }}" @endif class="mt-6 text-xs {{ $isDark ? 'text-gray-400' : 'text-text-muted' }}">
                {{ $note }}
            </p>
            @endif
        </div>
    </div>
</section>
