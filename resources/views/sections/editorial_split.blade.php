@php
    $heading = $elements['heading']?->content ?? 'Pioneering Himalayan Mastery';
    $subheading = $elements['subheading']?->content ?? 'Our Story & Philosophy';
    $body = $elements['body']?->content ?? '<p>Founded on high alpine passes and built upon generations of Sherpa mountaineering knowledge, Winterfell Treks & Expedition curates extraordinary expeditions across Nepal. We believe that stepping onto the highest ridges on Earth should be a safe, ethical, and deeply personal journey.</p>';
    $image = $elements['image']?->getImageUrl();
    if (!$image) {
        $image = '/images/about-story.jpg';
    }
    $imageAlt = $elements['image']?->getImageAlt() ?? $heading;
    $ctaElement = $elements['cta'] ?? null;
    $cta = $ctaElement?->getLinkUrl() ? (is_array($ctaElement->getContent()) ? $ctaElement->getContent() : json_decode($ctaElement->content, true)) : null;
    $layout = $settings['layout'] ?? 'text-left-image-right';
    $reverse = $layout === 'text-right-image-left';
@endphp

<section class="py-20 lg:py-32 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Text Column --}}
            <div class="{{ $reverse ? 'lg:order-2' : 'lg:order-1' }}" data-gsap="fade-{{ $reverse ? 'left' : 'right' }}">
                @if($subheading)
                <span class="text-accent-primary font-semibold text-xs sm:text-sm tracking-widest uppercase block mb-3">
                    {{ $subheading }}
                </span>
                @endif

                @if($heading)
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary mb-6 tracking-tight leading-tight">
                    {{ $heading }}
                </h2>
                @endif

                @if($body)
                <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed mb-8">
                    {!! $body !!}
                </div>
                @endif

                @if($cta && isset($cta['url']))
                <a href="{{ $cta['url'] }}" class="btn-primary inline-flex items-center gap-2 px-8 py-3.5 font-semibold shadow-md">
                    {{ $cta['text'] ?? 'Discover More' }}
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                @endif
            </div>

            {{-- Image Column --}}
            <div class="{{ $reverse ? 'lg:order-1' : 'lg:order-2' }} relative" data-gsap="fade-{{ $reverse ? 'right' : 'left' }}">
                <div class="aspect-[4/3] lg:aspect-[4/3] overflow-hidden shadow-xl border border-gray-100 relative group">
                    <img src="{{ $image }}" alt="{{ $imageAlt }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                </div>
                
                {{-- Decorative accent block --}}
                <div class="hidden sm:block absolute -bottom-4 -right-4 w-24 h-24 bg-accent-primary/10 -z-10" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>
