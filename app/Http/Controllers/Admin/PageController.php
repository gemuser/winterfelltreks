<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::withCount('sections')
            ->orderByDesc('show_in_navbar')
            ->orderByRaw('CASE WHEN show_in_navbar = 1 THEN navbar_order ELSE 999 END ASC')
            ->orderBy('title')
            ->paginate(50);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $layoutTypes = $this->getLayoutTypes();
        return view('admin.pages.create', compact('layoutTypes'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'robots_index' => $request->boolean('robots_index'),
            'is_published' => $request->boolean('is_published'),
            'show_in_navbar' => $request->boolean('show_in_navbar'),
            'show_in_sidebar' => $request->boolean('show_in_sidebar'),
            'show_in_footer' => $request->boolean('show_in_footer'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:pages,slug',
            'layout_type' => 'required|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|string|max:500',
            'robots_index' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
            'show_in_navbar' => 'sometimes|boolean',
            'navbar_title' => 'nullable|string|max:100',
            'navbar_order' => 'nullable|integer|min:0',
            'show_in_sidebar' => 'sometimes|boolean',
            'sidebar_title' => 'nullable|string|max:100',
            'sidebar_order' => 'nullable|integer|min:0',
            'show_in_footer' => 'sometimes|boolean',
            'footer_column' => 'nullable|integer|between:1,5',
            'footer_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter++;
        }

        $page = Page::create($validated);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $page->load(['sections.elements']);
        $layoutTypes = $this->getLayoutTypes();
        $sectionTypes = $this->getSectionTypes();

        // Also fetch page data for dynamic previews (like destinations, packages)
        $publicController = new \App\Http\Controllers\PageController();
        $reflection = new \ReflectionClass($publicController);
        $method = $reflection->getMethod('getPageData');
        $method->setAccessible(true);
        $pageData = $method->invoke($publicController, $page);

        return view('admin.pages.edit', compact('page', 'layoutTypes', 'sectionTypes', 'pageData'));
    }

    public function update(Request $request, Page $page)
    {
        $booleanFields = ['robots_index', 'is_published', 'show_in_navbar', 'show_in_sidebar', 'show_in_footer'];
        $mergeData = [];
        foreach ($booleanFields as $field) {
            if ($request->has($field)) {
                $mergeData[$field] = $request->boolean($field);
            }
        }
        if (!$request->has('layout_type')) {
            $mergeData['layout_type'] = $page->layout_type ?? 'default';
        }
        if (!empty($mergeData)) {
            $request->merge($mergeData);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:pages,slug,' . $page->id,
            'layout_type' => 'sometimes|required|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|string|max:500',
            'robots_index' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
            'show_in_navbar' => 'sometimes|boolean',
            'navbar_title' => 'nullable|string|max:100',
            'navbar_order' => 'nullable|integer|min:0',
            'show_in_sidebar' => 'sometimes|boolean',
            'sidebar_title' => 'nullable|string|max:100',
            'sidebar_order' => 'nullable|integer|min:0',
            'show_in_footer' => 'sometimes|boolean',
            'footer_column' => 'nullable|integer|between:1,5',
            'footer_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return back()->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:pages,id',
        ]);

        foreach ($request->order as $index => $id) {
            Page::where('id', $id)->update(['navbar_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Str::slug($newPage->title) . '-' . uniqid();
        $newPage->is_published = false;
        $newPage->save();

        // Duplicate sections and elements
        foreach ($page->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $newPage->id;
            $newSection->save();

            foreach ($section->elements as $element) {
                $newElement = $element->replicate();
                $newElement->section_id = $newSection->id;
                $newElement->save();
            }
        }

        return redirect()->route('admin.pages.edit', $newPage)
            ->with('success', 'Page duplicated successfully.');
    }

    private function getLayoutTypes(): array
    {
        return [
            'standard' => 'Standard Page',
            'home' => 'Homepage',
            'legal' => 'Legal Page (Privacy/Terms)',
            'landing' => 'Landing Page',
        ];
    }

    private function getSectionTypes(): array
    {
        return [
            'hero_cinematic' => 'Hero Cinematic (Full-screen hero)',
            'page_hero' => 'Page Hero (Standard page header)',
            'editorial_split' => 'Editorial Split (Text + Image)',
            'destination_grid' => 'Destination Grid',
            'package_grid' => 'Package/Expedition Grid',
            'experience_grid' => 'Experience Grid',
            'feature_grid' => 'Feature Grid (Why Choose Us)',
            'gallery_masonry' => 'Gallery Masonry',
            'testimonial_slider' => 'Testimonial Slider',
            'article_grid' => 'Article/Blog Grid',
            'cta_banner' => 'CTA Banner',
            'mission_vision' => 'Mission & Vision',
            'values_grid' => 'Values Grid',
            'team_grid' => 'Team Grid',
            'destination_listing' => 'Destination Listing (with search/filters)',
            'package_listing' => 'Package Listing (with search/filters)',
            'experience_listing' => 'Experience Listing',
            'contact_info' => 'Contact Information',
            'contact_form' => 'Contact Form',
            'google_map' => 'Google Map Embed',
            'enquiry_form' => 'Enquiry Form',
            'faq_accordion' => 'FAQ Accordion',
            'legal_content' => 'Legal Content Block',
            'featured_article' => 'Featured Article',
            'article_listing' => 'Article Listing',
            'route_profile' => 'Route Profile (Elevation Chart)',
            'elevation_matrix' => 'Elevation Matrix',
            'text_stream' => 'Text Stream (Long-form content)',
        ];
    }
}