@php
    $columns = $settings['columns'] ?? 3;
    $showFilters = $settings['show_filters'] ?? true;
    $experiences = $pageData['experiences'] ?? collect();
    $categories = $pageData['categories'] ?? collect();
@endphp

<section class="py-20 lg:py-32" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $heading = $elements['heading']?->content ?? $elements['headline']?->content ?? $elements['title']?->content ?? null;
            $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
            $subheading = $elements['subheading']?->content ?? $elements['subheadline']?->content ?? $elements['subtitle']?->content ?? null;
            $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
            $badge = $elements['badge']?->content ?? $elements['tag']?->content ?? null;
            $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
        @endphp

        @if($heading || $subheading || $badge)
        <div class="text-center max-w-3xl mx-auto mb-10" data-gsap="fade-up">
            @if($badge)
            <span class="inline-block text-accent-primary text-xs uppercase tracking-widest font-semibold mb-3" data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif>
                {{ $badge }}
            </span>
            @endif
            @if($heading)
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight mb-4" data-key="heading" @if($headingEl) data-element-id="{{ $headingEl->id }}" @endif>
                {{ $heading }}
            </h2>
            @endif
            @if($subheading)
            <p class="text-text-secondary text-base sm:text-lg leading-relaxed" data-key="subheading" @if($subheadingEl) data-element-id="{{ $subheadingEl->id }}" @endif>
                {{ $subheading }}
            </p>
            @endif
        </div>
        @endif

        {{-- Filters --}}
        @if($showFilters && $categories->count())
        <div class="mb-12" data-gsap="fade-up">
            <div class="flex flex-wrap gap-3 justify-center" role="group" aria-label="Experience categories">
                <a 
                    href="{{ url('/experiences') }}" 
                    class="filter-btn px-4 py-2 text-sm font-medium rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors {{ !request('category') ? 'bg-accent-primary text-white border-accent-primary' : '' }}"
                >
                    All Experiences
                </a>
                @foreach($categories as $cat)
                <a 
                    href="{{ url('/experiences?category=' . urlencode($cat)) }}" 
                    class="filter-btn px-4 py-2 text-sm font-medium rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors {{ request('category') === $cat ? 'bg-accent-primary text-white border-accent-primary' : '' }}"
                >
                    {{ $cat }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $columns }} gap-6 lg:gap-8" role="list">
            @forelse($experiences as $exp)
            <article class="experience-card group relative overflow-hidden bg-white border border-gray-100 flex flex-col justify-between" data-gsap="fade-up" data-gsap-delay="{{ $loop->index * 0.05 }}">
                <div>
                    @if($exp->hero_image)
                    <div class="aspect-[4/3] overflow-hidden relative">
                        <img src="{{ $exp->hero_image }}" alt="{{ $exp->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                        @if($exp->category)
                        <div class="absolute top-4 left-4 bg-accent-primary text-white px-3 py-1 text-xs font-semibold uppercase tracking-wider">
                            {{ $exp->category }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="p-6">
                        @if($exp->recommended_duration)
                        <span class="text-xs text-text-secondary font-medium tracking-wide uppercase block mb-2">{{ $exp->recommended_duration }}</span>
                        @endif

                        <h3 class="font-display text-xl font-bold text-text-primary mb-2">
                            <a href="{{ url('/experiences/' . $exp->slug) }}" class="hover:text-accent-primary transition-colors">
                                {{ $exp->name }}
                            </a>
                        </h3>

                        @if($exp->short_description)
                        <p class="text-text-secondary text-sm leading-relaxed mb-4">
                            {{ Str::limit(strip_tags($exp->short_description), 130) }}
                        </p>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-gray-50 flex gap-3">
                    <a href="{{ url('/experiences/' . $exp->slug) }}" class="btn-primary flex-1 text-center py-2.5 text-sm">
                        View Details
                    </a>
                    <a href="{{ url('/enquiry?experience_slug=' . $exp->slug) }}" class="btn-secondary flex-1 text-center py-2.5 text-sm">
                        Enquire Now
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-16" data-gsap="fade-up">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-display text-xl font-bold text-text-primary mb-2">No experiences found</h3>
                <p class="text-text-secondary">Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(method_exists($experiences, 'links'))
        <div class="mt-12">
            {{ $experiences->links() }}
        </div>
        @endif
    </div>
</section>

