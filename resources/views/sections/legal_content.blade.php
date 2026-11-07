@php
    $body = $elements['body']?->content ?? '';
@endphp

@if($body)
<section class="py-20 lg:py-32" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto" data-gsap="fade-up">
            <div class="prose prose-lg prose-gray max-w-none text-text-secondary leading-relaxed">
                {!! $body !!}
            </div>
        </div>
    </div>
</section>
@endif