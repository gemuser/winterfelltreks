<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $seo['title'] ?? config('app.name') }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    @if($seo['keywords'] ?? false)
    <meta name="keywords" content="{{ $seo['keywords'] }}">
    @endif

    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta name="robots" content="{{ $seo['robots_index'] ?? true ? 'index, follow' : 'noindex, nofollow' }}">

    @if($seo['og_image'] ?? false)
    <meta property="og:title" content="{{ $seo['title'] ?? config('app.name') }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:image" content="{{ is_array($seo['og_image']) ? $seo['og_image']['url'] : $seo['og_image'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ is_array($seo['og_image']) ? $seo['og_image']['url'] : $seo['og_image'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800;900&family=Cinzel+Decorative:wght@400;700;900&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/winterfell-nobg.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if(isset($structured_data))
    <script type="application/ld+json">
        {{ json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}
    </script>
    @endif

    {{-- Start Animation Fast-path check to eliminate flash of intro on repeated visits --}}
    <script>
        (function() {
            try {
                if (sessionStorage.getItem('wf_intro_seen') && !window.location.search.includes('intro=')) {
                    document.documentElement.classList.add('wf-intro-done');
                }
            } catch (e) {}
        })();
    </script>
    <style>
        html.wf-intro-done #wf-intro-overlay { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="font-body antialiased bg-white text-text-primary selection:bg-accent selection:text-white">
    {{-- Start Animation Intro (Plays on first visit) --}}
    @include('components.intro-animation')

    @include('components.header')

    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>

    @include('components.footer')

    <script>
        // Defer non-critical scripts
        window.addEventListener('load', function() {
            // Lazy load images if needed
        }, { once: true });
    </script>
    @stack('scripts')
</body>
</html>
