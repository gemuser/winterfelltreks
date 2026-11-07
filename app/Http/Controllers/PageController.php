<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Destination;
use App\Models\Expedition;
use App\Models\Experience;
use App\Models\BlogPost;
use App\Models\GalleryImage;
use App\Models\GalleryCategory;
use App\Models\Testimonial;
use App\Models\FAQ;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home(): View
    {
        return $this->renderPage('home');
    }

    public function render(string $slug = 'home'): View
    {
        return $this->renderPage($slug);
    }

    public function destinationDetail(string $slug): View
    {
        $destination = Destination::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPackages = Expedition::published()
            ->where('region', $destination->region)
            ->take(6)
            ->get();

        $relatedDestinations = Destination::published()
            ->where('id', '!=', $destination->id)
            ->take(4)
            ->get();

        $seo = [
            'title' => $destination->meta_title ?? $destination->name . ' | Winterfell Treks & Expedition',
            'description' => strip_tags($destination->meta_description ?? $destination->short_description ?? ''),
            'canonical' => url('/destinations/' . $destination->slug),
            'og_image' => $destination->featured_image ?? '/images/dest-hero.jpg',
            'robots_index' => true,
        ];

        return view('pages.destination-detail', compact('destination', 'relatedPackages', 'relatedDestinations', 'seo'));
    }

    public function packageDetail(string $slug): View
    {
        $package = Expedition::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPackages = Expedition::published()
            ->where('id', '!=', $package->id)
            ->where('region', $package->region)
            ->take(3)
            ->get();

        if ($relatedPackages->isEmpty()) {
            $relatedPackages = Expedition::published()
                ->where('id', '!=', $package->id)
                ->take(3)
                ->get();
        }

        $seo = [
            'title' => $package->title . ' | Tour Packages | Winterfell Treks',
            'description' => Str::limit(strip_tags($package->route_overview ?? ('Explore ' . $package->title . ' in ' . $package->region)), 160),
            'canonical' => url('/packages/' . $package->slug),
            'og_image' => $package->featured_image ?? '/images/pkg-hero.jpg',
            'robots_index' => true,
        ];

        return view('pages.package-detail', compact('package', 'relatedPackages', 'seo'));
    }

    public function experienceDetail(string $slug): View
    {
        $experience = Experience::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedExpeditions = $experience->relatedExpeditions();
        if ($relatedExpeditions->isEmpty()) {
            $relatedExpeditions = Expedition::published()->take(3)->get();
        }

        $relatedExperiences = Experience::published()
            ->where('id', '!=', $experience->id)
            ->take(3)
            ->get();

        $seo = [
            'title' => $experience->meta_title ?? $experience->name . ' | Winterfell Experiences',
            'description' => strip_tags($experience->meta_description ?? $experience->short_description ?? ''),
            'canonical' => url('/experiences/' . $experience->slug),
            'og_image' => $experience->hero_image ?? '/images/exp-hero.jpg',
            'robots_index' => true,
        ];

        return view('pages.experience-detail', compact('experience', 'relatedExpeditions', 'relatedExperiences', 'seo'));
    }

    public function articleDetail(string $slug): View
    {
        $article = BlogPost::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedArticles = BlogPost::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->take(3)
            ->get();

        if ($relatedArticles->isEmpty()) {
            $relatedArticles = BlogPost::published()
                ->where('id', '!=', $article->id)
                ->take(3)
                ->get();
        }

        $seo = [
            'title' => $article->meta_title ?? $article->title . ' | Winterfell Travel Guide',
            'description' => strip_tags($article->meta_description ?? $article->excerpt ?? ''),
            'canonical' => url('/travel-guide/' . $article->slug),
            'og_image' => $article->cover_image ?? '/images/blog-hero.jpg',
            'robots_index' => true,
        ];

        return view('pages.article-detail', compact('article', 'relatedArticles', 'seo'));
    }

    private function renderPage(string $slug): View
    {
        $page = Page::with(['visibleSections.elements'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Load dynamic data for specific page types
        $data = $this->getPageData($page);

        // SEO data
        $seo = [
            'title' => $page->meta_title ?? ($page->title . ' | Winterfell Treks & Expedition'),
            'description' => $page->meta_description ?? 'Discover extraordinary Himalayan expeditions with Winterfell Treks & Expedition.',
            'keywords' => $page->meta_keywords ?? '',
            'og_image' => $page->og_image ?? ['url' => '/images/og-default.jpg'],
            'canonical' => $page->canonical_url ?? url()->current(),
            'robots_index' => $page->robots_index ?? true,
        ];

        // Structured data
        $structured_data = $this->generateStructuredData($page);

        return view('pages.render', compact('page', 'data', 'seo', 'structured_data'));
    }

    private function getPageData(Page $page): array
    {
        $data = [];

        switch ($page->slug) {
            case 'destinations':
                $data['destinations'] = Destination::published()
                    ->latest()
                    ->paginate(12);
                $data['categories'] = Destination::published()
                    ->select('category')
                    ->distinct()
                    ->pluck('category')
                    ->filter()
                    ->values();
                break;

            case 'packages':
                $data['expeditions'] = Expedition::published()
                    ->latest()
                    ->paginate(12);
                $data['regions'] = Expedition::published()
                    ->select('region')
                    ->distinct()
                    ->pluck('region')
                    ->filter()
                    ->values();
                break;

            case 'experiences':
                $data['experiences'] = Experience::published()
                    ->latest()
                    ->paginate(12);
                $data['categories'] = Experience::published()
                    ->select('category')
                    ->distinct()
                    ->pluck('category')
                    ->filter()
                    ->values();
                break;

            case 'travel-guide':
                $data['posts'] = BlogPost::published()
                    ->with('category')
                    ->latest('published_at')
                    ->paginate(10);
                $data['categories'] = BlogPost::published()
                    ->join('blog_categories', 'blog_posts.category_id', '=', 'blog_categories.id')
                    ->select('blog_categories.*')
                    ->distinct()
                    ->orderBy('blog_categories.sort_order')
                    ->get();
                $data['featured'] = BlogPost::published()
                    ->with('category')
                    ->latest('published_at')
                    ->first();
                break;

            case 'gallery':
                $data['images'] = GalleryImage::visible()
                    ->with('category')
                    ->latest()
                    ->paginate(24);
                $data['categories'] = GalleryCategory::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
                break;

            case 'about':
                $data['testimonials'] = Testimonial::published()->take(6)->get();
                break;

            case 'contact':
                // Contact form handled via AJAX to enquiry endpoint
                break;

            case 'enquiry':
                // Enquiry form page
                break;

            case 'faq':
                $data['faqs'] = FAQ::published()
                    ->get()
                    ->groupBy('category');
                break;
        }

        return $data;
    }

    private function generateStructuredData(Page $page): array
    {
        $base = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->meta_title ?? $page->title,
            'description' => $page->meta_description ?? 'Winterfell Treks & Expedition',
            'url' => url()->current(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Winterfell Treks & Expedition',
                'founder' => [
                    '@type' => 'Person',
                    'name' => 'Mr. Asmin Bhattarai',
                ],
                'foundingDate' => '2083 BS',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Kathmandu',
                    'addressCountry' => 'NP',
                ],
            ],
        ];

        if ($page->slug === 'home') {
            $base['@type'] = 'WebSite';
            $base['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/destinations') . '?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }

        if (in_array($page->slug, ['destinations', 'packages', 'experiences'])) {
            $base['@type'] = 'CollectionPage';
        }

        return $base;
    }
}
