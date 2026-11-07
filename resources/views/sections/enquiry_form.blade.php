@php
    $heading = $elements['heading']?->content ?? 'Plan Your Himalayan Journey';
    $subheading = $elements['subheading']?->content ?? 'Fill out our detailed enquiry form and one of our mountain specialists will craft a customized itinerary and quotation within 24 hours.';
    $type = $settings['type'] ?? 'general';

    // Preselected context
    $preselectedDestination = request('destination') ?? request('destination_slug') ?? '';
    $preselectedPackage = request('package') ?? request('package_slug') ?? '';
@endphp

<section class="py-20 lg:py-32 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="text-center mb-12 sm:mb-16" data-gsap="fade-up">
            <span class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">Bespoke Expeditions</span>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-base sm:text-lg max-w-2xl mx-auto">{{ $subheading }}</p>
            @endif
        </div>
        @endif

        {{-- Session Flash Success / Error --}}
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
                            window.location.href = data.redirect || '{{ url('/enquiry/success') }}';
                        } else if (data.errors) {
                            this.errors = data.errors;
                            this.errorMessage = 'Please review the highlighted fields below.';
                        } else {
                            this.errorMessage = data.message || 'An unexpected error occurred. Please try again.';
                        }
                    } catch (err) {
                        form.submit(); // Fallback to normal HTTP submit
                    } finally {
                        this.submitting = false;
                    }
                }
            }" @submit.prevent="submitForm($event)">
                @csrf

                {{-- Hidden context fields --}}
                @if($type !== 'general' && isset($pageData))
                <input type="hidden" name="enquiry_type" value="{{ $type }}">
                @elseif(request()->filled('destination_slug') || request()->filled('destination'))
                <input type="hidden" name="enquiry_type" value="destination">
                <input type="hidden" name="destination_slug" value="{{ request('destination_slug') ?? request('destination') }}">
                @elseif(request()->filled('package_slug') || request()->filled('package'))
                <input type="hidden" name="enquiry_type" value="package">
                <input type="hidden" name="package_slug" value="{{ request('package_slug') ?? request('package') }}">
                @elseif(request()->filled('experience_slug') || request()->filled('experience'))
                <input type="hidden" name="enquiry_type" value="experience">
                <input type="hidden" name="experience_slug" value="{{ request('experience_slug') ?? request('experience') }}">
                @else
                <input type="hidden" name="enquiry_type" value="general">
                @endif

                {{-- Top Error Alert --}}
                <div x-show="errorMessage" x-cloak class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-semibold" x-text="errorMessage"></p>
                </div>

                {{-- Section 1: Personal Information --}}
                <fieldset class="mb-10">
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-200">
                        <span class="w-7 h-7 rounded-full bg-accent-primary text-white text-xs font-bold flex items-center justify-center">1</span>
                        <legend class="font-display text-xl font-bold text-text-primary">Personal Details</legend>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-text-primary mb-2">Full Name <span class="text-accent-primary" aria-hidden="true">*</span></label>
                            <input type="text" id="full_name" name="full_name" required value="{{ old('full_name') }}" class="form-input w-full" placeholder="e.g. Eleanor Vance" autocomplete="name">
                            <p x-show="errors.full_name" x-text="errors.full_name ? errors.full_name[0] : ''" class="mt-1 text-xs text-red-600"></p>
                            @error('full_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-semibold text-text-primary mb-2">Email Address <span class="text-accent-primary" aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" class="form-input w-full" placeholder="eleanor@example.com" autocomplete="email">
                            <p x-show="errors.email" x-text="errors.email ? errors.email[0] : ''" class="mt-1 text-xs text-red-600"></p>
                            @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-text-primary mb-2">Phone / WhatsApp</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-input w-full" placeholder="+1 (555) 000-0000" autocomplete="tel">
                            <p x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''" class="mt-1 text-xs text-red-600"></p>
                        </div>
                        
                        <div>
                            <label for="country" class="block text-sm font-semibold text-text-primary mb-2">Country of Residence</label>
                            <select id="country" name="country" class="form-input w-full">
                                <option value="">Select country</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Australia">Australia</option>
                                <option value="Canada">Canada</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Nepal">Nepal</option>
                                <option value="India">India</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Other">Other Country</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                {{-- Section 2: Trip & Destination --}}
                <fieldset class="mb-10">
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-200">
                        <span class="w-7 h-7 rounded-full bg-accent-primary text-white text-xs font-bold flex items-center justify-center">2</span>
                        <legend class="font-display text-xl font-bold text-text-primary">Expedition Preferences</legend>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="destination" class="block text-sm font-semibold text-text-primary mb-2">Destination / Region <span class="text-accent-primary" aria-hidden="true">*</span></label>
                            <select id="destination" name="destination" required class="form-input w-full">
                                <option value="">Select destination</option>
                                <option value="Everest Region" {{ $preselectedDestination === 'Everest Region' || str_contains(strtolower($preselectedDestination), 'everest') ? 'selected' : '' }}>Everest Region / Khumbu</option>
                                <option value="Annapurna Region" {{ $preselectedDestination === 'Annapurna Region' || str_contains(strtolower($preselectedDestination), 'annapurna') ? 'selected' : '' }}>Annapurna Region</option>
                                <option value="Langtang Valley" {{ str_contains(strtolower($preselectedDestination), 'langtang') ? 'selected' : '' }}>Langtang Valley</option>
                                <option value="Upper Mustang" {{ str_contains(strtolower($preselectedDestination), 'mustang') ? 'selected' : '' }}>Upper Mustang</option>
                                <option value="Manaslu Circuit" {{ str_contains(strtolower($preselectedDestination), 'manaslu') ? 'selected' : '' }}>Manaslu Circuit</option>
                                <option value="Dolpo & Remote West" {{ str_contains(strtolower($preselectedDestination), 'dolpo') ? 'selected' : '' }}>Dolpo & Remote West</option>
                                <option value="Custom Mixed Journey">Custom Mixed Journey</option>
                            </select>
                            <p x-show="errors.destination" x-text="errors.destination ? errors.destination[0] : ''" class="mt-1 text-xs text-red-600"></p>
                        </div>
                        
                        <div>
                            <label for="travel_type" class="block text-sm font-semibold text-text-primary mb-2">Expedition Style</label>
                            <select id="travel_type" name="travel_type" class="form-input w-full">
                                <option value="Trekking">Trekking & Hiking</option>
                                <option value="Peak Climbing">Trekking Peak Climbing (6000m+)</option>
                                <option value="Major Mountaineering">Major Mountaineering (7000m - 8000m)</option>
                                <option value="Luxury Lodge Trek">Luxury Lodge Trek</option>
                                <option value="Cultural & Helicopter Tour">Cultural & Helicopter Tour</option>
                                <option value="Photography Expedition">Photography Expedition</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label for="expected_departure_date" class="block text-sm font-semibold text-text-primary mb-2">Target Departure</label>
                            <input type="date" id="expected_departure_date" name="expected_departure_date" class="form-input w-full" min="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div>
                            <label for="number_of_travellers" class="block text-sm font-semibold text-text-primary mb-2">Total Travellers <span class="text-accent-primary" aria-hidden="true">*</span></label>
                            <input type="number" id="number_of_travellers" name="number_of_travellers" required min="1" max="50" value="1" class="form-input w-full">
                            <p x-show="errors.number_of_travellers" x-text="errors.number_of_travellers ? errors.number_of_travellers[0] : ''" class="mt-1 text-xs text-red-600"></p>
                        </div>

                        <div>
                            <label for="budget_range" class="block text-sm font-semibold text-text-primary mb-2">Estimated Budget (USD / Person)</label>
                            <select id="budget_range" name="budget_range" class="form-input w-full">
                                <option value="">Select range</option>
                                <option value="1500-2500">$1,500 - $2,500</option>
                                <option value="2500-4000">$2,500 - $4,000</option>
                                <option value="4000-7000">$4,000 - $7,000</option>
                                <option value="7000+">$7,000+ (Ultra-luxury / Peak Expedition)</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                {{-- Section 3: Notes & Customization --}}
                <fieldset class="mb-10">
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-200">
                        <span class="w-7 h-7 rounded-full bg-accent-primary text-white text-xs font-bold flex items-center justify-center">3</span>
                        <legend class="font-display text-xl font-bold text-text-primary">Itinerary Notes & Requirements</legend>
                    </div>

                    <div>
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
                    <button type="submit" :disabled="submitting" class="btn-primary px-10 py-4 text-base sm:text-lg font-bold w-full sm:w-auto shadow-md">
                        <span x-show="!submitting">Submit Custom Expedition Request</span>
                        <span x-show="submitting" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Transmitting Request...
                        </span>
                    </button>
                    
                    <p class="mt-4 text-xs sm:text-sm text-text-secondary">
                        100% Enquiry based · No immediate payment required · Dedicated Sherpa lead assigned to your enquiry
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
