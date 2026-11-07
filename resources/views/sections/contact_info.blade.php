@php
    $heading = $elements['heading']?->content ?? 'Get in Touch';
    $layout = $settings['layout'] ?? 'grid';
    $contact = $globals['contact'] ?? [];
    $social = $globals['social'] ?? [];
@endphp

<section class="py-20 lg:py-32" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="max-w-3xl mb-12" data-gsap="fade-up">
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
        </div>
        @endif

        @if($layout === 'grid')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <div data-gsap="fade-up">
                <h3 class="font-medium text-text-primary mb-4">Contact</h3>
                <address class="not-italic space-y-4 text-text-secondary">
                    @if($contact['phone'] ?? false)
                    <a href="tel:{{ $contact['phone'] }}" class="flex items-center gap-3 hover:text-text-primary transition-colors">
                        <svg class="w-6 h-6 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $contact['phone'] }}
                    </a>
                    @endif
                    @if($contact['email'] ?? false)
                    <a href="mailto:{{ $contact['email'] }}" class="flex items-center gap-3 hover:text-text-primary transition-colors">
                        <svg class="w-6 h-6 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $contact['email'] }}
                    </a>
                    @endif
                    @if($contact['address'] ?? false)
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-accent-primary flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $contact['address'] }}
                    </div>
                    @endif
                    @if($contact['hours'] ?? false)
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $contact['hours'] }}
                    </div>
                    @endif
                </address>
            </div>

            <div data-gsap="fade-up" data-gsap-delay="0.1">
                <h3 class="font-medium text-text-primary mb-4">Social</h3>
                <ul class="flex flex-wrap gap-3" role="list">
                    @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'tiktok' => 'TikTok'] as $key => $label)
                    @if($social[$key] ?? false)
                    <li>
                        <a href="{{ $social[$key] }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-text-secondary hover:bg-accent-primary hover:text-white transition-colors" aria-label="{{ $label }}">
                            @if($key === 'facebook')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            @elseif($key === 'instagram')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.692.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            @elseif($key === 'youtube')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12 9.545 15.568z"/></svg>
                            @elseif($key === 'tiktok')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.521,2.055c-1.317,0-2.448,0.777-2.83,1.814c-1.329,0-2.597,1.351-2.688,3.026h-1.96V20h3.947V9.158c0-1.071,0.234-1.975,0.949-2.658C12.879,5.136,12.697,2.055,12.521,2.055z M18.544,13.742c-0.387,0.487-1.008,1.05-1.647,1.13c-2.313,0.255-3.775-1.415-3.644-3.525c0.058-0.945,0.534-1.777,1.461-2.191c1.146-0.51,2.533-0.32,3.272,0.625c0.843,1.085,0.742,2.784-0.149,3.644C18.939,13.335,18.859,13.461,18.544,13.742z"/></svg>
                            @endif
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>

            <div data-gsap="fade-up" data-gsap-delay="0.2">
                <h3 class="font-medium text-text-primary mb-4">Emergency</h3>
                <p class="text-text-secondary mb-2">24/7 rescue coordination</p>
                @if($contact['phone_emergency'] ?? false)
                <a href="tel:{{ $contact['phone_emergency'] }}" class="text-accent-primary font-semibold hover:underline">{{ $contact['phone_emergency'] }}</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>