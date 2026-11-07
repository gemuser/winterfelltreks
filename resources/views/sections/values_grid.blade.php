@php
    $heading = $elements['heading']?->content ?? 'Our Core Values';
    $subheading = $elements['subheading']?->content ?? 'The non-negotiable principles that shape every trail we walk, every summit we attempt, and every guest we host.';
    $values = $elements['values']?->getRepeaterItems() ?? [];
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
            <span class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">Integrity in Action</span>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-base sm:text-lg">{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-6 lg:gap-8" role="list">
            @foreach($values as $index => $value)
            <article class="value-card group p-8 bg-white border border-gray-100 relative overflow-hidden transition-[border-color,box-shadow] duration-300 hover:border-accent-primary/40 hover:shadow-lg flex flex-col justify-between" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="font-mono text-2xl font-bold text-gray-200 group-hover:text-accent-primary transition-colors">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="w-8 h-8 rounded-full bg-accent-primary/5 group-hover:bg-accent-primary/10 flex items-center justify-center transition-colors">
                            <span class="w-2 h-2 rounded-full bg-accent-primary"></span>
                        </div>
                    </div>
                    @if(isset($value['title']))
                    <h3 class="font-display text-xl font-bold text-text-primary mb-3 group-hover:text-accent-primary transition-colors">
                        {{ $value['title'] }}
                    </h3>
                    @endif
                    @if(isset($value['description']))
                    <p class="text-text-secondary text-sm sm:text-base leading-relaxed">
                        {{ $value['description'] }}
                    </p>
                    @endif
                </div>
                <div class="w-full h-0.5 bg-transparent group-hover:bg-accent-primary transition-colors mt-6"></div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
