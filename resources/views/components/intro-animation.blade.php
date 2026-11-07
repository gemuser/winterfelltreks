@php
    // Read the SVG path data directly from public/Logo.svg
    $logoFile = null;
    if (file_exists(public_path('Logo.svg'))) {
        $logoFile = public_path('Logo.svg');
    } elseif (file_exists(public_path('logo.svg'))) {
        $logoFile = public_path('logo.svg');
    }

    $mountainPath = '';
    if ($logoFile) {
        $rawSvg = file_get_contents($logoFile);
        if (preg_match('/<path\s+d="([^"]+)"/i', $rawSvg, $matches)) {
            // Extract the pure, unbroken mountain contour (Subpath 0).
            // This strips out all disconnected tracing artifacts/noise,
            // resulting in a silky, continuous, non-jittery line drawing!
            $subpaths = preg_split('/(?=[Mm])/', trim($matches[1]), -1, PREG_SPLIT_NO_EMPTY);
            $mountainPath = trim($subpaths[0]);
        }
    }
@endphp

{{-- Fullscreen Start Animation Overlay --}}
<div id="wf-intro-overlay" 
     role="dialog" 
     aria-label="Winterfell Treks & Expedition"
     aria-modal="true">

    {{-- Discreet Luxury Skip Button --}}
    <button type="button" id="wf-intro-skip-btn" class="wf-skip-btn" aria-label="Skip start animation">
        <span>Skip</span>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    {{-- Center Stage --}}
    <div class="wf-stage">
        
        {{-- Logo Box (Exact 800:498 mathematical aspect ratio matching Logo.svg & winterfell-nobg.png) --}}
        <div class="wf-logo-frame">
            
            {{-- Real full-color stained-glass artwork that gently illuminates --}}
            <img id="wf-intro-color" 
                 src="{{ asset('images/winterfell-nobg.png') }}" 
                 alt="Winterfell Treks & Expedition"
                 class="wf-logo-color">

            {{-- Pure unbroken SVG stroke contour --}}
            <div id="wf-intro-stroke-wrap" class="wf-logo-stroke">
                <svg id="wf-intro-svg" viewBox="126 274 800 498" preserveAspectRatio="none">
                    <g transform="translate(0.000000,1024.000000) scale(0.100000,-0.100000)">
                        <path id="wf-intro-path" 
                              d="{{ $mountainPath }}" 
                              fill="transparent" 
                              stroke="#ea580c" 
                              stroke-width="18" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" />
                    </g>
                </svg>
            </div>
        </div>

        {{-- Luxury Brand Typography Reveal --}}
        <div id="wf-intro-brand-title" class="wf-brand-name">
            W I N T E R F E L L
        </div>
        <div id="wf-intro-brand-sub" class="wf-brand-tagline">
            Treks & Expedition &bull; Himalayas
        </div>
    </div>
</div>

<style>
    /* Fullscreen Overlay */
    #wf-intro-overlay {
        position: fixed !important;
        inset: 0 !important;
        z-index: 999999 !important;
        background-color: #080c14 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        user-select: none !important;
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1) !important;
        will-change: opacity, transform !important;
    }

    /* Lock scrolling while start animation is active */
    html.wf-intro-active, 
    html.wf-intro-active body {
        overflow: hidden !important;
        height: 100vh !important;
    }

    /* Zero flicker on repeat visits */
    html.wf-intro-done #wf-intro-overlay {
        display: none !important;
    }

    /* Dissolve animation into site */
    #wf-intro-overlay.wf-intro-dissolve {
        opacity: 0 !important;
        transform: scale(1.025) !important;
        pointer-events: none !important;
    }

    /* Stage */
    .wf-stage {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
        max-width: 520px;
        width: 100%;
        text-align: center;
    }

    /* Logo Box */
    .wf-logo-frame {
        position: relative;
        width: 310px;
        height: 193px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @media (min-width: 640px) {
        .wf-logo-frame {
            width: 390px;
            height: 243px;
        }
    }

    /* Real full-color artwork that softly illuminates */
    .wf-logo-color {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: fill;
        opacity: 0;
        transform: scale(0.985);
        transition: opacity 0.95s cubic-bezier(0.16, 1, 0.3, 1), transform 0.95s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 1;
        pointer-events: none;
        will-change: opacity, transform;
    }
    .wf-logo-color.wf-filled {
        opacity: 1 !important;
        transform: scale(1) !important;
    }

    /* SVG Stroke Layer */
    .wf-logo-stroke {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }
    .wf-logo-stroke svg {
        width: 100%;
        height: 100%;
        overflow: visible;
    }
    .wf-logo-stroke path {
        fill: transparent !important;
        stroke: #ea580c;
        stroke-width: 18;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition: stroke 0.95s ease, opacity 0.95s ease, stroke-width 0.95s ease;
        will-change: stroke-dashoffset;
    }
    .wf-logo-stroke.wf-filled path {
        stroke: #1e293b !important;
        opacity: 0.35 !important;
        stroke-width: 12 !important;
    }

    /* Typography */
    .wf-brand-name {
        margin-top: 26px;
        font-family: 'Cinzel', serif;
        font-size: 21px;
        font-weight: 700;
        letter-spacing: 0.36em;
        text-transform: uppercase;
        color: #ffffff;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .wf-brand-name.wf-visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    .wf-brand-tagline {
        margin-top: 7px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: #ea580c;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.12s, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.12s;
        will-change: opacity, transform;
    }
    .wf-brand-tagline.wf-visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    /* Skip Button */
    .wf-skip-btn {
        position: absolute;
        top: 24px;
        right: 28px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.5);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 6px 14px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .wf-skip-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
    }
</style>

<script>
(function() {
    'use strict';

    const overlay = document.getElementById('wf-intro-overlay');
    if (!overlay) return;

    // Check if intro has already played in this session (unless forced via ?intro=1)
    const urlParams = new URLSearchParams(window.location.search);
    const forceIntro = urlParams.has('intro') || window.location.hash === '#replay-intro';
    const hasSeenIntro = sessionStorage.getItem('wf_intro_seen');

    if (hasSeenIntro && !forceIntro) {
        document.documentElement.classList.add('wf-intro-done');
        overlay.remove();
        return;
    }

    // Lock page scroll during animation
    document.documentElement.classList.add('wf-intro-active');

    const path = document.getElementById('wf-intro-path');
    const colorImg = document.getElementById('wf-intro-color');
    const strokeWrap = document.getElementById('wf-intro-stroke-wrap');
    const title = document.getElementById('wf-intro-brand-title');
    const sub = document.getElementById('wf-intro-brand-sub');
    const skipBtn = document.getElementById('wf-intro-skip-btn');

    let dismissed = false;

    function dismissIntro() {
        if (dismissed) return;
        dismissed = true;

        try {
            sessionStorage.setItem('wf_intro_seen', '1');
        } catch (e) {}

        overlay.classList.add('wf-intro-dissolve');
        document.documentElement.classList.remove('wf-intro-active');

        setTimeout(function() {
            document.documentElement.classList.add('wf-intro-done');
            if (overlay && overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 750);
    }

    if (skipBtn) {
        skipBtn.addEventListener('click', function(e) {
            e.preventDefault();
            dismissIntro();
        });
    }

    // Safety fallback timer: never get stuck
    const safetyTimer = setTimeout(dismissIntro, 3800);

    if (!path) {
        setTimeout(dismissIntro, 800);
        return;
    }

    // Measure total length of the continuous mountain contour (~27,790 units)
    let len = 28000;
    try {
        len = Math.ceil(path.getTotalLength()) || 28000;
    } catch (err) {}

    path.style.transition = 'none';
    path.style.strokeDasharray = len + ' ' + len;
    path.style.strokeDashoffset = len;
    path.style.stroke = '#ea580c';

    // Force reflow so the starting state is locked before animation begins
    void path.getBoundingClientRect();

    // Start drawing animation with buttery smooth pacing
    requestAnimationFrame(function() {
        requestAnimationFrame(function() {
            // Calm, graceful, serene drawing pace (1.5s)
            const DRAW_TIME = 1500;

            // 1. Trace the clean mountain contour smoothly
            path.style.transition = 'stroke-dashoffset ' + DRAW_TIME + 'ms cubic-bezier(0.35, 0.0, 0.25, 1)';
            path.style.strokeDashoffset = '0';

            // 2. Soft, organic color bloom begins as line nears completion (~1000ms)
            setTimeout(function() {
                if (colorImg) colorImg.classList.add('wf-filled');
                if (strokeWrap) strokeWrap.classList.add('wf-filled');
                if (title) title.classList.add('wf-visible');
                if (sub) sub.classList.add('wf-visible');
            }, 1000);

            // 3. Serene hold to appreciate the art, then gently dissolve into the website
            setTimeout(function() {
                clearTimeout(safetyTimer);
                dismissIntro();
            }, 2450); // Total duration ~2.4s
        });
    });
})();
</script>
