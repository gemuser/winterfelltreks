@extends('layouts.app')

@push('styles')
<style id="page-dynamic-styles">
@if(isset($page) && isset($page->visibleSections))
    @foreach($page->visibleSections as $sec)
        @php
            $anchor = $sec->custom_anchor_id ?: ('section-' . $sec->id);
            $secId = $sec->id;
        @endphp
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
                        [data-section-id="{{ $secId }}"] [data-key="{{ $targetKey }}"],
                        #section-{{ $secId }}-wrapper [data-key="{{ $targetKey }}"],
                        #section-{{ $secId }} [data-key="{{ $targetKey }}"],
                        #{{ $anchor }} [data-key="{{ $targetKey }}"]
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
                        #section-{{ $secId }}-wrapper [data-key="{{ $targetKey }}"],
                        #section-{{ $secId }} [data-key="{{ $targetKey }}"],
                        #{{ $anchor }} [data-key="{{ $targetKey }}"]
                        @if(in_array($targetKey, ['primary_cta', 'secondary_cta', 'view_all', 'button', 'cta']))
                            , #{{ $anchor }} a[data-key="{{ $targetKey }}"], #section-{{ $secId }} a[data-key="{{ $targetKey }}"], [data-section-id="{{ $secId }}"] a.btn-primary
                        @endif
                        {
                            @foreach($boxRules as $p => $v)
                                {!! $p !!}: {!! $v !!} !important;
                            @endforeach
                        }
                        @endif

                        @if(!empty($textRules))
                        #section-{{ $secId }}-wrapper [data-key="{{ $targetKey }}"],
                        #section-{{ $secId }}-wrapper [data-key="{{ $targetKey }}"] *,
                        #section-{{ $secId }} [data-key="{{ $targetKey }}"],
                        #section-{{ $secId }} [data-key="{{ $targetKey }}"] *,
                        #{{ $anchor }} [data-key="{{ $targetKey }}"],
                        #{{ $anchor }} [data-key="{{ $targetKey }}"] *
                        @if(in_array($targetKey, ['headline', 'heading', 'title']))
                            , #{{ $anchor }} h1, #{{ $anchor }} h2, #{{ $anchor }} h1 *, #{{ $anchor }} h2 *, #section-{{ $secId }} h1, #section-{{ $secId }} h2, #section-{{ $secId }} h1 *, #section-{{ $secId }} h2 *
                        @elseif(in_array($targetKey, ['subheadline', 'subheading', 'subtitle']))
                            , #{{ $anchor }} h3, #{{ $anchor }} h4, #{{ $anchor }} [data-key="subheadline"] *, #section-{{ $secId }} h3, #section-{{ $secId }} h4, #section-{{ $secId }} [data-key="subheadline"] *
                        @elseif(in_array($targetKey, ['body', 'content', 'description']))
                            , #{{ $anchor }} .prose, #{{ $anchor }} p, #{{ $anchor }} .prose *, #{{ $anchor }} p *, #section-{{ $secId }} .prose, #section-{{ $secId }} p, #section-{{ $secId }} .prose *, #section-{{ $secId }} p *
                        @elseif($targetKey === 'primary_cta')
                            , #{{ $anchor }} a[data-key="primary_cta"], #section-{{ $secId }} a[data-key="primary_cta"], [data-section-id="{{ $secId }}"] a.btn-primary
                        @elseif($targetKey === 'secondary_cta')
                            , #{{ $anchor }} a[data-key="secondary_cta"], #section-{{ $secId }} a[data-key="secondary_cta"], [data-section-id="{{ $secId }}"] a.btn-secondary
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
            #{{ $anchor }} > div, #section-{{ $secId }} > div, [data-section-id="{{ $secId }}"] > div {
                text-align: {{ $sec->settings['content_alignment'] }} !important;
            }
        @endif
        @if(!empty($sec->settings['padding']))
            #{{ $anchor }}, #section-{{ $secId }}, [data-section-id="{{ $secId }}"] {
                padding-top: {{ $sec->settings['padding'] === 'compact' ? '2.5rem' : ($sec->settings['padding'] === 'spacious' ? '7rem' : '4.5rem') }} !important;
                padding-bottom: {{ $sec->settings['padding'] === 'compact' ? '2.5rem' : ($sec->settings['padding'] === 'spacious' ? '7rem' : '4.5rem') }} !important;
            }
        @endif
        @if(!empty($sec->settings['overlay_opacity']))
            #{{ $anchor }} [class*="inset-0"][class*="bg-"], #section-{{ $secId }} [class*="inset-0"][class*="bg-"], [data-section-id="{{ $secId }}"] [class*="inset-0"][class*="bg-"] {
                opacity: {{ $sec->settings['overlay_opacity'] }} !important;
            }
        @endif
    @endforeach
@endif
</style>
@endpush

@section('content')
    @if($page->layout_type === 'home')
        {{-- Homepage renders all sections directly --}}
        @foreach($page->visibleSections as $section)
            @php
                $secAnchor = $section->custom_anchor_id ?: ('section-' . $section->id);
                $customElements = $section->elements->filter(fn($e) => str_starts_with($e->element_key, 'custom_'));
            @endphp
            <div id="{{ $secAnchor }}-wrapper" data-section-id="{{ $section->id }}" class="relative canva-section-container overflow-hidden">
                @include('sections.' . $section->section_type, [
                    'section' => $section,
                    'elements' => $section->elements->keyBy('element_key'),
                    'settings' => $section->settings ?? [],
                    'page' => $page
                ])

                @if($customElements->isNotEmpty())
                    @foreach($customElements as $cEl)
                        @php
                            $elStyles = $section->settings['styles'][$cEl->element_key] ?? [];
                            $top = $elStyles['top'] ?? '50%';
                            $left = $elStyles['left'] ?? '50%';
                            $pos = $elStyles['position'] ?? 'absolute';
                            $transform = ($pos === 'absolute') ? 'translate(-50%, -50%)' : 'none';
                            $width = $elStyles['width'] ?? null;
                        @endphp
                        <div class="canva-placed-element"
                             data-key="{{ $cEl->element_key }}"
                             data-element-id="{{ $cEl->id }}"
                             style="position: {{ $pos }}; top: {{ $top }}; left: {{ $left }}; transform: {{ $transform }}; @if($width) width: {{ $width }}; max-width: 95vw; @else max-width: 90%; @endif z-index: 25;">
                            @php
                                $cContent = $cEl->getContent();
                                $cText = is_array($cContent) ? ($cContent['text'] ?? $cContent['title'] ?? reset($cContent)) : $cContent;
                                $cLinkUrl = $cEl->getLinkUrl() ?: (is_array($cContent) ? ($cContent['url'] ?? '#') : '#');
                                $cLinkText = $cEl->getLinkText() ?: (is_array($cContent) ? ($cContent['text'] ?? 'Explore More') : (is_string($cContent) ? $cContent : 'Explore More'));
                            @endphp
                            @if($cEl->element_type === 'component' || str_contains($cEl->element_key, 'comp_') || str_contains($cEl->element_key, 'search') || str_contains($cEl->element_key, 'badge') || str_contains($cEl->element_key, 'card'))
                                @include('components.canva_component', ['cEl' => $cEl, 'isEditor' => false])
                            @elseif($cEl->element_type === 'image' || str_contains($cEl->element_key, 'photo') || str_contains($cEl->element_key, 'image'))
                                @include('components.canva_photo_box', ['cEl' => $cEl, 'isEditor' => false, 'elStyles' => $elStyles])
                            @elseif($cEl->element_type === 'link')
                                <a href="{{ $cLinkUrl }}" data-key="{{ $cEl->element_key }}" class="canva-custom-button btn-primary py-2.5 px-6 font-semibold inline-block shadow-md">
                                    {{ $cLinkText }}
                                </a>
                            @elseif(str_contains($cEl->element_key, 'headline'))
                                <h3 data-key="{{ $cEl->element_key }}" class="canva-custom-text font-display font-bold text-3xl drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#111827' }};">
                                    {{ $cText }}
                                </h3>
                            @elseif(str_contains($cEl->element_key, 'subheading'))
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-xl font-medium drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#374151' }};">
                                    {{ $cText }}
                                </div>
                            @elseif(str_contains($cEl->element_key, 'body'))
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text prose max-w-xl drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#4b5563' }};">
                                    {!! $cText !!}
                                </div>
                            @else
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-base drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#111827' }};">
                                    {{ $cText }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    @elseif($page->layout_type === 'legal')
        {{-- Legal pages (privacy, terms) --}}
        <div class="min-h-screen">
            @foreach($page->visibleSections as $section)
                @php
                    $secAnchor = $section->custom_anchor_id ?: ('section-' . $section->id);
                    $customElements = $section->elements->filter(fn($e) => str_starts_with($e->element_key, 'custom_'));
                @endphp
                <div id="{{ $secAnchor }}-wrapper" data-section-id="{{ $section->id }}" class="relative canva-section-container overflow-hidden">
                    @include('sections.' . $section->section_type, [
                        'section' => $section,
                        'elements' => $section->elements->keyBy('element_key'),
                        'settings' => $section->settings ?? [],
                        'page' => $page
                    ])

                    @if($customElements->isNotEmpty())
                        @foreach($customElements as $cEl)
                            @php
                                $elStyles = $section->settings['styles'][$cEl->element_key] ?? [];
                                $top = $elStyles['top'] ?? '50%';
                                $left = $elStyles['left'] ?? '50%';
                                $pos = $elStyles['position'] ?? 'absolute';
                                $transform = ($pos === 'absolute') ? 'translate(-50%, -50%)' : 'none';
                                $width = $elStyles['width'] ?? null;
                            @endphp
                        <div class="canva-placed-element"
                             data-key="{{ $cEl->element_key }}"
                             data-element-id="{{ $cEl->id }}"
                             style="position: {{ $pos }}; top: {{ $top }}; left: {{ $left }}; transform: {{ $transform }}; @if($width) width: {{ $width }}; max-width: 95vw; @else max-width: 90%; @endif z-index: 25;">
                            @php
                                $cContent = $cEl->getContent();
                                $cText = is_array($cContent) ? ($cContent['text'] ?? $cContent['title'] ?? reset($cContent)) : $cContent;
                                $cLinkUrl = $cEl->getLinkUrl() ?: (is_array($cContent) ? ($cContent['url'] ?? '#') : '#');
                                $cLinkText = $cEl->getLinkText() ?: (is_array($cContent) ? ($cContent['text'] ?? 'Explore More') : (is_string($cContent) ? $cContent : 'Explore More'));
                            @endphp
                            @if($cEl->element_type === 'component' || str_contains($cEl->element_key, 'comp_') || str_contains($cEl->element_key, 'search') || str_contains($cEl->element_key, 'badge') || str_contains($cEl->element_key, 'card'))
                                @include('components.canva_component', ['cEl' => $cEl, 'isEditor' => false])
                            @elseif($cEl->element_type === 'image' || str_contains($cEl->element_key, 'photo') || str_contains($cEl->element_key, 'image'))
                                @include('components.canva_photo_box', ['cEl' => $cEl, 'isEditor' => false, 'elStyles' => $elStyles])
                            @elseif($cEl->element_type === 'link')
                                <a href="{{ $cLinkUrl }}" data-key="{{ $cEl->element_key }}" class="canva-custom-button btn-primary py-2.5 px-6 font-semibold inline-block shadow-md">
                                    {{ $cLinkText }}
                                </a>
                            @elseif(str_contains($cEl->element_key, 'headline'))
                                <h3 data-key="{{ $cEl->element_key }}" class="canva-custom-text font-display font-bold text-3xl drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#111827' }};">
                                    {{ $cText }}
                                </h3>
                            @elseif(str_contains($cEl->element_key, 'subheading'))
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-xl font-medium drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#374151' }};">
                                    {{ $cText }}
                                </div>
                            @elseif(str_contains($cEl->element_key, 'body'))
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text prose max-w-xl drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#4b5563' }};">
                                    {!! $cText !!}
                                </div>
                            @else
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-base drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#111827' }};">
                                    {{ $cText }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    @else
        {{-- Standard pages with dynamic data --}}
        <div class="min-h-screen">
            @foreach($page->visibleSections as $section)
                @php
                    $secAnchor = $section->custom_anchor_id ?: ('section-' . $section->id);
                    $customElements = $section->elements->filter(fn($e) => str_starts_with($e->element_key, 'custom_'));
                @endphp
                <div id="{{ $secAnchor }}-wrapper" data-section-id="{{ $section->id }}" class="relative canva-section-container overflow-hidden">
                    @include('sections.' . $section->section_type, [
                        'section' => $section,
                        'elements' => $section->elements->keyBy('element_key'),
                        'settings' => $section->settings ?? [],
                        'page' => $page,
                        'pageData' => $data ?? []
                    ])

                    @if($customElements->isNotEmpty())
                        @foreach($customElements as $cEl)
                            @php
                                $elStyles = $section->settings['styles'][$cEl->element_key] ?? [];
                                $top = $elStyles['top'] ?? '50%';
                                $left = $elStyles['left'] ?? '50%';
                                $pos = $elStyles['position'] ?? 'absolute';
                                $transform = ($pos === 'absolute') ? 'translate(-50%, -50%)' : 'none';
                                $width = $elStyles['width'] ?? null;
                            @endphp
                        <div class="canva-placed-element"
                             data-key="{{ $cEl->element_key }}"
                             data-element-id="{{ $cEl->id }}"
                             style="position: {{ $pos }}; top: {{ $top }}; left: {{ $left }}; transform: {{ $transform }}; @if($width) width: {{ $width }}; max-width: 95vw; @else max-width: 90%; @endif z-index: 25;">
                            @php
                                $cContent = $cEl->getContent();
                                $cText = is_array($cContent) ? ($cContent['text'] ?? $cContent['title'] ?? reset($cContent)) : $cContent;
                                $cLinkUrl = $cEl->getLinkUrl() ?: (is_array($cContent) ? ($cContent['url'] ?? '#') : '#');
                                $cLinkText = $cEl->getLinkText() ?: (is_array($cContent) ? ($cContent['text'] ?? 'Explore More') : (is_string($cContent) ? $cContent : 'Explore More'));
                            @endphp
                            @if($cEl->element_type === 'component' || str_contains($cEl->element_key, 'comp_') || str_contains($cEl->element_key, 'search') || str_contains($cEl->element_key, 'badge') || str_contains($cEl->element_key, 'card'))
                                @include('components.canva_component', ['cEl' => $cEl, 'isEditor' => false])
                            @elseif($cEl->element_type === 'image' || str_contains($cEl->element_key, 'photo') || str_contains($cEl->element_key, 'image'))
                                @include('components.canva_photo_box', ['cEl' => $cEl, 'isEditor' => false, 'elStyles' => $elStyles])
                            @elseif($cEl->element_type === 'link')
                                <a href="{{ $cLinkUrl }}" data-key="{{ $cEl->element_key }}" class="canva-custom-button btn-primary py-2.5 px-6 font-semibold inline-block shadow-md">
                                    {{ $cLinkText }}
                                </a>
                            @elseif(str_contains($cEl->element_key, 'headline'))
                                <h3 data-key="{{ $cEl->element_key }}" class="canva-custom-text font-display font-bold text-3xl drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#111827' }};">
                                    {{ $cText }}
                                </h3>
                            @elseif(str_contains($cEl->element_key, 'subheading'))
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-xl font-medium drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#374151' }};">
                                    {{ $cText }}
                                </div>
                            @elseif(str_contains($cEl->element_key, 'body'))
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text prose max-w-xl drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#4b5563' }};">
                                    {!! $cText !!}
                                </div>
                            @else
                                <div data-key="{{ $cEl->element_key }}" class="canva-custom-text font-body text-base drop-shadow-sm" style="color: {{ $elStyles['color'] ?? '#111827' }};">
                                    {{ $cText }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize silky smooth GSAP reveal animations
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                
                if (!prefersReducedMotion) {
                    gsap.utils.toArray('[data-gsap]').forEach(el => {
                        const animation = el.dataset.gsap || 'fade-up';
                        // Cap delay so elements don't lag behind fast scrolling
                        const rawDelay = parseFloat(el.dataset.gsapDelay) || 0;
                        const delay = Math.min(rawDelay, 0.2);
                        
                        gsap.from(el, {
                            scrollTrigger: {
                                trigger: el,
                                start: 'top 92%',
                                once: true,
                                fastScrollEnd: true
                            },
                            ...getAnimationProps(animation),
                            delay: delay,
                            ease: 'power2.out',
                            clearProps: 'transform,opacity'
                        });
                    });
                    
                    // Refresh trigger positions once images/fonts are fully loaded
                    window.addEventListener('load', function() {
                        ScrollTrigger.refresh();
                    }, { once: true });
                }
            }
            
            // Lightbox for galleries
            initLightbox();
        });
        
        function getAnimationProps(type) {
            switch(type) {
                case 'fade-up': return { y: 24, opacity: 0, duration: 0.5 };
                case 'fade-down': return { y: -24, opacity: 0, duration: 0.5 };
                case 'fade-left': return { x: 24, opacity: 0, duration: 0.5 };
                case 'fade-right': return { x: -24, opacity: 0, duration: 0.5 };
                case 'scale-up': return { scale: 0.96, opacity: 0, duration: 0.45 };
                default: return { y: 24, opacity: 0, duration: 0.5 };
            }
        }
        
        function initLightbox() {
            document.querySelectorAll('[data-lightbox]').forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    const src = this.dataset.lightbox;
                    const alt = this.dataset.lightboxAlt || '';
                    openLightbox(src, alt);
                });
            });
        }
        
        function openLightbox(src, alt) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4';
            overlay.innerHTML = `
                <button class="absolute top-4 right-4 text-white/70 hover:text-white text-3xl" aria-label="Close lightbox">&times;</button>
                <button class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-4xl" aria-label="Previous">&lsaquo;</button>
                <img src="${src}" alt="${alt}" class="max-h-[85vh] max-w-[90vw] object-contain">
                <button class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-4xl" aria-label="Next">&rsaquo;</button>
            `;
            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';
            
            overlay.querySelector('button').addEventListener('click', () => {
                document.body.removeChild(overlay);
                document.body.style.overflow = '';
            });
            
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    document.body.removeChild(overlay);
                    document.body.style.overflow = '';
                }
            });
            
            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    if (document.body.contains(overlay)) {
                        document.body.removeChild(overlay);
                        document.body.style.overflow = '';
                    }
                    document.removeEventListener('keydown', escHandler);
                }
            });
        }
    </script>
@endpush