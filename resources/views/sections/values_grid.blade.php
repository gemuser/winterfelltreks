@php
    $headingEl = $elements['heading'] ?? $elements['headline'] ?? $elements['title'] ?? null;
    $heading = $headingEl?->content ?? 'Our Core Values';
    $subheadingEl = $elements['subheading'] ?? $elements['subheadline'] ?? $elements['subtitle'] ?? null;
    $subheading = $subheadingEl?->content ?? 'The non-negotiable principles that shape every trail we walk, every summit we attempt, and every guest we host.';
    $badgeEl = $elements['badge'] ?? $elements['tag'] ?? null;
    $badge = $badgeEl?->content ?? 'Integrity in Action';
    $valuesEl = $elements['values'] ?? null;
    $valuesId = $valuesEl?->id ?? '';
    $values = $valuesEl?->getRepeaterItems() ?? [];
    $columns = (int)($settings['columns'] ?? 3);

    $gridClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };

    if (empty($values)) {
        $values = [
            [
                'title' => 'Uncompromising Safety',
                'description' => 'Comprehensive medical kits, satellite communications, acclimatization protocols, and certified alpine guides on every high-altitude trek.'
            ],
            [
                'title' => 'Ethical Sherpa Leadership',
                'description' => 'Fair living wages, full medical coverage, premium high-altitude gear, and deep respect for our local mountain crew.'
            ],
            [
                'title' => 'Leave No Trace',
                'description' => 'Dedicated waste-out policies, single-use plastic elimination, and active participation in clean Himalayan trail initiatives.'
            ],
            [
                'title' => 'Curated Authenticity',
                'description' => 'Intimate group sizes and bespoke custom itineraries that connect travelers deeply with local Himalayan traditions and communities.'
            ],
            [
                'title' => 'Precision Logistics',
                'description' => 'Seamless Kathmandu transfers, domestic mountain flights, comfortable tea house selections, and rapid emergency evacuation coverage.'
            ],
            [
                'title' => 'Continuous Innovation',
                'description' => 'Constant route scouting, altitude physiology education, and upgraded trail gear to ensure the finest trekking experience in Nepal.'
            ],
        ];
    }
@endphp

@if($heading || !empty($values))
<section class="py-20 lg:py-32 bg-background-alt" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="max-w-3xl mb-12 sm:mb-16" data-gsap="fade-up">
            @if($badge)
            <span data-key="badge" @if($badgeEl) data-element-id="{{ $badgeEl->id }}" @endif class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">{{ $badge }}</span>
            @endif
            <h2 data-key="heading" data-element-id="{{ $headingEl?->id ?? '' }}" class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p data-key="subheading" data-element-id="{{ $subheadingEl?->id ?? '' }}" class="text-text-secondary mt-3 text-base sm:text-lg">{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-6 lg:gap-8" role="list">
            @foreach($values as $index => $val)
            <div class="bg-white p-8 border border-gray-100 shadow-sm relative overflow-hidden group hover:border-accent-primary/40 transition-colors duration-300" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div class="text-accent-primary font-mono text-sm mb-4 font-bold">0{{ $index + 1 }}</div>
                <h3 data-repeater-id="{{ $valuesId }}" data-repeater-key="values" data-repeater-index="{{ $index }}" data-repeater-field="title" class="font-display text-xl font-bold text-text-primary mb-3">{{ $val['title'] ?? '' }}</h3>
                <p data-repeater-id="{{ $valuesId }}" data-repeater-key="values" data-repeater-index="{{ $index }}" data-repeater-field="description" class="text-text-secondary text-sm leading-relaxed">{{ $val['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
