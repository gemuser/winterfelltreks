@extends('admin.layout')

@section('title', 'Canva Editor: ' . $page->title)

@section('main-classes', 'p-0')

{{-- Tell Layout to auto-hide sidebar and render floating left arrow toggle --}}
@section('hide-sidebar', true)

@section('content')
{{-- Load Curated High Quality Fonts for Canva Typography --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

@php
    $sectionsPayload = [];
    foreach ($page->sections as $sec) {
        $rawSec = $sectionTypes[$sec->section_type] ?? null;
        $sName = is_array($rawSec) ? ($rawSec['label'] ?? $sec->section_type) : ($rawSec ?? $sec->section_type);
        
        $elemList = [];
        foreach ($sec->elements as $el) {
            $cDec = is_string($el->content) ? json_decode($el->content, true) : $el->content;
            $elemList[] = [
                'id' => $el->id,
                'key' => $el->element_key,
                'label' => ucwords(str_replace('_', ' ', $el->element_key)),
                'type' => $el->element_type,
                'value' => is_string($el->content) ? $el->content : '',
                'url' => is_array($cDec) ? ($cDec['url'] ?? '') : (is_string($el->content) ? $el->content : ''),
                'alt' => is_array($cDec) ? ($cDec['alt'] ?? '') : '',
                'text' => is_array($cDec) ? ($cDec['text'] ?? '') : '',
            ];
        }

        $sectionsPayload[$sec->id] = [
            'id' => $sec->id,
            'name' => $sName,
            'type' => $sec->section_type,
            'anchor' => $sec->custom_anchor_id ?? '',
            'settings' => $sec->settings ?? [],
            'elements' => $elemList,
        ];
    }
@endphp

{{-- Dynamically Inject Saved Typography & Placement Styles --}}
<style id="canva-dynamic-styles">
@foreach($page->sections as $sec)
    @if(!empty($sec->settings['styles']) && is_array($sec->settings['styles']))
        @foreach($sec->settings['styles'] as $targetKey => $rules)
            @php
                $cleanRules = [];
                foreach($rules as $p => $v) {
                    $k = Str::kebab($p);
                    if (!in_array($k, ['top', 'left', 'position', 'transform', 'z-index'])) {
                        $cleanRules[$k] = html_entity_decode($v, ENT_QUOTES, 'UTF-8');
                    }
                }
            @endphp
            @if(!empty($cleanRules))
                @if(str_starts_with($targetKey, 'custom_'))
                    {{-- Custom Canva Placed Element: target ONLY the exact element/wrapper, never its descendants --}}
                    #section-view-{{ $sec->id }} [data-key="{{ $targetKey }}"]
                    {
                        @foreach($cleanRules as $p => $v)
                            {!! $p !!}: {!! $v !!} !important;
                        @endforeach
                    }
                @else
                    {{-- Standard Section Elements: Box properties apply ONLY to the element; Typography applies to element + descendants --}}
                    @php
                        $boxProps = ['width', 'height', 'min-width', 'max-width', 'min-height', 'max-height', 'padding', 'margin', 'border', 'border-radius', 'background', 'background-color', 'box-shadow', 'display'];
                        $boxRules = array_filter($cleanRules, fn($k) => in_array($k, $boxProps), ARRAY_FILTER_USE_KEY);
                        $textRules = array_filter($cleanRules, fn($k) => !in_array($k, $boxProps), ARRAY_FILTER_USE_KEY);
                    @endphp

                    @if(!empty($boxRules))
                    #section-view-{{ $sec->id }} [data-key="{{ $targetKey }}"]
                    @if(in_array($targetKey, ['primary_cta', 'secondary_cta', 'view_all', 'button', 'cta']))
                        , #section-view-{{ $sec->id }} a[data-key="{{ $targetKey }}"], #section-view-{{ $sec->id }} a.btn-primary
                    @endif
                    {
                        @foreach($boxRules as $p => $v)
                            {!! $p !!}: {!! $v !!} !important;
                        @endforeach
                    }
                    @endif

                    @if(!empty($textRules))
                    #section-view-{{ $sec->id }} [data-key="{{ $targetKey }}"],
                    #section-view-{{ $sec->id }} [data-key="{{ $targetKey }}"] *
                    @if(in_array($targetKey, ['headline', 'heading', 'title']))
                        , #section-view-{{ $sec->id }} h1, #section-view-{{ $sec->id }} h2, #section-view-{{ $sec->id }} h1 *, #section-view-{{ $sec->id }} h2 *
                    @elseif(in_array($targetKey, ['subheadline', 'subheading', 'subtitle']))
                        , #section-view-{{ $sec->id }} h3, #section-view-{{ $sec->id }} h4, #section-view-{{ $sec->id }} [data-key="subheadline"] *, #section-view-{{ $sec->id }} span.text-accent-primary
                    @elseif(in_array($targetKey, ['body', 'content', 'description']))
                        , #section-view-{{ $sec->id }} .prose, #section-view-{{ $sec->id }} p, #section-view-{{ $sec->id }} .prose *, #section-view-{{ $sec->id }} p *
                    @elseif($targetKey === 'primary_cta')
                        , #section-view-{{ $sec->id }} a[data-key="primary_cta"], #section-view-{{ $sec->id }} a.btn-primary
                    @elseif($targetKey === 'secondary_cta')
                        , #section-view-{{ $sec->id }} a[data-key="secondary_cta"], #section-view-{{ $sec->id }} a.btn-secondary
                    @endif
                    {
                        @foreach($textRules as $p => $v)
                            {!! $p !!}: {!! $v !!} !important;
                        @endforeach
                    }
                    @endif
                @endif
            @endif
        @endforeach
    @endif
    @if(!empty($sec->settings['content_alignment']))
        #section-view-{{ $sec->id }} .canva-rendered-content,
        #section-view-{{ $sec->id }} .canva-rendered-content > section > div {
            text-align: {{ $sec->settings['content_alignment'] }} !important;
        }
    @endif
    @if(!empty($sec->settings['padding']))
        #section-view-{{ $sec->id }} section {
            padding-top: {{ $sec->settings['padding'] === 'compact' ? '2.5rem' : ($sec->settings['padding'] === 'spacious' ? '7rem' : '4.5rem') }} !important;
            padding-bottom: {{ $sec->settings['padding'] === 'compact' ? '2.5rem' : ($sec->settings['padding'] === 'spacious' ? '7rem' : '4.5rem') }} !important;
        }
    @endif
    @if(!empty($sec->settings['overlay_opacity']))
        #section-view-{{ $sec->id }} [class*="inset-0"][class*="bg-"] {
            opacity: {{ $sec->settings['overlay_opacity'] }} !important;
        }
    @endif
@endforeach

    /* =========================================================
       AUTHENTIC MOBILE & TABLET DEVICE VIEWPORT SIMULATION ENGINE
       Overrides Tailwind desktop media-query classes inside
       #canvas-inner so phone & tablet viewports look 100% authentic
       ========================================================= */

    /* 1. MOBILE VIEWPORT SIMULATION (390px iPhone / Modern Android) */
    #canvas-inner.device-mobile {
        width: 390px !important;
        max-width: 390px !important;
        border-radius: 40px !important;
        border: 12px solid #111827 !important;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1) inset !important;
        overflow: hidden !important;
        margin: 1.5rem auto !important;
    }

    /* Header Nav on Mobile: Hide desktop links & CTA, Show mobile hamburger */
    #canvas-inner.device-mobile header nav.hidden.lg\:flex,
    #canvas-inner.device-mobile header .hidden.lg\:flex,
    #canvas-inner.device-mobile header [class*="hidden"][class*="lg:flex"],
    #canvas-inner.device-mobile header [class*="space-x-7"],
    #canvas-inner.device-mobile header [class*="space-x-4"] {
        display: none !important;
    }
    #canvas-inner.device-mobile header button.lg\:hidden,
    #canvas-inner.device-mobile header .lg\:hidden {
        display: inline-flex !important;
    }
    #canvas-inner.device-mobile header .h-16,
    #canvas-inner.device-mobile header .lg\:h-20 {
        height: 4.25rem !important;
    }

    /* Grids on Mobile: Single vertical column for all sections */
    #canvas-inner.device-mobile .grid,
    #canvas-inner.device-mobile [class*="grid-cols-"],
    #canvas-inner.device-mobile [class*="sm:grid-cols-"],
    #canvas-inner.device-mobile [class*="md:grid-cols-"],
    #canvas-inner.device-mobile [class*="lg:grid-cols-"] {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 1.5rem !important;
    }

    /* Flex Rows on Mobile: Stack vertically */
    #canvas-inner.device-mobile [class*="sm:flex-row"],
    #canvas-inner.device-mobile [class*="md:flex-row"],
    #canvas-inner.device-mobile [class*="lg:flex-row"] {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    #canvas-inner.device-mobile [class*="sm:items-end"] {
        align-items: flex-start !important;
    }
    #canvas-inner.device-mobile [class*="sm:justify-between"] {
        justify-content: flex-start !important;
    }

    /* Typography on Mobile: Proportional scaling so titles never awkwardly wrap */
    #canvas-inner.device-mobile h1,
    #canvas-inner.device-mobile [class*="lg:text-7xl"],
    #canvas-inner.device-mobile [class*="sm:text-6xl"] {
        font-size: 2.25rem !important;
        line-height: 2.6rem !important;
    }
    #canvas-inner.device-mobile h2,
    #canvas-inner.device-mobile [class*="lg:text-5xl"],
    #canvas-inner.device-mobile [class*="sm:text-4xl"],
    #canvas-inner.device-mobile [class*="text-3xl"] {
        font-size: 1.65rem !important;
        line-height: 2.1rem !important;
    }
    #canvas-inner.device-mobile h3,
    #canvas-inner.device-mobile [class*="text-2xl"] {
        font-size: 1.35rem !important;
        line-height: 1.75rem !important;
    }
    #canvas-inner.device-mobile [class*="sm:text-xl"] {
        font-size: 1rem !important;
        line-height: 1.5rem !important;
    }

    /* Section Padding on Mobile */
    #canvas-inner.device-mobile section {
        padding-top: 3.5rem !important;
        padding-bottom: 3.5rem !important;
    }
    #canvas-inner.device-mobile [class*="py-28"],
    #canvas-inner.device-mobile [class*="py-24"],
    #canvas-inner.device-mobile [class*="py-20"] {
        padding-top: 3.5rem !important;
        padding-bottom: 3.5rem !important;
    }
    #canvas-inner.device-mobile [class*="px-8"],
    #canvas-inner.device-mobile [class*="sm:px-6"],
    #canvas-inner.device-mobile [class*="lg:px-8"] {
        padding-left: 1.25rem !important;
        padding-right: 1.25rem !important;
    }
    #canvas-inner.device-mobile .min-h-\[90vh\] {
        min-height: 70vh !important;
    }
    #canvas-inner.device-mobile .btn-primary,
    #canvas-inner.device-mobile .btn-secondary {
        width: 100% !important;
        text-align: center !important;
        justify-content: center !important;
    }
    #canvas-inner.device-mobile .canva-draggable-item {
        max-width: 92% !important;
    }
    #canvas-inner.device-mobile .canva-component-root {
        min-width: 0 !important;
        width: 100% !important;
    }

    /* 2. TABLET VIEWPORT SIMULATION (768px iPad / Tablet) */
    #canvas-inner.device-tablet {
        width: 768px !important;
        max-width: 768px !important;
        border-radius: 28px !important;
        border: 10px solid #1f2937 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35) !important;
        overflow: hidden !important;
        margin: 1.5rem auto !important;
    }

    /* Header Nav on Tablet */
    #canvas-inner.device-tablet header nav.hidden.lg\:flex,
    #canvas-inner.device-tablet header .hidden.lg\:flex {
        display: none !important;
    }
    #canvas-inner.device-tablet header button.lg\:hidden,
    #canvas-inner.device-tablet header .lg\:hidden {
        display: inline-flex !important;
    }

    /* Grids on Tablet: 2 columns */
    #canvas-inner.device-tablet [class*="grid-cols-"],
    #canvas-inner.device-tablet [class*="lg:grid-cols-3"],
    #canvas-inner.device-tablet [class*="lg:grid-cols-4"] {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 1.5rem !important;
    }

    /* Typography on Tablet */
    #canvas-inner.device-tablet h1,
    #canvas-inner.device-tablet [class*="lg:text-7xl"] {
        font-size: 3rem !important;
        line-height: 3.5rem !important;
    }
    #canvas-inner.device-tablet h2,
    #canvas-inner.device-tablet [class*="lg:text-5xl"] {
        font-size: 2.15rem !important;
        line-height: 2.6rem !important;
    }
    #canvas-inner.device-tablet section {
        padding-top: 4.5rem !important;
        padding-bottom: 4.5rem !important;
    }
    #canvas-inner.device-tablet [class*="px-8"],
    #canvas-inner.device-tablet [class*="lg:px-8"] {
        padding-left: 2rem !important;
        padding-right: 2rem !important;
    }
</style>

<div class="h-full w-full flex flex-col bg-gray-100 overflow-hidden overflow-x-hidden font-body" x-data="canvaEditor()">

    {{-- CANVA TOPBAR: Global controls + Device viewports --}}
    <div class="bg-gray-950 text-white px-4 py-2 flex items-center justify-between border-b border-gray-800 shadow-md shrink-0 z-40 select-none">
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('admin.pages.index') }}" class="p-1.5 hover:bg-gray-800 rounded-lg text-gray-400 hover:text-white transition-colors" title="Back to Pages">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>

            {{-- Full Admin Navigation Menu Toggle --}}
            <button type="button" 
                    @click="$dispatch('toggle-admin-menu')"
                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-900 hover:bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold border border-gray-700 hover:border-accent-primary transition-all shadow-xs cursor-pointer" 
                    title="Open Full Admin Menu (Dashboard, Expeditions, Enquiries, Settings)">
                <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span class="font-bold text-3xs uppercase tracking-wider hidden sm:inline">Admin Menu</span>
            </button>

            <div class="h-4 w-px bg-gray-800"></div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-display font-bold text-white text-sm tracking-wide">{{ $page->title }}</span>
                    <span class="text-3xs font-mono px-2 py-0.5 rounded-full font-bold uppercase {{ $page->is_published ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                        {{ $page->is_published ? 'Live' : 'Draft' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Center: Responsive Canvas Viewport Switcher & View Toggles --}}
        <div class="flex items-center gap-2">
            {{-- Device View Switcher --}}
            <div class="flex items-center bg-gray-900 p-0.5 rounded-lg border border-gray-800 text-xs">
                <button type="button" @click="setDeviceView('desktop')" 
                        :class="deviceView === 'desktop' ? 'bg-accent-primary text-white shadow-xs font-bold' : 'text-gray-400 hover:text-white'"
                        class="px-2.5 py-1 rounded transition-all flex items-center gap-1.5"
                        title="Desktop View (1280px)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="hidden sm:inline">Desktop</span>
                </button>
                <button type="button" @click="setDeviceView('tablet')" 
                        :class="deviceView === 'tablet' ? 'bg-accent-primary text-white shadow-xs font-bold' : 'text-gray-400 hover:text-white'"
                        class="px-2.5 py-1 rounded transition-all flex items-center gap-1.5"
                        title="Tablet View (768px)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span class="hidden sm:inline">Tablet</span>
                </button>
                <button type="button" @click="setDeviceView('mobile')" 
                        :class="deviceView === 'mobile' ? 'bg-accent-primary text-white shadow-xs font-bold' : 'text-gray-400 hover:text-white'"
                        class="px-2.5 py-1 rounded transition-all flex items-center gap-1.5"
                        title="Mobile View (390px)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span class="hidden sm:inline">Mobile</span>
                </button>
            </div>

            {{-- VIEW TOGGLES: Hide/Show Navbar on Canvas & Hide/Show Sidebar --}}
            <div class="flex items-center bg-gray-900 p-0.5 rounded-lg border border-gray-800 text-xs">
                {{-- 1. Navbar Visibility Toggle on Edit Page --}}
                <button type="button" 
                        @click="toggleNavbar()" 
                        :class="!showNavbar ? 'bg-amber-500/20 text-amber-400 font-bold border border-amber-500/40 shadow-2xs' : 'text-gray-400 hover:text-white'"
                        class="px-2.5 py-1 rounded transition-all flex items-center gap-1.5"
                        :title="showNavbar ? 'Click to hide Website Navbar in edit view' : 'Click to show Website Navbar in edit view'">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="showNavbar" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        <path x-show="!showNavbar" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                    </svg>
                    <span class="hidden md:inline" x-text="showNavbar ? 'Navbar' : 'Navbar Hidden'"></span>
                </button>

                <div class="h-3 w-px bg-gray-800 my-auto"></div>

                {{-- 2. Sidebar Visibility Toggle (especially useful in Mobile display) --}}
                <button type="button" 
                        @click="toggleSidebar()" 
                        :class="!showSidebar ? 'bg-amber-500/20 text-amber-400 font-bold border border-amber-500/40 shadow-2xs' : 'text-gray-400 hover:text-white'"
                        class="px-2.5 py-1 rounded transition-all flex items-center gap-1.5"
                        :title="showSidebar ? 'Click to hide Editor Sidebar (collapses tool dock & drawer)' : 'Click to show Editor Sidebar'">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span class="hidden md:inline" x-text="showSidebar ? 'Sidebar' : 'Sidebar Hidden'"></span>
                </button>
            </div>
        </div>

        {{-- Right Controls: Preview & Auto-Save status --}}
        <div class="flex items-center gap-3">
            <span x-show="savedNotice" x-transition.opacity class="text-xs font-bold text-emerald-400 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Changes Saved</span>
            </span>

            <a href="{{ url($page->slug === 'home' ? '/' : '/' . $page->slug) }}" target="_blank" 
               @click="if (_flushStylePending || (activeTextElement && activeTextElement.isContentEditable)) { $event.preventDefault(); saveAllChanges().then(() => window.open('{{ url($page->slug === 'home' ? '/' : '/' . $page->slug) }}', '_blank')); }"
               class="px-3 py-1.5 text-xs font-semibold text-gray-300 hover:text-white bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors flex items-center gap-1.5 border border-gray-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Preview Site</span>
            </a>

            <button type="button" @click="saveAllChanges()" 
                    class="btn-primary py-1.5 px-4 text-xs font-bold shadow-md flex items-center gap-1.5"
                    :disabled="isSaving">
                <svg x-show="isSaving" class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span x-text="isSaving ? 'Saving...' : 'Save All'"></span>
            </button>
        </div>
    </div>

    {{-- CANVA / FIGMA FLOATING CONTEXTUAL FORMATTING TOOLBAR --}}
    <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center justify-between gap-3 shadow-xs shrink-0 z-30 select-none overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-gray-200 hover:scrollbar-thumb-gray-400" 
         @wheel.passive="if ($event.deltaY && !$event.deltaX) { $el.scrollLeft += $event.deltaY * 0.7; }">
        
        {{-- Left: Active Element Indicator or Typography Tools --}}
        <div class="flex items-center gap-2 shrink-0">
            <span class="text-3xs uppercase font-extrabold text-accent-primary bg-accent-primary/10 px-2.5 py-1 rounded-md flex items-center gap-1 shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-accent-primary animate-pulse"></span>
                <span x-text="selectedTag || (activeTextKey ? ('Styling: ' + activeTextKey) : (activeSectionId ? ('Block #' + activeSectionId) : 'Canvas'))"></span>
            </span>

            {{-- 1. BUTTON QUICK INSPECTOR (HIGHEST PRIORITY WHEN BUTTON IS SELECTED - SHOWN FIRST SO NEVER CUT OFF) --}}
            <div x-show="isButtonSelected" class="flex items-center gap-1.5 bg-orange-50 border border-orange-300 px-2.5 py-1 rounded-lg shadow-2xs shrink-0">
                <span class="text-3xs uppercase font-extrabold text-accent-primary">Btn:</span>
                <input type="text" x-model="activeButtonText" @input="updateActiveButton('text', $event.target.value)" 
                       class="form-input text-xs py-0.5 px-2 bg-white border-orange-300 rounded font-semibold w-24 text-gray-900" placeholder="Button Text" title="Edit Button Label">
                <input type="text" x-model="activeButtonUrl" @input="updateActiveButton('url', $event.target.value)" 
                       class="form-input text-3xs py-0.5 px-2 bg-white border-orange-300 rounded font-mono text-gray-700 w-28" placeholder="URL (/packages)" title="Edit Button Link">
                
                {{-- Button Size Controls (XS, SM, MD, LG, XL) --}}
                <div class="h-3.5 w-px bg-orange-300"></div>
                <div class="flex items-center gap-1">
                    <span class="text-3xs uppercase font-extrabold text-orange-700">Size:</span>
                    <div class="flex items-center bg-white rounded-md border border-orange-200 p-0.5 shadow-2xs">
                        <button type="button" @click="setButtonSize('xs')" :class="activeButtonSize === 'xs' ? 'bg-orange-600 text-white font-extrabold' : 'text-gray-700 hover:bg-orange-100'" class="px-1.5 py-0.5 text-4xs rounded font-bold transition-colors" title="Extra Small Button">XS</button>
                        <button type="button" @click="setButtonSize('sm')" :class="activeButtonSize === 'sm' ? 'bg-orange-600 text-white font-extrabold' : 'text-gray-700 hover:bg-orange-100'" class="px-1.5 py-0.5 text-4xs rounded font-bold transition-colors" title="Small Button">SM</button>
                        <button type="button" @click="setButtonSize('md')" :class="activeButtonSize === 'md' ? 'bg-orange-600 text-white font-extrabold' : 'text-gray-700 hover:bg-orange-100'" class="px-1.5 py-0.5 text-4xs rounded font-bold transition-colors" title="Medium Button">MD</button>
                        <button type="button" @click="setButtonSize('lg')" :class="activeButtonSize === 'lg' ? 'bg-orange-600 text-white font-extrabold' : 'text-gray-700 hover:bg-orange-100'" class="px-1.5 py-0.5 text-4xs rounded font-bold transition-colors" title="Large Button">LG</button>
                        <button type="button" @click="setButtonSize('xl')" :class="activeButtonSize === 'xl' ? 'bg-orange-600 text-white font-extrabold' : 'text-gray-700 hover:bg-orange-100'" class="px-1.5 py-0.5 text-4xs rounded font-bold transition-colors" title="Extra Large Button">XL</button>
                    </div>
                </div>

                {{-- Button Padding Stepper --}}
                <div class="flex items-center bg-white rounded-md border border-orange-200 p-0.5 shadow-2xs">
                    <button type="button" @click="stepButtonPadding(-2, -4)" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-orange-100 rounded" title="Decrease Button Padding">-</button>
                    <span class="text-4xs font-mono font-bold text-orange-700 px-1" x-text="(currentPaddingV || 10) + 'px'"></span>
                    <button type="button" @click="stepButtonPadding(2, 4)" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-orange-100 rounded" title="Increase Button Padding">+</button>
                </div>
            </div>

            {{-- 2. PHOTO BOX QUICK INSPECTOR (HIGHEST PRIORITY WHEN PHOTO IS SELECTED) --}}
            <div x-show="isPhotoSelected" class="flex items-center gap-1.5 bg-indigo-50 border border-indigo-300 px-2.5 py-1 rounded-lg shadow-2xs shrink-0">
                <span class="text-3xs uppercase font-extrabold text-indigo-700 flex items-center gap-1">
                    <span>📷</span>
                    <span>Photo Box:</span>
                </span>
                
                <button type="button" @click="openImageModal(activeSectionId, activeCustomElementId)" 
                        class="px-2 py-0.5 text-3xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow-2xs flex items-center gap-1 transition-colors" 
                        title="Choose or Upload New Image for this Box">
                    <span>Change Image</span>
                </button>

                <div class="h-3.5 w-px bg-indigo-300"></div>

                {{-- Corner Radius / Shape --}}
                <div class="flex items-center gap-1">
                    <span class="text-4xs uppercase font-bold text-gray-500">Shape:</span>
                    <div class="flex items-center bg-white rounded border border-indigo-200 p-0.5 shadow-2xs">
                        <button type="button" @click="setPhotoStyle('borderRadius', '0px')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Square Corners">Square</button>
                        <button type="button" @click="setPhotoStyle('borderRadius', '16px')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Rounded Corners">Rounded</button>
                        <button type="button" @click="setPhotoStyle('borderRadius', '9999px')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Circular Badge">Circle</button>
                    </div>
                </div>

                <div class="h-3.5 w-px bg-indigo-300"></div>

                {{-- Aspect Ratio --}}
                <div class="flex items-center gap-1">
                    <span class="text-4xs uppercase font-bold text-gray-500">Ratio:</span>
                    <div class="flex items-center bg-white rounded border border-indigo-200 p-0.5 shadow-2xs">
                        <button type="button" @click="setPhotoStyle('aspectRatio', '16/9')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Landscape 16:9">16:9</button>
                        <button type="button" @click="setPhotoStyle('aspectRatio', '4/3')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Standard 4:3">4:3</button>
                        <button type="button" @click="setPhotoStyle('aspectRatio', '1/1')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Square 1:1">1:1</button>
                        <button type="button" @click="setPhotoStyle('aspectRatio', '3/4')" class="px-1.5 py-0.5 text-4xs font-bold text-gray-700 hover:bg-indigo-100 rounded" title="Portrait 3:4">3:4</button>
                    </div>
                </div>

                <div class="h-3.5 w-px bg-indigo-300"></div>

                {{-- Shadow & Frame --}}
                <div class="flex items-center gap-1">
                    <button type="button" @click="togglePhotoShadow()" class="px-1.5 py-0.5 text-4xs font-bold bg-white text-gray-700 hover:bg-indigo-100 border border-indigo-200 rounded shadow-2xs" title="Toggle Shadow">Shadow</button>
                    <button type="button" @click="togglePhotoBorder()" class="px-1.5 py-0.5 text-4xs font-bold bg-white text-gray-700 hover:bg-indigo-100 border border-indigo-200 rounded shadow-2xs" title="Toggle Border Frame">Border</button>
                </div>
            </div>

            {{-- 3. QUICK CANVA PLACEMENT (WHEN SELECTED PLACED ELEMENT) --}}
            <div x-show="activeTextKey && activeTextKey.startsWith('custom_')" class="flex items-center gap-1 bg-gray-100 p-0.5 rounded-lg border border-gray-300 shrink-0">
                <span class="text-3xs uppercase font-extrabold text-gray-500 px-1 flex items-center gap-0.5">
                    <svg class="w-3 h-3 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                    Place:
                </span>
                <button type="button" @click="setCustomElementPosition('20%', '20%')" class="px-1.5 py-0.5 text-3xs font-bold hover:bg-white text-gray-700 rounded transition-colors" title="Top Left">Top-L</button>
                <button type="button" @click="setCustomElementPosition('20%', '50%')" class="px-1.5 py-0.5 text-3xs font-bold hover:bg-white text-gray-700 rounded transition-colors" title="Top Center">Top-C</button>
                <button type="button" @click="setCustomElementPosition('20%', '80%')" class="px-1.5 py-0.5 text-3xs font-bold hover:bg-white text-gray-700 rounded transition-colors" title="Top Right">Top-R</button>
                <button type="button" @click="setCustomElementPosition('50%', '50%')" class="px-1.5 py-0.5 text-3xs font-bold bg-white text-accent-primary shadow-2xs rounded transition-colors" title="Center">Center</button>
                <button type="button" @click="setCustomElementPosition('80%', '50%')" class="px-1.5 py-0.5 text-3xs font-bold hover:bg-white text-gray-700 rounded transition-colors" title="Bottom Center">Bottom-C</button>
            </div>

            <div class="h-4 w-px bg-gray-300 shrink-0" x-show="isButtonSelected || isPhotoSelected"></div>

            {{-- FONT FAMILY PICKER --}}
            <div class="relative shrink-0">
                <select :value="currentStyles.fontFamily" 
                        @change="applyStyle('fontFamily', $event.target.value)" 
                        class="form-input text-xs py-1 px-2.5 bg-gray-50 border-gray-300 font-semibold text-gray-800 rounded-lg cursor-pointer hover:bg-gray-100"
                        title="Font Family">
                    <option value="'Cinzel', serif">Cinzel (Himalayan / Display)</option>
                    <option value="'Cormorant Garamond', serif">Cormorant Garamond (Luxury Serif)</option>
                    <option value="'Playfair Display', serif">Playfair Display (Editorial)</option>
                    <option value="'Outfit', sans-serif">Outfit (Modern Clean)</option>
                    <option value="'Space Grotesk', sans-serif">Space Grotesk (Adventure)</option>
                    <option value="'Montserrat', sans-serif">Montserrat (Geometric Bold)</option>
                    <option value="'Inter', sans-serif">Inter (Clean Sans)</option>
                </select>
            </div>

            {{-- FONT SIZE CONTROLS (Direct Input + Steppers) --}}
            <div class="flex items-center bg-gray-100 rounded-lg border border-gray-300 p-0.5 shrink-0">
                <button type="button" @click="stepFontSize(-2)" class="px-2 py-0.5 text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded" title="Decrease Size">-</button>
                <div class="flex items-center px-1">
                    <input type="number" min="10" max="140" x-model.number="currentFontSize" 
                           @input="applyStyle('fontSize', currentFontSize + 'px')"
                           class="w-10 text-center text-xs font-mono font-bold text-gray-800 bg-transparent border-0 p-0 focus:ring-0 focus:outline-none" title="Type Font Size (px)">
                    <span class="text-3xs font-mono text-gray-400">px</span>
                </div>
                <button type="button" @click="stepFontSize(2)" class="px-2 py-0.5 text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded" title="Increase Size">+</button>
            </div>

            <div class="h-4 w-px bg-gray-300 shrink-0"></div>

            {{-- FONT WEIGHT & DECORATION --}}
            <div class="flex items-center gap-0.5 bg-gray-100 p-0.5 rounded-lg border border-gray-300 shrink-0">
                <button type="button" @click="toggleStyle('fontWeight', 'bold', 'normal')" 
                        :class="currentStyles.fontWeight === 'bold' ? 'bg-white shadow-2xs font-extrabold text-accent-primary' : 'text-gray-600 hover:text-gray-900'"
                        class="px-2.5 py-1 text-xs rounded transition-all font-bold" title="Bold">
                    B
                </button>
                <button type="button" @click="toggleStyle('fontStyle', 'italic', 'normal')" 
                        :class="currentStyles.fontStyle === 'italic' ? 'bg-white shadow-2xs italic font-bold text-accent-primary' : 'text-gray-600 hover:text-gray-900'"
                        class="px-2.5 py-1 text-xs rounded transition-all italic font-serif" title="Italic">
                    I
                </button>
                <button type="button" @click="toggleStyle('textDecoration', 'underline', 'none')" 
                        :class="currentStyles.textDecoration === 'underline' ? 'bg-white shadow-2xs underline font-bold text-accent-primary' : 'text-gray-600 hover:text-gray-900'"
                        class="px-2.5 py-1 text-xs rounded transition-all underline" title="Underline">
                    U
                </button>
                <button type="button" @click="toggleStyle('textTransform', 'uppercase', 'none')" 
                        :class="currentStyles.textTransform === 'uppercase' ? 'bg-white shadow-2xs font-bold text-accent-primary' : 'text-gray-600 hover:text-gray-900'"
                        class="px-2 py-1 text-3xs rounded transition-all uppercase font-bold" title="Uppercase">
                    TT
                </button>
            </div>

            <div class="h-4 w-px bg-gray-300 shrink-0"></div>

            {{-- COLOR PALETTE SWATCHES --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="text-3xs font-bold text-gray-400 uppercase">Color:</span>
                <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg border border-gray-300">
                    <button type="button" @click="applyStyle('color', '#ffffff')" class="w-4 h-4 rounded-full bg-white border border-gray-400 shadow-2xs hover:scale-125 transition-transform" title="White"></button>
                    <button type="button" @click="applyStyle('color', '#ea580c')" class="w-4 h-4 rounded-full bg-orange-600 shadow-2xs hover:scale-125 transition-transform" title="Himalayan Orange"></button>
                    <button type="button" @click="applyStyle('color', '#d4af37')" class="w-4 h-4 rounded-full bg-amber-500 shadow-2xs hover:scale-125 transition-transform" title="Gold"></button>
                    <button type="button" @click="applyStyle('color', '#38bdf8')" class="w-4 h-4 rounded-full bg-sky-400 shadow-2xs hover:scale-125 transition-transform" title="Sky Blue"></button>
                    <button type="button" @click="applyStyle('color', '#10b981')" class="w-4 h-4 rounded-full bg-emerald-500 shadow-2xs hover:scale-125 transition-transform" title="Emerald"></button>
                    <button type="button" @click="applyStyle('color', '#111827')" class="w-4 h-4 rounded-full bg-gray-900 shadow-2xs hover:scale-125 transition-transform" title="Charcoal"></button>
                    
                    {{-- Custom Color Picker with active swatch indicator --}}
                    <label class="w-5 h-5 rounded-full border border-gray-400 overflow-hidden cursor-pointer shadow-2xs relative flex items-center justify-center hover:scale-110 transition-transform" title="Custom Text Color">
                        <input type="color" :value="currentStyles.color" @input="applyStyle('color', $event.target.value)" class="opacity-0 absolute inset-0 cursor-pointer w-full h-full">
                        <span class="w-full h-full rounded-full" :style="'background-color: ' + currentStyles.color"></span>
                    </label>
                </div>
            </div>

            {{-- BACKGROUND COLOR (BG) PALETTE SWATCHES --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="text-3xs font-bold text-gray-400 uppercase">Bg:</span>
                <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg border border-gray-300">
                    <button type="button" @click="applyStyle('backgroundColor', 'transparent')" class="w-4 h-4 rounded-full border border-gray-400 bg-white shadow-2xs hover:scale-125 transition-transform flex items-center justify-center text-red-500 text-4xs font-bold" title="Transparent">✕</button>
                    <button type="button" @click="applyStyle('backgroundColor', '#ea580c')" class="w-4 h-4 rounded-full bg-orange-600 shadow-2xs hover:scale-125 transition-transform" title="Orange"></button>
                    <button type="button" @click="applyStyle('backgroundColor', '#111827')" class="w-4 h-4 rounded-full bg-gray-900 shadow-2xs hover:scale-125 transition-transform" title="Dark Charcoal"></button>
                    <button type="button" @click="applyStyle('backgroundColor', '#ffffff')" class="w-4 h-4 rounded-full bg-white border border-gray-400 shadow-2xs hover:scale-125 transition-transform" title="White"></button>
                    <button type="button" @click="applyStyle('backgroundColor', '#d4af37')" class="w-4 h-4 rounded-full bg-amber-500 shadow-2xs hover:scale-125 transition-transform" title="Gold"></button>
                    <button type="button" @click="applyStyle('backgroundColor', '#0284c7')" class="w-4 h-4 rounded-full bg-sky-600 shadow-2xs hover:scale-125 transition-transform" title="Sky Blue"></button>
                    
                    {{-- Custom Background Color Picker --}}
                    <label class="w-5 h-5 rounded-full border border-gray-400 overflow-hidden cursor-pointer shadow-2xs relative flex items-center justify-center hover:scale-110 transition-transform" title="Custom Background Color">
                        <input type="color" :value="currentStyles.backgroundColor || '#ea580c'" @input="applyStyle('backgroundColor', $event.target.value)" class="opacity-0 absolute inset-0 cursor-pointer w-full h-full">
                        <span class="w-full h-full rounded-full" :style="'background-color: ' + (currentStyles.backgroundColor || 'transparent')"></span>
                    </label>
                </div>
            </div>

            {{-- BORDER RADIUS / SHAPE --}}
            <div class="flex items-center gap-0.5 bg-gray-100 p-0.5 rounded-lg border border-gray-300 shrink-0">
                <button type="button" @click="applyStyle('borderRadius', '0px')" :class="currentStyles.borderRadius === '0px' ? 'bg-white shadow-2xs font-bold text-accent-primary' : 'text-gray-600'" class="px-2 py-0.5 text-3xs rounded font-semibold" title="Sharp Corners (0px)">Sharp</button>
                <button type="button" @click="applyStyle('borderRadius', '8px')" :class="currentStyles.borderRadius === '8px' ? 'bg-white shadow-2xs font-bold text-accent-primary' : 'text-gray-600'" class="px-2 py-0.5 text-3xs rounded font-semibold" title="Rounded Corners (8px)">Round</button>
                <button type="button" @click="applyStyle('borderRadius', '9999px')" :class="currentStyles.borderRadius === '9999px' ? 'bg-white shadow-2xs font-bold text-accent-primary' : 'text-gray-600'" class="px-2 py-0.5 text-3xs rounded font-semibold" title="Pill Shape">Pill</button>
            </div>

            <div class="h-4 w-px bg-gray-300 shrink-0"></div>

            {{-- TEXT ALIGNMENT / PLACEMENT --}}
            <div class="flex items-center gap-0.5 bg-gray-100 p-0.5 rounded-lg border border-gray-300 shrink-0">
                <button type="button" @click="applyStyle('textAlign', 'left')" 
                        :class="currentStyles.textAlign === 'left' ? 'bg-white text-accent-primary shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                        class="p-1 rounded text-xs" title="Align Left">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14"></path></svg>
                </button>
                <button type="button" @click="applyStyle('textAlign', 'center')" 
                        :class="currentStyles.textAlign === 'center' ? 'bg-white text-accent-primary shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                        class="p-1 rounded text-xs" title="Align Center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"></path></svg>
                </button>
                <button type="button" @click="applyStyle('textAlign', 'right')" 
                        :class="currentStyles.textAlign === 'right' ? 'bg-white text-accent-primary shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'"
                        class="p-1 rounded text-xs" title="Align Right">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14"></path></svg>
                </button>
            </div>
        </div>

        {{-- Right: Quick Block Actions --}}
        <div class="flex items-center gap-2 shrink-0">
            <template x-if="activeSectionId">
                <div class="flex items-center gap-2">
                    <button type="button" @click="openBlockDrawer(activeSectionId)" class="px-2.5 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span>Properties</span>
                    </button>
                    <button type="button" @click="addNewTextElement(activeSectionId, 'headline')" class="btn-primary py-1 px-2.5 text-xs font-bold shadow-2xs flex items-center gap-1" title="Add text directly onto this block (drag anywhere)">
                        <span>+ Text</span>
                    </button>
                    <button type="button" @click="addNewTextElement(activeSectionId, 'button')" class="bg-gray-900 hover:bg-black text-white py-1 px-2.5 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1 transition-colors" title="Add customizable button onto this block">
                        <span>+ Button</span>
                    </button>
                    <button type="button" @click="addNewPhotoElement(activeSectionId, 'landscape')" class="bg-indigo-600 hover:bg-indigo-500 text-white py-1 px-2.5 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1 transition-colors" title="Add customizable, resizable photo box onto this block">
                        <span>📷 + Photo</span>
                    </button>
                    <button type="button" @click="leftTab = (leftTab === 'components' ? null : 'components')" class="bg-amber-600 hover:bg-amber-500 text-white py-1 px-2.5 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1 transition-colors" title="Add pre-built components (search, buttons, badges, widgets)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>+ Component</span>
                    </button>
                </div>
            </template>
        </div>

    </div>

    {{-- CANVA MAIN WORKSPACE: Side Rail + Canvas --}}
    <div class="flex-1 flex overflow-hidden overflow-x-hidden relative">

        {{-- CANVA ICON NAVIGATION RAIL (Canva / Figma Left Tool Dock) --}}
        <div x-show="showSidebar" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full"
             class="w-18 bg-gray-950 flex flex-col items-center py-3 border-r border-gray-800 shrink-0 z-30 select-none">
            <button type="button" @click="leftTab = (leftTab === 'blocks' ? null : 'blocks')" 
                    :class="leftTab === 'blocks' ? 'bg-accent-primary text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-900'"
                    class="w-14 h-13 rounded-xl flex flex-col items-center justify-center gap-0.5 transition-all mb-2" title="Design Blocks">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Blocks</span>
            </button>

            <button type="button" @click="leftTab = (leftTab === 'components' ? null : 'components')" 
                    :class="leftTab === 'components' ? 'bg-accent-primary text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-900'"
                    class="w-14 h-13 rounded-xl flex flex-col items-center justify-center gap-0.5 transition-all mb-2 relative group" title="Add Components & Widgets">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Add</span>
                <span class="absolute -top-1 -right-0.5 px-1 py-0.2 bg-amber-500 text-gray-950 font-black text-[8px] rounded-full uppercase leading-none shadow-xs">New</span>
            </button>

            <button type="button" @click="leftTab = (leftTab === 'text' ? null : 'text')" 
                    :class="leftTab === 'text' ? 'bg-accent-primary text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-900'"
                    class="w-14 h-13 rounded-xl flex flex-col items-center justify-center gap-0.5 transition-all mb-2" title="Add Text & Typography">
                <span class="text-base font-serif font-extrabold leading-none">T</span>
                <span class="text-[10px] font-bold tracking-tight">Text</span>
            </button>

            <button type="button" @click="leftTab = (leftTab === 'photos' ? null : 'photos')" 
                    :class="leftTab === 'photos' ? 'bg-accent-primary text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-900'"
                    class="w-14 h-13 rounded-xl flex flex-col items-center justify-center gap-0.5 transition-all mb-2" title="Photos & Uploads">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Photos</span>
            </button>

            <button type="button" @click="leftTab = (leftTab === 'layout' ? null : 'layout')" 
                    :class="leftTab === 'layout' ? 'bg-accent-primary text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-900'"
                    class="w-14 h-13 rounded-xl flex flex-col items-center justify-center gap-0.5 transition-all mb-2" title="Placement & Layout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Layout</span>
            </button>

            {{-- Bottom Actions: SEO Settings & Sidebar Collapse --}}
            <div class="mt-auto flex flex-col items-center gap-1 w-full px-2">
                <button type="button" @click="showSettingsModal = true" 
                        class="w-14 h-12 rounded-xl flex flex-col items-center justify-center gap-0.5 text-gray-400 hover:text-white hover:bg-gray-900 transition-all" title="Page Settings & SEO">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    <span class="text-[10px] font-bold tracking-tight">SEO</span>
                </button>

                <div class="w-8 h-px bg-gray-800 my-0.5"></div>

                <button type="button" @click="toggleSidebar(false)" 
                        class="w-14 h-10 rounded-xl flex flex-col items-center justify-center gap-0.5 text-gray-500 hover:text-white hover:bg-gray-900 transition-all group" title="Hide Sidebar (Collapse)">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    <span class="text-[9px] font-bold">Hide</span>
                </button>
            </div>
        </div>

        {{-- Floating Toggle Pill to Show Sidebar when hidden --}}
        <div x-show="!showSidebar" 
             x-transition.opacity
             class="absolute left-3 top-3 z-40 select-none"
             style="display: none;">
            <button type="button" 
                    @click="toggleSidebar(true)" 
                    class="bg-gray-950/95 hover:bg-black text-white px-3 py-2 rounded-xl shadow-2xl border border-gray-700 hover:border-accent-primary flex items-center gap-2 text-xs font-bold transition-all hover:scale-105 group backdrop-blur-sm">
                <svg class="w-4 h-4 text-accent-primary group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                <span>Show Sidebar</span>
            </button>
        </div>

        {{-- CANVA SECONDARY SLIDE DRAWER (Content for active left tab) --}}
        <div x-show="showSidebar && leftTab" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             class="w-84 sm:w-88 bg-white border-r border-gray-200 flex flex-col shrink-0 z-20 shadow-xl overflow-hidden">

            {{-- TAB: BLOCKS / TEMPLATES (DRAGGABLE TO REORDER) --}}
            <template x-if="leftTab === 'blocks'">
                <div class="flex flex-col h-full">
                    <div class="p-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <span class="text-xs font-bold uppercase text-gray-700 tracking-wider">Drag Blocks ({{ $page->sections->count() }})</span>
                        <button type="button" @click="showAddModal = true" class="text-xs font-bold text-accent-primary bg-accent-primary/10 px-2 py-0.5 rounded hover:bg-accent-primary/20">+ Add Block</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-2.5 space-y-2">
                        @foreach($page->sections as $idx => $sec)
                        @php
                            $rawSec = $sectionTypes[$sec->section_type] ?? null;
                            $sName = is_array($rawSec) ? ($rawSec['label'] ?? $sec->section_type) : ($rawSec ?? $sec->section_type);
                        @endphp
                        <div class="p-2.5 rounded-xl border transition-all cursor-grab active:cursor-grabbing flex items-center justify-between gap-2 group/drag"
                             draggable="true"
                             @dragstart="onSectionDragStart($event, {{ $sec->id }})"
                             @dragend="onSectionDragEnd($event)"
                             @dragover="onSectionDragOver($event, {{ $sec->id }})"
                             @drop.prevent="onSectionDrop($event, {{ $sec->id }})"
                             :class="{
                                 'bg-accent-primary/10 border-accent-primary shadow-xs': activeSectionId == {{ $sec->id }},
                                 'border-t-4 border-accent-primary': dropTargetSectionId == {{ $sec->id }},
                                 'bg-white border-gray-200 hover:border-gray-300': activeSectionId != {{ $sec->id }} && dropTargetSectionId != {{ $sec->id }}
                             }"
                             @click="scrollToSection({{ $sec->id }})">
                            <div class="flex items-center gap-2 truncate">
                                <span class="text-gray-400 group-hover/drag:text-accent-primary cursor-grab font-bold">⠿</span>
                                <div class="truncate">
                                    <span class="font-bold text-xs text-gray-900 block truncate leading-tight">{{ $sName }}</span>
                                    <span class="text-3xs text-gray-400 font-mono">{{ $sec->section_type }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5 shrink-0">
                                <button type="button" @click.stop="moveSection({{ $sec->id }}, 'up')" class="p-1 hover:bg-gray-200 rounded text-gray-400 hover:text-gray-800" title="Move Up">↑</button>
                                <button type="button" @click.stop="moveSection({{ $sec->id }}, 'down')" class="p-1 hover:bg-gray-200 rounded text-gray-400 hover:text-gray-800" title="Move Down">↓</button>
                                <button type="button" @click.stop="deleteSection({{ $sec->id }}, '{{ addslashes($sName) }}')" class="p-1 hover:bg-red-100 rounded text-gray-400 hover:text-red-600" title="Delete">✕</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </template>

            {{-- TAB: COMPONENTS & WIDGETS (SEARCH, BUTTONS, BADGES, CARDS) --}}
            <template x-if="leftTab === 'components'">
                <div class="flex flex-col h-full bg-white select-none">
                    {{-- Header --}}
                    <div class="p-3.5 border-b border-gray-200 flex items-center justify-between bg-gray-50/90">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                            <span class="text-xs font-display font-bold uppercase text-gray-800 tracking-wider">Components & Widgets</span>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-700 text-xs p-1 rounded-md hover:bg-gray-200 transition-colors" @click="leftTab = null">✕</button>
                    </div>

                    {{-- Search & Filter Section --}}
                    <div class="p-3 border-b border-gray-100 bg-white space-y-2.5">
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" x-model="componentSearch" placeholder="Search components..." 
                                   class="w-full pl-8 pr-7 py-1.5 text-xs bg-gray-50 hover:bg-white border border-gray-200 rounded-lg focus:border-accent-primary focus:bg-white focus:ring-1 focus:ring-accent-primary transition-all">
                            <button type="button" x-show="componentSearch" @click="componentSearch = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 text-3xs font-bold">✕</button>
                        </div>
                        <div class="flex items-center gap-1 overflow-x-auto pb-0.5 select-none no-scrollbar">
                            <button type="button" @click="componentCategory = 'all'" :class="componentCategory === 'all' ? 'bg-gray-900 text-white font-bold shadow-2xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 text-3xs rounded-full transition-colors whitespace-nowrap">All</button>
                            <button type="button" @click="componentCategory = 'search'" :class="componentCategory === 'search' ? 'bg-gray-900 text-white font-bold shadow-2xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 text-3xs rounded-full transition-colors whitespace-nowrap">Search</button>
                            <button type="button" @click="componentCategory = 'buttons'" :class="componentCategory === 'buttons' ? 'bg-gray-900 text-white font-bold shadow-2xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 text-3xs rounded-full transition-colors whitespace-nowrap">Buttons</button>
                            <button type="button" @click="componentCategory = 'badges'" :class="componentCategory === 'badges' ? 'bg-gray-900 text-white font-bold shadow-2xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 text-3xs rounded-full transition-colors whitespace-nowrap">Badges</button>
                            <button type="button" @click="componentCategory = 'cards'" :class="componentCategory === 'cards' ? 'bg-gray-900 text-white font-bold shadow-2xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-2.5 py-1 text-3xs rounded-full transition-colors whitespace-nowrap">Cards</button>
                        </div>
                    </div>

                    {{-- Dynamic Hint Banner --}}
                    <div class="px-3 py-1.5 bg-orange-50/70 border-b border-orange-100 flex items-center justify-between text-3xs text-orange-950">
                        <span class="flex items-center gap-1.5">
                            <span>💡</span>
                            <span class="font-semibold">Drag onto canvas or click +Add</span>
                        </span>
                        <span class="text-4xs text-orange-700 font-mono">Anywhere</span>
                    </div>

                    {{-- Scrollable Component Cards List --}}
                    <div class="flex-1 overflow-y-auto p-3 space-y-4 canva-drawer-scroll">
                        
                        {{-- Search Bars Category --}}
                        <div x-show="componentCategory === 'all' || componentCategory === 'search'">
                            <div class="flex items-center gap-1.5 text-2xs font-extrabold uppercase text-gray-600 tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <span>Search Bars</span>
                            </div>
                            <div class="space-y-2.5">
                                {{-- Expedition Search Bar --}}
                                <div x-show="filterComponent('search', 'Expedition Search', 'packages treks')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'search_packages', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Expedition Search</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'search_packages')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">Live search bar linking to packages & treks</p>
                                    <div class="p-1.5 bg-gray-50 rounded-lg flex items-center gap-2 border border-gray-200">
                                        <span class="text-accent-primary pl-1 text-xs">🔍</span>
                                        <span class="text-4xs text-gray-400 font-medium truncate flex-1">Search Everest, Treks...</span>
                                        <span class="bg-accent-primary text-white text-4xs px-2 py-0.5 rounded font-bold whitespace-nowrap">Explore</span>
                                    </div>
                                </div>

                                {{-- Destination Finder --}}
                                <div x-show="filterComponent('search', 'Destination Finder', 'regions peaks')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'search_destinations', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Destination Finder</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'search_destinations')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">Dark luxury search for regions & peaks</p>
                                    <div class="p-1.5 bg-gray-950 text-white rounded-lg flex items-center gap-2 border border-gray-700">
                                        <span class="text-accent-primary pl-1 text-xs">📍</span>
                                        <span class="text-4xs text-gray-400 font-medium truncate flex-1">Search Regions & Peaks...</span>
                                        <span class="bg-accent-primary text-white text-4xs px-2 py-0.5 rounded font-bold whitespace-nowrap">Find</span>
                                    </div>
                                </div>

                                {{-- Search Capsule --}}
                                <div x-show="filterComponent('search', 'Search Capsule', 'minimal compact')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'search_capsule', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Search Capsule</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'search_capsule')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">Compact minimal search capsule</p>
                                    <div class="p-1 bg-white rounded-full flex items-center gap-1.5 border border-gray-300 shadow-2xs">
                                        <span class="text-gray-400 pl-2 text-xs">🔍</span>
                                        <span class="text-4xs text-gray-500 font-medium truncate flex-1">Quick search...</span>
                                        <span class="bg-gray-900 text-white text-3xs w-5 h-5 rounded-full flex items-center justify-center font-bold">&rarr;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons & CTAs Category --}}
                        <div x-show="componentCategory === 'all' || componentCategory === 'buttons'">
                            <div class="flex items-center gap-1.5 text-2xs font-extrabold uppercase text-gray-600 tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                <span>Buttons & Action CTAs</span>
                            </div>
                            <div class="space-y-2.5">
                                {{-- WhatsApp Button --}}
                                <div x-show="filterComponent('buttons', 'WhatsApp Specialist', 'chat expert message')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'button_whatsapp', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-emerald-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-emerald-500 font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-emerald-600">WhatsApp Specialist</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'button_whatsapp')" class="bg-emerald-600 hover:bg-emerald-500 text-white py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">Direct WhatsApp mountain expert chat link</p>
                                    <div class="inline-flex items-center gap-2 bg-emerald-600 text-white text-3xs font-bold px-3 py-1.5 rounded-lg shadow-xs">
                                        <span>💬</span>
                                        <span>Chat with Mountain Expert</span>
                                    </div>
                                </div>

                                {{-- Satellite Phone / SOS Button --}}
                                <div x-show="filterComponent('buttons', '24/7 Satellite SOS', 'call emergency phone rescue')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'button_phone', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">24/7 Satellite SOS</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'button_phone')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">Emergency helicopter & SOS call hotline</p>
                                    <div class="inline-flex items-center gap-2 bg-gray-900 text-white text-3xs font-bold px-3 py-1.5 rounded-lg shadow-xs border border-gray-700">
                                        <span class="text-accent-primary">📞</span>
                                        <span>+977-1-456789 (24/7 SOS)</span>
                                    </div>
                                </div>

                                {{-- Download Expedition Brochure --}}
                                <div x-show="filterComponent('buttons', 'Download Expedition Guide', 'pdf brochure brochure')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'button_brochure', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-amber-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-amber-500 font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-amber-600">Download Guide</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'button_brochure')" class="bg-amber-500 hover:bg-amber-600 text-gray-950 py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">PDF itinerary & brochure download button</p>
                                    <div class="inline-flex items-center gap-2 bg-amber-500 text-gray-950 text-3xs font-bold px-3 py-1.5 rounded-lg shadow-xs">
                                        <span>📥</span>
                                        <span>Download Expedition Guide</span>
                                    </div>
                                </div>

                                {{-- Customizable Action CTA --}}
                                <div x-show="filterComponent('buttons', 'Custom Action Button', 'cta link custom')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'text', 'button')"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Custom Action CTA</span>
                                        </div>
                                        <button type="button" @click="addNewTextElement(activeSectionId, 'button')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4 mb-2">Fully customizable button (size, color, link)</p>
                                    <div class="inline-flex items-center gap-2 bg-accent-primary text-white text-3xs font-bold px-3 py-1.5 rounded-lg shadow-xs">
                                        <span>Explore More &rarr;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Trust Badges Category --}}
                        <div x-show="componentCategory === 'all' || componentCategory === 'badges'">
                            <div class="flex items-center gap-1.5 text-2xs font-extrabold uppercase text-gray-600 tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span>Trust & Proof Badges</span>
                            </div>
                            <div class="space-y-2.5">
                                {{-- IFMGA Guides --}}
                                <div x-show="filterComponent('badges', 'IFMGA Certified Guides', 'certified shield trust')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'badge_certified', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">IFMGA Certified</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'badge_certified')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <div class="inline-flex items-center gap-2 bg-black/85 text-white text-3xs font-bold px-3 py-1 rounded-full border border-orange-500/40">
                                        <span class="text-accent-primary">🛡</span>
                                        <span>100% IFMGA Certified Guides</span>
                                    </div>
                                </div>

                                {{-- 5-Star Reviews --}}
                                <div x-show="filterComponent('badges', '5-Star Reviews Badge', 'reviews rating stars rating')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'badge_reviews', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">5-Star Reviews</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'badge_reviews')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <div class="inline-flex items-center gap-2 bg-white text-gray-900 text-3xs font-bold px-3 py-1 rounded-full border border-gray-200 shadow-xs">
                                        <span class="text-amber-500 font-extrabold">★★★★★</span>
                                        <span>4.9/5 Rating (500+ Summits)</span>
                                    </div>
                                </div>

                                {{-- Guaranteed Fixed Departures --}}
                                <div x-show="filterComponent('badges', 'Guaranteed Departures', 'guaranteed dates departure')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'badge_guarantee', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Guaranteed Departures</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'badge_guarantee')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <div class="inline-flex items-center gap-2 bg-orange-500/10 text-orange-600 text-3xs font-bold px-3 py-1 rounded-full border border-orange-500/30">
                                        <span>✓</span>
                                        <span>Guaranteed Fixed Departures</span>
                                    </div>
                                </div>

                                {{-- Leave No Trace Partner --}}
                                <div x-show="filterComponent('badges', 'Leave No Trace Partner', 'eco green sustainability')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'badge_sustainability', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-emerald-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-emerald-500 font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-emerald-600">Leave No Trace</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'badge_sustainability')" class="bg-emerald-600 hover:bg-emerald-500 text-white py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <div class="inline-flex items-center gap-2 bg-emerald-950/80 text-emerald-300 text-3xs font-bold px-3 py-1 rounded-full border border-emerald-500/30">
                                        <span>🌿</span>
                                        <span>Leave No Trace Partner</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Cards & Widgets Category --}}
                        <div x-show="componentCategory === 'all' || componentCategory === 'cards'">
                            <div class="flex items-center gap-1.5 text-2xs font-extrabold uppercase text-gray-600 tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span>Cards & Widgets</span>
                            </div>
                            <div class="space-y-2.5">
                                {{-- 24/7 Satellite Dispatch Card --}}
                                <div x-show="filterComponent('cards', 'Satellite Dispatch Card', 'emergency helicopter support')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'card_support', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Satellite Dispatch Card</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'card_support')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4">Emergency helicopter rescue & support card</p>
                                </div>

                                {{-- Summit Stats Widget --}}
                                <div x-show="filterComponent('cards', 'Himalayan Summit Stats', 'stats 8848m numbers counter')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'card_stats', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-accent-primary">Himalayan Summit Stats</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'card_stats')" class="btn-primary py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4">3-column stats card: 8848m, 14 Peaks, 99.8%</p>
                                </div>

                                {{-- Mountain Quote Box --}}
                                <div x-show="filterComponent('cards', 'Mountaineering Quote', 'quote inspiration words')"
                                     draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'component', 'card_quote', {})"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-amber-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-amber-500 font-bold text-xs">⠿</span>
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-amber-600">Mountaineering Quote</span>
                                        </div>
                                        <button type="button" @click="addComponentElement(activeSectionId, 'card_quote')" class="bg-amber-500 hover:bg-amber-600 text-gray-950 py-1 px-2.5 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <p class="text-4xs text-gray-500 pl-4">Inspirational mountain quote box</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </template>

            {{-- TAB: TEXT & TYPOGRAPHY PRESETS --}}
            <template x-if="leftTab === 'text'">
                <div class="flex flex-col h-full">
                    <div class="p-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <span class="text-xs font-bold uppercase text-gray-700 tracking-wider">Add Text & Styles</span>
                        <button type="button" class="text-gray-400 hover:text-gray-600 text-sm" @click="leftTab = null">✕</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div class="p-2.5 bg-accent-primary/10 border border-accent-primary/30 rounded-xl">
                            <div class="flex items-center gap-1.5 text-accent-primary font-bold text-2xs uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                Canva Free Placement
                            </div>
                            <p class="text-3xs text-gray-600 mt-1">Click anywhere on a section block to place text at that spot, then drag it freely anywhere on the canvas!</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="block text-2xs font-bold uppercase text-gray-500 tracking-wider">Drag onto Canvas or Click:</span>
                                <span class="text-4xs text-accent-primary font-mono uppercase bg-accent-primary/10 px-1.5 py-0.5 rounded font-bold">⠿ Draggable</span>
                            </div>
                            <div class="space-y-2.5">
                                {{-- Headline Preset --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'text', 'headline')"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-3 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-display font-bold text-lg text-gray-900 group-hover:text-accent-primary">Headline</span>
                                        </div>
                                        <button type="button" @click="addNewTextElement(activeSectionId, 'headline')" class="btn-primary py-0.5 px-2 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <span class="text-3xs text-gray-400 pl-4 block">Cinzel display heading (48px serif)</span>
                                </div>

                                {{-- Subheading Preset --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'text', 'subheading')"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-3 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-sans font-bold text-base text-gray-800 group-hover:text-accent-primary">Subheading</span>
                                        </div>
                                        <button type="button" @click="addNewTextElement(activeSectionId, 'subheading')" class="btn-primary py-0.5 px-2 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <span class="text-3xs text-gray-400 pl-4 block">Outfit modern tagline (20px sans)</span>
                                </div>

                                {{-- Body Paragraph Preset --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'text', 'body')"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-3 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-sans font-medium text-sm text-gray-700 group-hover:text-accent-primary">Body Text</span>
                                        </div>
                                        <button type="button" @click="addNewTextElement(activeSectionId, 'body')" class="btn-primary py-0.5 px-2 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <span class="text-3xs text-gray-400 pl-4 block">Descriptive expedition paragraph (16px)</span>
                                </div>

                                {{-- Action Button Preset --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'text', 'button')"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-3 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-gray-300 group-hover:text-accent-primary font-bold text-xs">⠿</span>
                                            <span class="font-sans font-bold text-xs uppercase text-accent-primary tracking-wider">Action Button</span>
                                        </div>
                                        <button type="button" @click="addNewTextElement(activeSectionId, 'button')" class="btn-primary py-0.5 px-2 text-3xs font-bold rounded-lg shadow-2xs hover:scale-105 transition-transform">
                                            + Add
                                        </button>
                                    </div>
                                    <span class="text-3xs text-gray-400 pl-4 block">Customizable CTA link button</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <span class="block text-2xs font-bold uppercase text-gray-500 tracking-wider mb-2">Typography Pairings:</span>
                            <div class="space-y-2">
                                <div class="p-2.5 rounded-lg border border-gray-200 hover:border-gray-400 cursor-pointer" @click="applyFontPairing('cinzel', 'outfit')">
                                    <span class="font-display font-bold text-sm block">Cinzel + Outfit</span>
                                    <span class="text-3xs text-gray-400">Grand Himalayan & Modern Alpine</span>
                                </div>
                                <div class="p-2.5 rounded-lg border border-gray-200 hover:border-gray-400 cursor-pointer" @click="applyFontPairing('playfair', 'inter')">
                                    <span class="font-serif italic font-bold text-sm block">Playfair + Inter</span>
                                    <span class="text-3xs text-gray-400">Editorial Elegance & Clean Simplicity</span>
                                </div>
                                <div class="p-2.5 rounded-lg border border-gray-200 hover:border-gray-400 cursor-pointer" @click="applyFontPairing('grotesk', 'space')">
                                    <span class="font-mono font-bold text-sm block">Space Grotesk + Inter</span>
                                    <span class="text-3xs text-gray-400">Adventure Technical & Modern Expeditions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- TAB: PHOTOS & MEDIA --}}
            <template x-if="leftTab === 'photos'">
                <div class="flex flex-col h-full">
                    <div class="p-3 border-b border-gray-200 flex items-center justify-between bg-gray-50 shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase text-gray-800 tracking-wider">Photos & Media</span>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600 text-sm p-1" @click="leftTab = null">✕</button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-3.5 space-y-4 no-scrollbar">
                        {{-- 1-Click Action Card --}}
                        <div class="p-3 bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl shadow-xs">
                            <div class="flex items-center justify-between gap-1 mb-1.5">
                                <span class="text-3xs uppercase font-extrabold text-indigo-700 tracking-wider flex items-center gap-1">
                                    <span>📷</span>
                                    <span>Canva Photo Boxes</span>
                                </span>
                                <span class="text-4xs font-bold px-1.5 py-0.5 rounded bg-indigo-200/60 text-indigo-800">Anywhere</span>
                            </div>
                            <p class="text-3xs text-gray-600 mb-2.5 leading-relaxed">
                                Add resizable, draggable photo boxes anywhere on the section. Drag to move, resize with 8 handles, and double-click to replace images!
                            </p>
                            <button type="button" 
                                    @click="addNewPhotoElement(activeSectionId, 'landscape')" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-3 rounded-xl text-xs shadow-xs transition-all hover:scale-102 flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>+ Add Photo Box to Block</span>
                            </button>
                        </div>

                        {{-- READY-TO-USE PHOTO BOX FRAMES (DRAGGABLE ONTO CANVAS) --}}
                        <div>
                            <span class="block text-2xs font-extrabold uppercase text-gray-700 tracking-wider mb-2 flex items-center gap-1">
                                <span>📐</span>
                                <span>Photo Box Frames:</span>
                            </span>
                            <div class="space-y-2">
                                {{-- Landscape 16:9 --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'photo', 'landscape', { url: '/images/hero-everest.jpg' })"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-gray-300 group-hover:text-indigo-600 font-bold text-xs">⠿</span>
                                        <div class="w-12 h-7 rounded bg-indigo-100 border border-indigo-200 overflow-hidden shrink-0 flex items-center justify-center">
                                            <span class="text-4xs font-bold text-indigo-700">16:9</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs text-gray-900 group-hover:text-indigo-600">Landscape Frame</div>
                                            <div class="text-4xs text-gray-500">16:9 Panorama (Drag anywhere)</div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addNewPhotoElement(activeSectionId, 'landscape')" class="bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 text-3xs font-bold rounded-lg shadow-2xs">
                                        + Add
                                    </button>
                                </div>

                                {{-- Portrait 3:4 --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'photo', 'portrait', { url: '/images/hero-everest.jpg' })"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-gray-300 group-hover:text-indigo-600 font-bold text-xs">⠿</span>
                                        <div class="w-8 h-10 rounded bg-indigo-100 border border-indigo-200 overflow-hidden shrink-0 flex items-center justify-center">
                                            <span class="text-4xs font-bold text-indigo-700">3:4</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs text-gray-900 group-hover:text-indigo-600">Portrait Frame</div>
                                            <div class="text-4xs text-gray-500">3:4 Vertical mountain shot</div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addNewPhotoElement(activeSectionId, 'portrait')" class="bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 text-3xs font-bold rounded-lg shadow-2xs">
                                        + Add
                                    </button>
                                </div>

                                {{-- Square 1:1 --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'photo', 'square', { url: '/images/hero-everest.jpg' })"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-gray-300 group-hover:text-indigo-600 font-bold text-xs">⠿</span>
                                        <div class="w-8 h-8 rounded bg-indigo-100 border border-indigo-200 overflow-hidden shrink-0 flex items-center justify-center">
                                            <span class="text-4xs font-bold text-indigo-700">1:1</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs text-gray-900 group-hover:text-indigo-600">Square Photo</div>
                                            <div class="text-4xs text-gray-500">1:1 Modern adventure square</div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addNewPhotoElement(activeSectionId, 'square')" class="bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 text-3xs font-bold rounded-lg shadow-2xs">
                                        + Add
                                    </button>
                                </div>

                                {{-- Circular Badge --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'photo', 'circle', { url: '/images/hero-everest.jpg' })"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-gray-300 group-hover:text-indigo-600 font-bold text-xs">⠿</span>
                                        <div class="w-8 h-8 rounded-full bg-orange-100 border-2 border-orange-500 overflow-hidden shrink-0 flex items-center justify-center">
                                            <span class="text-4xs font-bold text-orange-700">●</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs text-gray-900 group-hover:text-indigo-600">Circular Badge</div>
                                            <div class="text-4xs text-gray-500">Rounded avatar / expedition badge</div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addNewPhotoElement(activeSectionId, 'circle')" class="bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 text-3xs font-bold rounded-lg shadow-2xs">
                                        + Add
                                    </button>
                                </div>

                                {{-- Polaroid Frame --}}
                                <div draggable="true"
                                     @dragstart="onDrawerItemDragStart($event, 'photo', 'polaroid', { url: '/images/hero-everest.jpg' })"
                                     @dragend="onDrawerItemDragEnd($event)"
                                     class="p-2.5 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group bg-white cursor-grab active:cursor-grabbing select-none flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-gray-300 group-hover:text-indigo-600 font-bold text-xs">⠿</span>
                                        <div class="w-8 h-9 rounded-sm bg-white border-2 border-gray-300 shadow-xs p-0.5 flex flex-col justify-between shrink-0">
                                            <div class="w-full h-5 bg-gray-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs text-gray-900 group-hover:text-indigo-600">Polaroid Card</div>
                                            <div class="text-4xs text-gray-500">White border shadow polaroid</div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addNewPhotoElement(activeSectionId, 'polaroid')" class="bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 text-3xs font-bold rounded-lg shadow-2xs">
                                        + Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Upload from Computer --}}
                        <div>
                            <label class="block text-2xs font-extrabold uppercase text-gray-700 tracking-wider mb-1.5 flex items-center gap-1">
                                <span>📤</span>
                                <span>Upload from Computer:</span>
                            </label>
                            <label class="border-2 border-dashed border-gray-300 hover:border-indigo-500 rounded-xl p-3 flex flex-col items-center justify-center bg-gray-50 hover:bg-indigo-50/50 cursor-pointer transition-all">
                                <input type="file" @change="uploadImage($event)" accept="image/*" class="hidden">
                                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <span class="text-xs font-bold text-gray-700">Choose File to Upload</span>
                                <span class="text-4xs text-gray-400">JPG, PNG, WebP (Max 12MB)</span>
                            </label>
                        </div>

                        {{-- Himalayan Presets Library (Drag onto canvas or click) --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-2xs font-extrabold uppercase text-gray-700 tracking-wider flex items-center gap-1">
                                    <span>🏔</span>
                                    <span>Himalayan Library:</span>
                                </span>
                                <span class="text-4xs text-gray-400">Drag or Click</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 max-h-72 overflow-y-auto no-scrollbar p-0.5">
                                <template x-for="p in imagePresets" :key="p.url">
                                    <div draggable="true"
                                         @dragstart="onDrawerItemDragStart($event, 'photo', 'landscape', { url: p.url, alt: p.name })"
                                         @dragend="onDrawerItemDragEnd($event)"
                                         class="rounded-xl overflow-hidden border border-gray-200 hover:border-indigo-500 hover:shadow-md relative group bg-white cursor-grab active:cursor-grabbing select-none transition-all">
                                        <img :src="p.url" :alt="p.name" class="h-18 w-full object-cover">
                                        <div class="p-1.5 bg-white">
                                            <span class="text-4xs font-bold text-gray-800 truncate block" x-text="p.name"></span>
                                            <div class="flex items-center gap-1 mt-1">
                                                <button type="button" 
                                                        @click.stop="addNewPhotoElement(activeSectionId, 'landscape', p.url)"
                                                        class="flex-1 py-0.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded text-4xs font-bold transition-colors">
                                                    + Box
                                                </button>
                                                <button type="button" 
                                                        @click.stop="quickApplyImage(p.url, p.name)"
                                                        class="py-0.5 px-1 bg-gray-100 hover:bg-gray-800 text-gray-700 hover:text-white rounded text-4xs font-bold transition-colors"
                                                        title="Set as Block Background Image">
                                                    Bg
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- TAB: PLACEMENT & LAYOUT --}}
            <template x-if="leftTab === 'layout'">
                <div class="flex flex-col h-full">
                    <div class="p-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <span class="text-xs font-bold uppercase text-gray-700 tracking-wider">Placement & Layout</span>
                        <button type="button" class="text-gray-400 hover:text-gray-600 text-sm" @click="leftTab = null">✕</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-gray-500 tracking-wider mb-1.5">Content Alignment:</label>
                            <div class="grid grid-cols-3 gap-1.5">
                                <button type="button" @click="setBlockAlignment('left')" class="p-2 border rounded-lg text-center text-xs font-bold hover:border-accent-primary">Left</button>
                                <button type="button" @click="setBlockAlignment('center')" class="p-2 border rounded-lg text-center text-xs font-bold hover:border-accent-primary">Center</button>
                                <button type="button" @click="setBlockAlignment('right')" class="p-2 border rounded-lg text-center text-xs font-bold hover:border-accent-primary">Right</button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-2xs font-bold uppercase text-gray-500 tracking-wider mb-1.5">Vertical Padding:</label>
                            <div class="grid grid-cols-3 gap-1.5">
                                <button type="button" @click="setBlockPadding('compact')" class="p-2 border rounded-lg text-center text-xs font-semibold hover:border-accent-primary">Compact</button>
                                <button type="button" @click="setBlockPadding('normal')" class="p-2 border rounded-lg text-center text-xs font-semibold hover:border-accent-primary">Normal</button>
                                <button type="button" @click="setBlockPadding('spacious')" class="p-2 border rounded-lg text-center text-xs font-semibold hover:border-accent-primary">Spacious</button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-2xs font-bold uppercase text-gray-500 tracking-wider mb-1.5">Overlay Darkness:</label>
                            <div class="grid grid-cols-4 gap-1">
                                <button type="button" @click="setBlockOverlay(0.2)" class="p-1.5 border rounded text-center text-3xs font-semibold">20%</button>
                                <button type="button" @click="setBlockOverlay(0.5)" class="p-1.5 border rounded text-center text-3xs font-semibold">50%</button>
                                <button type="button" @click="setBlockOverlay(0.7)" class="p-1.5 border rounded text-center text-3xs font-semibold">70%</button>
                                <button type="button" @click="setBlockOverlay(0.9)" class="p-1.5 border rounded text-center text-3xs font-semibold">90%</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        {{-- CANVA MAIN CANVAS WORKSPACE --}}
        <div class="flex-1 bg-gray-200/90 overflow-y-auto overflow-x-hidden p-4 md:p-8 flex justify-center" id="canvas-scroll-container">
            <div class="bg-white shadow-2xl transition-all duration-300 min-h-full rounded-xl border border-gray-300 overflow-hidden overflow-x-hidden flex flex-col relative"
                 :style="getDeviceStyle()"
                 :class="{
                     'device-mobile': deviceView === 'mobile',
                     'device-tablet': deviceView === 'tablet',
                     'device-desktop': deviceView === 'desktop'
                 }"
                 id="canvas-inner">

                {{-- Phone Top Speaker / Notch (Visible only in Mobile mode) --}}
                <div x-show="deviceView === 'mobile'" class="w-24 h-4 bg-gray-900 mx-auto rounded-b-xl shrink-0 -mt-0.5 mb-1 flex items-center justify-center gap-1.5 z-50">
                    <div class="w-8 h-1 bg-gray-700 rounded-full"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-800 border border-gray-700"></div>
                </div>

                {{-- Canva Browser Preview Frame --}}
                <div class="bg-gray-100 border-b border-gray-200 px-4 py-2 flex items-center justify-between shrink-0 select-none">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded px-3 py-0.5 text-3xs font-mono text-gray-500">
                        winterfelltreks.com/{{ $page->slug === 'home' ? '' : $page->slug }}
                    </div>
                    <div class="text-3xs font-semibold text-accent-primary items-center gap-1.5 bg-accent-primary/10 px-2.5 py-1 rounded-full hidden sm:flex canva-mode-badge"
                         x-show="deviceView === 'desktop'">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent-primary animate-ping"></span>
                        <span>Canva Mode: Click & Drag contents to edit and rearrange</span>
                    </div>
                </div>

                {{-- SITE NAVIGATION HEADER (CANVAS PREVIEW - TOGGLEABLE) --}}
                <div x-show="showNavbar" x-transition.opacity class="relative group/nav-preview bg-white border-b border-gray-100 select-none" @click="if ($event.target.closest('a') && !$event.target.closest('button')) $event.preventDefault()">
                    <div class="opacity-95">
                        @include('components.header')
                    </div>
                </div>

                {{-- Slim Informational Banner when Navbar is Hidden in Editor --}}
                <div x-show="!showNavbar" x-transition.opacity class="bg-amber-500/10 border-b border-amber-500/20 py-1.5 px-4 flex items-center justify-between text-3xs text-amber-700 font-medium select-none" style="display: none;">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        <span>Website Navbar is hidden in edit page</span>
                    </div>
                    <button type="button" @click="toggleNavbar(true)" class="text-accent-primary hover:text-accent-dark font-bold underline flex items-center gap-1">
                        <span>Show Navbar</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                </div>

                {{-- LIVE RENDERED SECTIONS WITH DRAGGING AND SELECTING --}}
                <div class="divide-y divide-gray-100 overflow-x-hidden" id="sections-canvas-wrapper">
                    @foreach($page->sections as $sec)
                    @php
                        $rawSec = $sectionTypes[$sec->section_type] ?? null;
                        $sName = is_array($rawSec) ? ($rawSec['label'] ?? $sec->section_type) : ($rawSec ?? $sec->section_type);
                        $secImg = $sec->elements->firstWhere('element_type', 'image');
                    @endphp
                    <div class="relative group transition-all duration-200 canva-section-block" 
                         id="section-view-{{ $sec->id }}"
                         data-section-id="{{ $sec->id }}"
                         data-section-type="{{ $sec->section_type }}"
                         @dragover="onSectionDragOver($event, {{ $sec->id }})"
                         @drop.prevent="onSectionDrop($event, {{ $sec->id }})"
                         :class="{
                             'ring-2 ring-accent-primary ring-inset shadow-lg': activeSectionId == {{ $sec->id }},
                             'border-t-4 border-accent-primary': dropTargetSectionId == {{ $sec->id }},
                             'hover:outline hover:outline-1 hover:outline-accent-primary/40': activeSectionId != {{ $sec->id }} && dropTargetSectionId != {{ $sec->id }}
                         }"
                         @click="setActiveSection({{ $sec->id }})">

                        {{-- Floating Canva Block Drag Bar & Actions (Top-Right) --}}
                        <div class="absolute top-2 right-4 z-30 opacity-90 group-hover:opacity-100 transition-opacity bg-gray-900/95 text-white rounded-lg shadow-xl px-3 py-1.5 flex items-center gap-2 backdrop-blur-xs select-none">
                            <span draggable="true"
                                  @dragstart="onSectionDragStart($event, {{ $sec->id }})"
                                  @dragend="onSectionDragEnd($event)"
                                  class="cursor-grab active:cursor-grabbing text-3xs font-bold uppercase tracking-wider text-accent-primary flex items-center gap-1 hover:text-accent-light" 
                                  title="Click and drag to reorder this section">
                                <span class="text-gray-400">⠿</span>
                                <span>{{ $sName }}</span>
                            </span>
                            <div class="h-3 w-px bg-gray-700"></div>
                            
                            <button type="button" @click.stop="openBlockDrawer({{ $sec->id }})" class="text-3xs text-white hover:text-accent-primary font-bold flex items-center gap-1" title="Properties">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Properties</span>
                            </button>
                            <div class="h-3 w-px bg-gray-700"></div>

                            <button type="button" @click.stop="deleteSection({{ $sec->id }}, '{{ addslashes($sName) }}')" class="text-3xs text-red-400 hover:text-red-300" title="Delete Block">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>

                        {{-- Floating "📷 Change Image" Button (Prominent & Discoverable) --}}
                        @if($secImg)
                        <div class="absolute bottom-4 left-4 z-30 opacity-90 group-hover:opacity-100 transition-all select-none">
                            <button type="button" 
                                    @click.stop="openImageModal({{ $sec->id }}, {{ $secImg->id }})"
                                    class="px-3 py-1.5 bg-gray-900/90 hover:bg-accent-primary text-white text-xs font-bold rounded-lg shadow-xl backdrop-blur-xs flex items-center gap-1.5 border border-white/20 transition-all hover:scale-105">
                                <svg class="w-4 h-4 text-accent-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Change Image / Background</span>
                            </button>
                        </div>
                        @endif

                        {{-- Canva Quick Elements Chip Bar --}}
                        <div x-show="activeSectionId == {{ $sec->id }}" class="bg-gray-900 border-b border-gray-800 px-4 py-2 flex flex-wrap items-center justify-between gap-2 text-xs text-white select-none z-20 relative shadow-sm overflow-x-hidden">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-3xs uppercase font-extrabold text-accent-primary bg-accent-primary/20 px-2 py-0.5 rounded">Elements:</span>
                                
                                <template x-for="el in (sectionsData[{{ $sec->id }}]?.elements || [])" :key="el.id">
                                    <button type="button" 
                                            @click.stop="handleElementChipClick({{ $sec->id }}, el)"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-3xs font-semibold bg-gray-800 hover:bg-accent-primary hover:text-white text-gray-200 transition-colors border border-gray-700">
                                        <span x-text="el.type === 'image' ? '📷' : (el.type === 'link' ? '🔗' : '✏')"></span>
                                        <span x-text="el.label"></span>
                                    </button>
                                </template>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click.stop="addNewTextElement({{ $sec->id }}, 'headline')" class="text-3xs font-bold text-accent-light hover:underline">
                                    + Add Text
                                </button>
                                <span class="text-gray-600">•</span>
                                <button type="button" @click.stop="addNewTextElement({{ $sec->id }}, 'button')" class="text-3xs font-bold text-accent-light hover:underline">
                                    + Add Button
                                </button>
                                <span class="text-gray-600">•</span>
                                <button type="button" @click.stop="addNewPhotoElement({{ $sec->id }}, 'landscape')" class="text-3xs font-bold text-indigo-400 hover:text-indigo-300 hover:underline">
                                    📷 + Add Photo
                                </button>
                                <span class="text-gray-600">•</span>
                                <button type="button" @click.stop="leftTab = 'components'" class="text-3xs font-bold text-amber-400 hover:text-amber-300 hover:underline">
                                    + Add Component
                                </button>
                                <span class="text-gray-600">•</span>
                                <button type="button" @click.stop="openBlockDrawer({{ $sec->id }})" class="btn-primary py-0.5 px-2.5 text-3xs font-bold shadow-xs">
                                    Properties &rarr;
                                </button>
                            </div>
                        </div>

                        {{-- ACTUAL REAL BLADE SECTION RENDERING --}}
                        <div class="canva-rendered-content transition-all relative overflow-visible min-h-[160px]"
                             @click="handleContentClick($event, {{ $sec->id }})"
                             @dragover.prevent="handleCanvasDragOver($event, {{ $sec->id }})"
                             @dragleave="handleCanvasDragLeave($event, {{ $sec->id }})"
                             @drop.prevent="handleCanvasDrop($event, {{ $sec->id }})">

                            {{-- Canva Visual Drop Target Highlight Overlay --}}
                            <div x-show="canvasDropTargetSectionId == {{ $sec->id }}"
                                 x-transition.opacity
                                 class="absolute inset-0 z-40 bg-accent-primary/10 border-2 border-dashed border-accent-primary rounded-xl pointer-events-none flex items-center justify-center backdrop-blur-2xs"
                                 style="display: none;">
                                <div class="bg-gray-950/95 text-white px-4 py-2 rounded-xl shadow-2xl flex items-center gap-2 border border-accent-primary transform scale-105 transition-transform">
                                    <span class="text-accent-primary text-base font-bold animate-bounce">📍</span>
                                    <span class="text-xs font-bold tracking-wide">Drop anywhere on this section to place!</span>
                                </div>
                            </div>
                            @includeIf('sections.' . $sec->section_type, [
                                'section' => $sec,
                                'elements' => $sec->elements->keyBy('element_key'),
                                'settings' => $sec->settings ?? [],
                                'page' => $page,
                                'pageData' => $pageData ?? []
                            ])

                            {{-- Custom dynamically added Canva elements positioned anywhere on this section --}}
                            @php
                                $customElements = $sec->elements->filter(fn($e) => str_starts_with($e->element_key, 'custom_'));
                            @endphp
                            @if($customElements->isNotEmpty())
                                @foreach($customElements as $cEl)
                                @php
                                    $elStyles = $sec->settings['styles'][$cEl->element_key] ?? [];
                                    $top = $elStyles['top'] ?? '50%';
                                    $left = $elStyles['left'] ?? '50%';
                                    $pos = $elStyles['position'] ?? 'absolute';
                                    $transform = ($pos === 'absolute') ? 'translate(-50%, -50%)' : 'none';
                                    $width = $elStyles['width'] ?? null;
                                @endphp
                                <div class="canva-draggable-item group/custom select-none z-30 inline-block p-1.5 rounded-lg transition-shadow duration-150 relative cursor-move"
                                     :class="activeTextKey === '{{ $cEl->element_key }}' ? 'ring-2 ring-accent-primary ring-offset-2 ring-offset-black/50 shadow-2xl' : 'hover:ring-1 hover:ring-accent-primary/60'"
                                     data-key="{{ $cEl->element_key }}"
                                     data-element-id="{{ $cEl->id }}"
                                     data-section-id="{{ $sec->id }}"
                                     @mousedown="startDraggingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', {{ $cEl->id }})"
                                     @dblclick.stop="if ('{{ $cEl->element_type }}' === 'image' || '{{ $cEl->element_key }}'.includes('photo')) { openImageModal({{ $sec->id }}, {{ $cEl->id }}); } else { startInlineEditing($event, {{ $sec->id }}, '{{ $cEl->element_key }}'); }"
                                     style="position: {{ $pos }}; top: {{ $top }}; left: {{ $left }}; transform: {{ $transform }}; @if($width) width: {{ $width }}; max-width: 95vw; @else max-width: 90%; @endif">

                                    {{-- Canva Drag Handle Bar (Top Center) --}}
                                    <div class="canva-drag-handle-bar absolute -top-8 left-1/2 -translate-x-1/2 flex items-center gap-1.5 bg-gray-950 text-white text-3xs px-2.5 py-1 rounded-full shadow-xl border border-gray-700 z-50 cursor-grab active:cursor-grabbing select-none whitespace-nowrap opacity-0 group-hover/custom:opacity-100 transition-opacity before:content-[''] before:absolute before:inset-x-0 before:top-full before:h-3 before:bg-transparent"
                                         :class="activeTextKey === '{{ $cEl->element_key }}' ? '!opacity-100' : ''"
                                         @mousedown.stop="startDraggingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', {{ $cEl->id }})">
                                        <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h16"></path></svg>
                                        <span class="font-bold">Drag to Move</span>
                                        <span class="text-gray-500">|</span>
                                        <button type="button" @click.stop="duplicateCustomElement({{ $sec->id }}, {{ $cEl->id }})" class="hover:text-accent-primary px-1" title="Duplicate Layer">📋</button>
                                        <span class="text-gray-500">|</span>
                                        <button type="button" @click.stop="deleteCustomElement({{ $sec->id }}, {{ $cEl->id }})" class="hover:text-red-400 px-1 font-bold" title="Delete Element">✕</button>
                                    </div>

                                    {{-- 8 Figma/Canva Resize Handles --}}
                                    <template x-if="activeTextKey === '{{ $cEl->element_key }}'">
                                        <div class="canva-resize-handles">
                                            <div class="canva-handle handle-nw" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'nw')" title="Resize NW"></div>
                                            <div class="canva-handle handle-ne" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'ne')" title="Resize NE"></div>
                                            <div class="canva-handle handle-se" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'se')" title="Resize SE"></div>
                                            <div class="canva-handle handle-sw" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'sw')" title="Resize SW"></div>
                                            <div class="canva-handle handle-n" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'n')" title="Resize Top"></div>
                                            <div class="canva-handle handle-s" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 's')" title="Resize Bottom"></div>
                                            <div class="canva-handle handle-e" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'e')" title="Resize Width"></div>
                                            <div class="canva-handle handle-w" @mousedown.stop.prevent="startResizingElement($event, {{ $sec->id }}, '{{ $cEl->element_key }}', 'w')" title="Resize Width"></div>
                                        </div>
                                    </template>

                                    @if($cEl->element_type === 'component' || str_contains($cEl->element_key, 'comp_') || str_contains($cEl->element_key, 'search') || str_contains($cEl->element_key, 'badge') || str_contains($cEl->element_key, 'card'))
                                        @include('components.canva_component', ['cEl' => $cEl, 'isEditor' => true])
                                    @elseif($cEl->element_type === 'image' || str_contains($cEl->element_key, 'photo') || str_contains($cEl->element_key, 'image'))
                                        @include('components.canva_photo_box', ['cEl' => $cEl, 'isEditor' => true, 'elStyles' => $elStyles])
                                    @elseif($cEl->element_type === 'link')
                                        <a href="{{ $cEl->getLinkUrl() ?? '#' }}" @click.prevent data-key="{{ $cEl->element_key }}" class="canva-custom-button btn-primary py-2.5 px-6 font-semibold inline-block shadow-md pointer-events-auto">
                                            {{ $cEl->getLinkText() ?? 'Explore More' }}
                                        </a>
                                    @elseif(str_contains($cEl->element_key, 'headline'))
                                        <h3 data-key="{{ $cEl->element_key }}" class="canva-custom-text font-display font-bold text-3xl text-white drop-shadow-lg cursor-text pointer-events-auto">
                                            {{ is_string($cEl->content) ? $cEl->content : ($cEl->content['text'] ?? '') }}
                                        </h3>
                                    @elseif(str_contains($cEl->element_key, 'subheading'))
                                        <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-xl font-medium text-gray-100 drop-shadow-md cursor-text pointer-events-auto">
                                            {{ is_string($cEl->content) ? $cEl->content : ($cEl->content['text'] ?? '') }}
                                        </div>
                                    @elseif(str_contains($cEl->element_key, 'body'))
                                        <div data-key="{{ $cEl->element_key }}" class="canva-custom-text prose max-w-xl text-gray-200 drop-shadow cursor-text pointer-events-auto">
                                            {!! is_string($cEl->content) ? $cEl->content : ($cEl->content['text'] ?? '') !!}
                                        </div>
                                    @else
                                        <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-base text-white drop-shadow cursor-text pointer-events-auto">
                                            {{ is_string($cEl->content) ? $cEl->content : ($cEl->content['text'] ?? '') }}
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- FLOATING QUICK-ADD MENU (APPEARS ANYWHERE ON CANVAS CLICK) --}}
        <div x-show="quickAddMenu && quickAddMenu.show"
             @click.outside="quickAddMenu.show = false"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed z-[100] bg-gray-950 text-white rounded-2xl shadow-2xl border border-gray-700/80 p-2 flex items-center gap-1.5 backdrop-blur-md select-none transform -translate-x-1/2 -translate-y-full mb-3"
             :style="'left: ' + quickAddMenu.x + 'px; top: ' + (quickAddMenu.y - 12) + 'px;'"
             style="display: none;">
            
            <div class="flex items-center gap-1 text-3xs font-extrabold uppercase text-gray-400 pl-2 pr-1 border-r border-gray-800">
                <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                <span>Add Here:</span>
            </div>

            {{-- Quick Headline --}}
            <button type="button" 
                    @click.stop="addFromQuickMenu('text', 'headline')"
                    class="px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-accent-primary text-white text-xs font-semibold flex items-center gap-1 transition-all hover:scale-105"
                    title="Add Headline at this location">
                <span class="font-serif font-black text-accent-light">H</span>
                <span>Headline</span>
            </button>

            {{-- Quick Subtitle --}}
            <button type="button" 
                    @click.stop="addFromQuickMenu('text', 'subheading')"
                    class="px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-accent-primary text-white text-xs font-semibold flex items-center gap-1 transition-all hover:scale-105"
                    title="Add Subtitle at this location">
                <span class="font-sans font-black text-gray-300">T</span>
                <span>Subtitle</span>
            </button>

            {{-- Quick Button --}}
            <button type="button" 
                    @click.stop="addFromQuickMenu('text', 'button')"
                    class="px-2.5 py-1.5 rounded-lg bg-accent-primary/90 hover:bg-accent-primary text-white text-xs font-bold flex items-center gap-1 shadow-sm transition-all hover:scale-105"
                    title="Add Action Button at this location">
                <span>⚡ Button</span>
            </button>

            {{-- Quick Photo Box --}}
            <button type="button" 
                    @click.stop="addFromQuickMenu('photo', 'landscape')"
                    class="px-2.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold flex items-center gap-1 shadow-sm transition-all hover:scale-105"
                    title="Add Resizable Photo Box at this location">
                <span>📷 Photo</span>
            </button>

            {{-- Quick Component Picker Dropdown --}}
            <div class="relative" x-data="{ openCompPicker: false }" @click.outside="openCompPicker = false">
                <button type="button" 
                        @click.stop="openCompPicker = !openCompPicker"
                        class="px-2.5 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold flex items-center gap-1 transition-all hover:scale-105"
                        title="Add a Component at this location">
                    <span>🧩 Component</span>
                    <svg class="w-3 h-3 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="openCompPicker" 
                     x-transition 
                     class="absolute left-0 bottom-full mb-2 w-56 bg-gray-900 text-white rounded-xl shadow-2xl border border-gray-700 py-1 z-50 text-xs divide-y divide-gray-800">
                    <div class="px-3 py-1.5 text-3xs font-extrabold uppercase text-gray-400 tracking-wider">Ready Components</div>
                    <div class="p-1 space-y-0.5">
                        <button type="button" @click.stop="openCompPicker = false; addFromQuickMenu('component', 'search_packages')" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-accent-primary flex items-center gap-2 transition-colors">
                            <span>🔍</span>
                            <div>
                                <div class="font-bold text-3xs">Expedition Search</div>
                                <div class="text-4xs text-gray-400">Search packages & treks</div>
                            </div>
                        </button>
                        <button type="button" @click.stop="openCompPicker = false; addFromQuickMenu('component', 'search_destinations')" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-accent-primary flex items-center gap-2 transition-colors">
                            <span>📍</span>
                            <div>
                                <div class="font-bold text-3xs">Destination Finder</div>
                                <div class="text-4xs text-gray-400">Regions & peaks search</div>
                            </div>
                        </button>
                        <button type="button" @click.stop="openCompPicker = false; addFromQuickMenu('component', 'button_whatsapp')" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-accent-primary flex items-center gap-2 transition-colors">
                            <span>💬</span>
                            <div>
                                <div class="font-bold text-3xs">WhatsApp Specialist</div>
                                <div class="text-4xs text-gray-400">Direct WhatsApp chat</div>
                            </div>
                        </button>
                        <button type="button" @click.stop="openCompPicker = false; addFromQuickMenu('component', 'badge_certified')" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-accent-primary flex items-center gap-2 transition-colors">
                            <span>🛡️</span>
                            <div>
                                <div class="font-bold text-3xs">Govt Certified Badge</div>
                                <div class="text-4xs text-gray-400">Official license badge</div>
                            </div>
                        </button>
                        <button type="button" @click.stop="openCompPicker = false; addFromQuickMenu('component', 'card_stats')" class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-accent-primary flex items-center gap-2 transition-colors">
                            <span>📊</span>
                            <div>
                                <div class="font-bold text-3xs">Summit Stats</div>
                                <div class="text-4xs text-gray-400">8848m, 14 Peaks</div>
                            </div>
                        </button>
                    </div>
                    <div class="p-1">
                        <button type="button" @click.stop="openCompPicker = false; quickAddMenu.show = false; leftTab = 'components'" class="w-full text-center px-2 py-1 text-3xs font-bold text-accent-light hover:underline">
                            Browse all components &rarr;
                        </button>
                    </div>
                </div>
            </div>

            {{-- Close Button --}}
            <button type="button" 
                    @click.stop="quickAddMenu.show = false"
                    class="text-gray-400 hover:text-white p-1 rounded-md hover:bg-gray-800 transition-colors ml-1 text-xs" 
                    title="Close">
                ✕
            </button>
        </div>
        <div x-show="editingSection" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="fixed right-0 top-16 bottom-0 w-96 max-w-full bg-white border-l border-gray-200 shadow-2xl z-40 flex flex-col overflow-x-hidden"
             style="display: none;"
             @click.outside="editingSection = null">
            
            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <div>
                    <span class="text-3xs uppercase font-bold text-accent-primary tracking-wider">Properties Inspector</span>
                    <h3 class="font-display font-bold text-gray-900 text-sm mt-0.5" x-text="editingSection ? editingSection.name : ''"></h3>
                </div>
                <button type="button" class="p-1 text-gray-400 hover:text-gray-700" @click="editingSection = null">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-5" id="drawer-form-container">
                <template x-if="editingSection">
                    <form @submit.prevent="saveSectionDrawer()" class="space-y-4">
                        @csrf
                        <div class="space-y-4">
                            <template x-for="elem in editingSection.elements" :key="elem.id">
                                <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-2xs font-bold uppercase tracking-wider text-gray-700" x-text="elem.label"></label>
                                        <span class="text-3xs font-mono uppercase text-gray-400 bg-gray-200 px-1.5 py-0.5 rounded" x-text="elem.type"></span>
                                    </div>
                                    
                                    {{-- Plain text --}}
                                    <template x-if="elem.type === 'text'">
                                        <input type="text" :name="'elements[' + elem.id + ']'" x-model="elem.value" class="form-input w-full text-xs bg-white">
                                    </template>

                                    {{-- Rich Text --}}
                                    <template x-if="elem.type === 'richtext'">
                                        <textarea :name="'elements[' + elem.id + ']'" rows="3" x-model="elem.value" class="form-input w-full text-xs bg-white"></textarea>
                                    </template>

                                    {{-- Image --}}
                                    <template x-if="elem.type === 'image'">
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" :name="'elements[' + elem.id + '][url]'" x-model="elem.url" placeholder="Image URL (e.g. /images/hero-everest.jpg)" class="form-input flex-1 text-xs bg-white">
                                                <button type="button" @click="openImageModal(editingSection.id, elem)" class="btn-primary py-1.5 px-3 text-2xs font-bold shrink-0">
                                                    Browse
                                                </button>
                                            </div>
                                            <input type="text" :name="'elements[' + elem.id + '][alt]'" x-model="elem.alt" placeholder="Alt Description" class="form-input w-full text-xs bg-white">
                                            <template x-if="elem.url">
                                                <div class="relative group mt-1 rounded-lg overflow-hidden border border-gray-200">
                                                    <img :src="elem.url" alt="" class="h-24 w-full object-cover">
                                                    <button type="button" @click="openImageModal(editingSection.id, elem)" class="absolute inset-0 bg-black/50 text-white font-bold text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        📷 Change Image
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- Link / Button --}}
                                    <template x-if="elem.type === 'link'">
                                        <div class="space-y-2">
                                            <input type="text" :name="'elements[' + elem.id + '][text]'" x-model="elem.text" placeholder="Button Label" class="form-input w-full text-xs bg-white">
                                            <input type="text" :name="'elements[' + elem.id + '][url]'" x-model="elem.url" placeholder="Link URL (/expeditions)" class="form-input w-full text-xs bg-white">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="pt-4 border-t border-gray-200 flex justify-end gap-2">
                            <button type="button" @click="editingSection = null" class="btn-secondary py-1.5 px-3 text-xs">Close</button>
                            <button type="submit" class="btn-primary py-1.5 px-4 text-xs font-bold" :disabled="isSaving">
                                <span x-text="isSaving ? 'Saving...' : 'Update Section'"></span>
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>

    {{-- CANVA IMAGE REPLACER MODAL --}}
    <div x-show="activeImageElement" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs" style="display: none;">
        <div class="fixed inset-0" @click="activeImageElement = null"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[92vh] overflow-hidden flex flex-col z-10" @click.stop>
            <div class="p-4 px-6 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-accent-primary animate-pulse"></span>
                    <div>
                        <h3 class="font-display font-bold text-gray-900 text-base">Select or Upload Image</h3>
                        <p class="text-3xs text-gray-500">Pick from presets, upload from computer, or paste a URL</p>
                    </div>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-700 p-1.5 text-lg font-bold" @click="activeImageElement = null">✕</button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 space-y-5">
                <template x-if="activeImageElement">
                    <div class="space-y-5">
                        {{-- Computer File Upload Dropzone --}}
                        <div>
                            <label class="block text-2xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Upload from Computer</label>
                            <label class="border-2 border-dashed border-gray-300 hover:border-accent-primary rounded-xl p-4 flex flex-col items-center justify-center bg-gray-50 hover:bg-accent-primary/5 cursor-pointer transition-all">
                                <input type="file" @change="uploadImage($event)" accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden">
                                <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <span class="text-xs font-bold text-gray-700">Click to choose photo from your computer</span>
                                <span class="text-3xs text-gray-400 mt-0.5">Supports JPG, PNG, WebP (up to 12MB)</span>
                                <span x-show="isUploading" class="text-accent-primary font-bold text-xs mt-2 animate-pulse">Uploading photo... Please wait</span>
                            </label>
                        </div>

                        {{-- URL & Description Inputs --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold text-gray-700 uppercase tracking-wider mb-1">Image URL / Path</label>
                                <input type="text" x-model="activeImageElement.url" placeholder="e.g. /images/hero-everest.jpg" class="form-input w-full text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alt Description</label>
                                <input type="text" x-model="activeImageElement.alt" placeholder="Alt text for SEO & accessibility" class="form-input w-full text-xs bg-white">
                            </div>
                        </div>

                        {{-- Current Preview --}}
                        <template x-if="activeImageElement.url">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center gap-4">
                                <img :src="activeImageElement.url" alt="Selected Preview" class="h-20 w-32 object-cover rounded-lg border border-gray-300 shadow-2xs">
                                <div class="truncate">
                                    <span class="text-3xs font-bold uppercase tracking-wider text-emerald-600 block">✓ Selected Preview</span>
                                    <span class="text-xs font-semibold text-gray-900 truncate block mt-0.5" x-text="activeImageElement.alt || activeImageElement.url"></span>
                                    <span class="text-3xs text-gray-400 font-mono truncate block" x-text="activeImageElement.url"></span>
                                </div>
                            </div>
                        </template>

                        {{-- Presets Grid --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-2xs font-bold text-gray-700 uppercase tracking-wider">Himalayan Media Presets</label>
                                <span class="text-3xs text-gray-400">Click any image to select instantly</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-64 overflow-y-auto p-1">
                                <template x-for="preset in imagePresets" :key="preset.url">
                                    <div class="relative group cursor-pointer border rounded-xl overflow-hidden transition-all"
                                         :class="activeImageElement?.url === preset.url ? 'ring-3 ring-accent-primary border-accent-primary scale-[1.02] shadow-md' : 'border-gray-200 hover:border-gray-400'"
                                         @click="activeImageElement.url = preset.url; activeImageElement.alt = preset.name">
                                        <img :src="preset.url" :alt="preset.name" class="h-24 w-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent flex items-end p-2">
                                            <span class="text-3xs font-bold text-white truncate leading-tight" x-text="preset.name"></span>
                                        </div>
                                        <template x-if="activeImageElement?.url === preset.url">
                                            <span class="absolute top-1.5 right-1.5 bg-accent-primary text-white text-3xs font-bold px-1.5 py-0.5 rounded shadow">✓ Active</span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 px-6 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <span class="text-3xs text-gray-500">Changes apply directly to your visual page preview</span>
                <div class="flex items-center gap-2">
                    <button type="button" @click="activeImageElement = null" class="btn-secondary py-1.5 px-3.5 text-xs">Cancel</button>
                    <button type="button" @click="saveImageReplacer()" class="btn-primary py-1.5 px-5 text-xs font-bold shadow-md" :disabled="isSaving || isUploading">
                        <span x-text="isSaving ? 'Applying...' : 'Apply Image'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- CANVA BUTTON / LINK EDITOR MODAL --}}
    <div x-show="activeLinkElement" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display: none;">
        <div class="fixed inset-0" @click="activeLinkElement = null"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col z-10" @click.stop>
            <div class="p-4 px-6 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <div>
                    <h3 class="font-display font-bold text-gray-900 text-base">Edit Button / Link</h3>
                    <p class="text-3xs text-gray-500">Change button label and destination URL</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="activeLinkElement = null">✕</button>
            </div>

            <div class="p-5 space-y-4">
                <template x-if="activeLinkElement">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-2xs font-bold text-gray-700 uppercase tracking-wider mb-1">Button Text</label>
                            <input type="text" x-model="activeLinkElement.text" placeholder="e.g. Explore Expeditions" class="form-input w-full text-xs">
                        </div>

                        <div>
                            <label class="block text-2xs font-bold text-gray-700 uppercase tracking-wider mb-1">Destination URL</label>
                            <input type="text" x-model="activeLinkElement.url" placeholder="e.g. /expeditions or https://..." class="form-input w-full text-xs">
                        </div>

                        <div>
                            <span class="block text-3xs font-bold text-gray-500 uppercase tracking-wider mb-1">Quick Links:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" @click="activeLinkElement.url = '/expeditions'" class="text-3xs bg-gray-100 hover:bg-gray-200 px-2 py-0.5 rounded text-gray-700 font-mono">/expeditions</button>
                                <button type="button" @click="activeLinkElement.url = '/destinations'" class="text-3xs bg-gray-100 hover:bg-gray-200 px-2 py-0.5 rounded text-gray-700 font-mono">/destinations</button>
                                <button type="button" @click="activeLinkElement.url = '/enquiry'" class="text-3xs bg-gray-100 hover:bg-gray-200 px-2 py-0.5 rounded text-gray-700 font-mono">/enquiry</button>
                                <button type="button" @click="activeLinkElement.url = '/contact'" class="text-3xs bg-gray-100 hover:bg-gray-200 px-2 py-0.5 rounded text-gray-700 font-mono">/contact</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-2">
                <button type="button" @click="activeLinkElement = null" class="btn-secondary py-1.5 px-3 text-xs">Cancel</button>
                <button type="button" @click="saveLinkEditor()" class="btn-primary py-1.5 px-4 text-xs font-bold" :disabled="isSaving">
                    <span x-text="isSaving ? 'Saving...' : 'Apply Button'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ADD SECTION CANVA TEMPLATE PICKER MODAL --}}
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display: none;">
        <div class="fixed inset-0" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-xl w-full max-h-[85vh] overflow-hidden flex flex-col z-10" @click.stop>
            <div class="p-4 px-6 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <div>
                    <h3 class="font-display font-bold text-gray-900 text-base">Add Design Block</h3>
                    <p class="text-3xs text-gray-500">Pick any design block to add to your page</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showAddModal = false">✕</button>
            </div>

            <div class="p-5 overflow-y-auto flex-1 space-y-4">
                <input type="text" x-model="searchFilter" placeholder="Search templates (e.g. hero, destinations, gallery, cta)..." class="form-input w-full text-xs">

                <div class="grid grid-cols-2 gap-3">
                    @foreach($sectionTypes as $type => $label)
                    @php
                        $bLabel = is_array($label) ? ($label['label'] ?? $type) : $label;
                        $bDesc = is_array($label) ? ($label['description'] ?? '') : '';
                    @endphp
                    <div class="p-3 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-accent-primary/5 cursor-pointer transition-all flex flex-col justify-between"
                         @click="insertSection('{{ $type }}')"
                         x-show="!searchFilter || '{{ strtolower($bLabel . ' ' . $type) }}'.includes(searchFilter.toLowerCase())">
                        <div>
                            <span class="font-bold text-xs text-gray-900 block leading-tight">{{ $bLabel }}</span>
                            <span class="font-mono text-3xs text-gray-400 block mt-0.5">{{ $type }}</span>
                        </div>
                        <span class="mt-3 text-3xs font-bold text-accent-primary">+ Add</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- PAGE SETTINGS MODAL --}}
    <div x-show="showSettingsModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display: none;">
        <div class="fixed inset-0" @click="showSettingsModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto p-6 z-10" @click.stop>
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-4">
                <h3 class="font-bold text-gray-900 text-base">Page Settings & SEO</h3>
                <button type="button" @click="showSettingsModal = false" class="text-gray-400 hover:text-gray-700">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Page Title</label>
                    <input type="text" name="title" value="{{ $page->title }}" required class="form-input w-full text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ $page->slug }}" required class="form-input w-full text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="form-input w-full text-xs">{{ $page->meta_description }}</textarea>
                </div>
                <div class="pt-2 space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-gray-700">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ $page->is_published ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary">
                        <span>Page is Published</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-gray-700">
                        <input type="hidden" name="show_in_navbar" value="0">
                        <input type="checkbox" name="show_in_navbar" value="1" {{ $page->show_in_navbar ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary">
                        <span>Show in Navigation Header</span>
                    </label>
                </div>
                <div class="pt-4 border-t border-gray-200 flex justify-end gap-2">
                    <button type="button" @click="showSettingsModal = false" class="btn-secondary py-1.5 px-3 text-xs">Cancel</button>
                    <button type="submit" class="btn-primary py-1.5 px-4 text-xs font-bold">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Floating Live Coordinates Badge (Drag) --}}
    <div x-show="dragCoordinates && dragCoordinates.show" 
         class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-950 text-white px-4 py-2 rounded-full shadow-2xl border border-accent-primary/60 text-xs font-mono font-bold z-50 flex items-center gap-2 pointer-events-none" style="display: none;">
        <svg class="w-4 h-4 text-accent-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"></path></svg>
        <span>X: <span class="text-accent-light" x-text="dragCoordinates.x + '%'"></span> | Y: <span class="text-accent-light" x-text="dragCoordinates.y + '%'"></span></span>
    </div>

    {{-- Floating Live Measurement Badge (Resize) --}}
    <div x-show="measureInfo && measureInfo.show" 
         class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-950 text-white px-4 py-2 rounded-full shadow-2xl border border-accent-primary/60 text-xs font-mono font-bold z-50 flex items-center gap-2 pointer-events-none" style="display: none;">
        <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
        <span>Width: <span class="text-accent-light" x-text="measureInfo.width + 'px'"></span> | Font: <span class="text-accent-light" x-text="measureInfo.fontSize + 'px'"></span></span>
    </div>

</div>

<style>
/* Figma / Canva Real Resize Handles */
.canva-resize-handles {
    position: absolute;
    inset: -6px;
    border: 2px solid #ea580c;
    border-radius: 4px;
    pointer-events: none;
    z-index: 45;
}
.canva-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #ffffff;
    border: 2px solid #ea580c;
    border-radius: 2px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.35);
    pointer-events: auto;
    z-index: 50;
    transition: transform 0.1s ease, background-color 0.1s ease;
}
.canva-handle:hover {
    transform: scale(1.35);
    background-color: #ea580c;
}
.handle-nw { top: -6px; left: -6px; cursor: nwse-resize; }
.handle-ne { top: -6px; right: -6px; cursor: nesw-resize; }
.handle-se { bottom: -6px; right: -6px; cursor: nwse-resize; }
.handle-sw { bottom: -6px; left: -6px; cursor: nesw-resize; }
.handle-n  { top: -6px; left: calc(50% - 5px); cursor: ns-resize; }
.handle-s  { bottom: -6px; left: calc(50% - 5px); cursor: ns-resize; }
.handle-w  { left: -6px; top: calc(50% - 5px); cursor: ew-resize; }
.handle-e  { right: -6px; top: calc(50% - 5px); cursor: ew-resize; }

.canva-drag-handle-bar {
    pointer-events: auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    backdrop-filter: blur(4px);
}
/* Canva & Figma Selection Box and Typography editing styles */
.canva-rendered-content h1, 
.canva-rendered-content h2, 
.canva-rendered-content h3, 
.canva-rendered-content h4, 
.canva-rendered-content p,
.canva-custom-text {
    position: relative;
    cursor: text;
    transition: outline 0.15s ease, background-color 0.15s ease;
}

.canva-rendered-content h1:hover, 
.canva-rendered-content h2:hover, 
.canva-rendered-content h3:hover, 
.canva-rendered-content h4:hover, 
.canva-rendered-content p:hover,
.canva-custom-text:hover {
    outline: 2px dashed #ea580c !important;
    outline-offset: 4px;
}

.canva-rendered-content {
    user-select: text !important;
}
.canva-rendered-content * {
    user-select: text !important;
}

/* FIGMA / CANVA SELECTION BOX WITH CORNER HANDLES */
.canva-selected-box {
    outline: 2px solid #ea580c !important;
    outline-offset: 4px;
    position: relative;
    border-radius: 4px;
    box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.15) !important;
}

.canva-selected-box::before {
    content: attr(data-tag);
    position: absolute;
    top: -24px;
    left: 0;
    background: #ea580c;
    color: #ffffff;
    font-size: 9px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    pointer-events: none;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    z-index: 50;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.canva-rendered-content img {
    cursor: pointer;
    transition: transform 0.2s, outline 0.2s;
}

.canva-rendered-content img:hover {
    outline: 3px solid #ea580c !important;
    outline-offset: 2px;
}

.canva-rendered-content a,
.canva-rendered-content button,
.canva-rendered-content .btn-primary {
    cursor: pointer;
}

/* Canva drawer custom slim scrollbars */
.canva-drawer-scroll::-webkit-scrollbar {
    width: 5px;
}
.canva-drawer-scroll::-webkit-scrollbar-track {
    background: transparent;
}
.canva-drawer-scroll::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 9999px;
}
.canva-drawer-scroll::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* === GLOBAL ANTI-SCROLL GLITCH FIXES === */
/* Prevent any horizontal scroll on the editing page */
html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
}

/* Prevent sections from causing horizontal overflow */
.canva-section-block {
    overflow-x: hidden !important;
    max-width: 100% !important;
}

/* Prevent canvas rendered content from causing overflow */
.canva-rendered-content {
    position: relative;
    max-width: 100% !important;
}
.canva-rendered-content > * {
    max-width: 100% !important;
}
.canva-rendered-content section {
    max-width: 100% !important;
    overflow-x: hidden !important;
}
.canva-rendered-content img {
    max-width: 100% !important;
}

/* Prevent draggable items from causing overflow */
.canva-draggable-item {
    overflow: visible;
    max-width: 95% !important;
}

/* Prevent the floating coordinate badge from causing scroll */
[class*="fixed"][class*="bottom-6"] {
    max-width: calc(100vw - 2rem) !important;
}

/* Canvas inner wrapper must not bleed */
#canvas-inner {
    overflow-x: hidden !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Sections canvas wrapper */
#sections-canvas-wrapper {
    overflow-x: hidden !important;
    max-width: 100% !important;
}

/* Prevent the category filter pills from causing horizontal scroll */
.no-scrollbar {
    overflow-x: auto !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch;
}

/* Prevent canvas scroll container from showing horizontal scrollbar */
#canvas-scroll-container {
    overflow-x: hidden !important;
}

/* Fix the floating sidebar toggle from causing layout shift */
[class*="bottom-5"][class*="left-0"] {
    z-index: 25;
}

/* Prevent images inside sections from overflowing container */
.canva-section-block img,
.canva-section-block video {
    max-width: 100% !important;
    height: auto;
}

/* Fix random scroll jumps when elements get focus */
.canva-custom-text:focus,
.canva-custom-text[contenteditable="true"] {
    outline: 2px solid #ea580c !important;
    outline-offset: 4px;
}

/* Prevent the selection box animation from causing reflow */
.canva-selected-box {
    will-change: outline;
}

/* Fix for the drop overlay causing layout issues */
.canva-rendered-content > [x-show] {
    overflow: hidden !important;
}

/* Stop component cards in drawer from exceeding drawer width */
.canva-drawer-scroll > div {
    max-width: 100% !important;
    overflow-x: hidden !important;
}

/* Prevent Quick-Add menu from going outside viewport */
[class*="fixed"][class*="z-\[100\]"] {
    max-width: calc(100vw - 1rem) !important;
}

/* Ensure modals don't cause scroll */
.fixed.inset-0 {
    overflow: hidden !important;
}

/* Stop resize handles from creating extra scroll space */
.canva-resize-handles {
    overflow: visible;
    contain: layout;
}
</style>

<script>
function canvaEditor() {
    return {
        deviceView: 'desktop',
        showNavbar: localStorage.getItem('canva_show_navbar') !== 'false', // Navbar visibility toggle on edit canvas
        showSidebar: window.innerWidth >= 1024, // Sidebar visibility toggle (auto-hidden on mobile screens)
        sidebarWasOpenBeforeMobile: true, // Keeps track to restore sidebar when leaving mobile display
        activeSectionId: {{ $page->sections->first()?->id ?? 'null' }},
        leftTab: 'blocks', // 'blocks', 'components', 'text', 'photos', 'layout'
        showAddModal: false,
        showSettingsModal: false,
        searchFilter: '',
        isSaving: false,
        isUploading: false,
        savedNotice: false,
        editingSection: null,

        // Components & Drag State
        componentSearch: '',
        componentCategory: 'all',
        isDraggingFromDrawer: false,
        draggedDrawerItem: null,
        canvasDropTargetSectionId: null,
        quickAddMenu: { show: false, x: 0, y: 0, sectionId: null, leftPct: '50%', topPct: '50%' },
        lastClickPos: null,

        // Dragging state
        draggedSectionId: null,
        dropTargetSectionId: null,
        sectionsOrder: @json($page->sections->pluck('id')),
        _pendingStyleBatch: {},

        // Active text selection & styling
        activeTextElement: null,
        activeTextKey: null,
        selectedTag: null,
        currentFontSize: 36,
        currentStyles: {
            fontFamily: "'Cinzel', serif",
            fontWeight: 'normal',
            fontStyle: 'normal',
            textDecoration: 'none',
            textTransform: 'none',
            color: '#ffffff',
            backgroundColor: 'transparent',
            borderRadius: '0px',
            textAlign: 'center'
        },

        // Button live inspector state
        isButtonSelected: false,
        activeButtonElement: null,
        activeButtonText: '',
        activeButtonUrl: '',
        activeButtonSize: 'md',
        currentPaddingV: 12,
        currentPaddingH: 24,

        // Photo live inspector state
        isPhotoSelected: false,
        activeCustomElementId: null,

        // Live measurements & drag coordinates
        measureInfo: { show: false, width: 0, fontSize: 0 },
        dragCoordinates: { show: false, x: 0, y: 0 },

        // Modals for direct element editing
        activeImageElement: null,
        activeImageSectionId: null,
        activeLinkElement: null,
        activeLinkSectionId: null,

        // High resolution Himalayan Presets
        imagePresets: [
            { name: 'Mount Everest Sunrise', url: '/images/hero-everest.jpg' },
            { name: 'Annapurna Sanctuary', url: '/images/dest-annapurna.jpg' },
            { name: 'Everest Base Camp', url: '/images/gal-everest-sunrise.jpg' },
            { name: 'Ama Dablam Peak', url: '/images/gal-ama-dablam.jpg' },
            { name: 'Gokyo Turquoise Lakes', url: '/images/gal-gokyo-lakes.jpg' },
            { name: 'Langtang Glacier', url: '/images/dest-langtang.jpg' },
            { name: 'Manaslu Circuit', url: '/images/dest-manaslu.jpg' },
            { name: 'Sherpa Monasteries & Culture', url: '/images/exp-monastery.jpg' },
            { name: 'High Himalayan Basecamp', url: '/images/pkg-ebc.jpg' },
            { name: 'Founder & Mountain Guide', url: '/images/about-founder.jpg' },
            { name: 'Alpine Trekking Panorama', url: '/images/dest-1.jpg' },
            { name: 'Himalayan Wildlife & Nature', url: '/images/exp-wildlife.jpg' },
        ],

        // Data map for all sections
        sectionsData: @json($sectionsPayload),

        // Component drawer filtering
        filterComponent(cat, title, tags = '') {
            if (this.componentCategory !== 'all' && this.componentCategory !== cat) {
                return false;
            }
            if (!this.componentSearch || !this.componentSearch.trim()) {
                return true;
            }
            const query = this.componentSearch.toLowerCase().trim();
            const haystack = (title + ' ' + tags + ' ' + cat).toLowerCase();
            return haystack.includes(query);
        },

        // HTML5 Drawer item drag & drop onto canvas
        onDrawerItemDragStart(event, kind, type, config = {}) {
            this.isDraggingFromDrawer = true;
            this.draggedDrawerItem = { kind, type, config };
            try {
                if (event.dataTransfer) {
                    event.dataTransfer.effectAllowed = 'copy';
                    const payloadStr = JSON.stringify({ kind, type, config });
                    event.dataTransfer.setData('application/json', payloadStr);
                    event.dataTransfer.setData('text/plain', payloadStr);
                }
            } catch (e) {}
        },

        onDrawerItemDragEnd(event) {
            setTimeout(() => {
                this.isDraggingFromDrawer = false;
                this.draggedDrawerItem = null;
                this.canvasDropTargetSectionId = null;
            }, 100);
        },

        handleCanvasDragOver(event, sectionId) {
            event.preventDefault();
            if (event.dataTransfer) {
                event.dataTransfer.dropEffect = 'copy';
            }
            this.canvasDropTargetSectionId = sectionId;
            this.activeSectionId = sectionId;
        },

        handleCanvasDragLeave(event, sectionId) {
            const secView = document.getElementById('section-view-' + sectionId);
            if (this.canvasDropTargetSectionId === sectionId) {
                if (!event.relatedTarget || (secView && !secView.contains(event.relatedTarget))) {
                    this.canvasDropTargetSectionId = null;
                }
            }
        },

        handleCanvasDrop(event, sectionId) {
            event.preventDefault();
            this.canvasDropTargetSectionId = null;
            const secView = document.getElementById('section-view-' + sectionId);
            const targetContainer = secView ? (secView.querySelector('.canva-rendered-content') || secView) : (event.currentTarget || event.target);
            const rect = targetContainer ? targetContainer.getBoundingClientRect() : { left: 0, top: 0, width: 800, height: 400 };
            const dropX = rect.width ? Math.round(((event.clientX - rect.left) / rect.width) * 100) : 50;
            const dropY = rect.height ? Math.round(((event.clientY - rect.top) / rect.height) * 100) : 50;
            const leftPct = Math.max(2, Math.min(95, dropX)) + '%';
            const topPct = Math.max(2, Math.min(95, dropY)) + '%';

            let item = null;
            try {
                const raw = event.dataTransfer ? (event.dataTransfer.getData('application/json') || event.dataTransfer.getData('text/plain')) : null;
                if (raw && (raw.startsWith('{') || raw.startsWith('['))) {
                    item = JSON.parse(raw);
                }
            } catch (e) {}

            if (!item && this.draggedDrawerItem) {
                item = this.draggedDrawerItem;
            }

            this.isDraggingFromDrawer = false;
            this.draggedDrawerItem = null;

            if (!item) return;

            if (item.kind === 'photo') {
                this.addNewPhotoElement(sectionId, item.type || 'landscape', item.config?.url || null, leftPct, topPct);
            } else if (item.kind === 'component') {
                this.addComponentElement(sectionId, item.type, item.config || {}, leftPct, topPct);
            } else if (item.kind === 'text') {
                this.addNewTextElement(sectionId, item.type, null, leftPct, topPct);
            }
        },

        openQuickAddMenu(event, sectionId, leftPct, topPct) {
            this.quickAddMenu = {
                show: true,
                x: event.clientX,
                y: event.clientY,
                sectionId: sectionId,
                leftPct: leftPct,
                topPct: topPct
            };
        },

        addFromQuickMenu(kind, type, config = {}) {
            const secId = this.quickAddMenu.sectionId || this.activeSectionId;
            const l = this.quickAddMenu.leftPct;
            const t = this.quickAddMenu.topPct;
            this.quickAddMenu.show = false;
            if (kind === 'photo') {
                this.addNewPhotoElement(secId, type, config.url || null, l, t);
            } else if (kind === 'component') {
                this.addComponentElement(secId, type, config, l, t);
            } else {
                this.addNewTextElement(secId, type, null, l, t);
            }
        },

        init() {
            this.$nextTick(() => {
                this.tagRenderedElements();

                // Prevent random scroll jumps: lock scroll during drag operations
                document.addEventListener('dragstart', () => {
                    document.body.style.overflowX = 'hidden';
                });
                document.addEventListener('dragend', () => {
                    document.body.style.overflowX = 'hidden';
                });
                
                // Prevent wheel horizontal scrolling on the editing page
                document.addEventListener('wheel', (e) => {
                    if (Math.abs(e.deltaX) > Math.abs(e.deltaY) && !e.target.closest('.overflow-x-auto, .no-scrollbar')) {
                        e.preventDefault();
                    }
                }, { passive: false });
                const savedSec = sessionStorage.getItem('canva_scroll_section');
                const savedKey = sessionStorage.getItem('canva_active_key');
                if (savedSec) {
                    sessionStorage.removeItem('canva_scroll_section');
                    this.scrollToSection(parseInt(savedSec));
                    if (savedKey) {
                        sessionStorage.removeItem('canva_active_key');
                        setTimeout(() => {
                            const el = document.querySelector(`.canva-draggable-item[data-key="${savedKey}"]`) || document.querySelector(`[data-key="${savedKey}"]`);
                            if (el) {
                                this.activeSectionId = parseInt(savedSec);
                                this.activeTextKey = savedKey;
                                this.attachSelectionBox(el, 'Placed Layer');
                                
                                const isPhoto = el.querySelector('.canva-photo-box') || savedKey.includes('photo') || savedKey.includes('image');
                                if (isPhoto) {
                                    this.isPhotoSelected = true;
                                    this.isButtonSelected = false;
                                    this.activeCustomElementId = el.dataset.elementId || null;
                                    this.activeTextElement = el;
                                } else {
                                    this.isPhotoSelected = false;
                                    const textTarget = el.querySelector('a, button, .canva-custom-text') || el;
                                    this.activeTextElement = textTarget;
                                    const btn = el.querySelector('a, button');
                                    if (btn) {
                                        this.isButtonSelected = true;
                                        this.activeButtonElement = btn;
                                        this.activeButtonText = btn.textContent.trim();
                                        this.activeButtonUrl = btn.getAttribute('href') || '#';
                                    }
                                }
                            }
                        }, 300);
                    }
                }
            });
        },

        // Auto-tag rendered elements with IDs and Keys for reliable two-way binding
        tagRenderedElements() {
            Object.values(this.sectionsData).forEach(sec => {
                const secContainer = document.getElementById('section-view-' + sec.id);
                if (!secContainer) return;

                const contentArea = secContainer.querySelector('.canva-rendered-content');
                if (!contentArea) return;

                sec.elements.forEach(el => {
                    if (el.type === 'image') {
                        const imgs = contentArea.querySelectorAll('img');
                        imgs.forEach(img => {
                            if (!img.dataset.elementId) {
                                img.dataset.elementId = el.id;
                                img.dataset.key = el.key;
                                img.dataset.sectionId = sec.id;
                            }
                        });
                    } else if (el.type === 'link') {
                        const links = contentArea.querySelectorAll('a, button');
                        links.forEach(l => {
                            if (!l.dataset.elementId && ((el.text && l.textContent.includes(el.text)) || (el.url && l.getAttribute('href') === el.url))) {
                                l.dataset.elementId = el.id;
                                l.dataset.key = el.key;
                                l.dataset.sectionId = sec.id;
                            }
                        });
                    } else if (el.type === 'text' || el.type === 'richtext') {
                        const candidates = contentArea.querySelectorAll('h1, h2, h3, h4, h5, h6, p, span, div.prose, [class*="font-body"]');
                        let matched = false;
                        candidates.forEach(c => {
                            if (!matched && !c.dataset.elementId && el.value && c.textContent.trim().length > 0 && c.textContent.trim().includes(el.value.trim().substring(0, 30))) {
                                c.dataset.elementId = el.id;
                                c.dataset.key = el.key;
                                c.dataset.sectionId = sec.id;
                                matched = true;
                            }
                        });
                    }
                });

                // Tag any remaining buttons, headings, and paragraphs with clean fallbacks
                contentArea.querySelectorAll('a, button').forEach((link, idx) => {
                    if (!link.dataset.key) {
                        link.dataset.key = (idx === 0) ? 'primary_cta' : 'secondary_cta';
                        link.dataset.sectionId = sec.id;
                    }
                });
                contentArea.querySelectorAll('h1, h2').forEach(h => {
                    if (!h.dataset.key) {
                        h.dataset.key = 'headline';
                        h.dataset.sectionId = sec.id;
                    }
                });
                contentArea.querySelectorAll('h3, h4, span.text-accent-primary').forEach(s => {
                    if (!s.dataset.key) {
                        s.dataset.key = 'subheadline';
                        s.dataset.sectionId = sec.id;
                    }
                });
                contentArea.querySelectorAll('p, .prose').forEach(p => {
                    if (!p.dataset.key) {
                        p.dataset.key = 'body';
                        p.dataset.sectionId = sec.id;
                    }
                });
            });
        },

        toggleNavbar(val = null) {
            this.showNavbar = val !== null ? val : !this.showNavbar;
            try {
                localStorage.setItem('canva_show_navbar', this.showNavbar ? 'true' : 'false');
            } catch (e) {}
        },

        toggleSidebar(val = null) {
            this.showSidebar = val !== null ? val : !this.showSidebar;
            if (!this.showSidebar) {
                this.leftTab = null;
            } else if (!this.leftTab) {
                this.leftTab = 'blocks';
            }
        },

        setDeviceView(view) {
            const prev = this.deviceView;
            this.deviceView = view;
            if (view === 'mobile') {
                if (prev !== 'mobile') {
                    this.sidebarWasOpenBeforeMobile = this.showSidebar;
                }
                // Automatically hide sidebar in mobile display to maximize phone preview area
                this.showSidebar = false;
                this.leftTab = null;
            } else if (prev === 'mobile') {
                // Restore sidebar when switching back to desktop/tablet if it was open before
                if (this.sidebarWasOpenBeforeMobile && window.innerWidth >= 1024) {
                    this.showSidebar = true;
                    if (!this.leftTab) this.leftTab = 'blocks';
                }
            }
        },

        getDeviceStyle() {
            if (this.deviceView === 'mobile') return 'width: 390px; max-width: 390px; min-height: 844px; margin: 1.5rem auto;';
            if (this.deviceView === 'tablet') return 'width: 768px; max-width: 768px; min-height: 1024px; margin: 1.5rem auto;';
            return 'width: 100%; max-width: 1280px; min-height: 100%; margin: 0 auto;';
        },

        setActiveSection(id) {
            this.activeSectionId = id;
        },

        scrollToSection(id) {
            this.activeSectionId = id;
            const el = document.getElementById('section-view-' + id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
            }
        },

        openBlockDrawer(id) {
            this.activeSectionId = id;
            this.editingSection = JSON.parse(JSON.stringify(this.sectionsData[id] || null));
        },

        // DRAG AND DROP REORDERING (Sections & Contents)
        onSectionDragStart(event, sectionId) {
            this.draggedSectionId = sectionId;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', sectionId);
            const block = document.getElementById('section-view-' + sectionId);
            if (block) block.classList.add('opacity-40');
        },

        onSectionDragEnd(event) {
            this.draggedSectionId = null;
            this.dropTargetSectionId = null;
            document.querySelectorAll('.canva-section-block').forEach(b => {
                b.classList.remove('opacity-40');
            });
        },

        onSectionDragOver(event, targetSectionId = null) {
            event.preventDefault();
            if (this.isDraggingFromDrawer || this.draggedDrawerItem) {
                this.canvasDropTargetSectionId = targetSectionId;
                this.activeSectionId = targetSectionId;
                if (event.dataTransfer) {
                    event.dataTransfer.dropEffect = 'copy';
                }
                return;
            }
            if (event.dataTransfer) {
                event.dataTransfer.dropEffect = 'move';
            }
            if (targetSectionId && targetSectionId !== this.draggedSectionId) {
                this.dropTargetSectionId = targetSectionId;
            }
        },

        async onSectionDrop(event, targetSectionId) {
            event.preventDefault();
            if (this.isDraggingFromDrawer || this.draggedDrawerItem) {
                return this.handleCanvasDrop(event, targetSectionId);
            }
            const sourceId = this.draggedSectionId;
            this.onSectionDragEnd(event);
            if (!sourceId || sourceId === targetSectionId) return;

            const list = [...this.sectionsOrder];
            const sourceIdx = list.indexOf(sourceId);
            const targetIdx = list.indexOf(targetSectionId);
            if (sourceIdx === -1 || targetIdx === -1) return;

            list.splice(sourceIdx, 1);
            list.splice(targetIdx, 0, sourceId);
            this.sectionsOrder = list;

            this.isSaving = true;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                await fetch('/admin/pages/{{ $page->id }}/sections/reorder', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: list })
                });
                window.location.reload();
            } catch (e) {
                console.error('Reorder error', e);
            } finally {
                this.isSaving = false;
            }
        },

        // FIGMA / CANVA SELECTION BOX ATTACHMENT
        attachSelectionBox(element, tag) {
            // Remove selection box from previous element
            document.querySelectorAll('.canva-selected-box').forEach(el => {
                el.classList.remove('canva-selected-box');
                el.removeAttribute('data-tag');
            });

            this.selectedTag = tag;
            element.classList.add('canva-selected-box');
            element.setAttribute('data-tag', tag);
        },

        rgbToHex(color) {
            if (!color) return '#ffffff';
            if (color.startsWith('#')) return color;
            const match = color.match(/\d+/g);
            if (!match || match.length < 3) return '#ffffff';
            return '#' + ((1 << 24) + (parseInt(match[0]) << 16) + (parseInt(match[1]) << 8) + parseInt(match[2])).toString(16).slice(1);
        },

        normalizeFontFamily(font) {
            if (!font) return "'Cinzel', serif";
            const s = font.toLowerCase().replace(/['"]/g, '');
            if (s.includes('playfair')) return "'Playfair Display', serif";
            if (s.includes('garamond')) return "'Cormorant Garamond', serif";
            if (s.includes('cinzel')) return "'Cinzel', serif";
            if (s.includes('outfit')) return "'Outfit', sans-serif";
            if (s.includes('grotesk')) return "'Space Grotesk', sans-serif";
            if (s.includes('montserrat')) return "'Montserrat', sans-serif";
            if (s.includes('inter')) return "'Inter', sans-serif";
            return "'Cinzel', serif";
        },

        // INJECT LIVE RUNTIME STYLESHEET RULE
        injectLiveStyleRule(kebabProp, value) {
            let styleTag = document.getElementById('canva-live-overrides');
            if (!styleTag) {
                styleTag = document.createElement('style');
                styleTag.id = 'canva-live-overrides';
                document.head.appendChild(styleTag);
            }

            let selector = '';
            const targetEl = this.activeButtonElement || this.activeTextElement;
            if (targetEl) {
                if (!targetEl.dataset.canvaUid) {
                    targetEl.dataset.canvaUid = 'canva-uid-' + Date.now() + '-' + Math.floor(Math.random()*1000);
                }
                const uid = targetEl.dataset.canvaUid;
                selector = `[data-canva-uid="${uid}"], [data-canva-uid="${uid}"] *`;
            } else if (this.activeSectionId) {
                selector = `#section-view-${this.activeSectionId} h1, #section-view-${this.activeSectionId} h2, #section-view-${this.activeSectionId} h3, #section-view-${this.activeSectionId} h4, #section-view-${this.activeSectionId} p, #section-view-${this.activeSectionId} a, #section-view-${this.activeSectionId} button, #section-view-${this.activeSectionId} span.text-accent-primary, #section-view-${this.activeSectionId} .canva-custom-text`;
            }

            if (selector) {
                const rule = `${selector} { ${kebabProp}: ${value} !important; }\n`;
                styleTag.appendChild(document.createTextNode(rule));
            }
        },

        // CANVA TYPOGRAPHY & STYLING ENGINE (LIVE 60FPS + AUTO-SAVE)
        applyStyle(property, value) {
            this.currentStyles[property] = value;
            const kebab = property.replace(/([A-Z])/g, '-$1').toLowerCase();

            let targets = [];
            const targetEl = this.activeButtonElement || this.activeTextElement;
            if (targetEl) {
                targets.push(targetEl);
                targetEl.querySelectorAll('*').forEach(c => targets.push(c));
            } else if (this.activeSectionId) {
                const secContainer = document.getElementById('section-view-' + this.activeSectionId);
                if (secContainer) {
                    targets = Array.from(secContainer.querySelectorAll('h1, h2, h3, h4, h5, p, a, button, span.text-accent-primary, .canva-custom-text'));
                }
            }

            // Immediately set inline property with !important on DOM nodes
            targets.forEach(t => {
                t.style.setProperty(kebab, value, 'important');
                if (property === 'textAlign') {
                    t.style.setProperty('width', '100%', 'important');
                    t.style.setProperty('display', 'block', 'important');
                }
            });

            // Inject into live overrides stylesheet
            this.injectLiveStyleRule(kebab, value);

            // Debounced persist to database
            this.persistStyleProperty(property, value);
        },

        // BUTTON LIVE EDITING (TEXT & LINK URL)
        updateActiveButton(field, value) {
            if (!this.activeButtonElement || !this.activeSectionId) return;

            if (field === 'text') {
                this.activeButtonText = value;
                const svg = this.activeButtonElement.querySelector('svg');
                this.activeButtonElement.textContent = value;
                if (svg) this.activeButtonElement.appendChild(svg);
            } else if (field === 'url') {
                this.activeButtonUrl = value;
                this.activeButtonElement.setAttribute('href', value);
            }

            clearTimeout(this._buttonSaveTimer);
            this._buttonSaveTimer = setTimeout(async () => {
                const elId = this.activeButtonElement.dataset?.elementId;
                const key = this.activeTextKey || 'primary_cta';

                let payloadElements = {};
                if (elId) {
                    payloadElements[elId] = {
                        text: this.activeButtonText,
                        url: this.activeButtonUrl,
                        style: 'primary'
                    };
                } else {
                    payloadElements[key] = {
                        text: this.activeButtonText,
                        url: this.activeButtonUrl,
                        style: 'primary'
                    };
                }

                await this.persistElements(this.activeSectionId, payloadElements);
            }, 350);
        },

        toggleStyle(property, onValue, offValue) {
            const current = this.currentStyles[property];
            const next = (current === onValue) ? offValue : onValue;
            this.applyStyle(property, next);
        },

        stepFontSize(step) {
            const cur = parseInt(this.currentFontSize) || 24;
            this.currentFontSize = Math.max(10, Math.min(140, cur + step));
            this.applyStyle('fontSize', this.currentFontSize + 'px');
        },

        setButtonSize(size) {
            this.activeButtonSize = size;
            const presets = {
                'xs': { v: 4, h: 10, f: '11px', pad: '4px 10px' },
                'sm': { v: 6, h: 14, f: '13px', pad: '6px 14px' },
                'md': { v: 10, h: 22, f: '15px', pad: '10px 22px' },
                'lg': { v: 14, h: 30, f: '16px', pad: '14px 30px' },
                'xl': { v: 18, h: 38, f: '18px', pad: '18px 38px' },
            };
            const p = presets[size] || presets['md'];
            this.currentPaddingV = p.v;
            this.currentPaddingH = p.h;
            this.currentFontSize = parseInt(p.f);
            this.applyStyle('padding', p.pad);
            this.applyStyle('fontSize', p.f);
        },

        stepButtonPadding(deltaV, deltaH) {
            this.currentPaddingV = Math.max(2, (this.currentPaddingV || 10) + deltaV);
            this.currentPaddingH = Math.max(6, (this.currentPaddingH || 20) + deltaH);
            const newPad = `${this.currentPaddingV}px ${this.currentPaddingH}px`;
            this.activeButtonSize = 'custom';
            this.applyStyle('padding', newPad);
        },

        applyFontPairing(headingFont, bodyFont) {
            let hf = "'Cinzel', serif";
            let bf = "'Outfit', sans-serif";

            if (headingFont === 'playfair') {
                hf = "'Playfair Display', serif";
                bf = "'Inter', sans-serif";
            } else if (headingFont === 'grotesk') {
                hf = "'Space Grotesk', sans-serif";
                bf = "'Inter', sans-serif";
            }

            if (this.activeTextElement) {
                this.applyStyle('fontFamily', hf);
            } else if (this.activeSectionId) {
                const secContainer = document.getElementById('section-view-' + this.activeSectionId);
                if (secContainer) {
                    secContainer.querySelectorAll('h1, h2').forEach(h => h.style.setProperty('font-family', hf, 'important'));
                    secContainer.querySelectorAll('p, div.prose').forEach(p => p.style.setProperty('font-family', bf, 'important'));
                }
                this.applyStyle('fontFamily', hf);
            }
        },

        persistStyleProperty(prop, val) {
            if (!this.activeSectionId) return;
            let key = this.activeTextKey;
            if (!key && this.activeButtonElement) {
                key = this.activeButtonElement.dataset?.key || (this.activeButtonElement.classList.contains('btn-primary') ? 'primary_cta' : 'secondary_cta');
            }
            if (!key && this.activeTextElement) {
                const tag = this.activeTextElement.tagName.toLowerCase();
                key = (tag.startsWith('h')) ? 'headline' : (tag === 'p' ? 'body' : 'subheadline');
            }
            if (!key) key = 'headline';

            if (!this._pendingStyleBatch) this._pendingStyleBatch = {};
            if (!this._pendingStyleBatch[this.activeSectionId]) this._pendingStyleBatch[this.activeSectionId] = {};
            if (!this._pendingStyleBatch[this.activeSectionId][key]) this._pendingStyleBatch[this.activeSectionId][key] = {};
            this._pendingStyleBatch[this.activeSectionId][key][prop] = val;

            const sectionId = this.activeSectionId;
            const doSave = async () => {
                clearTimeout(this._styleDebounceTimer);
                this._flushStylePending = null;
                const batch = this._pendingStyleBatch[sectionId] || {};
                const payload = {
                    settings: {
                        styles: batch
                    }
                };
                await this.persistElements(sectionId, {}, payload);
            };

            this._flushStylePending = doSave;
            clearTimeout(this._styleDebounceTimer);
            this._styleDebounceTimer = setTimeout(doSave, 250);
        },

        // PLACEMENT & LAYOUT CONTROLS
        async setBlockAlignment(align) {
            if (!this.activeSectionId) return;
            const secContainer = document.getElementById('section-view-' + this.activeSectionId);
            if (secContainer) {
                const inner = secContainer.querySelector('.canva-rendered-content');
                if (inner) inner.style.textAlign = align;
            }

            await this.persistElements(this.activeSectionId, {}, {
                settings: { content_alignment: align }
            });
        },

        async setBlockPadding(paddingType) {
            if (!this.activeSectionId) return;
            const secContainer = document.getElementById('section-view-' + this.activeSectionId);
            if (secContainer) {
                const sec = secContainer.querySelector('section');
                if (sec) {
                    sec.style.paddingTop = paddingType === 'compact' ? '3rem' : (paddingType === 'spacious' ? '8rem' : '5rem');
                    sec.style.paddingBottom = paddingType === 'compact' ? '3rem' : (paddingType === 'spacious' ? '8rem' : '5rem');
                }
            }

            await this.persistElements(this.activeSectionId, {}, {
                settings: { padding: paddingType }
            });
        },

        async setBlockOverlay(opacity) {
            if (!this.activeSectionId) return;
            const secContainer = document.getElementById('section-view-' + this.activeSectionId);
            if (secContainer) {
                const overlay = secContainer.querySelector('[class*="inset-0"][class*="bg-"]');
                if (overlay) {
                    overlay.style.opacity = opacity;
                }
            }

            await this.persistElements(this.activeSectionId, {}, {
                settings: { overlay_opacity: opacity }
            });
        },

        // ADD NEW TEXT ELEMENT / SECTION ADDITION (CANVA PLACED LAYERS)
        async addNewTextElement(sectionId, type, initialContent = null, customLeft = null, customTop = null) {
            if (!sectionId) sectionId = this.activeSectionId;
            if (!sectionId) {
                const firstSec = Object.keys(this.sectionsData)[0];
                if (firstSec) sectionId = parseInt(firstSec);
            }
            if (!sectionId) {
                alert('Please click on a section block on the canvas first.');
                return;
            }

            this.isSaving = true;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const timestamp = Date.now();
            let defaultContent = initialContent || 'New Headline Text';
            let elType = 'text';

            let initialLeft = customLeft || (this.lastClickPos && this.lastClickPos.sectionId == sectionId ? this.lastClickPos.left : '50%');
            let initialTop = customTop || (this.lastClickPos && this.lastClickPos.sectionId == sectionId ? this.lastClickPos.top : '45%');

            let initialStyles = {
                position: 'absolute',
                left: initialLeft,
                top: initialTop
            };

            if (type === 'subheading') {
                defaultContent = initialContent || 'New Subheading Description';
                elType = 'text';
                initialStyles.fontSize = '20px';
                initialStyles.fontFamily = "'Outfit', sans-serif";
                initialStyles.color = '#f3f4f6';
            } else if (type === 'body') {
                defaultContent = initialContent || 'Add your detailed expedition or page description here.';
                elType = 'richtext';
                initialStyles.fontSize = '16px';
                initialStyles.fontFamily = "'Outfit', sans-serif";
                initialStyles.color = '#e5e7eb';
            } else if (type === 'button') {
                defaultContent = initialContent || JSON.stringify({ text: 'Explore More', url: '/expeditions', style: 'primary' });
                elType = 'link';
                initialStyles.backgroundColor = '#ea580c';
                initialStyles.color = '#ffffff';
                initialStyles.borderRadius = '8px';
                initialStyles.fontSize = '16px';
                initialStyles.fontFamily = "'Outfit', sans-serif";
                initialStyles.fontWeight = 'bold';
            } else {
                initialStyles.fontSize = '48px';
                initialStyles.fontFamily = "'Cinzel', serif";
                initialStyles.color = '#ffffff';
                initialStyles.fontWeight = 'bold';
            }

            const elementKey = 'custom_' + type + '_' + timestamp;

            try {
                const res = await fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/content`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        new_element: {
                            type: elType,
                            key: elementKey,
                            content: defaultContent
                        },
                        settings: {
                            styles: {
                                [elementKey]: initialStyles
                            }
                        }
                    })
                });
                const data = await res.json();
                if (data.success) {
                    sessionStorage.setItem('canva_scroll_section', sectionId);
                    sessionStorage.setItem('canva_active_key', elementKey);
                    window.location.reload();
                }
            } catch (e) {
                alert('Could not add element.');
            } finally {
                this.isSaving = false;
            }
        },

        async addComponentElement(sectionId, componentType, config = {}, customLeft = null, customTop = null) {
            if (!sectionId) sectionId = this.activeSectionId;
            if (!sectionId) {
                const firstSec = Object.keys(this.sectionsData)[0];
                if (firstSec) sectionId = parseInt(firstSec);
            }
            if (!sectionId) {
                alert('Please click on a section block on the canvas first.');
                return;
            }

            this.isSaving = true;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const timestamp = Date.now();
            const elementKey = 'custom_comp_' + componentType + '_' + timestamp;

            let initialLeft = customLeft || (this.lastClickPos && this.lastClickPos.sectionId == sectionId ? this.lastClickPos.left : '50%');
            let initialTop = customTop || (this.lastClickPos && this.lastClickPos.sectionId == sectionId ? this.lastClickPos.top : '50%');

            const payloadContent = {
                type: componentType,
                ...config
            };

            let defaultWidth = null;
            if (['search_packages', 'search_destinations'].includes(componentType)) {
                defaultWidth = '540px';
            } else if (componentType === 'search_capsule') {
                defaultWidth = '420px';
            } else if (componentType.startsWith('card_')) {
                defaultWidth = '480px';
            }

            let initialStyles = {
                position: 'absolute',
                left: initialLeft,
                top: initialTop,
                zIndex: '30'
            };
            if (defaultWidth) {
                initialStyles.width = defaultWidth;
            }

            try {
                const res = await fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/content`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        new_element: {
                            type: 'component',
                            key: elementKey,
                            content: JSON.stringify(payloadContent)
                        },
                        settings: {
                            styles: {
                                [elementKey]: initialStyles
                            }
                        }
                    })
                });
                const data = await res.json();
                if (data.success) {
                    sessionStorage.setItem('canva_scroll_section', sectionId);
                    sessionStorage.setItem('canva_active_key', elementKey);
                    window.location.reload();
                }
            } catch (e) {
                alert('Could not add component.');
            } finally {
                this.isSaving = false;
            }
        },

        // ADD NEW PHOTO BOX ELEMENT (CANVA PLACED RESIZABLE PHOTO LAYER)
        async addNewPhotoElement(sectionId, preset = 'landscape', customUrl = null, customLeft = null, customTop = null) {
            if (!sectionId) sectionId = this.activeSectionId;
            if (!sectionId) {
                const firstSec = Object.keys(this.sectionsData)[0];
                if (firstSec) sectionId = parseInt(firstSec);
            }
            if (!sectionId) {
                alert('Please click on a section block on the canvas first.');
                return;
            }

            this.isSaving = true;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const timestamp = Date.now();
            const elementKey = 'custom_photo_' + timestamp;

            let initialLeft = customLeft || (this.lastClickPos && this.lastClickPos.sectionId == sectionId ? this.lastClickPos.left : '50%');
            let initialTop = customTop || (this.lastClickPos && this.lastClickPos.sectionId == sectionId ? this.lastClickPos.top : '50%');

            const defaultUrls = {
                landscape: '/images/hero-everest.jpg',
                portrait: '/images/dest-annapurna.jpg',
                square: '/images/gal-ama-dablam.jpg',
                circle: '/images/about-founder.jpg',
                polaroid: '/images/gal-gokyo-lakes.jpg'
            };

            const aspectRatios = {
                landscape: '16/9',
                portrait: '3/4',
                square: '1/1',
                circle: '1/1',
                polaroid: '1/1'
            };

            const borderRadii = {
                landscape: '16px',
                portrait: '16px',
                square: '16px',
                circle: '9999px',
                polaroid: '8px'
            };

            const widths = {
                landscape: '420px',
                portrait: '320px',
                square: '320px',
                circle: '220px',
                polaroid: '300px'
            };

            const chosenUrl = customUrl || defaultUrls[preset] || '/images/hero-everest.jpg';

            const payloadContent = {
                url: chosenUrl,
                alt: 'Himalayan Photo Frame',
                caption: preset === 'polaroid' ? 'High Himalayas • Nepal' : ''
            };

            let initialStyles = {
                position: 'absolute',
                left: initialLeft,
                top: initialTop,
                width: widths[preset] || '380px',
                borderRadius: borderRadii[preset] || '16px',
                aspectRatio: aspectRatios[preset] || '16/9',
                boxShadow: preset === 'polaroid' ? '0 20px 25px -5px rgba(0, 0, 0, 0.4)' : '0 10px 15px -3px rgba(0, 0, 0, 0.2)',
                border: preset === 'polaroid' ? '12px solid #ffffff' : 'none',
                zIndex: '30'
            };

            try {
                const res = await fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/content`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        new_element: {
                            type: 'image',
                            key: elementKey,
                            content: JSON.stringify(payloadContent)
                        },
                        settings: {
                            styles: {
                                [elementKey]: initialStyles
                            }
                        }
                    })
                });
                const data = await res.json();
                if (data.success) {
                    sessionStorage.setItem('canva_scroll_section', sectionId);
                    sessionStorage.setItem('canva_active_key', elementKey);
                    window.location.reload();
                }
            } catch (e) {
                alert('Could not add photo box.');
            } finally {
                this.isSaving = false;
            }
        },

        async setPhotoStyle(property, value) {
            if (!this.activeSectionId || !this.activeTextKey) return;
            const item = document.querySelector(`.canva-draggable-item[data-key="${this.activeTextKey}"]`);
            if (item) {
                if (property === 'borderRadius') {
                    item.style.borderRadius = value;
                    const innerBox = item.querySelector('.canva-photo-box');
                    if (innerBox) innerBox.style.borderRadius = value;
                    const img = item.querySelector('img');
                    if (img) img.style.borderRadius = value;
                } else if (property === 'aspectRatio') {
                    item.style.aspectRatio = value;
                    const innerBox = item.querySelector('.canva-photo-box');
                    if (innerBox) innerBox.style.aspectRatio = value;
                    const img = item.querySelector('img');
                    if (img) img.style.aspectRatio = value;
                } else {
                    item.style[property] = value;
                }
            }
            await this.persistElements(this.activeSectionId, {}, {
                settings: {
                    styles: {
                        [this.activeTextKey]: {
                            [property]: value
                        }
                    }
                }
            });
        },

        async togglePhotoShadow() {
            if (!this.activeSectionId || !this.activeTextKey) return;
            const item = document.querySelector(`.canva-draggable-item[data-key="${this.activeTextKey}"]`);
            const curShadow = item?.style.boxShadow || '';
            const newShadow = curShadow && curShadow !== 'none' ? 'none' : '0 25px 50px -12px rgba(0, 0, 0, 0.45)';
            await this.setPhotoStyle('boxShadow', newShadow);
        },

        async togglePhotoBorder() {
            if (!this.activeSectionId || !this.activeTextKey) return;
            const item = document.querySelector(`.canva-draggable-item[data-key="${this.activeTextKey}"]`);
            const curBorder = item?.style.border || '';
            const newBorder = curBorder && curBorder !== 'none' ? 'none' : '4px solid #ffffff';
            await this.setPhotoStyle('border', newBorder);
        },

        async duplicateCustomElement(sectionId, elementId) {
            if (!sectionId || !elementId) return;
            const sec = this.sectionsData[sectionId];
            const el = sec?.elements?.find(e => e.id == elementId);
            if (!el) return;

            if (el.type === 'image' || el.key.includes('photo') || el.key.includes('image')) {
                let imgData = typeof el.content === 'object' ? el.content : JSON.parse(el.content || '{}');
                await this.addNewPhotoElement(sectionId, 'landscape', imgData.url || null);
                return;
            }

            if (el.type === 'component' || el.key.includes('comp_') || el.key.includes('search') || el.key.includes('badge') || el.key.includes('card')) {
                let compData = typeof el.content === 'object' ? el.content : JSON.parse(el.content || '{}');
                await this.addComponentElement(sectionId, compData.type || 'badge_certified', compData);
                return;
            }

            let type = 'headline';
            if (el.key.includes('subheading')) type = 'subheading';
            else if (el.key.includes('body')) type = 'body';
            else if (el.key.includes('button') || el.type === 'link') type = 'button';

            await this.addNewTextElement(sectionId, type, el.content);
        },

        async deleteCustomElement(sectionId, elementId) {
            if (!confirm('Delete this element?')) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;

            await fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/content`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ delete_element_id: elementId })
            });
            window.location.reload();
        },

        // CANVA DRAG-AND-DROP ANYWHERE ON SECTION (ROCK SOLID ENGINE)
        startDraggingElement(event, sectionId, elementKey, elementId) {
            if (event.target.closest('.canva-handle')) return;
            if (event.target.closest('.canva-drag-handle-bar button')) return;
            if (event.target.isContentEditable) return;
            if (['SELECT', 'TEXTAREA'].includes(event.target.tagName) && !event.target.closest('.canva-draggable-item')) return;

            event.preventDefault();
            event.stopPropagation();
            this.quickAddMenu.show = false;

            this.activeSectionId = sectionId;
            this.activeTextKey = elementKey;
            this.activeCustomElementId = elementId;

            const dragItem = document.querySelector(`.canva-draggable-item[data-key="${elementKey}"]`) || event.currentTarget.closest('.canva-draggable-item') || event.currentTarget;
            if (!dragItem) return;

            this.attachSelectionBox(dragItem, 'Placed Layer');
            
            const isPhoto = dragItem.querySelector('.canva-photo-box') || elementKey.includes('photo') || elementKey.includes('image');
            let btnEl = null;
            if (isPhoto) {
                this.isPhotoSelected = true;
                this.isButtonSelected = false;
                this.activeButtonElement = null;
                this.activeTextElement = dragItem;
            } else {
                this.isPhotoSelected = false;
                btnEl = dragItem.querySelector('a, button');
                if (btnEl) {
                    this.isButtonSelected = true;
                    this.activeButtonElement = btnEl;
                    this.activeTextElement = btnEl;
                    this.activeButtonText = btnEl.textContent.trim();
                    this.activeButtonUrl = btnEl.getAttribute('href') || '#';
                } else {
                    this.isButtonSelected = false;
                    this.activeButtonElement = null;
                    this.activeTextElement = dragItem.querySelector('.canva-custom-text') || dragItem;
                }
            }

            // Sync current styles with toolbar
            if (this.activeTextElement) {
                const computed = window.getComputedStyle(this.activeTextElement);
                this.currentFontSize = parseInt(computed.fontSize) || 24;
                this.currentStyles.fontFamily = this.normalizeFontFamily(computed.fontFamily);
                const fw = computed.fontWeight;
                this.currentStyles.fontWeight = (fw === 'bold' || parseInt(fw) >= 600) ? 'bold' : 'normal';
                this.currentStyles.fontStyle = computed.fontStyle === 'italic' ? 'italic' : 'normal';
                this.currentStyles.textDecoration = (computed.textDecorationLine?.includes('underline') || computed.textDecoration?.includes('underline')) ? 'underline' : 'none';
                this.currentStyles.textTransform = computed.textTransform || 'none';
                this.currentStyles.color = this.rgbToHex(computed.color);
                this.currentStyles.backgroundColor = this.rgbToHex(computed.backgroundColor);
                this.currentStyles.borderRadius = computed.borderRadius;
                this.currentStyles.textAlign = computed.textAlign || 'left';

                if (btnEl) {
                    const padTop = parseInt(computed.paddingTop) || 10;
                    const padLeft = parseInt(computed.paddingLeft) || 20;
                    this.currentPaddingV = padTop;
                    this.currentPaddingH = padLeft;
                    if (padTop <= 5) this.activeButtonSize = 'xs';
                    else if (padTop <= 8) this.activeButtonSize = 'sm';
                    else if (padTop <= 12) this.activeButtonSize = 'md';
                    else if (padTop <= 16) this.activeButtonSize = 'lg';
                    else this.activeButtonSize = 'xl';
                }
            }

            const secContainer = document.getElementById('section-view-' + sectionId);
            const contentArea = secContainer?.querySelector('.canva-rendered-content') || secContainer;
            if (!contentArea) return;

            const contentRect = contentArea.getBoundingClientRect();
            const itemRect = dragItem.getBoundingClientRect();

            // Calculate exact center coordinates as percentages of contentArea
            const initialCenterX = itemRect.left + (itemRect.width / 2);
            const initialCenterY = itemRect.top + (itemRect.height / 2);
            const curLeftPct = ((initialCenterX - contentRect.left) / contentRect.width) * 100;
            const curTopPct = ((initialCenterY - contentRect.top) / contentRect.height) * 100;

            const startX = event.clientX;
            const startY = event.clientY;
            let moved = false;

            this.dragCoordinates = { show: true, x: Math.round(curLeftPct), y: Math.round(curTopPct) };

            const preventNativeDrag = (e) => e.preventDefault();
            window.addEventListener('dragstart', preventNativeDrag, { capture: true });

            const onMouseMove = (e) => {
                e.preventDefault();
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;

                if (!moved && Math.hypot(dx, dy) < 4) {
                    return;
                }
                moved = true;

                const deltaLeftPct = (dx / contentRect.width) * 100;
                const deltaTopPct = (dy / contentRect.height) * 100;

                const newLeft = Math.max(1, Math.min(99, Math.round(curLeftPct + deltaLeftPct)));
                const newTop = Math.max(1, Math.min(99, Math.round(curTopPct + deltaTopPct)));

                dragItem.style.setProperty('position', 'absolute', 'important');
                dragItem.style.setProperty('left', newLeft + '%', 'important');
                dragItem.style.setProperty('top', newTop + '%', 'important');
                dragItem.style.setProperty('transform', 'translate(-50%, -50%)', 'important');
                dragItem.style.setProperty('z-index', '40', 'important');

                this.dragCoordinates = { show: true, x: newLeft, y: newTop };
            };

            const onMouseUp = async (e) => {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
                window.removeEventListener('dragstart', preventNativeDrag, { capture: true });
                this.dragCoordinates.show = false;

                if (moved) {
                    const finalLeft = dragItem.style.left;
                    const finalTop = dragItem.style.top;

                    await this.persistElements(sectionId, {}, {
                        settings: {
                            styles: {
                                [elementKey]: {
                                    position: 'absolute',
                                    left: finalLeft,
                                    top: finalTop
                                }
                            }
                        }
                    });
                }
            };

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        },

        startInlineEditing(event, sectionId, elementKey) {
            const dragItem = event.currentTarget.closest('.canva-draggable-item') || event.currentTarget;
            const textEl = dragItem.querySelector('.canva-custom-text, a, button, h1, h2, h3, h4, p, span') || dragItem;
            if (!textEl) return;
            if (textEl.isContentEditable) return;

            textEl.contentEditable = "true";
            textEl.focus({ preventScroll: true });

            try {
                const range = document.createRange();
                range.selectNodeContents(textEl);
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            } catch (err) {}

            const originalText = textEl.innerText.trim();
            const onBlur = async () => {
                textEl.contentEditable = "false";
                textEl.removeEventListener('blur', onBlur);
                textEl.removeEventListener('keydown', onKey);

                const updatedText = textEl.innerText.trim();
                if (updatedText !== originalText && updatedText.length > 0) {
                    await this.handleTextUpdated(sectionId, textEl, updatedText);
                }
            };

            const onKey = (e) => {
                if (e.key === 'Escape') {
                    textEl.innerText = originalText;
                    textEl.blur();
                } else if (e.key === 'Enter' && !e.shiftKey && textEl.tagName !== 'P') {
                    e.preventDefault();
                    textEl.blur();
                }
            };

            textEl.addEventListener('blur', onBlur);
            textEl.addEventListener('keydown', onKey);
        },

        // FIGMA / CANVA 8-HANDLE ELEMENT RESIZING ENGINE
        startResizingElement(event, sectionId, elementKey, direction) {
            event.preventDefault();
            event.stopPropagation();
            this.quickAddMenu.show = false;

            this.activeSectionId = sectionId;
            this.activeTextKey = elementKey;

            const dragItem = document.querySelector(`.canva-draggable-item[data-key="${elementKey}"]`);
            if (!dragItem) return;

            const textEl = dragItem.querySelector('.canva-custom-text, a, button') || dragItem;
            const computed = window.getComputedStyle(textEl);
            const startWidth = dragItem.offsetWidth;
            const startFontSize = parseInt(computed.fontSize) || 24;
            const startX = event.clientX;
            const startY = event.clientY;

            this.measureInfo = { show: true, width: startWidth, fontSize: startFontSize };

            const onMouseMove = (e) => {
                e.preventDefault();
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;

                let newWidth = startWidth;
                if (direction.includes('e')) {
                    newWidth = Math.max(60, startWidth + dx);
                } else if (direction.includes('w')) {
                    newWidth = Math.max(60, startWidth - dx);
                }

                // If corner handle (nw, ne, se, sw) or s/n, scale font size proportionally for text elements (not components or photos)
                let newFontSize = startFontSize;
                const isComponent = elementKey.includes('comp_') || dragItem.querySelector('.canva-component-root');
                const isPhoto = elementKey.includes('photo') || elementKey.includes('image') || dragItem.querySelector('.canva-photo-box');
                if (!isComponent && !isPhoto) {
                    if (direction === 'se' || direction === 'ne' || direction === 'sw' || direction === 'nw') {
                        const ratio = newWidth / startWidth;
                        newFontSize = Math.max(12, Math.min(140, Math.round(startFontSize * ratio)));
                        textEl.style.setProperty('font-size', newFontSize + 'px', 'important');
                        this.currentFontSize = newFontSize;
                    } else if (direction === 's') {
                        newFontSize = Math.max(12, Math.min(140, Math.round(startFontSize + (dy * 0.5))));
                        textEl.style.setProperty('font-size', newFontSize + 'px', 'important');
                        this.currentFontSize = newFontSize;
                    } else if (direction === 'n') {
                        newFontSize = Math.max(12, Math.min(140, Math.round(startFontSize - (dy * 0.5))));
                        textEl.style.setProperty('font-size', newFontSize + 'px', 'important');
                        this.currentFontSize = newFontSize;
                    }
                }

                dragItem.style.setProperty('width', newWidth + 'px', 'important');
                dragItem.style.setProperty('max-width', 'none', 'important');

                this.measureInfo = { show: true, width: Math.round(newWidth), fontSize: newFontSize };
            };

            const onMouseUp = async () => {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
                this.measureInfo.show = false;

                const finalWidth = dragItem.style.width;
                const isComponent = elementKey.includes('comp_') || dragItem.querySelector('.canva-component-root');
                const isPhoto = elementKey.includes('photo') || elementKey.includes('image') || dragItem.querySelector('.canva-photo-box');
                const stylePayload = {
                    width: finalWidth
                };
                if (!isComponent && !isPhoto) {
                    stylePayload.fontSize = this.currentFontSize + 'px';
                }

                await this.persistElements(sectionId, {}, {
                    settings: {
                        styles: {
                            [elementKey]: stylePayload
                        }
                    }
                });
            };

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        },

        async setCustomElementPosition(top, left) {
            if (!this.activeSectionId || !this.activeTextKey) return;
            const item = document.querySelector(`.canva-draggable-item[data-key="${this.activeTextKey}"]`);
            if (item) {
                item.style.position = 'absolute';
                item.style.top = top;
                item.style.left = left;
                item.style.transform = 'translate(-50%, -50%)';
            }
            await this.persistElements(this.activeSectionId, {}, {
                settings: {
                    styles: {
                        [this.activeTextKey]: {
                            position: 'absolute',
                            top: top,
                            left: left
                        }
                    }
                }
            });
        },

        // MEDIA & IMAGES
        quickApplyImage(url, name) {
            if (!this.activeSectionId) {
                alert('Please select a block on the canvas first.');
                return;
            }
            const sec = this.sectionsData[this.activeSectionId];
            const imgEl = sec?.elements?.find(e => e.type === 'image');
            if (imgEl) {
                this.openImageModal(this.activeSectionId, imgEl.id);
                this.activeImageElement.url = url;
                this.activeImageElement.alt = name;
            } else {
                alert('The currently selected block does not have an image element. Click on a Hero, Editorial, or Gallery block to change its image.');
            }
        },

        handleElementChipClick(sectionId, element) {
            if (element.type === 'image') {
                this.openImageModal(sectionId, element.id);
            } else if (element.type === 'link') {
                this.openLinkModal(sectionId, element);
            } else {
                this.activeSectionId = sectionId;
                this.activeTextKey = element.key;
                const secContainer = document.getElementById('section-view-' + sectionId);
                if (secContainer) {
                    const el = secContainer.querySelector(`[data-element-id="${element.id}"], [data-key="${element.key}"], h1, h2, h3, p, span`);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
                        el.click();
                    }
                }
            }
        },

        openImageModal(sectionId, elementOrId) {
            this.activeImageSectionId = sectionId;
            const sec = this.sectionsData[sectionId];
            if (!sec) return;

            let el = null;
            if (typeof elementOrId === 'object' && elementOrId !== null) {
                el = elementOrId;
            } else if (elementOrId) {
                el = sec.elements.find(e => e.id == elementOrId);
            }
            if (!el) {
                el = sec.elements.find(e => e.type === 'image');
            }
            if (el) {
                this.activeImageElement = JSON.parse(JSON.stringify(el));
            } else {
                alert('This block does not contain an image element.');
            }
        },

        async uploadImage(event) {
            const file = event.target.files?.[0];
            if (!file) return;

            this.isUploading = true;
            const formData = new FormData();
            formData.append('image', file);
            const token = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const res = await fetch('/admin/media/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success && data.url) {
                    if (this.activeImageElement) {
                        this.activeImageElement.url = data.url;
                        if (!this.activeImageElement.alt) {
                            this.activeImageElement.alt = file.name.replace(/\.[^/.]+$/, "");
                        }
                    } else if (this.activeSectionId) {
                        this.quickApplyImage(data.url, file.name);
                    }
                } else {
                    alert(data.message || 'Image upload failed. Please verify format (JPG, PNG, WebP) and size (<12MB).');
                }
            } catch (e) {
                alert('Upload failed due to network error.');
            } finally {
                this.isUploading = false;
                event.target.value = '';
            }
        },

        async saveImageReplacer() {
            if (!this.activeImageElement || !this.activeImageSectionId) return;
            this.isSaving = true;

            const secId = this.activeImageSectionId;
            const elId = this.activeImageElement.id;
            const newUrl = this.activeImageElement.url;
            const newAlt = this.activeImageElement.alt || '';

            // Update on canvas immediately
            const secContainer = document.getElementById('section-view-' + secId);
            if (secContainer) {
                const img = secContainer.querySelector(`img[data-element-id="${elId}"]`) || secContainer.querySelector('img');
                if (img) {
                    img.src = newUrl;
                    img.alt = newAlt;
                }
                const bgEls = secContainer.querySelectorAll('[style*="background-image"], .bg-cover, section');
                bgEls.forEach(bgEl => {
                    if (bgEl.style.backgroundImage) {
                        bgEl.style.backgroundImage = `url('${newUrl}')`;
                    }
                });
            }

            // Sync with local sectionsData
            const secData = this.sectionsData[secId];
            if (secData) {
                const el = secData.elements.find(e => e.id === elId);
                if (el) {
                    el.url = newUrl;
                    el.alt = newAlt;
                }
            }

            // Persist to database
            const ok = await this.persistElements(secId, {
                [elId]: { url: newUrl, alt: newAlt }
            });

            this.activeImageElement = null;
            this.isSaving = false;

            if (ok) {
                window.location.reload();
            }
        },

        openLinkModal(sectionId, element) {
            this.activeLinkSectionId = sectionId;
            this.activeLinkElement = JSON.parse(JSON.stringify(element));
        },

        async saveLinkEditor() {
            if (!this.activeLinkElement || !this.activeLinkSectionId) return;
            this.isSaving = true;

            const secId = this.activeLinkSectionId;
            const elId = this.activeLinkElement.id;
            const newText = this.activeLinkElement.text;
            const newUrl = this.activeLinkElement.url;

            // Live update on canvas
            const secContainer = document.getElementById('section-view-' + secId);
            if (secContainer) {
                const link = secContainer.querySelector(`[data-element-id="${elId}"]`) || secContainer.querySelector('a, button');
                if (link) {
                    const svg = link.querySelector('svg');
                    link.textContent = newText;
                    if (svg) link.appendChild(svg);
                    link.setAttribute('href', newUrl);
                }
            }

            // Sync with local sectionsData
            const secData = this.sectionsData[secId];
            if (secData) {
                const el = secData.elements.find(e => e.id === elId);
                if (el) {
                    el.text = newText;
                    el.url = newUrl;
                }
            }

            // Send save request
            await this.persistElements(secId, {
                [elId]: { text: newText, url: newUrl, style: 'primary' }
            });

            this.activeLinkElement = null;
            this.isSaving = false;
        },

        handleContentClick(event, sectionId) {
            this.activeSectionId = sectionId;
            const target = event.target;

            // 0. If clicked inside quick-add menu or its dropdowns: do not interfere
            if (target.closest('.fixed.z-\\[100\\]')) {
                return;
            }
            this.quickAddMenu.show = false;

            // 1. If clicked a Resize Handle or Drag Handle Bar: do nothing (already handled by mousedown)
            if (target.closest('.canva-handle') || target.closest('.canva-drag-handle-bar')) {
                return;
            }

            // 1b. If clicked inside a placed custom draggable element:
            const placedDraggable = target.closest('.canva-draggable-item');
            if (placedDraggable) {
                event.stopPropagation();
                const dKey = placedDraggable.dataset?.key;
                const dId = placedDraggable.dataset?.elementId;
                this.activeSectionId = sectionId;
                this.activeTextKey = dKey;
                this.activeCustomElementId = dId;
                this.attachSelectionBox(placedDraggable, 'Placed Layer');
                
                const isPhoto = placedDraggable.querySelector('.canva-photo-box') || dKey?.includes('photo') || dKey?.includes('image');
                if (isPhoto) {
                    this.isPhotoSelected = true;
                    this.isButtonSelected = false;
                    this.activeButtonElement = null;
                    this.activeTextElement = placedDraggable;
                    return;
                }
                this.isPhotoSelected = false;

                const btn = placedDraggable.querySelector('a, button');
                if (btn) {
                    this.isButtonSelected = true;
                    this.activeButtonElement = btn;
                    this.activeTextElement = btn;
                    this.activeButtonText = btn.textContent.trim();
                    this.activeButtonUrl = btn.getAttribute('href') || '#';
                } else {
                    this.isButtonSelected = false;
                    this.activeButtonElement = null;
                    this.activeTextElement = placedDraggable.querySelector('.canva-custom-text') || placedDraggable;
                }
                return;
            }

            // 2. If clicked an Image or image area:
            const img = target.closest('img');
            if (img && !img.closest('.canva-draggable-item')) {
                event.preventDefault();
                event.stopPropagation();
                this.attachSelectionBox(img, 'Image');
                const elId = img.dataset?.elementId;
                this.openImageModal(sectionId, elId || null);
                return;
            }

            // 3. If clicked ANY Link / Button (Standard CTA or Placed Custom Button):
            const link = target.closest('a, button, .btn-primary, .btn-secondary');
            if (link && !link.closest('[x-show]') && !link.closest('.group\\/drag') && !link.closest('.canva-drag-handle-bar') && !link.closest('.canva-handle')) {
                event.preventDefault();
                event.stopPropagation();

                this.attachSelectionBox(link, 'Button / CTA');
                this.activeButtonElement = link;
                this.isButtonSelected = true;
                this.activeTextElement = link;

                // Find element key
                let bKey = link.dataset?.key;
                if (!bKey) {
                    const parentDraggable = link.closest('.canva-draggable-item');
                    if (parentDraggable) bKey = parentDraggable.dataset?.key;
                }
                if (!bKey) {
                    const elId = link.dataset?.elementId;
                    const sec = this.sectionsData[sectionId];
                    const linkEl = elId ? sec?.elements?.find(e => e.id == elId) : sec?.elements?.find(e => e.type === 'link');
                    if (linkEl) bKey = linkEl.key;
                }
                if (!bKey) {
                    bKey = link.classList.contains('btn-primary') ? 'primary_cta' : 'secondary_cta';
                }
                this.activeTextKey = bKey;

                // Populate button fields into toolbar
                this.activeButtonText = link.textContent.trim();
                this.activeButtonUrl = link.getAttribute('href') || '#';

                // Detect styles and populate toolbar
                const computed = window.getComputedStyle(link);
                this.currentFontSize = parseInt(computed.fontSize) || 16;
                this.currentStyles.fontFamily = this.normalizeFontFamily(computed.fontFamily);
                this.currentStyles.color = this.rgbToHex(computed.color);
                this.currentStyles.backgroundColor = this.rgbToHex(computed.backgroundColor);
                this.currentStyles.borderRadius = computed.borderRadius || '8px';
                this.currentStyles.fontWeight = (computed.fontWeight === 'bold' || parseInt(computed.fontWeight) >= 600) ? 'bold' : 'normal';

                const padTop = parseInt(computed.paddingTop) || 10;
                const padLeft = parseInt(computed.paddingLeft) || 20;
                this.currentPaddingV = padTop;
                this.currentPaddingH = padLeft;
                if (padTop <= 5) this.activeButtonSize = 'xs';
                else if (padTop <= 8) this.activeButtonSize = 'sm';
                else if (padTop <= 12) this.activeButtonSize = 'md';
                else if (padTop <= 16) this.activeButtonSize = 'lg';
                else this.activeButtonSize = 'xl';

                return;
            }

            // User clicked away from button or photo: reset selection
            this.isButtonSelected = false;
            this.isPhotoSelected = false;
            this.activeButtonElement = null;

            // 4. If clicked any Text element (h1-h6, p, span, li, blockquote, .canva-custom-text, or element with data-key/data-element-id):
            const textElem = target.closest('h1, h2, h3, h4, h5, h6, p, span, li, blockquote, .canva-custom-text, [data-key], [data-element-id]');
            if (textElem && !textElem.closest('a, button')) {
                const headingAncestor = target.closest('h1, h2, h3, h4, h5, h6');
                const actualText = headingAncestor || textElem;

                // If inside a draggable custom item, also set activeTextKey to that item's key
                const draggableParent = actualText.closest('.canva-draggable-item');
                if (draggableParent) {
                    this.activeTextKey = draggableParent.dataset?.key || actualText.dataset?.key;
                } else {
                    this.activeTextKey = actualText.dataset?.key || (actualText.tagName.toLowerCase().startsWith('h') ? 'headline' : (actualText.tagName.toLowerCase() === 'p' ? 'body' : 'subheadline'));
                }

                const tagName = actualText.tagName.toLowerCase();
                const tagLabel = tagName.startsWith('h') ? 'Headline (' + tagName.toUpperCase() + ')' : (tagName === 'p' ? 'Body Text' : (tagName === 'span' ? 'Subtitle' : 'Text'));
                this.attachSelectionBox(draggableParent || actualText, tagLabel);

                this.activeTextElement = actualText;

                // Detect current computed styles accurately
                const computed = window.getComputedStyle(actualText);
                this.currentFontSize = parseInt(computed.fontSize) || 24;
                this.currentStyles.fontFamily = this.normalizeFontFamily(computed.fontFamily);
                const fw = computed.fontWeight;
                this.currentStyles.fontWeight = (fw === 'bold' || parseInt(fw) >= 600) ? 'bold' : 'normal';
                this.currentStyles.fontStyle = computed.fontStyle === 'italic' ? 'italic' : 'normal';
                this.currentStyles.textDecoration = (computed.textDecorationLine?.includes('underline') || computed.textDecoration?.includes('underline')) ? 'underline' : 'none';
                this.currentStyles.textTransform = computed.textTransform || 'none';
                this.currentStyles.color = this.rgbToHex(computed.color);
                this.currentStyles.backgroundColor = this.rgbToHex(computed.backgroundColor);
                this.currentStyles.textAlign = computed.textAlign || 'left';

                const isAlreadySelected = (this.activeTextElement === actualText);
                this.activeTextElement = actualText;

                // Function to enable inline text typing
                const enableEditing = () => {
                    if (actualText.isContentEditable) return;
                    actualText.contentEditable = "true";
                    actualText.focus({ preventScroll: true });

                    const originalText = actualText.innerText.trim();
                    const onBlur = async () => {
                        actualText.contentEditable = "false";
                        actualText.removeEventListener('blur', onBlur);
                        actualText.removeEventListener('keydown', onKey);

                        const updatedText = actualText.innerText.trim();
                        if (updatedText !== originalText && updatedText.length > 0) {
                            await this.handleTextUpdated(sectionId, actualText, updatedText);
                        }
                    };

                    const onKey = (e) => {
                        if (e.key === 'Escape') {
                            actualText.innerText = originalText;
                            actualText.blur();
                        } else if (e.key === 'Enter' && !e.shiftKey && actualText.tagName !== 'P') {
                            e.preventDefault();
                            actualText.blur();
                        }
                    };

                    actualText.addEventListener('blur', onBlur);
                    actualText.addEventListener('keydown', onKey);
                };

                // Double click enables editing immediately
                actualText.ondblclick = (e) => {
                    e.stopPropagation();
                    enableEditing();
                };

                // Second click on already active element enables editing
                if (isAlreadySelected) {
                    enableEditing();
                }

                return;
            }

            // 5. Fallback: activate section and capture click coordinates for Canva placement
            this.setActiveSection(sectionId);
            const contentArea = event.currentTarget;
            if (contentArea) {
                const rect = contentArea.getBoundingClientRect();
                const clickX = Math.round(((event.clientX - rect.left) / rect.width) * 100);
                const clickY = Math.round(((event.clientY - rect.top) / rect.height) * 100);
                const leftPct = Math.max(5, Math.min(95, clickX)) + '%';
                const topPct = Math.max(5, Math.min(95, clickY)) + '%';
                this.lastClickPos = {
                    sectionId: sectionId,
                    left: leftPct,
                    top: topPct
                };
                this.openQuickAddMenu(event, sectionId, leftPct, topPct);
            }
        },

        async handleTextUpdated(sectionId, domElem, newText) {
            const sec = this.sectionsData[sectionId];
            if (!sec || !sec.elements) return;

            const tagName = domElem.tagName.toLowerCase();
            let matchedEl = null;

            if (domElem.dataset?.elementId) {
                matchedEl = sec.elements.find(e => e.id == domElem.dataset.elementId);
            }

            if (!matchedEl && domElem.dataset?.key) {
                matchedEl = sec.elements.find(e => e.key === domElem.dataset.key);
            }

            if (!matchedEl) {
                if (tagName.startsWith('h')) {
                    matchedEl = sec.elements.find(e => ['headline', 'heading', 'title'].includes(e.key) || e.type === 'text');
                } else if (tagName === 'span') {
                    matchedEl = sec.elements.find(e => ['subheadline', 'subheading', 'subtitle'].includes(e.key) || e.type === 'text');
                } else {
                    matchedEl = sec.elements.find(e => ['body', 'subheadline', 'subheading', 'content', 'description'].includes(e.key) || e.type === 'richtext');
                }
            }

            if (!matchedEl) {
                matchedEl = sec.elements.find(e => e.type === 'text' || e.type === 'richtext');
            }

            if (matchedEl) {
                matchedEl.value = newText;
                await this.persistElements(sectionId, {
                    [matchedEl.id]: newText
                });
            }
        },

        async persistElements(sectionId, elementsPayload, extraData = {}) {
            this.isSaving = true;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;

            const bodyPayload = {
                elements: elementsPayload,
                ...extraData
            };

            try {
                const res = await fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/content`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(bodyPayload)
                });
                const data = await res.json();
                if (data && data.success) {
                    this.savedNotice = true;
                    setTimeout(() => this.savedNotice = false, 2500);
                    return true;
                } else {
                    console.error('Save error from server:', data);
                    return false;
                }
            } catch (e) {
                console.error('Save network error:', e);
                return false;
            } finally {
                this.isSaving = false;
            }
        },

        async saveSectionDrawer() {
            if (!this.editingSection) return;
            this.isSaving = true;

            const payload = {};
            this.editingSection.elements.forEach(el => {
                if (el.type === 'image') {
                    payload[el.id] = { url: el.url, alt: el.alt };
                } else if (el.type === 'link') {
                    payload[el.id] = { text: el.text, url: el.url, style: 'primary' };
                } else if (el.type === 'text' || el.type === 'richtext') {
                    payload[el.id] = el.value;
                }
            });

            await this.persistElements(this.editingSection.id, payload);
            this.editingSection = null;
            window.location.reload();
        },

        async saveAllChanges() {
            this.isSaving = true;
            if (this._flushStylePending) {
                await this._flushStylePending();
            }
            if (this._pendingStyleBatch) {
                for (const secId in this._pendingStyleBatch) {
                    const batch = this._pendingStyleBatch[secId];
                    if (batch && Object.keys(batch).length > 0) {
                        await this.persistElements(secId, {}, {
                            settings: { styles: batch }
                        });
                    }
                }
                this._pendingStyleBatch = {};
            }
            // Blur any active contenteditable element to trigger onBlur save
            if (this.activeTextElement && this.activeTextElement.isContentEditable) {
                this.activeTextElement.blur();
                await new Promise(r => setTimeout(r, 400));
            }
            this.savedNotice = true;
            this.isSaving = false;
            setTimeout(() => this.savedNotice = false, 3000);
        },

        async moveSection(sectionId, direction) {
            const list = [...this.sectionsOrder];
            const idx = list.indexOf(sectionId);
            if (idx === -1) return;
            const target = direction === 'up' ? idx - 1 : idx + 1;
            if (target < 0 || target >= list.length) return;

            const temp = list[idx];
            list[idx] = list[target];
            list[target] = temp;
            this.sectionsOrder = list;

            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/admin/pages/{{ $page->id }}/sections/reorder', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order: list })
            });
            window.location.reload();
        },

        async deleteSection(sectionId, name) {
            if (!confirm(`Delete "${name}" block?`)) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;

            await fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            window.location.reload();
        },

        async insertSection(type) {
            this.showAddModal = false;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;

            const res = await fetch('/admin/pages/{{ $page->id }}/sections', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ section_type: type })
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            }
        }
    }
}
</script>
@endsection
