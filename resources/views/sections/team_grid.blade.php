@php
    $heading = $elements['heading']?->content ?? 'Our Mountain Leadership';
    $subheading = $elements['subheading']?->content ?? 'Decades of combined high-altitude mountaineering expertise, indigenous sherpa heritage, and world-class logistics.';
    $team = $elements['team']?->getRepeaterItems() ?? [];
    $columns = (int)($settings['columns'] ?? 3);

    $gridClass = match($columns) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };

    if (empty($team)) {
        $team = [
            [
                'name' => 'Tenzing Norbu Sherpa',
                'position' => 'Head of Mountain Operations & Lead Guide',
                'bio' => '8-time Everest summitter with 18 years leading high-altitude alpine expeditions across the Khumbu and Karakoram ranges.',
                'image' => '/images/team-1.jpg'
            ],
            [
                'name' => 'Pemba Doma Sherpa',
                'position' => 'Senior Expedition Medic & Guide',
                'bio' => 'Certified Wilderness First Responder (WFR) with extensive rescue experience in high-altitude medicine across Manaslu and Annapurna.',
                'image' => '/images/team-2.jpg'
            ],
            [
                'name' => 'Dawa Dorje Tamang',
                'position' => 'Director of Bespoke Logistics',
                'bio' => 'Expert in complex Himalayan logistics, government permits, helicopter evacuation protocols, and customized private expeditions.',
                'image' => '/images/team-3.jpg'
            ],
        ];
    }
@endphp

@if($heading || !empty($team))
<section class="py-20 lg:py-32 bg-white" id="{{ $section->custom_anchor_id ?: ('section-' . $section->id) }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($heading)
        <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20" data-gsap="fade-up">
            <span class="text-accent-primary font-medium text-xs sm:text-sm tracking-widest uppercase block mb-2">The People Behind Your Summit</span>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="text-text-secondary mt-3 text-base sm:text-lg">{{ $subheading }}</p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-8 lg:gap-12" role="list">
            @foreach($team as $index => $member)
            @php
                $img = $member['image'] ?? '/images/team-' . (($index % 3) + 1) . '.jpg';
            @endphp
            <article class="team-card group text-center flex flex-col items-center bg-white border border-gray-100 p-8 hover:border-gray-200 hover:shadow-lg transition-[border-color,box-shadow] duration-300" data-gsap="fade-up" data-gsap-delay="{{ $index * 0.05 }}">
                <div class="relative w-40 h-40 sm:w-48 sm:h-48 rounded-full overflow-hidden mb-6 border-4 border-gray-100 group-hover:border-accent-primary transition-colors duration-300 shadow-md">
                    <img src="{{ $img }}" alt="{{ $member['name'] ?? 'Team member' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                </div>
                
                @if(isset($member['name']))
                <h3 class="font-display text-xl sm:text-2xl font-bold text-text-primary mb-1">
                    {{ $member['name'] }}
                </h3>
                @endif
                
                @if(isset($member['position']))
                <p class="text-accent-primary font-semibold text-xs sm:text-sm uppercase tracking-wider mb-4">
                    {{ $member['position'] }}
                </p>
                @endif
                
                @if(isset($member['bio']))
                <p class="text-text-secondary text-sm leading-relaxed max-w-sm">
                    {{ $member['bio'] }}
                </p>
                @endif
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
