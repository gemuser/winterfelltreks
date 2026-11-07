@php
    $heading = $elements['heading']?->content ?? 'Words From Fellow Mountaineers';
    $subheading = $elements['subheading']?->content ?? 'Real reviews and expedition stories from travelers who explored the Himalayas with us';
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
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-lg">{{ $subheading }}</p>
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
                                <svg class="w-4 h-4 {{ $i <= $t['rating'] ? 'text-amber-400 fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                            @endif

                            @if(isset($t['testimonial_text']))
                            <blockquote class="text-text-secondary text-base sm:text-lg leading-relaxed mb-6 font-serif italic max-w-2xl mx-auto">
                                &ldquo;{{ $t['testimonial_text'] }}&rdquo;
                            </blockquote>
                            @endif

                            <footer>
                                @if(isset($t['customer_name']))
                                <cite class="font-display font-bold text-text-primary text-base not-italic block">{{ $t['customer_name'] }}</cite>
                                @endif
                                @if(isset($t['customer_country']) && $t['customer_country'])
                                <span class="text-xs text-text-secondary">{{ $t['customer_country'] }}</span>
                                @endif
                                @if(isset($t['destination_or_package']) && $t['destination_or_package'])
                                <p class="text-accent-primary text-xs font-semibold uppercase tracking-wider mt-1">{{ $t['destination_or_package'] }}</p>
                                @endif
                            </footer>
                        </article>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Slider Navigation Arrows --}}
            <button @click="prev()" class="absolute -left-3 sm:-left-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-md flex items-center justify-center text-text-primary hover:bg-accent-primary hover:text-white hover:border-accent-primary transition-all z-10" aria-label="Previous testimonial">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="absolute -right-3 sm:-right-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-md flex items-center justify-center text-text-primary hover:bg-accent-primary hover:text-white hover:border-accent-primary transition-all z-10" aria-label="Next testimonial">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            {{-- Dots --}}
            <div class="flex justify-center gap-2 mt-8" x-ref="dots">
                <template x-for="(_, index) in slides" :key="index">
                    <button @click="goTo(index)" :class="current === index ? 'bg-accent-primary w-6' : 'bg-gray-300 w-2.5'" class="h-2.5 rounded-full transition-all duration-300" :aria-label="'Go to slide ' + (index + 1)"></button>
                </template>
            </div>
        </div>
    </div>
</section>
@endif
