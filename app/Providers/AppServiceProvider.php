<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            static $cachedGlobals = null;
            static $cachedSeo = null;

            if ($cachedGlobals === null) {
                try {
                    if (Schema::hasTable('site_settings') && Schema::hasTable('pages')) {
                        $settings = SiteSetting::all()->pluck('value', 'key');
                        $footerPages = Page::inFooter()->get(['id', 'title', 'slug', 'footer_column']);

                        $cachedGlobals = [
                            'site_name' => $settings->get('site_name', 'Winterfell Treks & Expedition'),
                            'site_tagline' => $settings->get('site_tagline', 'Himalayan Expeditions & Wilderness Adventures'),
                            'logo' => '/images/winterfell-nobg.png',
                            'founder_name' => $settings->get('founder_name', 'Mr. Asmin Bhattarai'),
                            'established_year_bs' => $settings->get('established_year_bs', '2083'),
                            'navbar_pages' => Page::inNavbar()->get(['id', 'title', 'slug', 'navbar_title']),
                            'sidebar_pages' => Page::inSidebar()->get(['id', 'title', 'slug', 'sidebar_title']),
                            'footer_columns' => [
                                1 => $footerPages->where('footer_column', 1)->values(),
                                2 => $footerPages->where('footer_column', 2)->values(),
                                3 => $footerPages->where('footer_column', 3)->values(),
                                4 => $footerPages->where('footer_column', 4)->values(),
                                5 => $footerPages->where('footer_column', 5)->values(),
                            ],
                            'contact' => [
                                'phone' => $settings->get('phone_primary', '+977-1-4000000'),
                                'phone_emergency' => $settings->get('phone_emergency', '+977-9800000000'),
                                'email' => $settings->get('email_primary', 'info@winterfelltreks.com'),
                                'address' => $settings->get('office_address', 'Thamel, Kathmandu, Nepal'),
                                'hours' => $settings->get('business_hours', 'Mon-Sat: 9:00 AM - 6:00 PM (NPT)'),
                                'whatsapp' => $settings->get('whatsapp_number', '9779800000000'),
                                'map_url' => $settings->get('map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.0!2d85.31!3d27.71!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sThamel!5e0!3m2!1sen!2snp!4v1'),
                            ],
                            'social' => [
                                'facebook' => $settings->get('facebook_url', 'https://facebook.com'),
                                'instagram' => $settings->get('instagram_url', 'https://instagram.com'),
                                'youtube' => $settings->get('youtube_url', 'https://youtube.com'),
                                'tiktok' => $settings->get('tiktok_url', 'https://tiktok.com'),
                            ],
                            'copyright' => $settings->get('copyright_text', '© ' . date('Y') . ' Winterfell Treks & Expedition. All rights reserved.'),
                            'enquiry_button_text' => $settings->get('navbar_enquiry_button_text', 'Enquire Now'),
                        ];

                        $cachedSeo = [
                            'title' => $cachedGlobals['site_name'],
                            'description' => $settings->get('default_meta_description', 'Discover extraordinary Himalayan expeditions with Winterfell Treks.'),
                            'keywords' => $settings->get('default_meta_keywords', 'Nepal treks, Everest, Annapurna, expeditions'),
                            'og_image' => $settings->get('default_og_image', ['url' => '/images/og-default.jpg']),
                            'canonical' => url()->current(),
                            'robots_index' => true,
                        ];
                    }
                } catch (\Throwable $e) {
                    // Fallback if DB not ready or during early boots
                }

                if ($cachedGlobals === null) {
                    $cachedGlobals = [
                        'site_name' => 'Winterfell Treks & Expedition',
                        'site_tagline' => 'Himalayan Expeditions & Wilderness Adventures',
                        'logo' => '/images/winterfell-nobg.png',
                        'founder_name' => 'Mr. Asmin Bhattarai',
                        'established_year_bs' => '2083',
                        'navbar_pages' => collect(),
                        'sidebar_pages' => collect(),
                        'footer_columns' => [1 => collect(), 2 => collect(), 3 => collect(), 4 => collect(), 5 => collect()],
                        'contact' => [
                            'phone' => '+977-1-4000000',
                            'phone_emergency' => '+977-9800000000',
                            'email' => 'info@winterfelltreks.com',
                            'address' => 'Thamel, Kathmandu, Nepal',
                            'hours' => 'Mon-Sat: 9:00 AM - 6:00 PM (NPT)',
                            'whatsapp' => '9779800000000',
                            'map_url' => '',
                        ],
                        'social' => [],
                        'copyright' => '© ' . date('Y') . ' Winterfell Treks & Expedition. All rights reserved.',
                        'enquiry_button_text' => 'Enquire Now',
                    ];
                    $cachedSeo = [
                        'title' => 'Winterfell Treks & Expedition',
                        'description' => 'Himalayan Expeditions & Wilderness Adventures',
                        'canonical' => url()->current(),
                        'robots_index' => true,
                    ];
                }
            }

            // Provide globals if not already provided by controller
            if (!$view->offsetExists('globals')) {
                $view->with('globals', $cachedGlobals);
            }

            // Provide default SEO if not already provided
            if (!$view->offsetExists('seo')) {
                $view->with('seo', $cachedSeo);
            }

            // Ensure errors bag is always defined
            if (!$view->offsetExists('errors')) {
                $view->with('errors', session('errors', new \Illuminate\Support\ViewErrorBag()));
            }
        });
    }
}
