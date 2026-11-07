@php
    $heading = $elements['heading']?->content ?? $elements['headline']?->content ?? $elements['title']?->content ?? 'Frequently Asked Questions';
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $subheading = $elements['subheading']?->content ?? $elements['subheadline']?->content ?? $elements['subtitle']?->content ?? 'Everything you need to know about preparing, booking, and trekking in the Himalayas.';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Clarity & Preparation';
    
    // Grouped FAQs
    $faqs = $pageData['faqs'] ?? null;
    if (!$faqs || (is_countable($faqs) && count($faqs) === 0)) {
        $faqs = \App\Models\FAQ::published()->orderBy('sort_order')->get()->groupBy('category');
    }
    
    $definedCategories = $elements['categories']?->getContent();
    if (!empty($definedCategories) && is_array($definedCategories)) {
        $categories = $definedCategories;
    } elseif ($faqs instanceof \Illuminate\Support\Collection && $faqs->isNotEmpty()) {
        $categories = $faqs->keys()->toArray();
    } else {
        $categories = ['General', 'Trekking & Expeditions', 'Preparation & Gear', 'Booking & Safety'];
    }
@endphp

@if($faqs && $faqs->isNotEmpty())
<section class="py-20 lg:py-32 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="text-center mb-12 sm:mb-16" data-gsap="fade-up">
            @if($badge)
            <span class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2" data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif>{{ $badge }}</span>
            @endif
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight" data-key="heading" @if($headingEl) data-element-id="{{ $headingEl->id }}" @endif>
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-base sm:text-lg max-w-2xl mx-auto" data-key="subheading" @if($subheadingEl) data-element-id="{{ $subheadingEl->id }}" @endif>{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="space-y-12" x-data="{ openCategory: '{{ $categories[0] ?? '' }}' }">
            @if(count($categories) > 1)
            <div class="flex flex-wrap justify-center gap-2 mb-8" data-gsap="fade-up">
                @foreach($categories as $category)
                @if(isset($faqs[$category]) && $faqs[$category]->count())
                <button 
                    type="button" 
                    @click="openCategory = '{{ $category }}'"
                    :class="openCategory === '{{ $category }}' ? 'bg-accent-primary text-white border-accent-primary shadow-sm' : 'bg-white text-text-secondary border-gray-200 hover:border-accent-primary/50'"
                    class="px-5 py-2.5 text-xs sm:text-sm font-semibold border transition-all duration-200 cursor-pointer"
                >
                    {{ $category }} ({{ $faqs[$category]->count() }})
                </button>
                @endif
                @endforeach
            </div>
            @endif

            @foreach($categories as $category)
            @if(isset($faqs[$category]) && $faqs[$category]->count())
            <div x-show="openCategory === '{{ $category }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                <div class="divide-y divide-gray-200 border-y border-gray-200 bg-white shadow-sm" x-data="{ activeAccordion: null }">
                    @foreach($faqs[$category] as $faq)
                    <div class="transition-colors duration-200">
                        <button 
                            type="button" 
                            @click="activeAccordion = (activeAccordion === {{ $faq->id }} ? null : {{ $faq->id }})"
                            class="w-full text-left py-5 px-6 sm:px-8 flex justify-between items-center focus:outline-none cursor-pointer"
                            :aria-expanded="(activeAccordion === {{ $faq->id }}).toString()"
                        >
                            <span class="font-display text-base sm:text-lg font-bold text-text-primary pr-4">
                                {{ $faq->question }}
                            </span>
                            <span class="text-accent-primary shrink-0 transition-transform duration-200" :class="{ 'rotate-180': activeAccordion === {{ $faq->id }} }">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </button>
                        <div 
                            x-show="activeAccordion === {{ $faq->id }}" 
                            x-cloak 
                            x-collapse
                            class="px-6 sm:px-8 pb-6 text-text-secondary text-sm sm:text-base leading-relaxed prose prose-sm max-w-none border-t border-gray-100 pt-4"
                        >
                            {!! $faq->answer !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
