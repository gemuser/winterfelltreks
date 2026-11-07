@php
    $searchPlaceholder = $elements['search_placeholder']?->content ?? 'Search packages...';
    $columns = $settings['columns'] ?? 3;
    $showSearch = $settings['show_search'] ?? true;
    $showFilters = $settings['show_filters'] ?? true;
    $expeditions = $pageData['expeditions'] ?? collect();
    $regions = $pageData['regions'] ?? collect();
@endphp

<section class="py-20 lg:py-32" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Search --}}
        @if($showSearch)
        <div class="mb-8 lg:mb-12" data-gsap="fade-up">
            <form action="{{ url('/packages') }}" method="GET" class="relative max-w-xl mx-auto">
                <label for="package-search" class="sr-only">Search tour packages</label>
                <input 
                    type="search" 
                    id="package-search" 
                    name="search" 
                    placeholder="{{ $searchPlaceholder }}"
                    value="{{ request('search') }}"
                    class="form-input w-full pl-12 pr-4 py-3.5 text-lg"
                    autocomplete="off"
                >
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
        @endif

        {{-- Filters --}}
        @if($showFilters && $regions->count())
        <div class="mb-8 lg:mb-12" data-gsap="fade-up" data-gsap-delay="0.1">
            <div class="flex flex-wrap gap-3 justify-center" role="group" aria-label="Region filters">
                <a 
                    href="{{ url('/packages') }}" 
                    class="filter-btn px-4 py-2 text-sm font-medium rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors {{ !request('region') ? 'bg-accent-primary text-white border-accent-primary' : '' }}"
                >
                    All Regions
                </a>
                @foreach($regions as $r)
                <a 
                    href="{{ url('/packages?region=' . urlencode($r)) }}" 
                    class="filter-btn px-4 py-2 text-sm font-medium rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors {{ request('region') === $r ? 'bg-accent-primary text-white border-accent-primary' : '' }}"
                >
                    {{ $r }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $columns }} gap-6 lg:gap-8" role="list">
            @forelse($expeditions as $pkg)
            <article class="package-card group relative overflow-hidden bg-white border border-gray-100 flex flex-col justify-between" data-gsap="fade-up" data-gsap-delay="{{ $loop->index * 0.05 }}">
                <div>
                    @if($pkg->featured_image)
                    <div class="aspect-[4/3] overflow-hidden relative">
                        <img src="{{ $pkg->featured_image }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                        @if($pkg->max_elevation)
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1.5 text-xs font-semibold text-text-primary rounded-none shadow-sm">
                            {{ $pkg->max_elevation }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-2 text-xs text-text-secondary mb-3">
                            @if($pkg->region)
                            <span class="px-2.5 py-1 bg-gray-100 uppercase tracking-wider font-medium text-accent-primary">{{ $pkg->region }}</span>
                            @endif
                            @if($pkg->duration_days)
                            <span class="px-2.5 py-1 bg-gray-100 font-medium">{{ $pkg->duration_days }} Days</span>
                            @endif
                            @if($pkg->technical_grade)
                            <span class="px-2.5 py-1 bg-gray-100 font-medium">{{ $pkg->technical_grade }}</span>
                            @endif
                        </div>

                        <h3 class="font-display text-xl font-bold text-text-primary mb-2">
                            <a href="{{ url('/packages/' . $pkg->slug) }}" class="hover:text-accent-primary transition-colors">
                                {{ $pkg->title }}
                            </a>
                        </h3>

                        @if($pkg->route_overview)
                        <p class="text-text-secondary text-sm leading-relaxed mb-4">
                            {{ Str::limit(strip_tags($pkg->route_overview), 130) }}
                        </p>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-gray-50 flex gap-3">
                    <a href="{{ url('/packages/' . $pkg->slug) }}" class="btn-primary flex-1 text-center py-2.5 text-sm">
                        View Details
                    </a>
                    <a href="{{ url('/enquiry?package_slug=' . $pkg->slug) }}" class="btn-secondary flex-1 text-center py-2.5 text-sm">
                        Enquire Now
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-16" data-gsap="fade-up">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-display text-xl font-bold text-text-primary mb-2">No packages found</h3>
                <p class="text-text-secondary">Try adjusting your search or filters.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(method_exists($expeditions, 'links'))
        <div class="mt-12">
            {{ $expeditions->links() }}
        </div>
        @endif
    </div>
</section>

