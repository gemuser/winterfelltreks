@php
    $title = $elements['title']?->content ?? $page->title ?? 'Winterfell Treks';
    $subtitle = $elements['subtitle']?->content ?? '';
    $bgImage = $elements['background_image']?->getImageUrl();
    
    // Contextual fallback hero image if none set in CMS
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
    
    $bgAlt = $elements['background_image']?->getImageAlt() ?? $title;
    $bgType = $settings['background_type'] ?? 'image';
@endphp

<section class="relative min-h-[45vh] lg:min-h-[50vh] flex items-center py-20 lg:py-28 bg-gray-950 text-white overflow-hidden" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    @if($bgImage && $bgType === 'image')
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" alt="{{ $bgAlt }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/65 to-black/60"></div>
    </div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-3xl" data-gsap="fade-up">
            <span class="text-accent-primary font-semibold text-xs sm:text-sm tracking-widest uppercase block mb-3">
                Winterfell Treks & Expedition
            </span>

            @if($title)
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 tracking-tight">
                {{ $title }}
            </h1>
            @endif

            @if($subtitle)
            <p class="text-gray-200 text-base sm:text-xl leading-relaxed max-w-2xl">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        {{-- Breadcrumb --}}
        @if(isset($page) && $page->slug !== 'home')
        <nav class="mt-8 pt-6 border-t border-white/10" aria-label="Breadcrumb" data-gsap="fade-up" data-gsap-delay="0.2">
            <ol class="flex items-center space-x-2 text-xs sm:text-sm text-gray-300">
                <li>
                    <a href="{{ url('/') }}" class="hover:text-accent-primary transition-colors">Home</a>
                </li>
                <li class="text-gray-500" aria-hidden="true">/</li>
                <li class="text-white font-medium" aria-current="page">{{ $page->title }}</li>
            </ol>
        </nav>
        @endif
    </div>
</section>
