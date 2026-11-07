@php
    $searchPlaceholder = $elements['search_placeholder']?->content ?? 'Search destinations...';
    $columns = (int)($settings['columns'] ?? 3);
    $showSearch = $settings['show_search'] ?? true;
    $showFilters = $settings['show_filters'] ?? true;
    $destinations = $pageData['destinations'] ?? collect();
    $categories = $pageData['categories'] ?? collect();

    $gridClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };
@endphp

<section class="py-20 lg:py-32 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
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

        {{-- Search --}}
        @if($showSearch)
        <div class="mb-8 lg:mb-12" data-gsap="fade-up">
            <form action="{{ url('/destinations') }}" method="GET" class="relative max-w-xl mx-auto">
                <label for="destination-search" class="sr-only">Search destinations</label>
                <input 
                    type="search" 
                    id="destination-search" 
                    name="search" 
                    placeholder="{{ $searchPlaceholder }}"
                    value="{{ request('search') }}"
                    class="form-input w-full pl-12 pr-4 py-3.5 text-base sm:text-lg bg-white shadow-sm border border-gray-200"
                    autocomplete="off"
                >
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
        @endif

        {{-- Filters --}}
        @if($showFilters && $categories->count())
        <div class="mb-8 lg:mb-12" data-gsap="fade-up" data-gsap-delay="0.1">
            <div class="flex flex-wrap gap-2.5 justify-center" role="group" aria-label="Destination filters">
                <button 
                    type="button" 
                    class="filter-btn px-5 py-2 text-xs sm:text-sm font-semibold border transition-all duration-200 {{ !request('category') ? 'bg-accent-primary text-white border-accent-primary' : 'bg-white text-text-secondary border-gray-200 hover:border-accent-primary' }}"
                    data-category=""
                    onclick="filterCategory('')"
                >
                    All Destinations
                </button>
                @foreach($categories as $cat)
                <button 
                    type="button" 
                    class="filter-btn px-5 py-2 text-xs sm:text-sm font-semibold border transition-all duration-200 {{ request('category') === $cat ? 'bg-accent-primary text-white border-accent-primary' : 'bg-white text-text-secondary border-gray-200 hover:border-accent-primary' }}"
                    data-category="{{ $cat }}"
                    onclick="filterCategory('{{ $cat }}')"
                >
                    {{ $cat }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Results --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-6 lg:gap-8" role="list" id="destinations-grid">
            @forelse($destinations as $dest)
            @php
                $img = $dest->featured_image ?: '/images/dest-' . (($loop->index % 3) + 1) . '.jpg';
            @endphp
            <article class="destination-card group bg-white border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-xl transition-shadow duration-300" data-category="{{ $dest->category }}" data-gsap="fade-up" data-gsap-delay="{{ $loop->index * 0.05 }}">
                <div class="aspect-[4/3] overflow-hidden relative bg-gray-100 flex-shrink-0">
                    <img src="{{ $img }}" alt="{{ $dest->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                    @if($dest->region || $dest->country)
                    <span class="absolute top-3 left-3 bg-gray-950/80 backdrop-blur text-white text-xs font-semibold px-2.5 py-1 uppercase tracking-wider">
                        {{ $dest->region ?? $dest->country }}
                    </span>
                    @endif
                </div>
                <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-display text-xl sm:text-2xl font-bold text-text-primary mb-3">
                            <a href="{{ url('/destinations/' . $dest->slug) }}" class="hover:text-accent-primary transition-colors">
                                {{ $dest->name }}
                            </a>
                        </h3>
                        @if($dest->short_description)
                        <p class="text-text-secondary text-sm leading-relaxed mb-6">{{ Str::limit(strip_tags($dest->short_description), 130) }}</p>
                        @endif
                    </div>
                    <div class="pt-4 border-t border-gray-100 mt-auto flex items-center justify-between">
                        <a href="{{ url('/destinations/' . $dest->slug) }}" class="inline-flex items-center gap-1.5 text-accent-primary font-semibold text-sm hover:gap-2.5 transition-all">
                            Explore Destination
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @if($dest->best_time_to_visit)
                        <span class="text-xs text-text-muted font-medium flex items-center gap-1" title="{{ strip_tags($dest->best_time_to_visit) }}">
                            <svg class="w-3.5 h-3.5 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ Str::limit(strip_tags($dest->best_time_to_visit), 30) }}
                        </span>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-16 bg-white border border-gray-200" data-gsap="fade-up">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-display text-xl font-bold text-text-primary mb-2">No destinations found</h3>
                <p class="text-text-secondary mb-6">Try adjusting your search criteria or explore our popular trekking regions.</p>
                <a href="{{ url('/destinations') }}" class="btn-primary">View All Destinations</a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(method_exists($destinations, 'hasPages') && $destinations->hasPages())
        <div class="mt-12" data-gsap="fade-up">
            {{ $destinations->links() }}
        </div>
        @endif
    </div>
</section>

<script>
    function filterCategory(category) {
        const buttons = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('#destinations-grid .destination-card');
        
        buttons.forEach(btn => {
            if (btn.dataset.category === category) {
                btn.classList.add('bg-accent-primary', 'text-white', 'border-accent-primary');
                btn.classList.remove('bg-white', 'text-text-secondary', 'border-gray-200');
            } else {
                btn.classList.remove('bg-accent-primary', 'text-white', 'border-accent-primary');
                btn.classList.add('bg-white', 'text-text-secondary', 'border-gray-200');
            }
        });
        
        cards.forEach(card => {
            if (!category || card.dataset.category === category) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update URL without reload
        const url = new URL(window.location);
        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }
        window.history.replaceState({}, '', url);
    }
    
    // Initialize from URL on load
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const category = params.get('category') || '';
        if (category) {
            filterCategory(category);
        }
    });
</script>
