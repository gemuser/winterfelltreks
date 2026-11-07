<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Destination;
use App\Models\Expedition;
use App\Models\Experience;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Static pages
        $pages = Page::published()->get(['slug', 'updated_at']);
        foreach ($pages as $page) {
            $urls[] = [
                'loc' => url($page->slug === 'home' ? '/' : '/' . $page->slug),
                'lastmod' => $page->updated_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => $page->slug === 'home' ? '1.0' : '0.8',
            ];
        }

        // Destinations
        $destinations = Destination::published()->get(['slug', 'updated_at']);
        foreach ($destinations as $dest) {
            $urls[] = [
                'loc' => url('/destinations/' . $dest->slug),
                'lastmod' => $dest->updated_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        // Expeditions/Packages
        $expeditions = Expedition::published()->get(['slug', 'updated_at']);
        foreach ($expeditions as $exp) {
            $urls[] = [
                'loc' => url('/packages/' . $exp->slug),
                'lastmod' => $exp->updated_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        // Experiences
        $experiences = Experience::published()->get(['slug', 'updated_at']);
        foreach ($experiences as $exp) {
            $urls[] = [
                'loc' => url('/experiences/' . $exp->slug),
                'lastmod' => $exp->updated_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        // Blog posts
        $posts = BlogPost::published()->get(['slug', 'updated_at', 'published_at']);
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => url('/travel-guide/' . $post->slug),
                'lastmod' => $post->updated_at?->format('Y-m-d') ?? $post->published_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
        $content .= "\n";
        $content .= "# Winterfell Treks & Expedition\n";
        $content .= "# Founded 2083 BS by Mr. Asmin Bhattarai\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}