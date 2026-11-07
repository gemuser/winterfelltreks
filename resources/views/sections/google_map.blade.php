@php
    $heading = $elements['heading']?->content ?? 'Visit Our Office';
    $address = $elements['address']?->content ?? ($globals['contact']['address'] ?? 'Thamel, Kathmandu, Nepal');
    $embedUrl = $elements['embed_url']?->content ?? ($globals['contact']['map_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.0!2d85.31!3d27.71!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sThamel!5e0!3m2!1sen!2snp!4v1');
@endphp

<section class="py-16 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading || $address)
        <div class="text-center max-w-xl mx-auto mb-8" data-gsap="fade-up">
            @if($heading)
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary tracking-tight mb-2">
                {{ $heading }}
            </h2>
            @endif
            @if($address)
            <p class="text-text-secondary text-base">
                {{ $address }}
            </p>
            @endif
        </div>
        @endif

        <div class="aspect-[21/9] sm:aspect-[21/8] w-full rounded-none overflow-hidden border border-gray-200 shadow-sm" data-gsap="fade-up" data-gsap-delay="0.1">
            <iframe 
                src="{{ $embedUrl }}" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="Office Location Map"
                class="w-full h-full grayscale-[30%] contrast-[1.1] hover:grayscale-0 transition-all duration-500"
            ></iframe>
        </div>
    </div>
</section>

