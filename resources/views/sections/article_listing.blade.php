@php
    $searchPlaceholder = $elements['search_placeholder']?->content ?? 'Search articles...';
    $columns = $settings['columns'] ?? 3;
    $showSearch = $settings['show_search'] ?? true;
    $showCategories = $settings['show_categories'] ?? true;
    $posts = $pageData['posts'] ?? collect();
    $categories = $pageData['categories'] ?? collect();
@endphp

<section class="py-16 lg:py-24" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Search --}}
        @if($showSearch)
        <div class="mb-8 lg:mb-12" data-gsap="fade-up">
            <form action="{{ url('/travel-guide') }}" method="GET" class="relative max-w-xl mx-auto">
                <label for="article-search" class="sr-only">Search articles</label>
                <input 
                    type="search" 
                    id="article-search" 
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

        {{-- Category Filters --}}
        @if($showCategories && $categories->count())
        <div class="mb-12" data-gsap="fade-up" data-gsap-delay="0.1">
            <div class="flex flex-wrap gap-3 justify-center" role="group" aria-label="Article categories">
                <a 
                    href="{{ url('/travel-guide') }}" 
                    class="filter-btn px-4 py-2 text-sm font-medium rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors {{ !request('category') ? 'bg-accent-primary text-white border-accent-primary' : '' }}"
                >
                    All Categories
                </a>
                @foreach($categories as $cat)
                <a 
                    href="{{ url('/travel-guide?category=' . urlencode($cat->slug ?? $cat->name)) }}" 
                    class="filter-btn px-4 py-2 text-sm font-medium rounded-full border border-gray-200 text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors {{ request('category') === ($cat->slug ?? $cat->name) ? 'bg-accent-primary text-white border-accent-primary' : '' }}"
                >
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $columns }} gap-6 lg:gap-8" role="list">
            @forelse($posts as $article)
            <article class="article-card group bg-white border border-gray-100 overflow-hidden flex flex-col justify-between" data-gsap="fade-up" data-gsap-delay="{{ $loop->index * 0.05 }}">
                <div>
                    @if($article->cover_image)
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                        @if($article->category)
                        <span class="absolute top-3 left-3 bg-accent-primary text-white text-xs font-semibold px-2.5 py-1">
                            {{ $article->category->name }}
                        </span>
                        @endif
                    </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-text-secondary mb-3">
                            @if($article->published_at)
                            <time datetime="{{ $article->published_at->format('c') }}">
                                {{ $article->published_at->format('M d, Y') }}
                            </time>
                            @endif
                            @if($article->author)
                            <span>·</span>
                            <span>{{ $article->author }}</span>
                            @endif
                        </div>

                        <h3 class="font-display text-xl font-bold text-text-primary mb-3">
                            <a href="{{ url('/travel-guide/' . $article->slug) }}" class="hover:text-accent-primary transition-colors">
                                {{ $article->title }}
                            </a>
                        </h3>

                        @if($article->excerpt)
                        <p class="text-text-secondary text-sm leading-relaxed mb-4">
                            {{ Str::limit(strip_tags($article->excerpt), 120) }}
                        </p>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <a href="{{ url('/travel-guide/' . $article->slug) }}" class="inline-flex items-center gap-1 text-accent-primary font-medium text-sm hover:gap-2 transition-all">
                        Read Article
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-16" data-gsap="fade-up">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-display text-xl font-bold text-text-primary mb-2">No articles found</h3>
                <p class="text-text-secondary">Try adjusting your search or category filter.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(method_exists($posts, 'links'))
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</section>

