@php
    $heading = $elements->get('heading')?->content ?? 'Begin Your Himalayan Odyssey';
    $body = $elements->get('body')?->content ?? 'Contact our expedition architects to craft an unmatched, bespoke journey across the grandest peaks on Earth.';
    
    // Primary CTA
    $primaryElement = $elements->get('primary_cta') ?? $elements->get('cta');
    $ctaText = $elements->get('cta_text')?->content;
    $ctaUrl = $elements->get('cta_url')?->content;
    
    if (!$ctaText && $primaryElement) {
        $ctaText = $primaryElement->getLinkText();
        if (!$ctaText && is_string($primaryElement->content)) {
            $ctaText = $primaryElement->content;
        }
        $ctaUrl = $primaryElement->getLinkUrl() ?? $ctaUrl ?? url('/enquiry');
    }
    if (!$ctaText) {
        $ctaText = 'Plan Your Journey';
        $ctaUrl = $ctaUrl ?: url('/enquiry');
    }

    // Secondary CTA
    $secondaryElement = $elements->get('secondary_cta');
    $secondaryCtaText = $secondaryElement?->getLinkText();
    $secondaryCtaUrl = $secondaryElement?->getLinkUrl();
    if (!$secondaryCtaText && is_array($secondaryElement?->getContent())) {
        $secondaryCtaText = $secondaryElement->getContent()['text'] ?? null;
        $secondaryCtaUrl = $secondaryElement->getContent()['url'] ?? null;
    }

    $style = $settings['style'] ?? 'primary';
    $background = $settings['background'] ?? 'dark';
    $bgImage = $elements->get('background_image')?->getImageUrl();
    $isDark = ($background === 'dark' || $background === 'accent' || !empty($bgImage));
@endphp

<section class="relative overflow-hidden py-20 lg:py-32 {{ $background === 'accent' ? 'bg-accent-primary text-white' : ($isDark ? 'bg-gray-950 text-white' : 'bg-background-alt text-text-primary') }}" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    @if($bgImage)
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gray-950/75 backdrop-blur-[1px]"></div>
    </div>
    @elseif($background === 'dark')
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-900 via-gray-950 to-black opacity-90 z-0"></div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto" data-gsap="fade-up">
            <span class="text-accent-light font-medium text-xs sm:text-sm tracking-widest uppercase block mb-3">Custom Expeditions</span>
            
            @if($heading)
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-6 {{ $isDark ? 'text-white' : 'text-text-primary' }}">
                {{ $heading }}
            </h2>
            @endif

            @if($body)
            <div class="prose prose-lg {{ $isDark ? 'text-gray-300' : 'text-text-secondary' }} max-w-2xl mx-auto mb-10 text-base sm:text-lg leading-relaxed">
                {!! $body !!}
            </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" data-gsap="fade-up" data-gsap-delay="0.2">
                @if($ctaText && $ctaUrl)
                <a href="{{ $ctaUrl }}" class="{{ $isDark ? 'btn-primary' : 'btn-primary' }} px-8 py-4 font-semibold text-base min-w-[200px] text-center shadow-lg">
                    {{ $ctaText }}
                </a>
                @endif

                @if($secondaryCtaText && $secondaryCtaUrl)
                <a href="{{ $secondaryCtaUrl }}" class="{{ $isDark ? 'inline-flex items-center justify-center px-8 py-4 font-semibold text-white bg-white/10 hover:bg-white/20 border-2 border-white/30 transition-colors duration-200 min-w-[200px] text-center' : 'btn-secondary' }}">
                    {{ $secondaryCtaText }}
                </a>
                @endif
            </div>

            <p class="mt-6 text-xs {{ $isDark ? 'text-gray-400' : 'text-text-muted' }}">
                No obligation · 100% bespoke tailor-made itineraries · Direct contact with Himalayan experts
            </p>
        </div>
    </div>
</section>
