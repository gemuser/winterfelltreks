<footer class="bg-gray-950 text-gray-300" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-12">
            
            {{-- Column 1: Company Information --}}
            <div class="footer-column">
                <a href="{{ url('/') }}" class="inline-block mb-4 p-2 bg-white/5 hover:bg-white/10 rounded transition-colors" aria-label="{{ $globals['site_name'] ?? 'Winterfell Treks' }}">
                    <img src="/images/winterfell-nobg.png" alt="{{ $globals['site_name'] ?? 'Winterfell Treks' }}" class="h-12 sm:h-14 w-auto object-contain brightness-110">
                </a>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Premier Himalayan mountaineering and wilderness expeditions in Nepal. Founded in {{ $globals['established_year_bs'] ?? '2083' }} BS by {{ $globals['founder_name'] ?? 'Mr. Asmin Bhattarai' }}.
                </p>
                <div class="text-xs text-accent-primary font-medium uppercase tracking-wider">
                    Government Licensed & Certified
                </div>
            </div>

            {{-- Column 2: Quick Links --}}
            <div class="footer-column">
                <h3 class="font-display text-white text-base font-semibold mb-6 tracking-wide uppercase">
                    Quick Links
                </h3>
                <ul class="space-y-3 text-sm" role="list">
                    <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Home</a></li>
                    <li><a href="{{ url('/destinations') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Destinations</a></li>
                    <li><a href="{{ url('/packages') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Tour Packages</a></li>
                    <li><a href="{{ url('/experiences') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Experiences</a></li>
                    <li><a href="{{ url('/about') }}" class="text-gray-400 hover:text-accent-primary transition-colors">About Us</a></li>
                    <li><a href="{{ url('/travel-guide') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Travel Guide</a></li>
                    <li><a href="{{ url('/gallery') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Gallery</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Contact Us</a></li>
                </ul>
            </div>

            {{-- Column 3: Popular Destinations --}}
            <div class="footer-column">
                <h3 class="font-display text-white text-base font-semibold mb-6 tracking-wide uppercase">
                    Popular Destinations
                </h3>
                <ul class="space-y-3 text-sm" role="list">
                    <li><a href="{{ url('/destinations/everest-region') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Everest Region</a></li>
                    <li><a href="{{ url('/destinations/annapurna-region') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Annapurna Region</a></li>
                    <li><a href="{{ url('/destinations/langtang-region') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Langtang Valley</a></li>
                    <li><a href="{{ url('/destinations/manaslu-region') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Manaslu Circuit</a></li>
                    <li><a href="{{ url('/destinations/mustang-region') }}" class="text-gray-400 hover:text-accent-primary transition-colors">Upper Mustang</a></li>
                    <li><a href="{{ url('/destinations') }}" class="text-accent-primary hover:underline text-xs font-semibold uppercase tracking-wider block pt-2">View All Destinations &rarr;</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact Information --}}
            <div class="footer-column">
                <h3 class="font-display text-white text-base font-semibold mb-6 tracking-wide uppercase">
                    Contact Information
                </h3>
                <address class="not-italic space-y-4 text-sm text-gray-400">
                    @if($globals['contact']['phone'] ?? false)
                    <div>
                        <a href="tel:{{ $globals['contact']['phone'] }}" class="hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>{{ $globals['contact']['phone'] }}</span>
                        </a>
                    </div>
                    @endif

                    @if($globals['contact']['email'] ?? false)
                    <div>
                        <a href="mailto:{{ $globals['contact']['email'] }}" class="hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="truncate">{{ $globals['contact']['email'] }}</span>
                        </a>
                    </div>
                    @endif

                    @if($globals['contact']['address'] ?? false)
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-accent-primary flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $globals['contact']['address'] }}</span>
                    </div>
                    @endif

                    @if($globals['contact']['hours'] ?? false)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $globals['contact']['hours'] }}</span>
                    </div>
                    @endif

                    @if($globals['contact']['whatsapp'] ?? false)
                    <div>
                        <a href="https://wa.me/{{ $globals['contact']['whatsapp'] }}" target="_blank" rel="noopener" class="text-green-400 hover:text-green-300 transition-colors flex items-center gap-2 font-medium">
                            <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.074-2.127-.518-1.782-.738-2.92-2.56-3.008-2.678-.088-.118-.72-1.026-.72-1.956 0-.93.486-1.385.66-1.575.174-.189.38-.237.507-.237.126 0 .253.003.364.008.118.005.275-.045.431.33.161.388.55 1.341.597 1.44.048.099.08.216.015.344-.064.129-.096.208-.189.319-.094.111-.197.248-.282.333-.095.096-.194.2-.084.39.11.19.489.808 1.05 1.308.723.645 1.332.845 1.522.94.19.095.3.08.412-.048.112-.128.479-.558.607-.75.127-.191.254-.159.428-.095.174.064 1.111.524 1.302.62.19.095.318.143.364.223.048.079.048.461-.096.866z"/></svg>
                            <span>WhatsApp Chat</span>
                        </a>
                    </div>
                    @endif
                </address>
            </div>

            {{-- Column 5: Social Media & Action --}}
            <div class="footer-column">
                <h3 class="font-display text-white text-base font-semibold mb-6 tracking-wide uppercase">
                    Connect With Us
                </h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Follow our expeditions, summits, and trail reports from the Himalayas.
                </p>
                <div class="flex flex-wrap gap-3 mb-6">
                    @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'tiktok' => 'TikTok'] as $key => $label)
                    @if($globals['social'][$key] ?? false)
                    <a href="{{ $globals['social'][$key] }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-none bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:border-accent-primary hover:bg-accent-primary transition-all" aria-label="{{ $label }}">
                        @if($key === 'facebook')
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        @elseif($key === 'instagram')
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.692.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        @elseif($key === 'youtube')
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12 9.545 15.568z"/></svg>
                        @elseif($key === 'tiktok')
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"></path></svg>
                        @endif
                    </a>
                    @endif
                    @endforeach
                </div>
                <a href="{{ url('/enquiry') }}" class="btn-primary w-full text-center py-3 block text-xs uppercase tracking-wider font-semibold">
                    {{ $globals['enquiry_button_text'] ?? 'Plan an Enquiry' }}
                </a>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="mt-12 lg:mt-16 pt-8 border-t border-gray-900">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>
                    {{ $globals['copyright'] ?? ('© ' . date('Y') . ' Winterfell Treks & Expedition. All rights reserved.') }}
                </p>
                <nav class="flex items-center space-x-6" aria-label="Legal links">
                    <a href="{{ url('/privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ url('/terms-and-conditions') }}" class="hover:text-white transition-colors">Terms & Conditions</a>
                    <a href="{{ url('/faq') }}" class="hover:text-white transition-colors">FAQ</a>
                </nav>
            </div>
        </div>
    </div>
</footer>
