<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'customer_name' => 'Sarah Mitchell',
                'customer_photo' => '/images/testimonial-sarah.jpg',
                'testimonial_text' => 'Winterfell didn\'t just take us to Everest Base Camp — they curated an experience that changed how I see the mountains. Our guide Pemba knew every tea house owner, every side trail, every weather pattern. The acclimatization schedule was perfect. I felt safe, challenged, and deeply connected to the Khumbu.',
                'destination_or_package' => 'Everest Base Camp Trek',
                'rating' => 5,
                'is_verified' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'customer_name' => 'James & Lisa Chen',
                'customer_photo' => '/images/testimonial-chen.jpg',
                'testimonial_text' => 'We did the Annapurna Circuit with our teenage kids. Asmin personally designed an itinerary with shorter days, extra rest, and cultural activities the kids loved — archery in Manang, monastery visit in Braga, apple picking in Marpha. The whole team treated our family like their own.',
                'destination_or_package' => 'Annapurna Circuit (Family)',
                'rating' => 5,
                'is_verified' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'customer_name' => 'Dr. Michael Torres',
                'customer_photo' => '/images/testimonial-michael.jpg',
                'testimonial_text' => 'Island Peak was my first 6000m peak. The pre-climb training at Base Camp was thorough — rope skills, crevasse rescue, ice climbing basics. Summit day was long and hard, but our climbing guide Tenzing was calm, encouraging, and technically flawless. Standing on that summit at sunrise... indescribable.',
                'destination_or_package' => 'Island Peak Climbing',
                'rating' => 5,
                'is_verified' => true,
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'customer_name' => 'Emma Kowalski',
                'customer_photo' => '/images/testimonial-emma.jpg',
                'testimonial_text' => 'Upper Mustang in monsoon — best decision ever. While everyone else was dealing with monsoon clouds in Annapurna, we had crystal clear skies in the rain shadow. The Tiji Festival was a once-in-a-lifetime cultural immersion. Winterfell\'s permits and logistics were seamless for a restricted area.',
                'destination_or_package' => 'Upper Mustang Trek',
                'rating' => 5,
                'is_verified' => true,
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'customer_name' => 'Rajesh & Priya Sharma',
                'customer_photo' => '/images/testimonial-sharma.jpg',
                'testimonial_text' => 'As Indians, we appreciated that Winterfell understood our dietary needs (pure veg, no onion/garlic options) without us having to explain repeatedly. The team accommodated our parents\' pace on the Langtang trek. It felt like traveling with family who happen to be expert mountaineers.',
                'destination_or_package' => 'Langtang Valley Trek',
                'rating' => 5,
                'is_verified' => true,
                'is_published' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['customer_name' => $t['customer_name'], 'destination_or_package' => $t['destination_or_package']],
                $t
            );
        }
    }
}