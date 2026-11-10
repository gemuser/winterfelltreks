@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? 'Travel Journal';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $subheading = $subheadingEl?->content ?? 'Stories, guides, and inspiration from the heart of the Himalayas.';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Expedition Journal';

    // View All Button
    $viewAllEl = $elements['view_all'] ?? $elements['view_all_url'] ?? $elements['view_all_text'] ?? $elements['primary_cta'] ?? $elements['cta'] ?? null;
    $viewAllData = $viewAllEl ? (is_array($viewAllEl->getContent()) ? $viewAllEl->getContent() : (is_string($viewAllEl->content) && str_starts_with(trim($viewAllEl->content), '{') ? json_decode($viewAllEl->content, true) : null)) : null;
    $viewAllText = $viewAllData['text'] ?? ($viewAllEl && is_string($viewAllEl->content) && !str_starts_with(trim($viewAllEl->content), '{') && !str_starts_with($viewAllEl->content, 'http') && !str_starts_with($viewAllEl->content, '/') ? $viewAllEl->content : ($elements['view_all_text']?->content ?? 'Read All Articles'));
    $viewAllUrl = $viewAllData['url'] ?? ($viewAllEl && is_string($viewAllEl->content) && (str_starts_with($viewAllEl->content, 'http') || str_starts_with($viewAllEl->content, '/')) ? $viewAllEl->content : ($elements['view_all_url']?->content ?? url('/travel-guide')));

    $articlesEl = $elements['articles'] ?? null;
    $articlesId = $articlesEl?->id ?? '';
    $articles = $articlesEl?->getRepeaterItems() ?? [];
    $columns = (int)($settings['columns'] ?? 3);
    $showViewAll = $settings['show_view_all'] ?? true;

    $gridClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };

    $allArticles = !empty($articles) ? $articles : ($pageData['posts'] ?? null);
    if (empty($allArticles) || (is_countable($allArticles) && count($allArticles) === 0)) {
        $allArticles = \App\Models\BlogPost::published()->with('category')->latest('published_at')->take($columns ?: 3)->get();
    }
@endphp

@if(!empty($allArticles) && count($allArticles) > 0)
<section class="py-20 lg:py-32 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12" data-gsap="fade-up">
            <div>
                @if($badge)
                <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">{{ $badge }}</span>
                @endif
                @if($heading)
                <h2 data-key="heading" data-element-id="{{ $headingEl?->id ?? '' }}" class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                    {{ $heading }}
                </h2>
                @endif
                @if($subheading)
                <p data-key="subheading" data-element-id="{{ $subheadingEl?->id ?? '' }}" class="text-text-secondary mt-3 text-base sm:text-lg max-w-2xl">{{ $subheading }}</p>
                @endif
            </div>
            @if($showViewAll && $viewAllUrl)
            <a href="{{ $viewAllUrl }}" data-key="view_all" @if($viewAllEl) data-element-id="{{ $viewAllEl->id }}" @endif class="btn-secondary mt-4 sm:mt-0 whitespace-nowrap self-start sm:self-auto">
                {{ $viewAllText }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-6 lg:gap-8" role="list">
            @foreach($allArticles as $index => $article)
            @php
                $isModel = $article instanceof \App\Models\BlogPost;
                $title = $isModel ? $article->title : ($article['title'] ?? 'Himalayan Journal');
                $slug = $isModel ? $article->slug : ($article['slug'] ?? Str::slug($title));
                $coverImage = $isModel ? $article->cover_image : ($article['cover_image'] ?? null);
                if (!$coverImage) {
                    $coverImage = '/images/blog-' . (($index % 3) + 1) . '.jpg';
                }
                $categoryName = $isModel ? $article->category?->name : ($article['category'] ?? 'Travel');
                $excerpt = $isModel ? $article->excerpt : ($article['excerpt'] ?? null);
                $author = $isModel ? $article->author : ($article['author'] ?? 'Winterfell Team');
                $dateFormatted = null;
                if ($isModel && $article->published_at) {
                    $dateFormatted = $article->published_at->format('M d, Y');
                } elseif (isset($article['date'])) {
                    $dateFormatted = date('M d, Y', strtotime($article['date']));
                } else {
                    $dateFormatted = date('M d, Y');
                }
            @endphp
            <article class="article-card group bg-white border border-gray-100 flex flex-col h-full hover:shadow-xl transition-shadow duration-300" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div class="aspect-[16/10] overflow-hidden relative bg-gray-100 flex-shrink-0">
                    <img src="{{ $coverImage }}" alt="{{ $title }}" 
                         @if(!$isModel) data-repeater-id="{{ $articlesId }}" data-repeater-key="articles" data-repeater-index="{{ $index }}" data-repeater-field="cover_image" @endif
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 cursor-pointer" loading="lazy" decoding="async">
                    @if($categoryName)
                    <span @if(!$isModel) data-repeater-id="{{ $articlesId }}" data-repeater-key="articles" data-repeater-index="{{ $index }}" data-repeater-field="category" @endif class="absolute top-3 left-3 bg-accent-primary text-white text-xs font-semibold px-2.5 py-1 tracking-wider uppercase">
                        {{ $categoryName }}
                    </span>
                    @endif
                </div>
                <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-medium text-text-muted mb-3">
                            <time>{{ $dateFormatted }}</time>
                            <span>·</span>
                            <span @if(!$isModel) data-repeater-id="{{ $articlesId }}" data-repeater-key="articles" data-repeater-index="{{ $index }}" data-repeater-field="author" @endif>{{ $author }}</span>
                        </div>
                        <h3 @if(!$isModel) data-repeater-id="{{ $articlesId }}" data-repeater-key="articles" data-repeater-index="{{ $index }}" data-repeater-field="title" @endif class="font-display text-xl font-bold text-text-primary mb-3 leading-snug">
                            <a href="{{ url('/travel-guide/' . $slug) }}" class="hover:text-accent-primary transition-colors">
                                {{ $title }}
                            </a>
                        </h3>
                        @if($excerpt)
                        <p @if(!$isModel) data-repeater-id="{{ $articlesId }}" data-repeater-key="articles" data-repeater-index="{{ $index }}" data-repeater-field="excerpt" @endif class="text-text-secondary text-sm leading-relaxed mb-6">{{ Str::limit(strip_tags($excerpt), 120) }}</p>
                        @endif
                    </div>
                    <div class="pt-4 border-t border-gray-100 mt-auto">
                        <a href="{{ url('/travel-guide/' . $slug) }}" class="text-sm font-semibold text-accent-primary hover:text-accent-light inline-flex items-center gap-1 group/link">
                            Read Journal
                            <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
