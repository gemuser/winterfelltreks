<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'site_name', 'group' => 'branding', 'type' => 'text', 'value' => 'Winterfell Treks & Expedition'],
            ['key' => 'site_tagline', 'group' => 'branding', 'type' => 'text', 'value' => 'Premier High-Altitude Mountaineering & Wilderness Expeditions'],
            ['key' => 'founder_name', 'group' => 'branding', 'type' => 'text', 'value' => 'Mr. Asmin Bhattarai'],
            ['key' => 'established_year_bs', 'group' => 'branding', 'type' => 'text', 'value' => '2083'],
            ['key' => 'established_year_ad', 'group' => 'branding', 'type' => 'text', 'value' => '2026'],
            ['key' => 'logo_light', 'group' => 'branding', 'type' => 'image', 'value' => json_encode(['url' => '/images/winterfell-nobg.png', 'alt' => 'Winterfell Treks & Expedition'])],
            ['key' => 'logo_dark', 'group' => 'branding', 'type' => 'image', 'value' => json_encode(['url' => '/images/winterfell-nobg.png', 'alt' => 'Winterfell Treks & Expedition'])],
            ['key' => 'favicon', 'group' => 'branding', 'type' => 'image', 'value' => json_encode(['url' => '/favicon.ico', 'alt' => 'Winterfell Favicon'])],

            // Contact
            ['key' => 'phone_primary', 'group' => 'contact', 'type' => 'text', 'value' => '+977-1-4000000'],
            ['key' => 'phone_emergency', 'group' => 'contact', 'type' => 'text', 'value' => '+977-9800000000'],
            ['key' => 'email_primary', 'group' => 'contact', 'type' => 'text', 'value' => 'info@winterfelltreks.com'],
            ['key' => 'email_enquiries', 'group' => 'contact', 'type' => 'text', 'value' => 'enquiries@winterfelltreks.com'],
            ['key' => 'office_address', 'group' => 'contact', 'type' => 'textarea', 'value' => 'Thamel, Kathmandu, Bagmati Province, Nepal'],
            ['key' => 'business_hours', 'group' => 'contact', 'type' => 'text', 'value' => 'Mon-Sat: 9:00 AM - 6:00 PM (NPT)'],
            ['key' => 'whatsapp_number', 'group' => 'contact', 'type' => 'text', 'value' => '9779800000000'],
            ['key' => 'map_embed_url', 'group' => 'contact', 'type' => 'text', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.000000000000!2d85.31000000000001!3d27.71000000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb198a00000000%3A0x0!2sThamel%2C%20Kathmandu!5e0!3m2!1sen!2snp!4v1000000000000!5m2!1sen!2snp'],

            // Social
            ['key' => 'facebook_url', 'group' => 'social', 'type' => 'text', 'value' => 'https://facebook.com/winterfelltreks'],
            ['key' => 'instagram_url', 'group' => 'social', 'type' => 'text', 'value' => 'https://instagram.com/winterfelltreks'],
            ['key' => 'youtube_url', 'group' => 'social', 'type' => 'text', 'value' => 'https://youtube.com/@winterfelltreks'],
            ['key' => 'tiktok_url', 'group' => 'social', 'type' => 'text', 'value' => 'https://tiktok.com/@winterfelltreks'],

            // SEO Global
            ['key' => 'default_meta_title', 'group' => 'seo', 'type' => 'text', 'value' => 'Winterfell Treks & Expedition | Premier Himalayan Mountaineering'],
            ['key' => 'default_meta_description', 'group' => 'seo', 'type' => 'textarea', 'value' => 'Discover extraordinary Himalayan expeditions with Winterfell Treks. Expert-guided mountaineering, trekking, and wilderness adventures in Nepal. Founded by Mr. Asmin Bhattarai in 2083 BS.'],
            ['key' => 'default_meta_keywords', 'group' => 'seo', 'type' => 'text', 'value' => 'Himalayan trekking, mountaineering expeditions, Nepal adventure travel, high-altitude climbing, Winterfell Treks'],
            ['key' => 'default_og_image', 'group' => 'seo', 'type' => 'image', 'value' => json_encode(['url' => '/images/og-default.jpg', 'alt' => 'Winterfell Treks & Expedition'])],
            ['key' => 'google_analytics_id', 'group' => 'seo', 'type' => 'text', 'value' => ''],
            ['key' => 'google_site_verification', 'group' => 'seo', 'type' => 'text', 'value' => ''],

            // Footer
            ['key' => 'copyright_text', 'group' => 'footer', 'type' => 'text', 'value' => '© 2083 BS / 2026 AD Winterfell Treks & Expedition. All rights reserved.'],
            ['key' => 'privacy_policy_url', 'group' => 'footer', 'type' => 'text', 'value' => '/privacy-policy'],
            ['key' => 'terms_url', 'group' => 'footer', 'type' => 'text', 'value' => '/terms-and-conditions'],
            ['key' => 'cookie_policy_url', 'group' => 'footer', 'type' => 'text', 'value' => '/cookie-policy'],

            // Enquiry
            ['key' => 'enquiry_notification_email', 'group' => 'enquiry', 'type' => 'text', 'value' => 'enquiries@winterfelltreks.com'],
            ['key' => 'enquiry_auto_reply_enabled', 'group' => 'enquiry', 'type' => 'toggle', 'value' => true],
            ['key' => 'enquiry_auto_reply_subject', 'group' => 'enquiry', 'type' => 'text', 'value' => 'We received your enquiry - Winterfell Treks & Expedition'],
            ['key' => 'enquiry_auto_reply_body', 'group' => 'enquiry', 'type' => 'textarea', 'value' => 'Thank you for contacting Winterfell Treks & Expedition. We have received your enquiry (Reference: {reference_number}) and our team will contact you within 24 hours.'],

            // Navigation
            ['key' => 'navbar_enquiry_button_text', 'group' => 'navigation', 'type' => 'text', 'value' => 'Enquire Now'],
            ['key' => 'mobile_menu_label', 'group' => 'navigation', 'type' => 'text', 'value' => 'Menu'],

            // Hero Defaults
            ['key' => 'hero_heading', 'group' => 'hero', 'type' => 'text', 'value' => 'Discover Extraordinary Destinations'],
            ['key' => 'hero_subheading', 'group' => 'hero', 'type' => 'textarea', 'value' => 'Explore unforgettable destinations, discover unique experiences and plan your next journey with our travel specialists.'],
            ['key' => 'hero_primary_cta_text', 'group' => 'hero', 'type' => 'text', 'value' => 'Explore Destinations'],
            ['key' => 'hero_primary_cta_url', 'group' => 'hero', 'type' => 'text', 'value' => '/destinations'],
            ['key' => 'hero_secondary_cta_text', 'group' => 'hero', 'type' => 'text', 'value' => 'Discover Packages'],
            ['key' => 'hero_secondary_cta_url', 'group' => 'hero', 'type' => 'text', 'value' => '/packages'],
            ['key' => 'hero_background_image', 'group' => 'hero', 'type' => 'image', 'value' => json_encode(['url' => '/images/hero-everest.jpg', 'alt' => 'Mount Everest sunrise from Kala Patthar'])],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}