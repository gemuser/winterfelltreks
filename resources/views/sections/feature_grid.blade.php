@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? 'Why Choose Winterfell Treks';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $subheading = $subheadingEl?->content ?? '';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'The Winterfell Difference';
    $features = $elements['features']?->getRepeaterItems() ?? [];
    $columns = (int)($settings['columns'] ?? 3);
    $colClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };
@endphp

@if($heading || !empty($features))
<section class="py-20 lg:py-28 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="max-w-3xl mb-12" data-gsap="fade-up">
            @if($badge)
            <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">{{ $badge }}</span>
            @endif
            <h2 data-key="heading" data-element-id="{{ $headingEl?->id ?? '' }}" class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p data-key="subheading" data-element-id="{{ $subheadingEl?->id ?? '' }}" class="text-text-secondary mt-3 text-base sm:text-lg">{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $colClass }} gap-6 lg:gap-8" role="list">
            @foreach($features as $index => $feature)
            <article class="feature-card p-8 bg-white border border-gray-100 shadow-sm hover:border-accent-primary/40 hover:shadow-md transition-[border-color,box-shadow] duration-300 flex flex-col justify-start" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                @if(isset($feature['icon']))
                <div class="w-12 h-12 bg-accent-primary/10 rounded-none flex items-center justify-center mb-6 text-accent-primary">
                    @php
                        $icons = [
                            'mountain' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 20h18L14 7l-4 7-2-3-5 9z"></path>',
                            'shield' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                            'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                            'compass' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm3.5 6.5l-2 5.5-5.5 2 2-5.5 5.5-2z"></path>',
                        ];
                        $iconPath = $icons[$feature['icon']] ?? $icons['mountain'];
                    @endphp
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        {!! $iconPath !!}
                    </svg>
                </div>
                @endif
                @if(isset($feature['title']))
                <h3 class="font-display text-xl font-bold text-text-primary mb-3">{{ $feature['title'] }}</h3>
                @endif
                @if(isset($feature['description']))
                <p class="text-text-secondary leading-relaxed text-sm">{{ $feature['description'] }}</p>
                @endif
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
