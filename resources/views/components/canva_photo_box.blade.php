@php
    $cContent = $cEl->getContent();
    if (is_string($cContent)) {
        $cDec = json_decode($cContent, true);
        if (is_array($cDec)) {
            $imgUrl = $cDec['url'] ?? $cContent;
            $imgAlt = $cDec['alt'] ?? 'Photo Box';
            $imgCaption = $cDec['caption'] ?? '';
        } else {
            $imgUrl = $cContent;
            $imgAlt = 'Photo Box';
            $imgCaption = '';
        }
    } elseif (is_array($cContent)) {
        $imgUrl = $cContent['url'] ?? '/images/hero-everest.jpg';
        $imgAlt = $cContent['alt'] ?? 'Photo Box';
        $imgCaption = $cContent['caption'] ?? '';
    } else {
        $imgUrl = '/images/hero-everest.jpg';
        $imgAlt = 'Photo Box';
        $imgCaption = '';
    }

    $isEditor = $isEditor ?? false;
    $styles = $elStyles ?? [];
    $radius = $styles['borderRadius'] ?? '16px';
    $shadow = $styles['boxShadow'] ?? '0 20px 25px -5px rgba(0, 0, 0, 0.5)';
    $border = $styles['border'] ?? '2px solid rgba(255, 255, 255, 0.2)';
    $aspect = $styles['aspectRatio'] ?? 'auto';
    $fit = $styles['objectFit'] ?? 'cover';
    $height = $styles['height'] ?? 'auto';
@endphp

<div class="canva-photo-box relative overflow-hidden group/photo transition-all select-none w-full h-full pointer-events-auto {{ $isEditor ? 'cursor-move' : '' }}"
     data-key="{{ $cEl->element_key }}"
     data-element-id="{{ $cEl->id }}"
     style="border-radius: {{ $radius }}; box-shadow: {{ $shadow }}; border: {{ $border }};"
     @if($isEditor) @dblclick.stop="openImageModal({{ $cEl->section_id }}, {{ $cEl->id }})" title="Double click to change image" @endif>
    
    <img src="{{ $imgUrl }}" 
         alt="{{ $imgAlt }}" 
         draggable="false"
         ondragstart="return false;"
         loading="lazy"
         class="w-full h-full block object-cover transition-transform duration-300 {{ $isEditor ? 'pointer-events-none select-none' : 'group-hover/photo:scale-103 cursor-pointer' }}"
         style="border-radius: {{ $radius }}; object-fit: {{ $fit }}; aspect-ratio: {{ $aspect }}; height: {{ $height }}; {{ $isEditor ? '-webkit-user-drag: none; user-select: none; pointer-events: none;' : '' }}"
         @if(!$isEditor) data-lightbox="{{ $imgUrl }}" data-lightbox-alt="{{ $imgAlt }}" @endif>

    @if(!empty($imgCaption))
        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent p-2.5 text-white text-3xs font-semibold tracking-wide backdrop-blur-2xs">
            {{ $imgCaption }}
        </div>
    @endif

    @if($isEditor)
        {{-- Hover overlay in editor indicating editable photo --}}
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/photo:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5 p-2 pointer-events-none">
            <span class="bg-gray-950/90 text-white text-3xs font-bold px-2.5 py-1 rounded-full shadow-xl border border-white/30 backdrop-blur-xs flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Double-Click to Change</span>
            </span>
        </div>
    @endif
</div>

