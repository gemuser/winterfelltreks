@php
    $heading = $elements['heading']?->content ?? 'Travel Experiences';
    $subheading = $elements['subheading']?->content ?? 'Immersion in Sherpa culture, high-altitude photography, and Himalayan wildlife';
    $experiences = $elements['experiences']?->getRepeaterItems() ?? [];
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
                @if($heading)
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight">
                    {{ $heading }}
                </h2>
                @endif
                @if($subheading)
                <p class="text-text-secondary mt-2 text-lg">{{ $subheading }}</p>
                @endif
            </div>
            <a href="{{ url('/experiences') }}" class="btn-secondary mt-4 sm:mt-0 whitespace-nowrap self-start sm:self-auto">
                All Experiences
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
                        <img src="{{ $expImg }}" alt="{{ $exp['name'] ?? 'Experience' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                    </div>
                    <div class="p-6">
                        @if(isset($exp['name']))
                        <h3 class="font-display text-xl font-bold text-text-primary mb-2">
                            <a href="{{ url('/experiences/' . ($exp['slug'] ?? Str::slug($exp['name']))) }}" class="hover:text-accent-primary transition-colors">
                                {{ $exp['name'] }}
                            </a>
                        </h3>
                        @endif
                        @if(isset($exp['description']))
                        <p class="text-text-secondary text-sm leading-relaxed mb-4">{{ Str::limit(strip_tags($exp['description']), 130) }}</p>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <a href="{{ url('/experiences/' . ($exp['slug'] ?? Str::slug($exp['name'] ?? ''))) }}" class="inline-flex items-center gap-1 text-accent-primary font-medium text-sm hover:gap-2 transition-all">
                        Explore Experience
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
