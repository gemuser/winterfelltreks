@php
    $heading = $elements['heading']?->content ?? $elements['headline']?->content ?? $elements['title']?->content ?? 'Plan Your Himalayan Journey';
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $subheading = $elements['subheading']?->content ?? $elements['subheadline']?->content ?? $elements['subtitle']?->content ?? 'Fill out our detailed enquiry form and one of our mountain specialists will craft a customized itinerary and quotation within 24 hours.';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Bespoke Expeditions';

    $btnEl = $elements['button_text'] ?? $elements['primary_cta'] ?? $elements['cta'] ?? $elements['secondary_cta'] ?? $elements['button'] ?? null;
    $rawBtnContent = $btnEl?->getContent() ?? $btnEl?->content;
    $buttonText = 'Submit Custom Expedition Request';
    if (is_array($rawBtnContent)) {
        $buttonText = $rawBtnContent['text'] ?? ($rawBtnContent['label'] ?? 'Submit Custom Expedition Request');
    } elseif (is_string($rawBtnContent) && !empty($rawBtnContent)) {
        $decoded = json_decode($rawBtnContent, true);
        if (is_array($decoded)) {
            $buttonText = $decoded['text'] ?? ($decoded['label'] ?? 'Submit Custom Expedition Request');
        } else {
            $buttonText = $rawBtnContent;
        }
    }
    if (empty(trim((string)$buttonText))) {
        $buttonText = 'Submit Custom Expedition Request';
    }

    $noteEl = $elements['note'] ?? $elements['disclaimer'] ?? null;
    $note = $noteEl?->content ?? '100% Enquiry based · No immediate payment required · Dedicated Sherpa lead assigned to your enquiry';

    $type = $settings['type'] ?? 'general';

    $preselectedDestination = request('destination') ?? request('destination_slug') ?? '';
    $preselectedPackage = request('package') ?? request('package_slug') ?? '';
@endphp

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

        @if(session('success'))
        <div class="mb-8 p-6 bg-green-50 border border-green-200 text-green-800 rounded-lg text-center" data-gsap="fade-up">
            <h4 class="font-bold text-lg mb-1">Enquiry Received!</h4>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white border border-gray-200 p-8 sm:p-12 shadow-sm" data-gsap="fade-up">
            <form action="{{ route('enquiry.store') }}" method="POST" id="main-enquiry-form" x-data="{
                submitting: false,
                errorMessage: '',
                errors: {},
                async submitForm(e) {
                    this.submitting = true;
                    this.errorMessage = '';
                    this.errors = {};
                    const form = e.target;
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            window.location.href = data.redirect_url || '/enquiry?success=1';
                        } else {
                            this.submitting = false;
                            if (data.errors) {
                                this.errors = data.errors;
                            }
                            this.errorMessage = data.message || 'There were errors with your submission. Please check the form below.';
                        }
                    } catch (err) {
                        this.submitting = false;
                        this.errorMessage = 'A network error occurred. Please try again.';
                    }
                }
            }" @submit.prevent="submitForm($event)">
                @csrf

                <div x-show="errorMessage" x-cloak class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded">
                    <p class="font-semibold" x-text="errorMessage"></p>
                </div>

                {{-- Section 1: Trip Preferences --}}
                <fieldset class="mb-10">
                    <legend class="font-display text-xl font-bold text-text-primary pb-3 border-b border-gray-200 w-full mb-6">1. Expedition Preferences</legend>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="destination" class="block text-sm font-semibold text-text-primary mb-2">Preferred Himalayan Region</label>
                            <select id="destination" name="destination" class="form-input w-full">
                                <option value="">Select a region / unassigned</option>
                                <option value="Everest Region" {{ $preselectedDestination === 'Everest Region' || $preselectedDestination === 'everest-region' ? 'selected' : '' }}>Everest (Khumbu) Region</option>
                                <option value="Annapurna Region" {{ $preselectedDestination === 'Annapurna Region' || $preselectedDestination === 'annapurna-region' ? 'selected' : '' }}>Annapurna Region</option>
                                <option value="Langtang Valley" {{ $preselectedDestination === 'Langtang Valley' || $preselectedDestination === 'langtang-valley' ? 'selected' : '' }}>Langtang / Helambu Region</option>
                                <option value="Manaslu Circuit" {{ $preselectedDestination === 'Manaslu Circuit' || $preselectedDestination === 'manaslu-circuit' ? 'selected' : '' }}>Manaslu Region</option>
                                <option value="Mustang & Dolpo" {{ $preselectedDestination === 'Mustang & Dolpo' || $preselectedDestination === 'mustang-dolpo' ? 'selected' : '' }}>Upper Mustang / Wild Dolpo</option>
                                <option value="Eastern Nepal" {{ $preselectedDestination === 'Eastern Nepal' ? 'selected' : '' }}>Kanchenjunga / Makalu Region</option>
                                <option value="Open to Recommendation">Open to Recommendation</option>
                            </select>
                        </div>

                        <div>
                            <label for="duration_preference" class="block text-sm font-semibold text-text-primary mb-2">Trip Duration</label>
                            <select id="duration_preference" name="duration_preference" class="form-input w-full">
                                <option value="">Flexible / Not decided</option>
                                <option value="Under 7 days">Under 7 days (Short Trek)</option>
                                <option value="7 to 12 days">7 to 12 days (Standard Circuit)</option>
                                <option value="12 to 18 days">12 to 18 days (Classic High-Pass Trek)</option>
                                <option value="18 to 25+ days">18 to 25+ days (Grand Traverse / Peak Climb)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="departure_date" class="block text-sm font-semibold text-text-primary mb-2">Estimated Arrival / Start Month</label>
                            <input type="text" id="departure_date" name="departure_date" class="form-input w-full" placeholder="e.g. October 2026 or Spring 2027">
                        </div>

                        <div>
                            <label for="number_of_travellers" class="block text-sm font-semibold text-text-primary mb-2">
                                Number of Mountaineers / Trekkers
                                <span class="text-accent-primary" aria-hidden="true">*</span>
                            </label>
                            <input type="number" id="number_of_travellers" name="number_of_travellers" required min="1" max="50" value="1" class="form-input w-full">
                            <p x-show="errors.number_of_travellers" x-text="errors.number_of_travellers ? errors.number_of_travellers[0] : ''" class="mt-1 text-xs text-red-600"></p>
                        </div>
                    </div>

                    <div>
                        <label for="package_slug" class="block text-sm font-semibold text-text-primary mb-2">Specific Package of Interest (Optional)</label>
                        <input type="text" id="package_slug" name="package_slug" value="{{ $preselectedPackage }}" class="form-input w-full" placeholder="e.g. everest-base-camp-luxury-lodge-trek">
                    </div>
                </fieldset>

                {{-- Section 2: Contact Information --}}
                <fieldset class="mb-10">
                    <legend class="font-display text-xl font-bold text-text-primary pb-3 border-b border-gray-200 w-full mb-6">2. Lead Traveler Information</legend>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-text-primary mb-2">
                                Full Legal Name
                                <span class="text-accent-primary" aria-hidden="true">*</span>
                            </label>
                            <input type="text" id="full_name" name="full_name" required class="form-input w-full" placeholder="First and Last Name">
                            <p x-show="errors.full_name" x-text="errors.full_name ? errors.full_name[0] : ''" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-text-primary mb-2">
                                Email Address
                                <span class="text-accent-primary" aria-hidden="true">*</span>
                            </label>
                            <input type="email" id="email" name="email" required class="form-input w-full" placeholder="you@domain.com">
                            <p x-show="errors.email" x-text="errors.email ? errors.email[0] : ''" class="mt-1 text-xs text-red-600"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-text-primary mb-2">Phone / WhatsApp Number (with country code)</label>
                            <input type="tel" id="phone" name="phone" class="form-input w-full" placeholder="+1 (555) 000-0000">
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-semibold text-text-primary mb-2">Country of Citizenship / Residence</label>
                            <input type="text" id="country" name="country" class="form-input w-full" placeholder="e.g. United Kingdom, USA, Germany">
                        </div>
                    </div>
                </fieldset>

                {{-- Section 3: Bespoke Details --}}
                <fieldset class="mb-10">
                    <legend class="font-display text-xl font-bold text-text-primary pb-3 border-b border-gray-200 w-full mb-6">3. Bespoke Journey Details</legend>

                    <div class="mb-6">
                        <label for="additional_message" class="block text-sm font-semibold text-text-primary mb-2">Special Requests or Prior High-Altitude Experience</label>
                        <textarea id="additional_message" name="additional_message" rows="4" class="form-input w-full" placeholder="Tell us about your fitness level, any previous treks you have completed, preferred pace, or special milestones you wish to celebrate..."></textarea>
                    </div>
                </fieldset>

                {{-- Section 4: Privacy & Consent --}}
                <fieldset class="mb-10">
                    <div class="flex items-start gap-3 p-4 bg-gray-50 border border-gray-200">
                        <input type="checkbox" id="consent" name="consent" required value="1" class="form-checkbox h-5 w-5 text-accent-primary border-gray-300 rounded mt-0.5">
                        <label for="consent" class="text-xs sm:text-sm text-text-secondary leading-relaxed">
                            I consent to Winterfell Treks & Expedition processing my travel enquiry in accordance with the 
                            <a href="{{ url('/privacy-policy') }}" target="_blank" class="text-accent-primary underline">Privacy Policy</a>.
                            <span class="text-accent-primary" aria-hidden="true">*</span>
                        </label>
                    </div>
                    <p x-show="errors.consent" x-text="errors.consent ? errors.consent[0] : ''" class="mt-1 text-xs text-red-600"></p>
                </fieldset>

                {{-- Submit --}}
                <div class="text-center pt-4 border-t border-gray-100">
                    <button type="submit" data-key="button_text" @if($btnEl) data-element-id="{{ $btnEl->id }}" @endif :disabled="submitting" class="btn-primary px-10 py-4 text-base sm:text-lg font-bold w-full sm:w-auto shadow-md">
                        <span x-show="!submitting" data-key="button_text" @if($btnEl) data-element-id="{{ $btnEl->id }}" @endif>{{ $buttonText }}</span>
                        <span x-show="submitting" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Transmitting Request...
                        </span>
                    </button>
                    
                    @if($note)
                    <p data-key="note" @if($noteEl) data-element-id="{{ $noteEl->id }}" @endif class="mt-4 text-xs sm:text-sm text-text-secondary">
                        {{ $note }}
                    </p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
