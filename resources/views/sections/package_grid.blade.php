@php
    $heading = $elements['heading']?->content ?? 'Featured Expeditions';
    $subheading = $elements['subheading']?->content ?? 'Curated adventures for every level of mountaineer and trekker';
    $viewAllText = $elements['view_all_text']?->content ?? 'View All Packages';
    $viewAllUrl = $elements['view_all_url']?->content ?? url('/packages');
    $packages = $elements['packages']?->getRepeaterItems() ?? [];
    if (empty($packages)) {
        $packages = \App\Models\Expedition::published()->take(3)->get()->map(fn($p) => [
            'name' => $p->title,
            'slug' => $p->slug,
            'destination' => $p->region,
            'duration' => ($p->duration_days ? $p->duration_days . ' Days' : ''),
            'description' => $p->route_overview,
            'image' => $p->featured_image,
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

<section class="py-20 lg:py-28 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
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
            @foreach($packages as $index => $pkg)
            <article class="package-card group relative overflow-hidden bg-white border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div>
                    @php
                        $pkgImg = $pkg['image'] ?? '/images/pkg-' . (($index % 3) + 1) . '.jpg';
                    @endphp
                    <div class="aspect-[4/3] overflow-hidden relative bg-gray-100">
                        <img src="{{ $pkgImg }}" alt="{{ $pkg['name'] ?? 'Package' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                        @if(isset($pkg['price']) && !empty($pkg['price']))
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1 text-xs font-semibold text-text-primary shadow-sm">
                            {{ $pkg['price'] }}
                        </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-2 text-xs text-text-secondary mb-3">
                            @if(isset($pkg['destination']))
                            <span class="px-2 py-0.5 bg-gray-100 text-accent-primary font-medium uppercase tracking-wider">{{ $pkg['destination'] }}</span>
                            @endif
                            @if(isset($pkg['duration']))
                            <span class="px-2 py-0.5 bg-gray-100 font-medium">{{ $pkg['duration'] }}</span>
                            @endif
                        </div>

                        @if(isset($pkg['name']))
                        <h3 class="font-display text-xl font-bold text-text-primary mb-2">
                            <a href="{{ url('/packages/' . ($pkg['slug'] ?? Str::slug($pkg['name']))) }}" class="hover:text-accent-primary transition-colors">
                                {{ $pkg['name'] }}
                            </a>
                        </h3>
                        @endif

                        @if(isset($pkg['description']))
                        <p class="text-text-secondary text-sm leading-relaxed mb-4">{{ Str::limit(strip_tags($pkg['description']), 130) }}</p>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-gray-50 flex gap-3">
                    <a href="{{ url('/packages/' . ($pkg['slug'] ?? Str::slug($pkg['name'] ?? ''))) }}" class="btn-primary flex-1 text-center py-2.5 text-sm">
                        View Details
                    </a>
                    <a href="{{ url('/enquiry?package_slug=' . ($pkg['slug'] ?? '')) }}" class="btn-secondary flex-1 text-center py-2.5 text-sm">
                        Enquire Now
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
