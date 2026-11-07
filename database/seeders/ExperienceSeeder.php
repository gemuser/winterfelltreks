<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Experience;
use App\Models\ExperienceCategory;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Adventure', 'slug' => 'adventure', 'description' => 'High-adrenaline mountain activities', 'sort_order' => 1],
            ['name' => 'Cultural Experiences', 'slug' => 'cultural', 'description' => 'Immersive cultural encounters with Himalayan communities', 'sort_order' => 2],
            ['name' => 'Nature & Wildlife', 'slug' => 'nature', 'description' => 'Wildlife spotting, birdwatching, and natural history', 'sort_order' => 3],
            ['name' => 'Trekking & Hiking', 'slug' => 'trekking', 'description' => 'Multi-day treks and day hikes', 'sort_order' => 4],
            ['name' => 'Photography', 'slug' => 'photography', 'description' => 'Landscape, cultural, and wildlife photography', 'sort_order' => 5],
            ['name' => 'Spiritual & Wellness', 'slug' => 'wellness', 'description' => 'Meditation, yoga, monastery stays', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            ExperienceCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $experiences = [
            [
                'name' => 'Sherpa Culture Immersion',
                'slug' => 'sherpa-culture-immersion',
                'category' => 'cultural',
                'short_description' => 'Live with Sherpa families, participate in daily life, visit monasteries, and learn ancient traditions.',
                'overview' => '<p>Spend 3-5 days in a traditional Sherpa home in the Khumbu region. Participate in daily activities — cooking dal bhat, tending yak, spinning prayer wheels. Visit ancient monasteries with resident lamas. Learn about Sherpa Buddhism, mountain worship, and the community\'s deep connection to Chomolungma (Everest).</p>',
                'detailed_description' => '<p>This immersive experience goes far beyond a typical village visit. You\'ll stay with a carefully selected Sherpa family who have hosted guests for generations. Your hosts will share their language (Sherpa/Tibetan), teach you to make momos and butter tea, explain the significance of mani walls and chortens, and take you to their family\'s sacred sites.</p><p>Evening conversations with elders reveal oral histories of early Everest expeditions, the transformation of Khumbu from subsistence farming to mountaineering economy, and the community\'s vision for the future.</p>',
                'available_locations' => json_encode(['Khumjung', 'Kunde', 'Thame', 'Pangboche', 'Phortse']),
                'activities' => json_encode(['Homestay with Sherpa family', 'Monastery visits with lama', 'Traditional cooking class', 'Prayer ceremony participation', 'Village walk with elder', 'Sherpa language basics']),
                'recommended_duration' => '3-5 Days',
                'important_information' => '<p><strong>Best Season:</strong> October-November, March-May</p><p><strong>Accommodation:</strong> Traditional family home (basic but clean, shared bathroom)</p><p><strong>Meals:</strong> All meals with family (dal bhat, momos, thukpa, Tibetan bread)</p><p><strong>Cultural Etiquette:</strong> Modest dress, remove shoes indoors, ask before photographing religious objects, respect prayer times</p>',
                'hero_image' => '/images/exp-sherpa-detail.jpg',
                'gallery' => json_encode(['/images/exp-sherpa-1.jpg', '/images/exp-sherpa-2.jpg', '/images/exp-sherpa-3.jpg']),
                'related_package_ids' => json_encode([1, 3]),
                'is_published' => true,
                'meta_title' => 'Sherpa Culture Immersion | Winterfell Treks',
                'meta_description' => 'Authentic Sherpa homestay experience in the Everest region. Live with local families, visit monasteries, learn traditions.',
            ],
            [
                'name' => 'High-Altitude Photography Expedition',
                'slug' => 'high-altitude-photography',
                'category' => 'photography',
                'short_description' => 'Capture the Himalayas with professional guidance. Sunrise on Everest, star trails over monasteries, cultural portraits.',
                'overview' => '<p>Join a professional mountain photographer on a dedicated photography expedition. Designed for photographers of all levels — from smartphone shooters to professionals. Focus on landscape, astrophotography, cultural portraiture, and storytelling.</p>',
                'detailed_description' => '<p>Each day is planned around light: pre-dawn starts for sunrise on peaks, golden hour at monasteries, blue hour in villages, and night sessions for Milky Way and star trails over the Himalayas. Your photography guide provides hands-on instruction — composition, exposure, focus stacking, panoramas, time-lapse, and post-processing workflow.</p><p>Itineraries are flexible to chase weather and light. Non-photographer companions welcome (separate activities arranged).</p>',
                'available_locations' => json_encode(['Everest Region (Kala Patthar, Gokyo)', 'Annapurna Region (Poon Hill, Annapurna Base Camp)', 'Langtang Valley (Kyanjin Ri, Tserko Ri)', 'Upper Mustang (Lo Manthang, caves)']),
                'activities' => json_encode(['Sunrise/sunset peak photography', 'Astrophotography & star trails', 'Cultural portrait sessions', 'Monastery architecture', 'Wildlife photography', 'Post-processing workshops', 'Image review & critique']),
                'recommended_duration' => '7-14 Days',
                'important_information' => '<p><strong>Equipment:</strong> Bring your own camera gear. Tripod essential. Wide-angle (14-24mm), telephoto (70-200mm+), fast prime (24mm f/1.4) recommended.</p><p><strong>Cold Weather:</strong> Batteries drain fast — bring spares in inner pockets. Silica gel for condensation.</p><p><strong>Permits:</strong> Drone permits required separately (complex process, apply 30+ days ahead).</p>',
                'hero_image' => '/images/exp-photo-detail.jpg',
                'gallery' => json_encode(['/images/exp-photo-1.jpg', '/images/exp-photo-2.jpg', '/images/exp-photo-3.jpg']),
                'related_package_ids' => json_encode([1, 2, 4]),
                'is_published' => true,
                'meta_title' => 'Himalayan Photography Expeditions | Winterfell Treks',
                'meta_description' => 'Professional photography expeditions in the Himalayas. Landscape, astrophotography, cultural portraits with expert guidance.',
            ],
            [
                'name' => 'Snow Leopard & Wildlife Tracking',
                'slug' => 'snow-leopard-tracking',
                'category' => 'nature',
                'short_description' => 'Track elusive snow leopards in their high-altitude habitat with expert naturalists and camera traps.',
                'overview' => '<p>Join conservation researchers and expert trackers in the remote valleys of the Annapurna and Manaslu regions — prime snow leopard habitat. This citizen-science expedition contributes to ongoing research while offering a rare chance to glimpse the "Ghost of the Mountains."</p>',
                'detailed_description' => '<p>Based from a comfortable lodge in the Nar Phu Valley or Upper Manang, daily excursions follow known snow leopard corridors. You\'ll learn to identify signs — pugmarks, scrapes, scent marks, scat — and help check camera traps. Other target species: Himalayan blue sheep (bharal), Himalayan tahr, Himalayan wolf, red panda (lower elevations), and numerous raptors including lammergeier and golden eagle.</p><p>Evenings feature talks by researchers on snow leopard ecology, human-wildlife conflict mitigation, and community-based conservation. A portion of your fee directly supports the Snow Leopard Conservancy\'s work in Nepal.</p>',
                'available_locations' => json_encode(['Nar Phu Valley (Manaslu)', 'Upper Manang (Annapurna)', 'Kanchenjunga Conservation Area (Eastern Nepal)']),
                'activities' => json_encode(['Camera trap monitoring', 'Tracking & sign identification', 'Wildlife observation from blinds', 'Birdwatching (Himalayan specialties)', 'Conservation talks', 'Community visits']),
                'recommended_duration' => '7-10 Days',
                'important_information' => '<p><strong>Reality Check:</strong> Snow leopard sightings are NEVER guaranteed. These are wild, elusive animals in vast terrain. The experience is about tracking, learning, and contributing to conservation.</p><p><strong>Fitness:</strong> Moderate-high. Daily hikes 4-6 hours at 3,500-4,500m.</p><p><strong>Best Season:</strong> October-November (post-monsoon, cats active), February-March (mating season, more movement).</p>',
                'hero_image' => '/images/exp-wildlife-detail.jpg',
                'gallery' => json_encode(['/images/exp-wildlife-1.jpg', '/images/exp-wildlife-2.jpg']),
                'related_package_ids' => json_encode([2, 6]),
                'is_published' => true,
                'meta_title' => 'Snow Leopard Tracking Expedition | Winterfell Treks',
                'meta_description' => 'Track snow leopards in the Himalayas with conservation researchers. Citizen-science wildlife expedition in Nepal.',
            ],
            [
                'name' => 'Monastery Stay & Meditation Retreat',
                'slug' => 'monastery-meditation-retreat',
                'category' => 'wellness',
                'short_description' => 'Live as a monk in ancient Himalayan monasteries. Learn meditation, philosophy, and Tibetan Buddhist practices.',
                'overview' => '<p>Spend 5-10 days in a functioning Tibetan Buddhist monastery. Participate in daily pujas (prayers), learn meditation techniques from resident lamas, study basic Buddhist philosophy, and experience the rhythm of monastic life.</p>',
                'detailed_description' => '<p>Partner monasteries include Tengboche (Everest), Braga (Manang), Kyanjin Gompa (Langtang), and Lo Gekar (Mustang). Each offers a unique lineage and atmosphere. Days begin at 5:30 AM with morning puja, followed by breakfast, meditation instruction, philosophy teachings, and personal practice time. Afternoon includes work practice (karma yoga), circumambulation, and evening puja.</p><p>Silence is observed during designated periods. Vegetarian meals served in the monastery kitchen. Accommodation is simple — monk\'s cell or guest room with basic amenities.</p>',
                'available_locations' => json_encode(['Tengboche Monastery (Everest)', 'Braga Monastery (Manang)', 'Kyanjin Gompa (Langtang)', 'Lo Gekar / Thubchen (Upper Mustang)']),
                'activities' => json_encode(['Daily puja participation', 'Meditation instruction (Shamatha, Vipassana, Tonglen)', 'Buddhist philosophy teachings', 'Mantra recitation', 'Prostration practice', 'Circumambulation (kora)', 'Karma yoga (service work)']),
                'recommended_duration' => '5-10 Days',
                'important_information' => '<p><strong>Requirements:</strong> Sincere interest in Buddhist practice. No prior experience needed.</p><p><strong>Etiquette:</strong> Modest dress (shoulders/knees covered). No alcohol, tobacco, meat on premises. Respect silence periods. Ask before photographing.</p><p><strong>Language:</strong> Teachings in Tibetan/English with translation. Basic Tibetan phrases taught.</p>',
                'hero_image' => '/images/exp-monastery-detail.jpg',
                'gallery' => json_encode(['/images/exp-monastery-1.jpg', '/images/exp-monastery-2.jpg']),
                'related_package_ids' => json_encode([1, 2, 4]),
                'is_published' => true,
                'meta_title' => 'Monastery Stay & Meditation Retreat | Winterfell Treks',
                'meta_description' => 'Authentic monastery stays in the Himalayas. Learn meditation and Buddhist philosophy with resident lamas.',
            ],
            [
                'name' => 'Himalayan Culinary Journey',
                'slug' => 'himalayan-culinary',
                'category' => 'cultural',
                'short_description' => 'Explore the diverse flavors of the Himalayas — from Sherpa stews to Thakali thalis, Tibetan momos to Newari feasts.',
                'overview' => '<p>A gastronomic expedition through Nepal\'s mountain regions. Cook with local families, learn traditional recipes, visit markets, and understand how altitude, climate, and culture shape Himalayan cuisine.</p>',
                'detailed_description' => '<p>Each region has distinct flavors: Sherpa cuisine (thukpa, rildok, chang), Thakali (dal bhat with 12+ sides, apple brandy), Tibetan (momos, thenthuk, butter tea), Newari (bara, chatamari, yomari), Gurung (kodo ko roti, raksi). You\'ll cook in family kitchens, visit village mills and breweries, forage for wild edibles (fiddleheads, nettles, mushrooms in season), and feast at festival meals.</p>',
                'available_locations' => json_encode(['Khumbu (Sherpa)', 'Lower Mustang (Thakali)', 'Upper Mustang (Tibetan)', 'Kathmandu Valley (Newari)', 'Annapurna Foothills (Gurung)']),
                'activities' => json_encode(['Family cooking classes', 'Market visits', 'Traditional brewery tour', 'Foraging walks (seasonal)', 'Festival feast participation', 'Tea ceremony (Tibetan butter tea)', 'Recipe booklet to take home']),
                'recommended_duration' => '5-7 Days',
                'important_information' => '<p><strong>Dietary:</strong> Vegetarian options abundant. Vegan possible with notice. Allergies accommodated.</p><p><strong>Hygiene:</strong> Kitchens are traditional. We provide water purification. Guide monitors food safety.</p>',
                'hero_image' => '/images/exp-culinary-detail.jpg',
                'gallery' => json_encode(['/images/exp-culinary-1.jpg']),
                'related_package_ids' => json_encode([]),
                'is_published' => true,
                'meta_title' => 'Himalayan Culinary Journey | Winterfell Treks',
                'meta_description' => 'Food tour of the Himalayas. Cook with local families, learn traditional recipes, explore mountain cuisine.',
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::updateOrCreate(['slug' => $exp['slug']], $exp);
        }
    }
}