<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Enquiry;
use App\Models\Destination;
use App\Models\Expedition;
use App\Models\Experience;
use App\Models\BlogPost;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pages' => Page::count(),
            'published_pages' => Page::published()->count(),
            'destinations' => Destination::count(),
            'published_destinations' => Destination::published()->count(),
            'expeditions' => Expedition::count(),
            'published_expeditions' => Expedition::published()->count(),
            'experiences' => Experience::count(),
            'published_experiences' => Experience::published()->count(),
            'blog_posts' => BlogPost::count(),
            'published_posts' => BlogPost::published()->count(),
            'enquiries_total' => Enquiry::count(),
            'enquiries_new' => Enquiry::where('status', 'new')->count(),
            'enquiries_this_week' => Enquiry::where('created_at', '>=', now()->subWeek())->count(),
            'testimonials' => Testimonial::count(),
            'published_testimonials' => Testimonial::published()->count(),
        ];

        $recent_enquiries = Enquiry::latest()->take(10)->get();
        $recent_pages = Page::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_enquiries', 'recent_pages'));
    }
}