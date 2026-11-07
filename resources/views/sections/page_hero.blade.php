@php
    $titleEl = $elements['title'] ?? $elements['headline'] ?? $elements['heading'] ?? null;
    $title = $titleEl?->content ?? $page->title ?? 'Winterfell Treks';
    $subtitleEl = $elements['subtitle'] ?? $elements['subheadline'] ?? $elements['subheading'] ?? null;
    $subtitle = $subtitleEl?->content ?? '';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Winterfell Treks & Expedition';
    $bgImageEl = $elements['background_image'] ?? null;
    $bgImage = $bgImageEl?->getImageUrl();
    
    if (!$bgImage) {
        $slug = $page->slug ?? '';
        $bgImage = match($slug) {
            'destinations' => '/images/dest-hero.jpg',
            'packages' => '/images/pkg-hero.jpg',
            'experiences' => '/images/exp-hero.jpg',
            'travel-guide' => '/images/blog-hero.jpg',
            'about' => '/images/about-hero.jpg',
            'contact' => '/images/contact-hero.jpg',
            'enquiry' => '/images/enquiry-hero.jpg',
            'gallery' => '/images/gal-everest-sunrise.jpg',
            'faq' => '/images/dest-langtang.jpg',
            default => '/images/placeholder-hero.jpg',
        };
    }
    
    $bgAlt = $bgImageEl?->getImageAlt() ?? $title;
    $bgType = $settings['background_type'] ?? 'image';
@endphp

<section class="relative min-h-[45vh] lg:min-h-[50vh] flex items-center py-20 lg:py-28 bg-gray-950 text-white overflow-hidden" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    @if($bgImage && $bgType === 'image')
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" alt="{{ $bgAlt }}" class="w-full h-full object-cover" data-key="background_image" data-element-id="{{ $bgImageEl?->id ?? '' }}">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/65 to-black/60"></div>
    </div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-3xl" data-gsap="fade-up">
            @if($badge)
            <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-primary font-semibold text-xs sm:text-sm tracking-widest uppercase block mb-3">
                {{ $badge }}
            </span>
            @endif

            @if($title)
            <h1 data-key="title" data-element-id="{{ $titleEl?->id ?? '' }}" class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 tracking-tight">
                {{ $title }}
            </h1>
            @endif

            @if($subtitle)
            <p data-key="subtitle" data-element-id="{{ $subtitleEl?->id ?? '' }}" class="text-gray-200 text-base sm:text-xl leading-relaxed max-w-2xl">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        {{-- Breadcrumb --}}
        @if(isset($page) && $page->slug !== 'home')
        <nav class="mt-8 pt-6 border-t border-white/10" aria-label="Breadcrumb" data-gsap="fade-up" data-gsap-delay="0.2">
            <ol class="flex items-center space-x-2 text-xs sm:text-sm text-gray-300">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-white font-medium truncate" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        @endif
    </div>
</section>
