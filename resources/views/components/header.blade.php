<header class="sticky top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300 shadow-sm"
        x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            {{-- Logo --}}
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group" aria-label="{{ $globals['site_name'] ?? 'Winterfell Treks & Expedition' }}">
                    <img src="/images/winterfell-nobg.png" 
                         alt="{{ $globals['site_name'] ?? 'Winterfell Treks & Expedition' }}" 
                         class="h-12 sm:h-14 w-auto object-contain transition-transform duration-200 group-hover:scale-102"
                         width="202" height="127">
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center space-x-7" role="navigation" aria-label="Main navigation">
                @foreach($globals['navbar_pages'] ?? [] as $navPage)
                @php
                    $isHome = $navPage->slug === 'home';
                    $isActive = $isHome ? request()->is('/') : request()->is($navPage->slug, $navPage->slug . '/*');
                @endphp
                <a href="{{ url($isHome ? '/' : '/' . $navPage->slug) }}" 
                   class="relative py-2 text-sm font-medium tracking-wide transition-colors {{ $isActive ? 'text-accent-primary font-semibold' : 'text-text-secondary hover:text-accent-primary' }}">
                    {{ $navPage->navbar_title ?? $navPage->title }}
                    @if($isActive)
                    <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-accent-primary"></span>
                    @endif
                </a>
                @endforeach
            </nav>

            {{-- Desktop CTA --}}
            <div class="hidden lg:flex items-center space-x-4">
                @auth
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-accent-primary bg-accent-primary/10 hover:bg-accent-primary hover:text-white transition-colors rounded-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Admin Panel
                </a>
                @endauth
                <a href="{{ url('/enquiry') }}" 
                   class="btn-primary px-6 py-2.5 text-sm font-semibold tracking-wide"
                   role="button">
                    {{ $globals['enquiry_button_text'] ?? 'Enquire Now' }}
                </a>
            </div>

            {{-- Mobile Menu Hamburger Button --}}
            <button class="lg:hidden p-2 text-text-primary hover:text-accent-primary transition-colors focus:outline-none" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen.toString()" 
                    aria-controls="mobile-menu"
                    aria-label="Toggle navigation menu">
                <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-90': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="lg:hidden bg-white border-t border-gray-100 shadow-xl" 
             x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <nav class="py-4 px-2 space-y-1" role="navigation" aria-label="Mobile navigation">
                @foreach($globals['navbar_pages'] ?? [] as $navPage)
                @php
                    $isHome = $navPage->slug === 'home';
                    $isActive = $isHome ? request()->is('/') : request()->is($navPage->slug, $navPage->slug . '/*');
                @endphp
                <a href="{{ url($isHome ? '/' : '/' . $navPage->slug) }}" 
                   class="block px-4 py-2.5 text-base font-medium rounded-sm transition-colors {{ $isActive ? 'bg-accent-primary/10 text-accent-primary font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-gray-50' }}"
                   @click="mobileMenuOpen = false">
                    {{ $navPage->navbar_title ?? $navPage->title }}
                </a>
                @endforeach
                <div class="pt-4 mt-2 border-t border-gray-100 px-4">
                    <a href="{{ url('/enquiry') }}" 
                       class="btn-primary block w-full text-center px-6 py-3 font-semibold text-sm"
                       @click="mobileMenuOpen = false">
                        {{ $globals['enquiry_button_text'] ?? 'Enquire Now' }}
                    </a>
                </div>
            </nav>
        </div>
    </div>
</header>
