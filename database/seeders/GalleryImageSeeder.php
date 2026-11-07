<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GalleryImage;
use App\Models\GalleryCategory;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            // Destinations
            ['category_id' => GalleryCategory::where('slug', 'destinations')->first()?->id, 'title' => 'Everest Sunrise from Kala Patthar', 'image' => '/images/gal-everest-sunrise.jpg', 'alt_text' => 'First light on Everest summit from Kala Patthar (5,545m)', 'location' => 'Kala Patthar, Everest Region', 'sort_order' => 1],
            ['category_id' => GalleryCategory::where('slug', 'destinations')->first()?->id, 'title' => 'Gokyo Lakes at Dawn', 'image' => '/images/gal-gokyo-lakes.jpg', 'alt_text' => 'Third Gokyo Lake reflecting Cho Oyu at sunrise', 'location' => 'Gokyo, Everest Region', 'sort_order' => 2],
            ['category_id' => GalleryCategory::where('slug', 'destinations')->first()?->id, 'title' => 'Annapurna South from Poon Hill', 'image' => '/images/gal-annapurna-poonhill.jpg', 'alt_text' => 'Annapurna South (7,219m) and Hiunchuli from Poon Hill viewpoint', 'location' => 'Poon Hill, Annapurna Region', 'sort_order' => 3],
            ['category_id' => GalleryCategory::where('slug', 'destinations')->first()?->id, 'title' => 'Langtang Valley Glacier View', 'image' => '/images/gal-langtang-glacier.jpg', 'alt_text' => 'Langtang Glacier and Lirung Icefall from Kyanjin Ri', 'location' => 'Kyanjin Ri, Langtang Valley', 'sort_order' => 4],
            ['category_id' => GalleryCategory::where('slug', 'destinations')->first()?->id, 'title' => 'Lo Manthang Walled City', 'image' => '/images/gal-lo-manthang.jpg', 'alt_text' => 'Ancient walled capital of Upper Mustang with palace and monasteries', 'location' => 'Lo Manthang, Upper Mustang', 'sort_order' => 5],
            ['category_id' => GalleryCategory::where('slug', 'destinations')->first()?->id, 'title' => 'Manaslu from Larkya La', 'image' => '/images/gal-manaslu-larkya.jpg', 'alt_text' => 'Manaslu (8,163m) dominating the horizon from Larkya La Pass', 'location' => 'Larkya La, Manaslu Region', 'sort_order' => 6],

            // Mountains
            ['category_id' => GalleryCategory::where('slug', 'mountains')->first()?->id, 'title' => 'Ama Dablam — The Matterhorn of Himalaya', 'image' => '/images/gal-ama-dablam.jpg', 'alt_text' => 'Ama Dablam (6,812m) in evening light from Pangboche', 'location' => 'Pangboche, Everest Region', 'sort_order' => 1],
            ['category_id' => GalleryCategory::where('slug', 'mountains')->first()?->id, 'title' => 'Machhapuchhre — Fishtail Mountain', 'image' => '/images/gal-machhapuchhre.jpg', 'alt_text' => 'Sacred Machhapuchhre (6,993m) reflected in Fewa Lake', 'location' => 'Pokhara, Annapurna Region', 'sort_order' => 2],
            ['category_id' => GalleryCategory::where('slug', 'mountains')->first()?->id, 'title' => 'Dhaulagiri I — White Mountain', 'image' => '/images/gal-dhaulagiri.jpg', 'alt_text' => 'Dhaulagiri I (8,167m) from French Pass on Dhaulagiri Circuit', 'location' => 'French Pass, Dhaulagiri Region', 'sort_order' => 3],
            ['category_id' => GalleryCategory::where('slug', 'mountains')->first()?->id, 'title' => 'Kanchenjunga Massif', 'image' => '/images/gal-kanchenjunga.jpg', 'alt_text' => 'Kanchenjunga (8,586m) — third highest, from Pangpema Base Camp', 'location' => 'Pangpema, Kanchenjunga Region', 'sort_order' => 4],

            // Culture & People
            ['category_id' => GalleryCategory::where('slug', 'culture')->first()?->id, 'title' => 'Sherpa Elder at Tengboche', 'image' => '/images/gal-sherpa-elder.jpg', 'alt_text' => 'Sherpa elder spinning prayer wheel at Tengboche Monastery', 'location' => 'Tengboche Monastery', 'sort_order' => 1],
            ['category_id' => GalleryCategory::where('slug', 'culture')->first()?->id, 'title' => 'Tiji Festival Dance', 'image' => '/images/gal-tiji-festival.jpg', 'alt_text' => 'Monk dancers performing Vajrakila ritual at Tiji Festival', 'location' => 'Lo Manthang, Upper Mustang', 'sort_order' => 2],
            ['category_id' => GalleryCategory::where('slug', 'culture')->first()?->id, 'title' => 'Tamang Women in Langtang', 'image' => '/images/gal-tamang-women.jpg', 'alt_text' => 'Tamang women in traditional dress at Kyanjin Gompa', 'location' => 'Kyanjin Gompa, Langtang', 'sort_order' => 3],
            ['category_id' => GalleryCategory::where('slug', 'culture')->first()?->id, 'title' => 'Prayer Flags at Thorong La', 'image' => '/images/gal-prayer-flags.jpg', 'alt_text' => 'Thousands of prayer flags fluttering at Thorong La Pass (5,416m)', 'location' => 'Thorong La, Annapurna Circuit', 'sort_order' => 4],
            ['category_id' => GalleryCategory::where('slug', 'culture')->first()?->id, 'title' => 'Butter Lamp Offering', 'image' => '/images/gal-butter-lamps.jpg', 'alt_text' => 'Rows of butter lamps at Boudhanath Stupa during Losar', 'location' => 'Boudhanath, Kathmandu', 'sort_order' => 5],

            // Wildlife
            ['category_id' => GalleryCategory::where('slug', 'wildlife')->first()?->id, 'title' => 'Himalayan Blue Sheep (Bharal)', 'image' => '/images/gal-blue-sheep.jpg', 'alt_text' => 'Himalayan blue sheep on cliffs above Samagaon', 'location' => 'Manaslu Region', 'sort_order' => 1],
            ['category_id' => GalleryCategory::where('slug', 'wildlife')->first()?->id, 'title' => 'Himalayan Monal (Danphe)', 'image' => '/images/gal-himalayan-monal.jpg', 'alt_text' => 'Male Himalayan Monal — Nepal\'s national bird — in rhododendron forest', 'location' => 'Ghandruk, Annapurna Region', 'sort_order' => 2],
            ['category_id' => GalleryCategory::where('slug', 'wildlife')->first()?->id, 'title' => 'Lammergeier (Bearded Vulture)', 'image' => '/images/gal-lammergeier.jpg', 'alt_text' => 'Lammergeier soaring above Kali Gandaki Gorge', 'location' => 'Kali Gandaki, Annapurna Region', 'sort_order' => 3],

            // Expeditions
            ['category_id' => GalleryCategory::where('slug', 'expeditions')->first()?->id, 'title' => 'Crossing Cho La Pass', 'image' => '/images/gal-cho-la-crossing.jpg', 'alt_text' => 'Team crossing Cho La Pass (5,420m) with crampons', 'location' => 'Cho La, Everest Region', 'sort_order' => 1],
            ['category_id' => GalleryCategory::where('slug', 'expeditions')->first()?->id, 'title' => 'Island Peak Summit Push', 'image' => '/images/gal-island-peak-summit.jpg', 'alt_text' => 'Climbers on headwall of Island Peak (6,189m) at dawn', 'location' => 'Island Peak, Everest Region', 'sort_order' => 2],
            ['category_id' => GalleryCategory::where('slug', 'expeditions')->first()?->id, 'title' => 'Larkya La Descent', 'image' => '/images/gal-larkya-descent.jpg', 'alt_text' => 'Long descent from Larkya La toward Bimthang', 'location' => 'Larkya La, Manaslu Region', 'sort_order' => 3],

            // Base Camp Life
            ['category_id' => GalleryCategory::where('slug', 'base-camp')->first()?->id, 'title' => 'Morning Briefing at Base Camp', 'image' => '/images/gal-bc-briefing.jpg', 'alt_text' => 'Guide briefing team at Island Peak Base Camp', 'location' => 'Island Peak Base Camp', 'sort_order' => 1],
            ['category_id' => GalleryCategory::where('slug', 'base-camp')->first()?->id, 'title' => 'Acclimatization Hike', 'image' => '/images/gal-acclim-hike.jpg', 'alt_text' => 'Team on acclimatization hike above Dingboche', 'location' => 'Nangkartshang, Everest Region', 'sort_order' => 2],
            ['category_id' => GalleryCategory::where('slug', 'base-camp')->first()?->id, 'title' => 'Celebration at Summit', 'image' => '/images/gal-summit-celebration.jpg', 'alt_text' => 'Team celebrating successful Island Peak summit', 'location' => 'Island Peak Summit (6,189m)', 'sort_order' => 3],
        ];

        foreach ($images as $img) {
            GalleryImage::updateOrCreate(
                ['image' => $img['image']],
                $img
            );
        }
    }
}