@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? '';
    $bodyEl = $elements['body'] ?? $elements['content'] ?? null;
    $body = $bodyEl?->content ?? '';
@endphp

@if($body || $heading)
<section class="py-20 lg:py-32" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto" data-gsap="fade-up">
            @if($heading)
            <h2 data-key="heading" @if($headingEl) data-element-id="{{ $headingEl->id }}" @endif class="font-display text-3xl font-bold text-text-primary mb-8 tracking-tight">
                {{ $heading }}
            </h2>
            @endif
            @if($body)
            <div data-key="body" data-element-id="{{ $bodyEl?->id ?? '' }}" class="prose prose-lg prose-gray max-w-none text-text-secondary leading-relaxed">
                {!! $body !!}
            </div>
            @endif
        </div>
    </div>
</section>
@endif
