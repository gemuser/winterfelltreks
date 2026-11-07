<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            UserSeeder::class,
            PageSeeder::class,
            DestinationSeeder::class,
            ExpeditionSeeder::class,
            ExperienceSeeder::class,
            BlogCategorySeeder::class,
            BlogPostSeeder::class,
            GalleryCategorySeeder::class,
            GalleryImageSeeder::class,
            TestimonialSeeder::class,
            FAQSeeder::class,
        ]);
    }
}