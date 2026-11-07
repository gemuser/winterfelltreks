@php
    $heading = $elements['heading']?->content ?? 'Featured Destinations';
    $subheading = $elements['subheading']?->content ?? 'Explore the most iconic trekking regions on Earth';
    $viewAllText = $elements['view_all_text']?->content ?? 'View All Destinations';
    $viewAllUrl = $elements['view_all_url']?->content ?? url('/destinations');
    $destinations = $elements['destinations']?->getRepeaterItems() ?? [];
    if (empty($destinations)) {
        $destinations = \App\Models\Destination::published()->take(3)->get()->map(fn($d) => [
            'name' => $d->name,
            'slug' => $d->slug,
            'region' => $d->region,
            'description' => $d->short_description,
            'image' => $d->featured_image,
        ])->toArray();
    }
    $columns = (int)($settings['columns'] ?? 3);
    $colClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };
    $showViewAll = $settings['show_view_all'] ?? true;
@endphp

<section class="py-20 lg:py-28 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12" data-gsap="fade-up">
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
            @if($showViewAll && $viewAllUrl)
            <a href="{{ $viewAllUrl }}" class="btn-secondary mt-4 sm:mt-0 whitespace-nowrap self-start sm:self-auto">
                {{ $viewAllText }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $colClass }} gap-6 lg:gap-8" role="list">
            @foreach($destinations as $index => $dest)
            <article class="destination-card group relative overflow-hidden bg-white border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div>
                    @php
                        $destImg = $dest['image'] ?? '/images/dest-' . (($index % 3) + 1) . '.jpg';
                    @endphp
                    <div class="aspect-[4/3] overflow-hidden relative bg-gray-100">
                        <img src="{{ $destImg }}" alt="{{ $dest['name'] ?? 'Destination' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                    </div>
                    <div class="p-6">
                        @if(isset($dest['region']))
                        <span class="text-accent-primary text-xs font-semibold tracking-wider uppercase block mb-1">{{ $dest['region'] }}</span>
                        @endif
                        @if(isset($dest['name']))
                        <h3 class="font-display text-xl font-bold text-text-primary mb-2">
                            <a href="{{ url('/destinations/' . ($dest['slug'] ?? Str::slug($dest['name']))) }}" class="hover:text-accent-primary transition-colors">
                                {{ $dest['name'] }}
                            </a>
                        </h3>
                        @endif
                        @if(isset($dest['description']))
                        <p class="text-text-secondary text-sm leading-relaxed mb-4">{{ Str::limit(strip_tags($dest['description']), 130) }}</p>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <a href="{{ url('/destinations/' . ($dest['slug'] ?? Str::slug($dest['name']))) }}" class="inline-flex items-center gap-1 text-accent-primary font-medium text-sm hover:gap-2 transition-all">
                        Explore Destination
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
