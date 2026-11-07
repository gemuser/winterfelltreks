<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Travel Tips', 'slug' => 'travel-tips', 'description' => 'Practical advice for Himalayan travel', 'sort_order' => 1],
            ['name' => 'Destination Guides', 'slug' => 'destination-guides', 'description' => 'In-depth guides to Himalayan regions', 'sort_order' => 2],
            ['name' => 'Travel Advice', 'slug' => 'travel-advice', 'description' => 'Health, safety, and preparation guidance', 'sort_order' => 3],
            ['name' => 'Culture', 'slug' => 'culture', 'description' => 'Himalayan cultures, traditions, and festivals', 'sort_order' => 4],
            ['name' => 'Food', 'slug' => 'food', 'description' => 'Mountain cuisine and culinary traditions', 'sort_order' => 5],
            ['name' => 'Adventure', 'slug' => 'adventure', 'description' => 'Climbing, trekking, and expedition stories', 'sort_order' => 6],
            ['name' => 'Travel News', 'slug' => 'travel-news', 'description' => 'Updates on permits, trails, and regulations', 'sort_order' => 7],
        ];

        foreach ($categories as $cat) {
            BlogCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}