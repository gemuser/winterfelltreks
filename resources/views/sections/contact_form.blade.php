@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? 'Send Us a Message';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $subheading = $subheadingEl?->content ?? 'Our travel specialists are here to answer your questions and help you plan.';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Direct Communication';

    $btnEl = $elements['button_text'] ?? $elements['primary_cta'] ?? $elements['cta'] ?? $elements['button'] ?? null;
    $buttonText = $btnEl ? (is_array($btnEl->getContent()) ? ($btnEl->getContent()['text'] ?? '') : $btnEl->content) : 'Send Message';
@endphp

<section class="py-16 lg:py-24 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12" data-gsap="fade-up">
            @if($badge)
            <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">{{ $badge }}</span>
            @endif
            @if($heading)
            <h2 data-key="heading" data-element-id="{{ $headingEl?->id ?? '' }}" class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight mb-4">
                {{ $heading }}
            </h2>
            @endif
            @if($subheading)
            <p data-key="subheading" data-element-id="{{ $subheadingEl?->id ?? '' }}" class="text-text-secondary text-lg">
                {{ $subheading }}
            </p>
            @endif
        </div>

        <div class="bg-white p-8 sm:p-12 border border-gray-100 shadow-sm" data-gsap="fade-up" data-gsap-delay="0.1">
            <form action="{{ route('enquiry.store') }}" method="POST" class="space-y-6" x-data="enquiryForm" @submit.prevent="submit($el)">
                @csrf
                <input type="hidden" name="enquiry_type" value="general">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_full_name" class="block text-sm font-medium text-text-primary mb-2">
                            Full Name <span class="text-accent-primary" aria-hidden="true">*</span>
                        </label>
                        <input type="text" id="contact_full_name" name="full_name" required class="form-input w-full" placeholder="Your name">
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-text-primary mb-2">
                            Email Address <span class="text-accent-primary" aria-hidden="true">*</span>
                        </label>
                        <input type="email" id="contact_email" name="email" required class="form-input w-full" placeholder="you@example.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-text-primary mb-2">
                            Phone Number
                        </label>
                        <input type="tel" id="contact_phone" name="phone" class="form-input w-full" placeholder="+1 (555) 000-0000">
                    </div>

                    <div>
                        <label for="contact_country" class="block text-sm font-medium text-text-primary mb-2">
                            Country of Residence
                        </label>
                        <input type="text" id="contact_country" name="country" class="form-input w-full" placeholder="e.g. United Kingdom">
                    </div>
                </div>

                <div>
                    <label for="contact_travellers" class="block text-sm font-medium text-text-primary mb-2">
                        Estimated Number of Travellers <span class="text-accent-primary" aria-hidden="true">*</span>
                    </label>
                    <input type="number" id="contact_travellers" name="number_of_travellers" min="1" max="50" value="1" required class="form-input w-full">
                </div>

                <div>
                    <label for="contact_message" class="block text-sm font-medium text-text-primary mb-2">
                        Message / Travel Details
                    </label>
                    <textarea id="contact_message" name="additional_message" rows="5" class="form-input w-full" placeholder="Tell us about the destinations, routes, or questions you have..."></textarea>
                </div>

                <div class="flex items-start gap-3">
                    <input type="checkbox" id="contact_consent" name="consent" value="1" required class="form-checkbox mt-1 h-4 w-4">
                    <label for="contact_consent" class="text-sm text-text-secondary">
                        I agree to the <a href="{{ url('/privacy-policy') }}" class="text-accent-primary underline hover:text-accent-light">Privacy Policy</a> and consent to being contacted regarding my inquiry.
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full py-4 text-base font-semibold tracking-wide" :disabled="submitting">
                        <span x-show="!submitting" data-key="button_text" @if($btnEl) data-element-id="{{ $btnEl->id }}" @endif>{{ $buttonText }}</span>
                        <span x-show="submitting" x-cloak>Sending...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
