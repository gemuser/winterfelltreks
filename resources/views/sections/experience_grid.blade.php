@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? 'Travel Experiences';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $subheading = $subheadingEl?->content ?? 'Immersion in Sherpa culture, high-altitude photography, and Himalayan wildlife';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Authentic Immersion';

    $viewAllEl = $elements['view_all'] ?? $elements['view_all_url'] ?? $elements['view_all_text'] ?? $elements['primary_cta'] ?? $elements['cta'] ?? null;
    $viewAllData = $viewAllEl ? (is_array($viewAllEl->getContent()) ? $viewAllEl->getContent() : (is_string($viewAllEl->content) && str_starts_with(trim($viewAllEl->content), '{') ? json_decode($viewAllEl->content, true) : null)) : null;
    $viewAllText = $viewAllData['text'] ?? ($viewAllEl && is_string($viewAllEl->content) && !str_starts_with(trim($viewAllEl->content), '{') && !str_starts_with($viewAllEl->content, 'http') && !str_starts_with($viewAllEl->content, '/') ? $viewAllEl->content : 'All Experiences');
    $viewAllUrl = $viewAllData['url'] ?? ($viewAllEl && is_string($viewAllEl->content) && (str_starts_with($viewAllEl->content, 'http') || str_starts_with($viewAllEl->content, '/')) ? $viewAllEl->content : url('/experiences'));

    $experiencesEl = $elements['experiences'] ?? null;
    $experiencesId = $experiencesEl?->id ?? '';
    $experiences = $experiencesEl?->getRepeaterItems() ?? [];
    if (empty($experiences)) {
        $experiences = \App\Models\Experience::published()->take(3)->get()->map(fn($e) => [
            'name' => $e->name,
            'slug' => $e->slug,
            'description' => $e->short_description,
            'image' => $e->hero_image,
        ])->toArray();
    }
    $columns = (int)($settings['columns'] ?? 3);
    $colClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };
@endphp

<section class="py-20 lg:py-28 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12" data-gsap="fade-up">
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
            <a href="{{ $viewAllUrl }}" data-key="view_all" @if($viewAllEl) data-element-id="{{ $viewAllEl->id }}" @endif class="btn-secondary mt-4 sm:mt-0 whitespace-nowrap self-start sm:self-auto">
                {{ $viewAllText }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $colClass }} gap-6 lg:gap-8" role="list">
            @foreach($experiences as $index => $exp)
            <article class="experience-card group relative overflow-hidden bg-white border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div>
                    @php
                        $expImg = $exp['image'] ?? '/images/exp-' . (($index % 3) + 1) . '.jpg';
                    @endphp
                    <div class="aspect-[4/3] overflow-hidden relative bg-gray-100">
                        <img src="{{ $expImg }}" alt="{{ $exp['name'] ?? 'Experience' }}" 
                             data-repeater-id="{{ $experiencesId }}" data-repeater-key="experiences" data-repeater-index="{{ $index }}" data-repeater-field="image"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 cursor-pointer" loading="lazy" decoding="async">
                    </div>
                    <div class="p-6">
                        @if(isset($exp['name']))
                        <h3 data-repeater-id="{{ $experiencesId }}" data-repeater-key="experiences" data-repeater-index="{{ $index }}" data-repeater-field="name" class="font-display text-xl font-bold text-text-primary mb-2">
                            <a href="{{ url('/experiences/' . ($exp['slug'] ?? Str::slug($exp['name']))) }}" class="hover:text-accent-primary transition-colors">
                                {{ $exp['name'] }}
                            </a>
                        </h3>
                        @endif
                        @if(isset($exp['description']))
                        <p data-repeater-id="{{ $experiencesId }}" data-repeater-key="experiences" data-repeater-index="{{ $index }}" data-repeater-field="description" class="text-text-secondary text-sm line-clamp-2 leading-relaxed">
                            {{ $exp['description'] }}
                        </p>
                        @endif
                    </div>
                </div>
                <div class="p-6 pt-0">
                    <a href="{{ url('/experiences/' . ($exp['slug'] ?? Str::slug($exp['name'] ?? ''))) }}" class="text-accent-primary text-sm font-semibold hover:underline inline-flex items-center gap-1">
                        Discover &rarr;
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
