@php
    $heading = $elements['heading']?->content ?? 'Frequently Asked Questions';
    $subheading = $elements['subheading']?->content ?? 'Everything you need to know about preparing, booking, and trekking in the Himalayas.';
    
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
            <span class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">Clarity & Preparation</span>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-base sm:text-lg max-w-2xl mx-auto">{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="space-y-12" x-data="{ openCategory: '{{ $categories[0] ?? '' }}' }">
            {{-- Category Filter Pills (if multiple) --}}
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

            {{-- Category Accordion Blocks --}}
            @foreach($categories as $category)
            @if(isset($faqs[$category]) && $faqs[$category]->count())
            <div 
                class="faq-category-block" 
                x-show="openCategory === '{{ $category }}' || openCategory === ''" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                data-gsap="fade-up"
            >
                <h3 class="font-display text-xl sm:text-2xl font-bold text-text-primary mb-6 pb-3 border-b-2 border-accent-primary flex items-center justify-between">
                    <span>{{ $category }}</span>
                    <span class="text-xs font-mono text-text-muted font-normal uppercase">{{ $faqs[$category]->count() }} questions</span>
                </h3>
                
                <div class="space-y-4">
                    @foreach($faqs[$category] as $index => $faq)
                    <details class="faq-item group bg-white border border-gray-200 overflow-hidden shadow-sm hover:border-gray-300 transition-colors">
                        <summary class="flex items-center justify-between p-6 cursor-pointer list-none [&::-webkit-details-marker]:hidden bg-white hover:bg-gray-50/70 transition-colors select-none">
                            <h4 class="font-display text-base sm:text-lg font-bold text-text-primary pr-6 leading-snug">
                                {{ $faq->question }}
                            </h4>
                            <div class="w-8 h-8 rounded-full bg-gray-100 group-hover:bg-accent-primary/10 flex items-center justify-center flex-shrink-0 transition-colors">
                                <svg class="w-4 h-4 text-accent-primary transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </summary>
                        <div class="px-6 pb-6 pt-2 text-text-secondary leading-relaxed border-t border-gray-100 prose prose-sm max-w-none">
                            {!! $faq->answer !!}
                        </div>
                    </details>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Contact Support Card --}}
        <div class="mt-16 bg-white border border-gray-200 p-8 sm:p-10 text-center shadow-sm" data-gsap="fade-up">
            <h3 class="font-display text-2xl font-bold text-text-primary mb-2">Have a specific question not covered here?</h3>
            <p class="text-text-secondary text-sm sm:text-base max-w-xl mx-auto mb-6">
                Our Kathmandu-based mountain specialists are available to answer gear questions, permits, fitness guidelines, and custom itinerary options.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/enquiry') }}" class="btn-primary">
                    Ask an Expert
                </a>
                <a href="{{ url('/contact') }}" class="btn-secondary">
                    View Contact Details
                </a>
            </div>
        </div>
    </div>
</section>
@endif
