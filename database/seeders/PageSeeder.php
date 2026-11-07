<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionElement;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // HOMEPAGE
        // =============================================
        $homepage = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'layout_type' => 'home',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Home',
                'navbar_order' => 1,
                'meta_title' => 'Winterfell Treks & Expedition | Premier Himalayan Mountaineering',
                'meta_description' => 'Discover extraordinary Himalayan expeditions with Winterfell Treks. Expert-guided mountaineering, trekking, and wilderness adventures in Nepal. Founded by Mr. Asmin Bhattarai in 2083 BS.',
                'meta_keywords' => 'Himalayan trekking, mountaineering expeditions, Nepal adventure travel, high-altitude climbing',
            ]
        );

        $this->seedHomepageSections($homepage);

        // =============================================
        // DESTINATIONS
        // =============================================
        $destinations = Page::updateOrCreate(
            ['slug' => 'destinations'],
            [
                'title' => 'Destinations',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Destinations',
                'navbar_order' => 2,
                'meta_title' => 'Explore Himalayan Destinations | Winterfell Treks',
                'meta_description' => 'Discover breathtaking Himalayan destinations covered by Winterfell Treks. From Everest to Annapurna, explore mountains, valleys, and cultural sites.',
            ]
        );
        $this->seedDestinationsSections($destinations);

        // =============================================
        // TOUR PACKAGES
        // =============================================
        $packages = Page::updateOrCreate(
            ['slug' => 'packages'],
            [
                'title' => 'Tour Packages',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Tour Packages',
                'navbar_order' => 3,
                'meta_title' => 'Himalayan Tour Packages | Winterfell Treks & Expedition',
                'meta_description' => 'Explore curated tour packages and expeditions. From Everest Base Camp to Annapurna Circuit, find your perfect Himalayan adventure.',
            ]
        );
        $this->seedPackagesSections($packages);

        // =============================================
        // EXPERIENCES
        // =============================================
        $experiences = Page::updateOrCreate(
            ['slug' => 'experiences'],
            [
                'title' => 'Experiences',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Experiences',
                'navbar_order' => 4,
                'meta_title' => 'Himalayan Travel Experiences | Winterfell Treks',
                'meta_description' => 'Discover unique travel experiences in the Himalayas. Cultural immersion, adventure activities, wildlife encounters, and more.',
            ]
        );
        $this->seedExperiencesSections($experiences);

        // =============================================
        // ABOUT US
        // =============================================
        $about = Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'About Us',
                'navbar_order' => 5,
                'meta_title' => 'About Winterfell Treks & Expedition | Our Story',
                'meta_description' => 'Learn about Winterfell Treks & Expedition, founded by Mr. Asmin Bhattarai in 2083 BS. Our mission, vision, values, and team.',
            ]
        );
        $this->seedAboutSections($about);

        // =============================================
        // TRAVEL GUIDE
        // =============================================
        $travelGuide = Page::updateOrCreate(
            ['slug' => 'travel-guide'],
            [
                'title' => 'Travel Guide',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Travel Guide',
                'navbar_order' => 6,
                'meta_title' => 'Himalayan Travel Guide & Articles | Winterfell Treks',
                'meta_description' => 'Expert travel guides, tips, and articles for Himalayan adventures. Destination guides, packing lists, altitude advice, and cultural insights.',
            ]
        );
        $this->seedTravelGuideSections($travelGuide);

        // =============================================
        // GALLERY
        // =============================================
        $gallery = Page::updateOrCreate(
            ['slug' => 'gallery'],
            [
                'title' => 'Gallery',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Gallery',
                'navbar_order' => 7,
                'meta_title' => 'Himalayan Photography Gallery | Winterfell Treks',
                'meta_description' => 'Stunning photography from our Himalayan expeditions. Mountains, landscapes, culture, wildlife, and adventure moments captured.',
            ]
        );
        $this->seedGallerySections($gallery);

        // =============================================
        // CONTACT US
        // =============================================
        $contact = Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => true,
                'navbar_title' => 'Contact',
                'navbar_order' => 8,
                'meta_title' => 'Contact Winterfell Treks & Expedition',
                'meta_description' => 'Get in touch with Winterfell Treks. Phone, email, office address, WhatsApp, and contact form for your Himalayan adventure enquiries.',
            ]
        );
        $this->seedContactSections($contact);

        // =============================================
        // GENERAL ENQUIRY
        // =============================================
        $enquiry = Page::updateOrCreate(
            ['slug' => 'enquiry'],
            [
                'title' => 'Enquiry',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => false,
                'meta_title' => 'Plan Your Journey | Enquiry Form',
                'meta_description' => 'Submit your travel enquiry to Winterfell Treks. Tell us about your preferences and our specialists will create a customized itinerary.',
            ]
        );
        $this->seedEnquirySections($enquiry);

        // =============================================
        // FAQ
        // =============================================
        $faq = Page::updateOrCreate(
            ['slug' => 'faq'],
            [
                'title' => 'FAQ',
                'layout_type' => 'standard',
                'is_published' => true,
                'show_in_navbar' => false,
                'show_in_footer' => true,
                'footer_column' => 2,
                'footer_order' => 1,
                'meta_title' => 'Frequently Asked Questions | Winterfell Treks',
                'meta_description' => 'Answers to common questions about Himalayan trekking, expeditions, bookings, permits, gear, and travel preparation.',
            ]
        );
        $this->seedFAQSections($faq);

        // =============================================
        // PRIVACY POLICY
        // =============================================
        $privacy = Page::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'layout_type' => 'legal',
                'is_published' => true,
                'show_in_navbar' => false,
                'show_in_footer' => true,
                'footer_column' => 4,
                'footer_order' => 1,
                'meta_title' => 'Privacy Policy | Winterfell Treks',
            ]
        );
        $this->seedLegalSections($privacy, 'privacy');

        // =============================================
        // TERMS AND CONDITIONS
        // =============================================
        $terms = Page::updateOrCreate(
            ['slug' => 'terms-and-conditions'],
            [
                'title' => 'Terms and Conditions',
                'layout_type' => 'legal',
                'is_published' => true,
                'show_in_navbar' => false,
                'show_in_footer' => true,
                'footer_column' => 4,
                'footer_order' => 2,
                'meta_title' => 'Terms & Conditions | Winterfell Treks',
            ]
        );
        $this->seedLegalSections($terms, 'terms');

        // =============================================
        // 404 PAGE (not in DB, handled by view)
        // =============================================
    }

    private function createSection(Page $page, string $type, int $order, array $settings = []): PageSection
    {
        return PageSection::updateOrCreate(
            ['page_id' => $page->id, 'section_type' => $type, 'sort_order' => $order],
            [
                'page_id' => $page->id,
                'section_type' => $type,
                'sort_order' => $order,
                'is_visible' => true,
                'settings' => $settings,
            ]
        );
    }

    private function createElement(PageSection $section, string $key, string $type, mixed $content, int $order = 0): void
    {
        SectionElement::updateOrCreate(
            ['section_id' => $section->id, 'element_key' => $key],
            [
                'section_id' => $section->id,
                'element_key' => $key,
                'element_type' => $type,
                'content' => $content,
                'sort_order' => $order,
            ]
        );
    }

    private function seedHomepageSections(Page $page): void
    {
        // Hero Section
        $hero = $this->createSection($page, 'hero_cinematic', 1, [
            'background_type' => 'image',
            'overlay_opacity' => 0.4,
            'content_alignment' => 'center',
            'animation' => 'fade-up',
        ]);
        $this->createElement($hero, 'headline', 'text', 'Discover Extraordinary Destinations');
        $this->createElement($hero, 'subheadline', 'richtext', '<p>Explore unforgettable destinations, discover unique experiences and plan your next journey with our travel specialists.</p>');
        $this->createElement($hero, 'primary_cta', 'link', json_encode(['text' => 'Explore Destinations', 'url' => '/destinations', 'style' => 'primary']));
        $this->createElement($hero, 'secondary_cta', 'link', json_encode(['text' => 'Discover Packages', 'url' => '/packages', 'style' => 'secondary']));
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/hero-everest.jpg', 'alt' => 'Mount Everest sunrise from Kala Patthar']));

        // Company Introduction
        $intro = $this->createSection($page, 'editorial_split', 2, ['layout' => 'text-left-image-right']);
        $this->createElement($intro, 'heading', 'text', 'Winterfell Treks & Expedition');
        $this->createElement($intro, 'subheading', 'text', 'Founded in 2083 BS by Mr. Asmin Bhattarai');
        $this->createElement($intro, 'body', 'richtext', '<p>Winterfell Treks & Expedition is a premier high-altitude mountaineering and wilderness expedition agency based in Kathmandu, Nepal. We specialize in curated Himalayan adventures — from iconic treks like Everest Base Camp and Annapurna Circuit to technical mountaineering expeditions on 8000-meter peaks.</p><p>Our team of IFMGA-certified guides, experienced Sherpas, and local experts ensure every journey is safe, authentic, and deeply connected to the land and its people.</p>');
        $this->createElement($intro, 'image', 'image', json_encode(['url' => '/images/about-founder.jpg', 'alt' => 'Mr. Asmin Bhattarai in the Himalayas']));
        $this->createElement($intro, 'cta', 'link', json_encode(['text' => 'Read Our Story', 'url' => '/about', 'style' => 'primary']));

        // Featured Destinations
        $featuredDest = $this->createSection($page, 'destination_grid', 3, ['columns' => 3, 'show_view_all' => true]);
        $this->createElement($featuredDest, 'heading', 'text', 'Featured Destinations');
        $this->createElement($featuredDest, 'subheading', 'text', 'Handpicked Himalayan destinations for your next adventure');
        $this->createElement($featuredDest, 'view_all_text', 'text', 'View All Destinations');
        $this->createElement($featuredDest, 'view_all_url', 'text', '/destinations');
        $this->createElement($featuredDest, 'destinations', 'repeater', json_encode([
            ['name' => 'Everest Region', 'region' => 'Khumbu, Nepal', 'description' => 'Home to the world\'s highest peak and legendary Sherpa culture', 'image' => '/images/dest-everest.jpg', 'slug' => 'everest-region'],
            ['name' => 'Annapurna Region', 'region' => 'Gandaki, Nepal', 'description' => 'Diverse landscapes from subtropical valleys to high alpine deserts', 'image' => '/images/dest-annapurna.jpg', 'slug' => 'annapurna-region'],
            ['name' => 'Langtang Valley', 'region' => 'Bagmati, Nepal', 'description' => 'The valley of glaciers, rich in Tamang heritage and biodiversity', 'image' => '/images/dest-langtang.jpg', 'slug' => 'langtang-valley'],
        ]));

        // Featured Tour Packages
        $featuredPkg = $this->createSection($page, 'package_grid', 4, ['columns' => 3, 'show_view_all' => true]);
        $this->createElement($featuredPkg, 'heading', 'text', 'Featured Expeditions');
        $this->createElement($featuredPkg, 'subheading', 'text', 'Curated adventures for every level of experience');
        $this->createElement($featuredPkg, 'view_all_text', 'text', 'View All Packages');
        $this->createElement($featuredPkg, 'view_all_url', 'text', '/packages');
        $this->createElement($featuredPkg, 'packages', 'repeater', json_encode([
            ['name' => 'Everest Base Camp Trek', 'destination' => 'Everest Region', 'duration' => '14 Days', 'description' => 'Classic trek to the foot of the world\'s highest mountain', 'price' => 'Contact for pricing', 'image' => '/images/pkg-ebc.jpg', 'slug' => 'everest-base-camp'],
            ['name' => 'Annapurna Circuit Trek', 'destination' => 'Annapurna Region', 'duration' => '16 Days', 'description' => 'Circumnavigate the Annapurna massif through diverse landscapes', 'price' => 'Contact for pricing', 'image' => '/images/pkg-annapurna.jpg', 'slug' => 'annapurna-circuit'],
            ['name' => 'Island Peak Climbing', 'destination' => 'Everest Region', 'duration' => '18 Days', 'description' => 'Technical climbing introduction on a 6,189m Himalayan peak', 'price' => 'Contact for pricing', 'image' => '/images/pkg-island.jpg', 'slug' => 'island-peak'],
        ]));

        // Experiences
        $exp = $this->createSection($page, 'experience_grid', 5, ['columns' => 3]);
        $this->createElement($exp, 'heading', 'text', 'Travel Experiences');
        $this->createElement($exp, 'subheading', 'text', 'Immerse yourself in the Himalayan way of life');
        $this->createElement($exp, 'experiences', 'repeater', json_encode([
            ['name' => 'Sherpa Culture Immersion', 'description' => 'Live with Sherpa families, learn traditions, visit monasteries', 'image' => '/images/exp-sherpa.jpg', 'slug' => 'sherpa-culture'],
            ['name' => 'High-Altitude Photography', 'description' => 'Capture stunning landscapes with professional guidance', 'image' => '/images/exp-photo.jpg', 'slug' => 'photography'],
            ['name' => 'Wildlife & Birdwatching', 'description' => 'Spot snow leopards, red pandas, and Himalayan birds', 'image' => '/images/exp-wildlife.jpg', 'slug' => 'wildlife'],
        ]));

        // Why Choose Us
        $why = $this->createSection($page, 'feature_grid', 6, ['columns' => 3]);
        $this->createElement($why, 'heading', 'text', 'Why Choose Winterfell');
        $this->createElement($why, 'features', 'repeater', json_encode([
            ['icon' => 'mountain', 'title' => 'Local Expertise', 'description' => 'Born and raised in the Himalayas, we know every trail, village, and hidden valley'],
            ['icon' => 'shield', 'title' => 'Safety First', 'description' => 'IFMGA guides, comprehensive risk management, satellite communication on all expeditions'],
            ['icon' => 'users', 'title' => 'Custom Itineraries', 'description' => 'No fixed departures — every journey tailored to your goals, fitness, and timeline'],
        ]));

        // Gallery Preview
        $gallery = $this->createSection($page, 'gallery_masonry', 7, ['columns' => 4, 'show_view_all' => true]);
        $this->createElement($gallery, 'heading', 'text', 'From the Field');
        $this->createElement($gallery, 'view_all_text', 'text', 'View Full Gallery');
        $this->createElement($gallery, 'view_all_url', 'text', '/gallery');
        $this->createElement($gallery, 'images', 'repeater', json_encode([
            ['url' => '/images/gal-1.jpg', 'alt' => 'Everest sunrise', 'location' => 'Kala Patthar'],
            ['url' => '/images/gal-2.jpg', 'alt' => 'Annapurna range', 'location' => 'Poon Hill'],
            ['url' => '/images/gal-3.jpg', 'alt' => 'Monastery in Tengboche', 'location' => 'Tengboche'],
            ['url' => '/images/gal-4.jpg', 'alt' => 'Sherpa porter on trail', 'location' => 'Khumbu'],
            ['url' => '/images/gal-5.jpg', 'alt' => 'Glacial lake Gokyo', 'location' => 'Gokyo Ri'],
            ['url' => '/images/gal-6.jpg', 'alt' => 'Prayer flags at Thorong La', 'location' => 'Thorong La Pass'],
        ]));

        // Testimonials (if available)
        $testimonials = $this->createSection($page, 'testimonial_slider', 8, ['autoplay' => true]);
        $this->createElement($testimonials, 'heading', 'text', 'Voices from the Mountains');
        $this->createElement($testimonials, 'testimonials', 'repeater', json_encode([]));

        // Travel Journal Preview
        $journal = $this->createSection($page, 'article_grid', 9, ['columns' => 3, 'show_view_all' => true]);
        $this->createElement($journal, 'heading', 'text', 'Travel Journal');
        $this->createElement($journal, 'subheading', 'text', 'Stories, guides, and insights from the Himalayas');
        $this->createElement($journal, 'view_all_text', 'text', 'Read All Articles');
        $this->createElement($journal, 'view_all_url', 'text', '/travel-guide');
        $this->createElement($journal, 'articles', 'repeater', json_encode([]));

        // Enquiry CTA
        $cta = $this->createSection($page, 'cta_banner', 10, ['style' => 'minimal', 'background' => 'dark']);
        $this->createElement($cta, 'heading', 'text', 'Planning Your Next Journey?');
        $this->createElement($cta, 'body', 'richtext', '<p>Tell us about your dream Himalayan adventure. Our specialists will craft a personalized itinerary and provide a detailed quotation within 24 hours.</p>');
        $this->createElement($cta, 'cta_text', 'text', 'Start Your Enquiry');
        $this->createElement($cta, 'cta_url', 'text', '/enquiry');
    }

    private function seedDestinationsSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'Explore Destinations');
        $this->createElement($hero, 'subtitle', 'text', 'Discover the Himalayan regions we call home');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/dest-hero.jpg', 'alt' => 'Himalayan mountain range']));

        $listing = $this->createSection($page, 'destination_listing', 2, ['columns' => 3, 'show_search' => true, 'show_filters' => true]);
        $this->createElement($listing, 'search_placeholder', 'text', 'Search destinations...');
    }

    private function seedPackagesSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'Tour Packages');
        $this->createElement($hero, 'subtitle', 'text', 'Curated Himalayan expeditions and treks for every adventurer');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/pkg-hero.jpg', 'alt' => 'Trekking in the Himalayas']));

        $listing = $this->createSection($page, 'package_listing', 2, ['columns' => 3, 'show_search' => true, 'show_filters' => true]);
        $this->createElement($listing, 'search_placeholder', 'text', 'Search packages...');
    }

    private function seedExperiencesSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'Travel Experiences');
        $this->createElement($hero, 'subtitle', 'text', 'Immerse yourself in the culture, nature, and adventure of the Himalayas');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/exp-hero.jpg', 'alt' => 'Cultural experience in Himalayan village']));

        $listing = $this->createSection($page, 'experience_listing', 2, ['columns' => 3, 'show_filters' => true]);
    }

    private function seedAboutSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'About Us');
        $this->createElement($hero, 'subtitle', 'text', 'Founded in 2083 BS by Mr. Asmin Bhattarai');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/about-hero.jpg', 'alt' => 'Winterfell team in the mountains']));

        $intro = $this->createSection($page, 'editorial_split', 2, ['layout' => 'text-left-image-right']);
        $this->createElement($intro, 'heading', 'text', 'Our Story');
        $this->createElement($intro, 'body', 'richtext', '<p>Winterfell Treks & Expedition was founded in 2083 BS (2026 AD) by Mr. Asmin Bhattarai with a vision to share the raw beauty and profound spirituality of the Nepalese Himalayas with the world.</p><p>Born and raised in the shadow of the mountains, Asmin understood that true Himalayan experiences cannot be packaged — they must be curated with deep local knowledge, genuine respect for the land and its people, and an unwavering commitment to safety.</p><p>Today, we operate as a boutique expedition agency, deliberately small to maintain the personal touch and flexibility that mass-market operators cannot provide. Every itinerary is designed from scratch, every guide is hand-selected, and every guest becomes part of our extended mountain family.</p>');
        $this->createElement($intro, 'image', 'image', json_encode(['url' => '/images/about-story.jpg', 'alt' => 'Mr. Asmin Bhattarai']));

        $mission = $this->createSection($page, 'mission_vision', 3);
        $this->createElement($mission, 'mission_title', 'text', 'Our Mission');
        $this->createElement($mission, 'mission_text', 'richtext', '<p>To deliver authentic, safe, and transformative Himalayan experiences that honor the mountains, empower local communities, and inspire a deeper connection with the natural world.</p>');
        $this->createElement($mission, 'vision_title', 'text', 'Our Vision');
        $this->createElement($mission, 'vision_text', 'richtext', '<p>To be the most trusted name in Himalayan mountaineering and trekking — recognized for excellence in guiding, leadership in sustainable tourism, and dedication to preserving the cultural and environmental integrity of the regions we explore.</p>');

        $values = $this->createSection($page, 'values_grid', 4, ['columns' => 3]);
        $this->createElement($values, 'heading', 'text', 'Our Values');
        $this->createElement($values, 'values', 'repeater', json_encode([
            ['title' => 'Responsible Tourism', 'description' => 'Leave no trace, support local economies, respect cultural traditions'],
            ['title' => 'Professional Excellence', 'description' => 'IFMGA-certified guides, continuous training, international safety standards'],
            ['title' => 'Cultural Respect', 'description' => 'Authentic engagement with Sherpa, Tamang, Gurung, and Thakali communities'],
            ['title' => 'Transparency', 'description' => 'Honest pricing, clear inclusions/exclusions, no hidden costs'],
            ['title' => 'Sustainable Travel', 'description' => 'Carbon-conscious operations, community development projects, conservation partnerships'],
            ['title' => 'Personalized Service', 'description' => 'Small groups, custom itineraries, 24/7 support from enquiry to return'],
        ]));

        $team = $this->createSection($page, 'team_grid', 5, ['columns' => 3]);
        $this->createElement($team, 'heading', 'text', 'Leadership');
        $this->createElement($team, 'team', 'repeater', json_encode([
            ['name' => 'Asmin Bhattarai', 'position' => 'Founder & CEO', 'bio' => 'Born in the Himalayas, Asmin has over 15 years of high-altitude guiding experience. IFMGA aspirant, multiple 8000m summits.', 'image' => '/images/team-asmin.jpg'],
        ]));

        $cta = $this->createSection($page, 'cta_banner', 6, ['style' => 'dual']);
        $this->createElement($cta, 'heading', 'text', 'Ready to Explore?');
        $this->createElement($cta, 'primary_cta', 'link', json_encode(['text' => 'Explore Our Packages', 'url' => '/packages', 'style' => 'primary']));
        $this->createElement($cta, 'secondary_cta', 'link', json_encode(['text' => 'Contact Our Team', 'url' => '/contact', 'style' => 'secondary']));
    }

    private function seedTravelGuideSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'Travel Guide');
        $this->createElement($hero, 'subtitle', 'text', 'Expert knowledge for your Himalayan journey');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/blog-hero.jpg', 'alt' => 'Reading a map on a mountain trail']));

        $featured = $this->createSection($page, 'featured_article', 2);
        $this->createElement($featured, 'heading', 'text', 'Featured Guide');
        $this->createElement($featured, 'article', 'json', json_encode([]));

        $listing = $this->createSection($page, 'article_listing', 3, ['columns' => 3, 'show_search' => true, 'show_categories' => true]);
        $this->createElement($listing, 'search_placeholder', 'text', 'Search articles...');
    }

    private function seedGallerySections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'color']);
        $this->createElement($hero, 'title', 'text', 'Gallery');
        $this->createElement($hero, 'subtitle', 'text', 'Moments captured from the roof of the world');

        $grid = $this->createSection($page, 'gallery_masonry', 2, ['columns' => 4, 'show_categories' => true, 'lightbox' => true]);
    }

    private function seedContactSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'Contact Us');
        $this->createElement($hero, 'subtitle', 'text', 'We\'d love to hear about your Himalayan dreams');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/contact-hero.jpg', 'alt' => 'Winterfell office in Kathmandu']));

        $info = $this->createSection($page, 'contact_info', 2, ['layout' => 'grid']);
        $this->createElement($info, 'heading', 'text', 'Get in Touch');

        $form = $this->createSection($page, 'contact_form', 3);
        $this->createElement($form, 'heading', 'text', 'Send a Message');

        $map = $this->createSection($page, 'google_map', 4);
        $this->createElement($map, 'heading', 'text', 'Our Location');
    }

    private function seedEnquirySections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'image']);
        $this->createElement($hero, 'title', 'text', 'Plan Your Next Journey');
        $this->createElement($hero, 'subtitle', 'text', 'Tell us about your travel preferences and requirements. Our team will contact you to discuss suitable travel options.');
        $this->createElement($hero, 'background_image', 'image', json_encode(['url' => '/images/enquiry-hero.jpg', 'alt' => 'Planning a Himalayan expedition']));

        $form = $this->createSection($page, 'enquiry_form', 2, ['type' => 'general']);
        $this->createElement($form, 'heading', 'text', 'Enquiry Form');
    }

    private function seedFAQSections(Page $page): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'color']);
        $this->createElement($hero, 'title', 'text', 'Frequently Asked Questions');
        $this->createElement($hero, 'subtitle', 'text', 'Quick answers to common questions');

        $faq = $this->createSection($page, 'faq_accordion', 2, ['categories' => ['General', 'Packages', 'Enquiries', 'Travel']]);
    }

    private function seedLegalSections(Page $page, string $type): void
    {
        $hero = $this->createSection($page, 'page_hero', 1, ['background_type' => 'color', 'minimal' => true]);
        $title = $type === 'privacy' ? 'Privacy Policy' : 'Terms and Conditions';
        $this->createElement($hero, 'title', 'text', $title);

        $content = $this->createSection($page, 'legal_content', 2);
        $this->createElement($content, 'body', 'richtext', $type === 'privacy' ? $this->getPrivacyContent() : $this->getTermsContent());
    }

    private function getPrivacyContent(): string
    {
        return '<h2>1. Introduction</h2>
<p>Winterfell Treks & Expedition ("we", "our", "us") is committed to protecting your personal information and your right to privacy.</p>

<h2>2. Information We Collect</h2>
<h3>Personal Information</h3>
<p>We collect information you provide directly when you submit an enquiry, contact us, or communicate with our team.</p>
<h3>Enquiry Form Information</h3>
<p>Name, email, phone, country, travel preferences, dates, group size, and any special requirements.</p>

<h2>3. Purpose of Data Collection</h2>
<p>We use your information to respond to enquiries, provide quotations, plan itineraries, and communicate about your travel arrangements.</p>

<h2>4. Data Storage and Security</h2>
<p>Your data is stored securely on our servers and retained only as long as necessary for the purposes outlined.</p>

<h2>5. Data Sharing</h2>
<p>We do not sell your data. We may share information with trusted local partners (guides, accommodation providers) only as necessary to deliver your travel experience.</p>

<h2>6. Cookies and Tracking</h2>
<p>Our website uses essential cookies for functionality and analytics cookies to improve user experience.</p>

<h2>7. Your Rights</h2>
<p>You have the right to access, correct, delete, or restrict processing of your personal data. Contact us at <a href="mailto:privacy@winterfelltreks.com">privacy@winterfelltreks.com</a>.</p>

<h2>8. Contact</h2>
<p>For privacy concerns, email <a href="mailto:privacy@winterfelltreks.com">privacy@winterfelltreks.com</a> or write to our office address.';
    }

    private function getTermsContent(): string
    {
        return '<h2>1. Introduction</h2>
<p>These Terms and Conditions govern your use of the Winterfell Treks & Expedition website and services.</p>

<h2>2. Website Usage</h2>
<p>This website is for information and enquiry purposes only. No booking or payment functionality is provided.</p>

<h2>3. Information Accuracy</h2>
<p>We strive for accuracy but cannot guarantee all information is current. Destination conditions, prices, and availability change.</p>

<h2>4. Destination Information</h2>
<p>Destination descriptions are based on typical conditions. Weather, trail conditions, and accessibility vary by season.</p>

<h2>5. Tour Package Information</h2>
<p>Package details, itineraries, and inclusions are representative. Final arrangements are confirmed upon enquiry and quotation.</p>

<h2>6. Enquiry Submission</h2>
<p>Submitting an enquiry does not constitute a booking, contract, or guarantee of availability. It initiates a conversation with our team.</p>

<h2>7. Quotations</h2>
<p>Quotations are valid for 30 days. Prices may change due to permit fees, exchange rates, or operational factors.</p>

<h2>8. Changes to Travel Arrangements</h2>
<p>Itineraries may change due to weather, safety, permit restrictions, or force majeure. We prioritize your safety above schedule adherence.</p>

<h2>9. Third-Party Services</h2>
<p>We work with trusted local partners. We are not liable for their independent actions or service failures.</p>

<h2>10. Intellectual Property</h2>
<p>All content, images, and branding are property of Winterfell Treks & Expedition. Unauthorized use is prohibited.</p>

<h2>11. Limitation of Liability</h2>
<p>We are not liable for indirect, consequential, or punitive damages arising from website use or travel arrangements.</p>

<h2>12. Governing Law</h2>
<p>These terms are governed by the laws of Nepal. Disputes resolved in Kathmandu courts.</p>

<h2>13. Contact</h2>
<p>For questions about these terms, contact <a href="mailto:legal@winterfelltreks.com">legal@winterfelltreks.com</a>.</p>

<p><strong>Important:</strong> Submitting an enquiry does not constitute a confirmed booking or contractual acceptance of a travel package. All bookings require a separate agreement and deposit.';
    }
}