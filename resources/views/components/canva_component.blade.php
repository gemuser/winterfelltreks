@php
    $rawContent = $cEl->getContent();
    if (is_string($rawContent)) {
        $comp = json_decode($rawContent, true);
        if (!is_array($comp)) {
            $comp = ['type' => 'badge_certified', 'text' => $rawContent];
        }
    } elseif (is_array($rawContent)) {
        $comp = $rawContent;
    } else {
        $comp = ['type' => 'badge_certified', 'text' => 'Component'];
    }
    $cType = $comp['type'] ?? 'badge_certified';
    $isEditor = $isEditor ?? false;
@endphp

@if($cType === 'search_packages')
    {{-- EXPEDITION & TREK SEARCH BAR --}}
    <div class="canva-component-root shadow-2xl rounded-2xl p-2 bg-white/95 backdrop-blur-md border border-white/40 text-gray-900 w-full min-w-[280px] sm:min-w-[460px] max-w-2xl overflow-hidden transition-all pointer-events-auto">
        <form action="{{ url('/packages') }}" method="GET" class="flex items-center gap-2 w-full" @if($isEditor) @submit.prevent @endif>
            <div class="pl-2 text-accent-primary shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" placeholder="{{ $comp['placeholder'] ?? 'Search Everest, Annapurna, Treks...' }}" class="bg-transparent border-0 text-xs sm:text-sm font-semibold text-gray-900 focus:ring-0 focus:outline-none flex-1 min-w-[120px] placeholder:text-gray-400 py-1.5 px-2 {{ $isEditor ? 'pointer-events-none select-none' : '' }}" @if($isEditor) readonly @endif>
            <button type="{{ $isEditor ? 'button' : 'submit' }}" class="btn-primary py-2 px-5 text-xs font-bold rounded-xl whitespace-nowrap shadow-md shrink-0 {{ $isEditor ? 'pointer-events-none select-none' : '' }}">
                {{ $comp['btn_text'] ?? 'Explore' }}
            </button>
        </form>
    </div>

@elseif($cType === 'search_destinations')
    {{-- DESTINATION FINDER (DARK LUXURY) --}}
    <div class="canva-component-root shadow-2xl rounded-2xl p-2 bg-gray-950/90 text-white backdrop-blur-md border border-gray-700 w-full min-w-[280px] sm:min-w-[460px] max-w-2xl overflow-hidden transition-all pointer-events-auto">
        <form action="{{ url('/destinations') }}" method="GET" class="flex items-center gap-2 w-full" @if($isEditor) @submit.prevent @endif>
            <div class="pl-2 text-accent-primary shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <input type="text" name="search" placeholder="{{ $comp['placeholder'] ?? 'Search Regions & Peaks...' }}" class="bg-transparent border-0 text-xs sm:text-sm font-semibold text-white focus:ring-0 focus:outline-none flex-1 min-w-[120px] placeholder:text-gray-400 py-1.5 px-2 {{ $isEditor ? 'pointer-events-none select-none' : '' }}" @if($isEditor) readonly @endif>
            <button type="{{ $isEditor ? 'button' : 'submit' }}" class="bg-accent-primary hover:bg-accent-light text-white py-2 px-5 text-xs font-bold rounded-xl whitespace-nowrap shadow-md shrink-0 {{ $isEditor ? 'pointer-events-none select-none' : '' }}">
                {{ $comp['btn_text'] ?? 'Find' }}
            </button>
        </form>
    </div>

@elseif($cType === 'search_capsule')
    {{-- MINIMAL SEARCH CAPSULE --}}
    <div class="canva-component-root shadow-xl rounded-full p-1.5 bg-white border border-gray-200 flex items-center gap-2 w-full min-w-[240px] sm:min-w-[380px] max-w-xl overflow-hidden transition-all pointer-events-auto">
        <div class="pl-2 text-gray-400 shrink-0 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <form action="{{ url('/packages') }}" method="GET" class="flex-1 min-w-0 flex items-center gap-2" @if($isEditor) @submit.prevent @endif>
            <input type="text" name="search" placeholder="{{ $comp['placeholder'] ?? 'Quick search packages...' }}" class="bg-transparent border-0 text-xs font-semibold text-gray-800 focus:ring-0 focus:outline-none flex-1 min-w-[100px] p-0 {{ $isEditor ? 'pointer-events-none select-none' : '' }}" @if($isEditor) readonly @endif>
            <button type="{{ $isEditor ? 'button' : 'submit' }}" class="bg-gray-900 text-white rounded-full p-1.5 hover:bg-black shrink-0 {{ $isEditor ? 'pointer-events-none select-none' : '' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>

@elseif($cType === 'button_whatsapp')
    {{-- WHATSAPP EXPEDITION SPECIALIST CHAT --}}
    <a href="{{ $isEditor ? '#' : 'https://wa.me/9779800000000' }}" target="_blank" @if($isEditor) @click.prevent @endif class="canva-component-root canva-custom-button inline-flex items-center gap-2.5 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-xl transition-all hover:scale-105 pointer-events-auto">
        <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.698.086-2.072-.486-1.758-.732-2.888-2.518-2.977-2.637-.089-.118-.707-.941-.707-1.795 0-.854.444-1.274.603-1.447.16-.174.348-.217.464-.217.116 0 .232.001.333.006.107.005.25-.041.391.297.145.348.493 1.202.536 1.29.044.087.073.189.015.304-.058.116-.087.188-.174.29-.087.101-.183.226-.262.304-.087.087-.178.181-.077.355.101.174.45 1.085 1.144 1.704.894.798 1.649 1.045 1.881 1.16.232.116.368.102.505-.058.138-.16.59-.687.747-.923.158-.236.315-.198.53-.118.216.079 1.365.644 1.6.76.236.116.393.174.45.271.058.097.058.563-.086.968z"/></svg>
        <span>{{ $comp['text'] ?? 'Chat with Mountain Expert' }}</span>
    </a>

@elseif($cType === 'button_phone')
    {{-- 24/7 HELICOPTER & SATELLITE DISPATCH --}}
    <a href="{{ $isEditor ? '#' : 'tel:+9771456789' }}" @if($isEditor) @click.prevent @endif class="canva-component-root canva-custom-button inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gray-900 hover:bg-black text-white font-bold text-sm shadow-xl border border-gray-700 transition-all hover:scale-105 pointer-events-auto">
        <svg class="w-4 h-4 text-accent-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        <span>{{ $comp['text'] ?? '+977-1-456789 (24/7 SOS)' }}</span>
    </a>

@elseif($cType === 'button_brochure')
    {{-- DOWNLOAD BROCHURE BUTTON --}}
    <a href="{{ $isEditor ? '#' : url('/packages') }}" @if($isEditor) @click.prevent @endif class="canva-component-root canva-custom-button inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold text-sm shadow-xl transition-all hover:scale-105 pointer-events-auto">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        <span>{{ $comp['text'] ?? 'Download Expedition Guide' }}</span>
    </a>

@elseif($cType === 'badge_certified')
    {{-- IFMGA CERTIFIED GUIDES BADGE --}}
    <div class="canva-component-root inline-flex items-center gap-2 px-4 py-2 rounded-full bg-black/85 text-white border border-accent-primary/60 shadow-xl backdrop-blur-md pointer-events-auto">
        <svg class="w-4 h-4 text-accent-primary shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"></path></svg>
        <span class="text-xs font-bold uppercase tracking-wider">{{ $comp['text'] ?? '100% IFMGA Certified Guides' }}</span>
    </div>

@elseif($cType === 'badge_reviews')
    {{-- 5-STAR RATING BADGE --}}
    <div class="canva-component-root inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-gray-950 border border-gray-200 shadow-xl pointer-events-auto">
        <span class="text-amber-500 font-extrabold text-sm tracking-tighter">★★★★★</span>
        <span class="text-xs font-extrabold text-gray-900">{{ $comp['text'] ?? '4.9/5 Rating (500+ Summits)' }}</span>
    </div>

@elseif($cType === 'badge_guarantee')
    {{-- GUARANTEED FIXED DEPARTURES --}}
    <div class="canva-component-root inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent-primary/20 text-accent-primary border border-accent-primary/40 shadow-md backdrop-blur-xs font-extrabold text-xs uppercase tracking-wide pointer-events-auto">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ $comp['text'] ?? 'Guaranteed Fixed Departures' }}</span>
    </div>

@elseif($cType === 'badge_sustainability')
    {{-- ECO-FRIENDLY TREKKING --}}
    <div class="canva-component-root inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-950/85 text-emerald-300 border border-emerald-500/40 shadow-xl backdrop-blur-md pointer-events-auto">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
        <span class="text-xs font-bold uppercase tracking-wider">{{ $comp['text'] ?? 'Leave No Trace Partner' }}</span>
    </div>

@elseif($cType === 'card_support')
    {{-- 24/7 SATELLITE DISPATCH CARD --}}
    <div class="canva-component-root bg-gray-950/90 text-white rounded-2xl p-4 shadow-2xl border border-gray-800 backdrop-blur-md w-full pointer-events-auto">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-accent-primary/20 flex items-center justify-center text-accent-primary shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-xs uppercase text-accent-primary tracking-wider">{{ $comp['title'] ?? '24/7 Satellite Dispatch' }}</h4>
                <p class="text-3xs text-gray-300 mt-0.5 leading-snug">{{ $comp['subtitle'] ?? 'Satellite phone & emergency helicopter rescue dispatch across the Himalayas.' }}</p>
            </div>
        </div>
    </div>

@elseif($cType === 'card_stats')
    {{-- HIMALAYAN SUMMIT STATS WIDGET --}}
    <div class="canva-component-root bg-white/95 text-gray-900 rounded-2xl p-4 shadow-2xl border border-gray-200 backdrop-blur-md w-full pointer-events-auto">
        <div class="grid grid-cols-3 gap-2 text-center divide-x divide-gray-200">
            <div>
                <span class="font-display font-black text-xl text-accent-primary block">8,848m</span>
                <span class="text-4xs uppercase font-bold text-gray-500">Everest</span>
            </div>
            <div>
                <span class="font-display font-black text-xl text-gray-900 block">14</span>
                <span class="text-4xs uppercase font-bold text-gray-500">8000m Peaks</span>
            </div>
            <div>
                <span class="font-display font-black text-xl text-emerald-600 block">99.8%</span>
                <span class="text-4xs uppercase font-bold text-gray-500">Safety</span>
            </div>
        </div>
    </div>

@elseif($cType === 'card_quote')
    {{-- MOUNTAIN INSPIRATIONAL QUOTE --}}
    <div class="canva-component-root bg-gradient-to-r from-gray-950 to-gray-900 text-white rounded-2xl p-5 shadow-2xl border border-amber-500/30 w-full pointer-events-auto">
        <p class="font-serif italic text-sm text-gray-200 leading-relaxed">“{{ $comp['quote'] ?? 'The mountains are calling and I must go.' }}”</p>
        <span class="block text-right text-3xs font-bold uppercase tracking-widest text-amber-400 mt-2">— {{ $comp['author'] ?? 'John Muir' }}</span>
    </div>

@else
    {{-- FALLBACK GENERIC COMPONENT --}}
    <div class="canva-component-root inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white border border-gray-700 shadow-md pointer-events-auto">
        <span class="text-xs font-semibold">{{ is_string($cEl->content) ? $cEl->content : ($comp['title'] ?? 'Component') }}</span>
    </div>
@endif
