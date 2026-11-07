<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;
use App\Models\DestinationCategory;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Mountains', 'slug' => 'mountains', 'description' => 'High-altitude mountain regions and peaks', 'sort_order' => 1],
            ['name' => 'Valleys', 'slug' => 'valleys', 'description' => 'Scenic valleys with cultural significance', 'sort_order' => 2],
            ['name' => 'Cultural Destinations', 'slug' => 'cultural', 'description' => 'Rich in heritage, monasteries, and traditions', 'sort_order' => 3],
            ['name' => 'Adventure Destinations', 'slug' => 'adventure', 'description' => 'Technical climbing and adventure sports', 'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            DestinationCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Destinations
        $destinations = [
            [
                'name' => 'Everest Region (Khumbu)',
                'slug' => 'everest-region',
                'country' => 'Nepal',
                'region' => 'Khumbu, Solukhumbu District',
                'short_description' => 'Home to Mount Everest (8,848m), legendary Sherpa culture, and the world\'s most iconic trekking routes.',
                'full_description' => '<p>The Everest Region, known locally as Khumbu, is the crown jewel of Himalayan trekking. Dominated by Sagarmatha (Mount Everest) at 8,848.86 meters, this region offers an unparalleled combination of dramatic mountain scenery, rich Sherpa culture, and world-class trekking infrastructure.</p><p>Key highlights include the Everest Base Camp trek, the Gokyo Lakes with their turquoise waters, the Three Passes trek for seasoned adventurers, and the Tengboche Monastery — the spiritual heart of the Khumbu. The region is home to the Sherpa people, renowned worldwide for their mountaineering prowess and warm hospitality.</p><p>Wildlife includes Himalayan tahr, musk deer, snow leopards (rarely seen), and numerous bird species including the Himalayan monal (Danphe), Nepal\'s national bird.</p>',
                'featured_image' => '/images/dest-everest-detail.jpg',
                'gallery' => json_encode([
                    '/images/dest-everest-1.jpg',
                    '/images/dest-everest-2.jpg',
                    '/images/dest-everest-3.jpg',
                ]),
                'best_time_to_visit' => '<p><strong>Pre-monsoon (March-May):</strong> Rhododendron blooms, stable weather, busy trails.</p><p><strong>Post-monsoon (October-November):</strong> Crystal clear skies, best mountain views, peak season.</p><p><strong>Winter (December-February):</strong> Cold but clear, fewer trekkers, some high passes may be snowed in.</p><p><strong>Monsoon (June-September):</strong> Heavy rain, leeches, obscured views, not recommended for high treks.</p>',
                'travel_information' => '<p><strong>Access:</strong> 35-minute flight Kathmandu to Lukla (2,860m), then trek. Alternative: 10-12 hour drive to Phaplu + 5-6 days trek to Namche.</p><p><strong>Permits:</strong> Sagarmatha National Park Permit (NPR 3,000), Khumbu Pasang Lhamu Rural Municipality Permit (NPR 2,000).</p><p><strong>Accommodation:</strong> Teahouses throughout, ranging from basic to comfortable lodges with hot showers and WiFi at lower elevations.</p><p><strong>Altitude Considerations:</strong> Minimum 2 acclimatization days (Namche 3,440m, Dingboche 4,410m). Diamox recommended for rapid ascents.</p>',
                'category' => 'mountains',
                'is_published' => true,
                'meta_title' => 'Everest Region (Khumbu) | Winterfell Treks',
                'meta_description' => 'Explore the Everest Region — home to Mount Everest, Sherpa culture, and legendary treks. Expert-guided adventures by Winterfell Treks.',
            ],
            [
                'name' => 'Annapurna Region',
                'slug' => 'annapurna-region',
                'country' => 'Nepal',
                'region' => 'Gandaki Province',
                'short_description' => 'Diverse landscapes from subtropical valleys to the Annapurna massif, featuring the classic Annapurna Circuit and Base Camp treks.',
                'full_description' => '<p>The Annapurna Region is Nepal\'s most diverse trekking destination, encompassing everything from lush subtropical forests and terraced farmland to high-altitude desert landscapes on the Tibetan plateau. The region is protected by the Annapurna Conservation Area (ACAP), Nepal\'s largest conservation area at 7,629 sq km.</p><p>The Annapurna massif includes Annapurna I (8,091m) — the 10th highest peak in the world — along with Annapurna II, III, IV, Gangapurna, and Machhapuchhre (Fishtail, 6,993m), widely considered one of the most beautiful mountains on Earth.</p><p>Cultural diversity is exceptional: Gurung, Magar, Thakali, Manangi, and Tibetan communities each with distinct languages, architecture, and traditions. The region is also famous for its apple orchards (Marpha), rhododendron forests (blooming March-April), and the world\'s deepest gorge — Kali Gandaki Gorge between Annapurna and Dhaulagiri.</p>',
                'featured_image' => '/images/dest-annapurna-detail.jpg',
                'gallery' => json_encode([
                    '/images/dest-annapurna-1.jpg',
                    '/images/dest-annapurna-2.jpg',
                    '/images/dest-annapurna-3.jpg',
                ]),
                'best_time_to_visit' => '<p><strong>Pre-monsoon (March-May):</strong> Rhododendrons in full bloom, warmer temperatures, occasional afternoon clouds.</p><p><strong>Post-monsoon (October-November):</strong> Clearest skies, best mountain views, most popular season.</p><p><strong>Winter (December-February):</strong> Cold at altitude, Thorong La Pass (5,416m) may be closed by snow, lower trails pleasant.</p><p><strong>Monsoon (June-September):</strong> Rain shadow effect — Upper Mustang and Manang stay relatively dry, good alternative season.</p>',
                'travel_information' => '<p><strong>Access:</strong> 6-8 hour drive or 25-minute flight Kathmandu to Pokhara, then road to trailheads (Nayapul, Jagat, Chame, Jomsom).</p><p><strong>Permits:</strong> ACAP Permit (NPR 3,000), TIMS Card (NPR 2,000 for groups, NPR 1,000 for individuals).</p><p><strong>Accommodation:</strong> Extensive teahouse network, from basic to luxury lodges. Most have hot showers, WiFi, and varied menus.</p><p><strong>Key Treks:</strong> Annapurna Circuit (14-21 days), Annapurna Base Camp (7-12 days), Ghorepani Poon Hill (4-5 days), Mardi Himal (5-7 days), Nar Phu Valley (9-12 days), Upper Mustang (10-14 days).</p>',
                'category' => 'mountains',
                'is_published' => true,
                'meta_title' => 'Annapurna Region | Winterfell Treks',
                'meta_description' => 'Discover the Annapurna Region — diverse landscapes, rich culture, and world-class treks. Customized expeditions by Winterfell Treks.',
            ],
            [
                'name' => 'Langtang Valley',
                'slug' => 'langtang-valley',
                'country' => 'Nepal',
                'region' => 'Bagmati Province, Rasuwa District',
                'short_description' => 'The "Valley of Glaciers" — pristine alpine landscapes, rich Tamang heritage, and accessible high-altitude trekking near Kathmandu.',
                'full_description' => '<p>Langtang Valley, often called the "Valley of Glaciers," lies just 51 km north of Kathmandu — making it the most accessible high-altitude trekking region from the capital. Despite its proximity, it sees far fewer visitors than Everest or Annapurna, offering a more tranquil experience.</p><p>The valley is dominated by Langtang Lirung (7,227m) and surrounded by peaks including Dorje Lakpa (6,966m), Langtang Ri (7,205m), and Ganesh Himal (7,422m). The Langtang Glacier and Lirung Glacier are major features.</p><p>The region is the homeland of the Tamang people, an ethnic group with Tibetan ancestry who practice Tibetan Buddhism. Ancient monasteries, mani walls, chortens, and prayer flags dot the landscape. Kyanjin Gompa (3,870m) is the valley\'s main monastery and a cultural highlight.</p><p>The 2015 earthquake devastated Langtang Village, but the community has rebuilt with remarkable resilience. Trekking here directly supports their recovery.</p>',
                'featured_image' => '/images/dest-langtang-detail.jpg',
                'gallery' => json_encode([
                    '/images/dest-langtang-1.jpg',
                    '/images/dest-langtang-2.jpg',
                    '/images/dest-langtang-3.jpg',
                ]),
                'best_time_to_visit' => '<p><strong>Pre-monsoon (March-May):</strong> Rhododendron blooms, stable weather, wildflowers at higher elevations.</p><p><strong>Post-monsoon (October-November):</strong> Clear skies, excellent views, comfortable temperatures.</p><p><strong>Winter (December-February):</strong> Cold, possible snow on high passes, very few trekkers, serene atmosphere.</p><p><strong>Monsoon (June-September):</strong> Heavy rain, landslides common, leeches, obscured views — not recommended.</p>',
                'travel_information' => '<p><strong>Access:</strong> 6-7 hour drive Kathmandu to Syabrubesi (1,550m), then trek. Road conditions vary seasonally.</p><p><strong>Permits:</strong> Langtang National Park Permit (NPR 3,000), TIMS Card (NPR 2,000/1,000).</p><p><strong>Accommodation:</strong> Teahouses available throughout, simpler than Everest/Annapurna regions. Hot showers and WiFi at larger villages.</p><p><strong>Key Treks:</strong> Langtang Valley (7-10 days), Tamang Heritage Trail (6-8 days), Gosainkunda Lake (5-7 days), Langtang-Gosainkunda-Helambu (12-15 days).</p><p><strong>Side Trips:</strong> Kyanjin Ri (4,773m), Tserko Ri (4,984m), Langshisha Kharka, Ganesh Himal Base Camp.</p>',
                'category' => 'valleys',
                'is_published' => true,
                'meta_title' => 'Langtang Valley | Winterfell Treks',
                'meta_description' => 'Explore Langtang Valley — the Valley of Glaciers near Kathmandu. Tamang culture, alpine scenery, and accessible trekking with Winterfell.',
            ],
            [
                'name' => 'Upper Mustang',
                'slug' => 'upper-mustang',
                'country' => 'Nepal',
                'region' => 'Gandaki Province, Mustang District',
                'short_description' => 'The "Last Forbidden Kingdom" — a high-altitude desert with ancient Tibetan culture, cave monasteries, and dramatic eroded landscapes.',
                'full_description' => '<p>Upper Mustang, formerly the Kingdom of Lo, was closed to outsiders until 1992 — earning its nickname as the "Last Forbidden Kingdom." This high-altitude trans-Himalayan region lies in the rain shadow of the Annapurna and Dhaulagiri ranges, creating a stark, beautiful desert landscape more akin to Tibet than the rest of Nepal.</p><p>The capital, Lo Manthang (3,840m), is a walled city with a 15th-century palace, four major monasteries (Jampa, Thubchen, Chode, Choprang), and narrow alleyways unchanged for centuries. The region is famous for its sky caves — thousands of ancient human-carved caves in vertical cliff faces, some containing 800-year-old Buddhist murals and manuscripts.</p><p>The culture is purely Tibetan Buddhist. The Loba people maintain traditions, language, and lifestyle nearly identical to pre-1950 Tibet. The Tiji Festival (usually May) is a spectacular three-day ritual dance celebrating the victory of good over evil.</p>',
                'featured_image' => '/images/dest-mustang-detail.jpg',
                'gallery' => json_encode([
                    '/images/dest-mustang-1.jpg',
                    '/images/dest-mustang-2.jpg',
                    '/images/dest-mustang-3.jpg',
                ]),
                'best_time_to_visit' => '<p><strong>Pre-monsoon (March-May):</strong> Best season — Tiji Festival in May, wildflowers, stable weather.</p><p><strong>Post-monsoon (October-November):</strong> Clear skies, cold nights, fewer tourists than spring.</p><p><strong>Monsoon (June-September):</strong> Rain shadow — excellent time to visit! Very little rain, green landscapes, fewer crowds.</p><p><strong>Winter (December-February):</strong> Extremely cold (-20°C at night), some passes blocked, limited services — only for well-prepared expeditions.</p>',
                'travel_information' => '<p><strong>Access:</strong> Flight Pokhara to Jomsom (20 min) + trek/jeep, or 4WD drive from Pokhara (8-10 hours). Flight weather-dependent.</p><p><strong>Permits:</strong> Restricted Area Permit USD 500 for 10 days + USD 50/day thereafter. ACAP Permit (NPR 3,000). Must travel with registered guide/agency.</p><p><strong>Accommodation:</strong> Basic teahouses in Lo Manthang and major villages. Camping required for remote areas. Limited electricity, no hot showers at higher elevations.</p><p><strong>Key Treks:</strong> Upper Mustang Trek (10-14 days), Upper Mustang + Damodar Kunda (16-18 days), Tiji Festival Trek (timed for festival).</p>',
                'category' => 'cultural',
                'is_published' => true,
                'meta_title' => 'Upper Mustang | Winterfell Treks',
                'meta_description' => 'Discover Upper Mustang — the Last Forbidden Kingdom. Ancient Tibetan culture, cave monasteries, and desert landscapes with Winterfell Treks.',
            ],
            [
                'name' => 'Manaslu Region',
                'slug' => 'manaslu-region',
                'country' => 'Nepal',
                'region' => 'Gandaki Province, Gorkha/Manang Districts',
                'short_description' => 'Remote wilderness around the world\'s 8th highest peak (8,163m) — the Manaslu Circuit offers raw Himalayan adventure off the beaten path.',
                'full_description' => '<p>The Manaslu Region centers on Mount Manaslu (8,163m), the world\'s 8th highest mountain. The Manaslu Circuit Trek, opened to foreigners in 1991, circumnavigates the massif through incredibly diverse landscapes — from subtropical forests at 600m to the Larkya La Pass at 5,106m.</p><p>This region sees a fraction of Annapurna\'s visitors, offering a more authentic, wilderness experience. The trail follows the Budhi Gandaki River through deep gorges, crosses suspension bridges, passes through Tibetan-influenced villages (Samagaon, Samdo), and offers stunning views of Manaslu, Himlung Himal, Cheo Himal, and Annapurna II.</p><p>The region is home to Gurung, Nubri, and Tsum communities. The Tsum Valley ("Hidden Valley of Happiness") is a sacred Buddhist valley with ancient monasteries (Mu Gompa, Rachen Gompa), milarepa caves, and a culture of non-violence (no animal slaughter).</p>',
                'featured_image' => '/images/dest-manaslu-detail.jpg',
                'gallery' => json_encode([
                    '/images/dest-manaslu-1.jpg',
                    '/images/dest-manaslu-2.jpg',
                    '/images/dest-manaslu-3.jpg',
                ]),
                'best_time_to_visit' => '<p><strong>Pre-monsoon (March-May):</strong> Rhododendrons, stable weather, warmer temperatures.</p><p><strong>Post-monsoon (October-November):</strong> Clearest skies, best views, most popular window.</p><p><strong>Winter (December-February):</strong> Larkya La often closed by heavy snow, very cold, minimal services.</p><p><strong>Monsoon (June-September):</strong> Heavy rain, landslides, leeches, poor views — not recommended.</p>',
                'travel_information' => '<p><strong>Access:</strong> 6-8 hour drive Kathmandu to Soti Khola (710m) or Machha Khola, then trek.</p><p><strong>Permits:</strong> Manaslu Restricted Area Permit (USD 100 first week Sep-Nov, USD 75 Dec-Aug + USD 15/25 per extra day), Manaslu Conservation Area Permit (NPR 3,000), ACAP (NPR 3,000 if crossing Larkya La to Annapurna side). Minimum 2 trekkers + registered guide required.</p><p><strong>Accommodation:</strong> Basic to moderate teahouses. More rustic than Everest/Annapurna. Limited hot showers, sporadic WiFi.</p><p><strong>Key Treks:</strong> Manaslu Circuit (14-18 days), Tsum Valley (10-14 days), Manaslu + Tsum Combined (21-24 days).</p>',
                'category' => 'mountains',
                'is_published' => true,
                'meta_title' => 'Manaslu Region | Winterfell Treks',
                'meta_description' => 'Explore the Manaslu Region — remote wilderness around the 8th highest peak. Authentic Himalayan adventure with Winterfell Treks.',
            ],
        ];

        foreach ($destinations as $dest) {
            Destination::updateOrCreate(['slug' => $dest['slug']], $dest);
        }
    }
}