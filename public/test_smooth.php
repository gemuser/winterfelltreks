<?php
$svg = file_get_contents('public/Logo.svg');
preg_match('/<path\s+d="([^"]+)"/', $svg, $m);
$d = $m[1];
$subpaths = preg_split('/(?=[Mm])/', trim($d), -1, PREG_SPLIT_NO_EMPTY);
$outlinePath = trim($subpaths[0]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Silky Smooth Intro Animation</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background-color: #080c14;
        color: #ffffff;
        font-family: 'Cinzel', serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    #wf-intro-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        background-color: #080c14;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        user-select: none;
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }

    #wf-intro-overlay.dismissed {
        opacity: 0 !important;
        transform: scale(1.025) !important;
        pointer-events: none !important;
    }

    .stage {
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

    .logo-frame {
        position: relative;
        width: 320px;
        height: 199px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @media (min-width: 640px) {
        .logo-frame {
            width: 400px;
            height: 249px;
        }
    }

    .logo-color {
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
    }
    .logo-color.filled {
        opacity: 1;
        transform: scale(1);
    }

    .logo-stroke {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }
    .logo-stroke svg {
        width: 100%;
        height: 100%;
        overflow: visible;
    }
    .logo-stroke path {
        fill: transparent !important;
        stroke: #ea580c;
        stroke-width: 18;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 28000 28000;
        stroke-dashoffset: 28000;
        transition: stroke 0.95s ease, opacity 0.95s ease;
    }
    .logo-stroke.filled path {
        stroke: #1e293b;
        opacity: 0.3;
        stroke-width: 12;
    }

    .brand-name {
        margin-top: 28px;
        font-family: 'Cinzel', serif;
        font-size: 21px;
        font-weight: 700;
        letter-spacing: 0.36em;
        text-transform: uppercase;
        color: #ffffff;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .brand-name.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .brand-tagline {
        margin-top: 7px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: #ea580c;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.12s, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.12s;
    }
    .brand-tagline.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .skip-btn {
        position: absolute;
        top: 24px;
        right: 28px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.45);
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.12);
        padding: 6px 14px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 10;
    }
</style>
</head>
<body>

<div id="wf-intro-overlay">
    <button class="skip-btn" onclick="startAnimation()">Replay</button>

    <div class="stage">
        <div class="logo-frame">
            <img id="logo-color" src="/images/winterfell-nobg.png" class="logo-color" alt="Winterfell Treks & Expedition">

            <div id="logo-stroke" class="logo-stroke">
                <svg viewBox="126 274 800 498" preserveAspectRatio="none">
                    <g transform="translate(0, 1024) scale(0.1, -0.1)">
                        <path id="wf-path" d="<?= $outlinePath ?>" />
                    </g>
                </svg>
            </div>
        </div>

        <div id="brand-name" class="brand-name">W I N T E R F E L L</div>
        <div id="brand-tagline" class="brand-tagline">Treks & Expedition &bull; Himalayas</div>
    </div>
</div>

<script>
    function startAnimation() {
        const path = document.getElementById('wf-path');
        const color = document.getElementById('logo-color');
        const strokeWrap = document.getElementById('logo-stroke');
        const name = document.getElementById('brand-name');
        const tagline = document.getElementById('brand-tagline');
        const overlay = document.getElementById('wf-intro-overlay');

        // Reset
        overlay.classList.remove('dismissed');
        color.classList.remove('filled');
        strokeWrap.classList.remove('filled');
        name.classList.remove('visible');
        tagline.classList.remove('visible');

        const len = 28000;
        path.style.transition = 'none';
        path.style.strokeDasharray = len + ' ' + len;
        path.style.strokeDashoffset = len;

        // Force browser layout flush so starting point is locked
        void path.getBoundingClientRect();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                // Silky smooth, calm, majestic drawing animation (1.4s)
                const DRAW_TIME = 1400;
                path.style.transition = `stroke-dashoffset ${DRAW_TIME}ms cubic-bezier(0.33, 1, 0.68, 1)`;
                path.style.strokeDashoffset = '0';

                // Elegant color bloom begins at 75% of the draw (~1050ms)
                setTimeout(() => {
                    color.classList.add('filled');
                    strokeWrap.classList.add('filled');
                    name.classList.add('visible');
                    tagline.classList.add('visible');
                }, 1050);

                // Gentle pause, then dissolve into website
                setTimeout(() => {
                    overlay.classList.add('dismissed');
                }, 2450);
            });
        });
    }

    window.addEventListener('DOMContentLoaded', startAnimation);
</script>
</body>
</html>
