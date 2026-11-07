@php
    $heading = $elements['heading']?->content ?? $elements['headline']?->content ?? $elements['title']?->content ?? 'Featured Guide';
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $featured = $pageData['featured'] ?? null;
@endphp

@if($featured)
<section class="py-12 lg:py-16 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="mb-8" data-gsap="fade-up">
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary tracking-tight" data-key="heading" @if($headingEl) data-element-id="{{ $headingEl->id }}" @endif>
                {{ $heading }}
            </h2>
        </div>
        @endif

        <article class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-white border border-gray-100 overflow-hidden shadow-sm" data-gsap="fade-up">
            @if($featured->cover_image)
            <div class="lg:col-span-7 aspect-[16/10] overflow-hidden">
                <img src="{{ $featured->cover_image }}" alt="{{ $featured->title }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
            </div>
            @endif

            <div class="p-8 lg:p-12 {{ $featured->cover_image ? 'lg:col-span-5' : 'lg:col-span-12' }}">
                <div class="flex items-center gap-3 text-sm text-text-secondary mb-4">
                    @if($featured->category)
                    <span class="px-2.5 py-1 bg-accent-primary text-white text-xs font-semibold uppercase tracking-wider">
                        {{ $featured->category->name }}
                    </span>
                    @endif
                    @if($featured->published_at)
                    <time datetime="{{ $featured->published_at->format('c') }}">
                        {{ $featured->published_at->format('M d, Y') }}
                    </time>
                    @endif
                    @if($featured->author)
                    <span>·</span>
                    <span>{{ $featured->author }}</span>
                    @endif
                </div>

                <h3 class="font-display text-2xl sm:text-3xl font-bold text-text-primary mb-4 leading-tight">
                    <a href="{{ url('/travel-guide/' . $featured->slug) }}" class="hover:text-accent-primary transition-colors">
                        {{ $featured->title }}
                    </a>
                </h3>

                @if($featured->excerpt)
                <p class="text-text-secondary leading-relaxed mb-6">
                    {{ strip_tags($featured->excerpt) }}
                </p>
                @endif

                <a href="{{ url('/travel-guide/' . $featured->slug) }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 font-semibold">
                    Read Article
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </article>
    </div>
</section>
@endif

