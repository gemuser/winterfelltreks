<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GalleryCategory;

class GalleryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Destinations', 'slug' => 'destinations', 'description' => 'Landscapes and vistas from our trekking regions', 'sort_order' => 1],
            ['name' => 'Mountains', 'slug' => 'mountains', 'description' => 'Peak portraits and mountain panoramas', 'sort_order' => 2],
            ['name' => 'Culture & People', 'slug' => 'culture', 'description' => 'Portraits, festivals, daily life in mountain communities', 'sort_order' => 3],
            ['name' => 'Wildlife', 'slug' => 'wildlife', 'description' => 'Himalayan fauna in their natural habitat', 'sort_order' => 4],
            ['name' => 'Expeditions', 'slug' => 'expeditions', 'description' => 'Action shots from our guided trips', 'sort_order' => 5],
            ['name' => 'Base Camp Life', 'slug' => 'base-camp', 'description' => 'Camps, acclimatization, team moments', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            GalleryCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}