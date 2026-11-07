@php
    $heading = $elements['heading']?->content ?? $elements['headline']?->content ?? $elements['title']?->content ?? 'Words From Fellow Mountaineers';
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $subheading = $elements['subheading']?->content ?? $elements['subheadline']?->content ?? $elements['subtitle']?->content ?? 'Real reviews and expedition stories from travelers who explored the Himalayas with us';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Field Dispatches';

    $testimonials = $elements['testimonials']?->getRepeaterItems() ?? [];
    if (empty($testimonials)) {
        if (isset($pageData['testimonials']) && is_object($pageData['testimonials'])) {
            $testimonials = $pageData['testimonials']->toArray();
        } else {
            $testimonials = \App\Models\Testimonial::published()->take(6)->get()->toArray();
        }
    }
    $autoplay = $settings['autoplay'] ?? true;
    $interval = $settings['interval'] ?? 6000;
@endphp

@if(!empty($testimonials))
<section class="py-20 lg:py-28 bg-gray-50 overflow-hidden" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="text-center max-w-3xl mx-auto mb-14" data-gsap="fade-up">
            @if($badge)
            <span class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2" data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif>{{ $badge }}</span>
            @endif
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight" data-key="heading" @if($headingEl) data-element-id="{{ $headingEl->id }}" @endif>
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-lg" data-key="subheading" @if($subheadingEl) data-element-id="{{ $subheadingEl->id }}" @endif>{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="relative max-w-4xl mx-auto" x-data="testimonialSlider({{ $autoplay ? 'true' : 'false' }}, {{ $interval }})" x-init="init()">
            <div class="testimonial-slider overflow-hidden rounded-none">
                <div class="testimonial-track flex transition-transform duration-500 ease-in-out" x-ref="track">
                    @foreach($testimonials as $index => $t)
                    @php
                        $photo = $t['customer_photo'] ?? null;
                        if (!$photo) {
                            $nameParts = explode(' ', $t['customer_name'] ?? 'sarah');
                            $first = strtolower(trim($nameParts[0], '.'));
                            $photo = file_exists(public_path('images/testimonial-' . $first . '.jpg'))
                                ? '/images/testimonial-' . $first . '.jpg'
                                : '/images/testimonial-sarah.jpg';
                        }
                    @endphp
                    <div class="testimonial-slide w-full flex-shrink-0 px-2 sm:px-4">
                        <article class="bg-white p-8 sm:p-12 border border-gray-100 text-center shadow-sm relative">
                            <img src="{{ $photo }}" alt="{{ $t['customer_name'] ?? 'Client' }}" class="w-20 h-20 rounded-full mx-auto mb-6 object-cover border-2 border-accent-primary shadow-md" loading="lazy" decoding="async">

                            @if(isset($t['rating']))
                            <div class="flex justify-center gap-1 mb-6" aria-label="{{ $t['rating'] }} out of 5 stars">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $t['rating'] ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                            @endif

                            <blockquote class="font-body text-text-secondary text-base sm:text-lg italic leading-relaxed mb-6 max-w-2xl mx-auto">
                                "{{ $t['review'] ?? $t['quote'] ?? '' }}"
                            </blockquote>

                            <div>
                                <h3 class="font-display font-bold text-text-primary text-base sm:text-lg">{{ $t['customer_name'] ?? 'Client' }}</h3>
                                @if(isset($t['customer_country']) || isset($t['expedition_name']))
                                <p class="text-xs sm:text-sm text-text-muted mt-0.5">
                                    {{ $t['customer_country'] ?? '' }}{{ isset($t['customer_country']) && isset($t['expedition_name']) ? ' · ' : '' }}{{ $t['expedition_name'] ?? '' }}
                                </p>
                                @endif
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-center items-center gap-4 mt-8">
                <button type="button" @click="prev()" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors cursor-pointer" aria-label="Previous testimonial">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="flex gap-2">
                    @foreach($testimonials as $i => $t)
                    <button type="button" @click="goTo({{ $i }})" class="w-2.5 h-2.5 rounded-full transition-colors cursor-pointer" :class="currentIndex === {{ $i }} ? 'bg-accent-primary' : 'bg-gray-300'" aria-label="Go to slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
                <button type="button" @click="next()" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-text-secondary hover:border-accent-primary hover:text-accent-primary transition-colors cursor-pointer" aria-label="Next testimonial">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </div>
</section>
@endif
