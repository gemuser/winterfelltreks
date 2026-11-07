@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? 'Himalayan Gallery';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $subheading = $subheadingEl?->content ?? '';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Visual Chronicles';

    $viewAllEl = $elements['view_all'] ?? $elements['view_all_url'] ?? $elements['view_all_text'] ?? $elements['primary_cta'] ?? $elements['cta'] ?? null;
    $viewAllData = $viewAllEl ? (is_array($viewAllEl->getContent()) ? $viewAllEl->getContent() : (is_string($viewAllEl->content) && str_starts_with(trim($viewAllEl->content), '{') ? json_decode($viewAllEl->content, true) : null)) : null;
    $viewAllText = $viewAllData['text'] ?? ($viewAllEl && is_string($viewAllEl->content) && !str_starts_with(trim($viewAllEl->content), '{') && !str_starts_with($viewAllEl->content, 'http') && !str_starts_with($viewAllEl->content, '/') ? $viewAllEl->content : ($elements['view_all_text']?->content ?? 'View Full Gallery'));
    $viewAllUrl = $viewAllData['url'] ?? ($viewAllEl && is_string($viewAllEl->content) && (str_starts_with($viewAllEl->content, 'http') || str_starts_with($viewAllEl->content, '/')) ? $viewAllEl->content : ($elements['view_all_url']?->content ?? url('/gallery')));

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
@endphp

@if(!empty($allImages) && count($allImages))
<section class="py-16 lg:py-24 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10" data-gsap="fade-up">
            <div>
                @if($badge)
                <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">{{ $badge }}</span>
                @endif
                @if($heading)
                <h2 data-key="heading" data-element-id="{{ $headingEl?->id ?? '' }}" class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight">
                    {{ $heading }}
                </h2>
                @endif
                @if($subheading)
                <p data-key="subheading" data-element-id="{{ $subheadingEl?->id ?? '' }}" class="text-text-secondary mt-2 text-lg">{{ $subheading }}</p>
                @endif
            </div>
            @if($showViewAll && $viewAllUrl && !request()->is('gallery'))
            <a href="{{ $viewAllUrl }}" data-key="view_all" @if($viewAllEl) data-element-id="{{ $viewAllEl->id }}" @endif class="btn-secondary mt-4 sm:mt-0 whitespace-nowrap self-start sm:self-auto">
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
                class="px-4 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full border border-gray-200 transition-colors bg-white text-text-secondary hover:border-accent-primary hover:text-accent-primary"
                onclick="window.location.href='{{ url('/gallery?category=' . urlencode($cat->name)) }}'">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Masonry Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $colClass }} gap-4 sm:gap-6" role="list">
            @foreach($allImages as $index => $img)
            @php
                $isModel = $img instanceof \App\Models\GalleryImage;
                $imageUrl = $isModel ? $img->image_url : ($img['url'] ?? $img['image_url'] ?? '');
                $caption = $isModel ? $img->caption : ($img['caption'] ?? '');
                $category = $isModel ? $img->category?->name : ($img['category'] ?? '');
            @endphp
            <div class="gallery-item group relative overflow-hidden bg-gray-100 aspect-[4/3] shadow-sm hover:shadow-md transition-shadow" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.04 }}">
                <img src="{{ $imageUrl }}" alt="{{ $caption ?: 'Himalayan mountain scenery' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    @if($category)
                    <span class="text-accent-primary text-xs font-semibold uppercase tracking-wider mb-1">{{ $category }}</span>
                    @endif
                    @if($caption)
                    <p class="text-white text-sm font-medium line-clamp-2">{{ $caption }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
