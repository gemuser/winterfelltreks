@php
    $heading = $elements['heading']?->content ?? 'Himalayan Gallery';
    $subheading = $elements['subheading']?->content ?? '';
    $viewAllText = $elements['view_all_text']?->content ?? 'View Full Gallery';
    $viewAllUrl = $elements['view_all_url']?->content ?? url('/gallery');
    $images = $elements['images']?->getRepeaterItems() ?? [];
    $allImages = !empty($images) ? $images : ($pageData['images'] ?? collect());
    $categories = $pageData['categories'] ?? collect();
    $showCategories = $settings['show_categories'] ?? false;
    $columns = (int)($settings['columns'] ?? 4);
    $colClass = match($columns) {
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        default => 'lg:grid-cols-4',
    };
    $showViewAll = $settings['show_view_all'] ?? false;
    $lightbox = $settings['lightbox'] ?? true;
@endphp

@if(!empty($allImages) && count($allImages))
<section class="py-16 lg:py-24 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10" data-gsap="fade-up">
            <div>
                @if($heading)
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight">
                    {{ $heading }}
                </h2>
                @endif
                @if($subheading)
                <p class="text-text-secondary mt-2 text-lg">{{ $subheading }}</p>
                @endif
            </div>
            @if($showViewAll && $viewAllUrl && !request()->is('gallery'))
            <a href="{{ $viewAllUrl }}" class="btn-secondary mt-4 sm:mt-0 whitespace-nowrap self-start sm:self-auto">
                {{ $viewAllText }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            @endif
        </div>

        {{-- Category Filters if on gallery page --}}
        @if($showCategories && $categories->isNotEmpty())
        <div class="mb-10 flex flex-wrap gap-2 justify-center" data-gsap="fade-up">
            <button 
                type="button" 
                class="px-4 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full border border-gray-200 transition-colors bg-accent-primary text-white border-accent-primary"
                onclick="window.location.href='{{ url('/gallery') }}'">
                All Photos
            </button>
            @foreach($categories as $cat)
            <button 
                type="button" 
                class="px-4 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors"
                onclick="window.location.href='{{ url('/gallery?category=' . $cat->id) }}'">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Masonry Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 {{ $colClass }} gap-4" role="list">
            @foreach($allImages as $index => $img)
            @php
                $url = is_object($img) ? ($img->image ?? $img->url) : ($img['image'] ?? $img['url'] ?? '');
                $alt = is_object($img) ? ($img->alt_text ?? $img->title ?? 'Himalayan photograph') : ($img['alt'] ?? $img['alt_text'] ?? $img['title'] ?? 'Himalayan photograph');
                $title = is_object($img) ? ($img->title ?? '') : ($img['title'] ?? '');
                $location = is_object($img) ? ($img->location ?? '') : ($img['location'] ?? '');
            @endphp
            @if($url)
            <article class="gallery-item group relative aspect-[4/3] overflow-hidden bg-gray-100 border border-gray-100" data-gsap="fade-up" data-gsap-delay="{{ ($index % 8) * 0.05 }}">
                <a href="{{ $url }}" {{ $lightbox ? 'data-lightbox=' . $url . ' data-lightbox-alt=' . htmlspecialchars($alt) : '' }} class="block w-full h-full cursor-zoom-in">
                    <img src="{{ $url }}" alt="{{ $alt }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                        @if($title)
                        <h4 class="font-display text-sm font-semibold mb-0.5 leading-snug">{{ $title }}</h4>
                        @endif
                        @if($location)
                        <span class="text-xs text-accent-light flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $location }}
                        </span>
                        @endif
                    </div>
                </a>
            </article>
            @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        @if(is_object($allImages) && method_exists($allImages, 'links'))
        <div class="mt-12">
            {{ $allImages->links() }}
        </div>
        @endif
    </div>
</section>
@endif
