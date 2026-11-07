<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function create(Page $page, Request $request)
    {
        $sectionTypes = $this->getSectionTypesWithFields();
        $sectionType = $request->get('type', 'editorial_split');
        $maxOrder = $page->sections()->max('sort_order') ?? -1;

        return view('admin.sections.create', compact('page', 'sectionTypes', 'sectionType', 'maxOrder'));
    }

    public function store(Request $request, Page $page)
    {
        $request->merge([
            'is_visible' => $request->has('is_visible') ? $request->boolean('is_visible') : true,
        ]);

        $validated = $request->validate([
            'section_type' => 'required|string|max:100',
            'custom_anchor_id' => 'nullable|string|max:100|alpha_dash',
            'sort_order' => 'nullable|integer|min:0',
            'is_visible' => 'boolean',
            'settings' => 'nullable|json',
        ]);

        $maxOrder = $page->sections()->max('sort_order') ?? -1;
        $validated['sort_order'] = $validated['sort_order'] ?? $maxOrder + 1;
        $validated['page_id'] = $page->id;

        $section = PageSection::create($validated);

        // Create default elements based on section type
        $this->createDefaultElements($section);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'section' => $section->load('elements'),
                'redirect' => route('admin.pages.edit', $page) . '#section-' . $section->id,
            ]);
        }

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Section added successfully.');
    }

    public function edit(Page $page, PageSection $section)
    {
        $section->load('elements');
        $sectionTypes = $this->getSectionTypesWithFields();
        $elementTypes = [
            'text' => 'Plain Text',
            'richtext' => 'Rich Text (HTML)',
            'image' => 'Image (with alt text)',
            'link' => 'Link/Button (text, URL, style)',
            'repeater' => 'Repeater (dynamic list of items)',
            'json' => 'JSON (raw structured data)',
        ];

        return view('admin.sections.edit', compact('page', 'section', 'sectionTypes', 'elementTypes'));
    }

    public function update(Request $request, Page $page, PageSection $section)
    {
        $request->merge([
            'is_visible' => $request->boolean('is_visible'),
        ]);

        $validated = $request->validate([
            'section_type' => 'required|string|max:100',
            'custom_anchor_id' => 'nullable|string|max:100|alpha_dash',
            'sort_order' => 'nullable|integer|min:0',
            'is_visible' => 'boolean',
            'settings' => 'nullable|json',
        ]);

        $section->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'section' => $section->load('elements')]);
        }

        return back()->with('success', 'Section updated successfully.');
    }

    public function destroy(Page $page, PageSection $section)
    {
        $section->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Section deleted successfully.');
    }

    public function reorder(Request $request, Page $page)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:page_sections,id',
        ]);

        foreach ($request->order as $index => $id) {
            PageSection::where('id', $id)->where('page_id', $page->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bulk save section content & elements directly from Page Visual CMS Editor.
     */
    public function updateContent(Request $request, Page $page, PageSection $section)
    {
        // Update section visibility or anchor if present
        if ($request->has('is_visible')) {
            $section->is_visible = $request->boolean('is_visible');
        }
        if ($request->has('custom_anchor_id')) {
            $section->custom_anchor_id = $request->input('custom_anchor_id');
        }
        if ($request->has('settings')) {
            $existing = is_array($section->settings) ? $section->settings : [];
            $newSettings = $request->input('settings', []);
            $section->settings = array_replace_recursive($existing, $newSettings);
        }
        $section->save();

        // Add new element if requested
        if ($request->has('new_element')) {
            $newElData = $request->input('new_element');
            $type = $newElData['type'] ?? 'text';
            $key = $newElData['key'] ?? ('custom_text_' . time());
            $content = $newElData['content'] ?? 'New Text Block';
            if (is_string($content) && (str_starts_with(trim($content), '{') || str_starts_with(trim($content), '['))) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $content = $decoded;
                }
            }
            $section->elements()->create([
                'element_key' => $key,
                'element_type' => $type,
                'content' => $content,
                'sort_order' => ($section->elements()->max('sort_order') ?? 0) + 1,
            ]);
        }

        // Delete element if requested
        if ($request->has('delete_element_id')) {
            $delEl = $section->elements()->where('id', $request->input('delete_element_id'))->first();
            if ($delEl) {
                $delKey = $delEl->element_key;
                $delEl->delete();
                $curSettings = is_array($section->settings) ? $section->settings : [];
                if (!empty($curSettings['styles'][$delKey])) {
                    unset($curSettings['styles'][$delKey]);
                    $section->settings = $curSettings;
                    $section->save();
                }
            }
        }

        // Update elements
        $elementsData = $request->input('elements', []);
        foreach ($elementsData as $elementId => $content) {
            $element = is_numeric($elementId) 
                ? $section->elements()->where('id', $elementId)->first()
                : $section->elements()->where('element_key', $elementId)->first();

            if (!$element && !is_numeric($elementId)) {
                // Check if a synonym element exists in this section
                $synonyms = match($elementId) {
                    'headline', 'heading', 'title' => ['heading', 'headline', 'title', 'mission_title', 'vision_title'],
                    'subheadline', 'subheading', 'subtitle' => ['subheading', 'subheadline', 'subtitle'],
                    'body', 'content', 'description' => ['body', 'content', 'description', 'mission_text', 'vision_text'],
                    'cta', 'primary_cta', 'button' => ['cta', 'primary_cta', 'button', 'cta_text'],
                    'secondary_cta' => ['secondary_cta', 'email'],
                    'email' => ['email', 'secondary_cta', 'contact_email'],
                    'phone' => ['phone', 'primary_cta', 'contact_phone'],
                    default => []
                };
                if (!empty($synonyms)) {
                    $element = $section->elements()->whereIn('element_key', $synonyms)->first();
                }

                if (!$element) {
                    $type = (str_contains($elementId, 'cta') || str_contains($elementId, 'button') || str_contains($elementId, 'link') || str_contains($elementId, 'email') || str_contains($elementId, 'phone')) ? 'link' :
                            ((str_contains($elementId, 'image') || str_contains($elementId, 'photo')) ? 'image' : 'text');
                    $element = $section->elements()->create([
                        'element_key' => $elementId,
                        'element_type' => $type,
                        'content' => $content,
                        'sort_order' => ($section->elements()->max('sort_order') ?? 0) + 1,
                    ]);
                }
            }

            if ($element) {
                if ($element->element_type === 'image' && is_string($content) && !str_starts_with(trim($content), '{')) {
                    $content = ['url' => $content, 'alt' => ''];
                } elseif (in_array($element->element_type, ['link', 'component']) && is_string($content) && (str_starts_with(trim($content), '{') || str_starts_with(trim($content), '['))) {
                    $decoded = json_decode($content, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $content = $decoded;
                    }
                } elseif ($element->element_type === 'link' && is_string($content)) {
                    // Preserve existing link URL if only text was edited directly
                    $existing = $element->getContent();
                    if (is_array($existing)) {
                        $content = array_merge($existing, ['text' => $content]);
                    } else {
                        $content = ['text' => $content, 'url' => '#', 'style' => 'primary'];
                    }
                }
                $element->content = $content;
                $element->save();

                // If this is a contact field or contains email/phone, synchronize with SiteSettings
                $extractedText = is_array($content) ? ($content['text'] ?? $content['url'] ?? '') : (is_string($content) ? $content : '');
                $cleanEmail = trim(str_replace('mailto:', '', $extractedText));
                if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                    \App\Models\SiteSetting::setValue('email_primary', $cleanEmail, 'contact');
                } elseif ($element->element_key === 'phone_emergency' || str_contains($element->element_key, 'emergency')) {
                    \App\Models\SiteSetting::setValue('phone_emergency', trim($extractedText), 'contact');
                } elseif (in_array($element->element_key, ['phone', 'contact_phone']) || ($section->section_type === 'contact_info' && preg_match('/^\+?[0-9\-\s\(\)]{7,}$/', trim($extractedText)))) {
                    \App\Models\SiteSetting::setValue('phone_primary', trim($extractedText), 'contact');
                } elseif (in_array($element->element_key, ['address', 'contact_address'])) {
                    \App\Models\SiteSetting::setValue('office_address', trim($extractedText), 'contact');
                }
            }
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'section' => $section->fresh(['elements']),
            ]);
        }

        return back()->with('success', 'Section saved successfully.');
    }

    private function createDefaultElements(PageSection $section): void
    {
        $defaults = $this->getDefaultElements($section->section_type);

        foreach ($defaults as $index => $element) {
            $section->elements()->create([
                'element_key' => $element['key'],
                'element_type' => $element['type'],
                'content' => $element['content'] ?? null,
                'sort_order' => $index,
            ]);
        }
    }

    private function getDefaultElements(string $sectionType): array
    {
        return match ($sectionType) {
            'hero_cinematic' => [
                ['key' => 'headline', 'type' => 'text', 'content' => ''],
                ['key' => 'subheadline', 'type' => 'richtext', 'content' => ''],
                ['key' => 'primary_cta', 'type' => 'link', 'content' => json_encode(['text' => '', 'url' => '', 'style' => 'primary'])],
                ['key' => 'secondary_cta', 'type' => 'link', 'content' => json_encode(['text' => '', 'url' => '', 'style' => 'secondary'])],
                ['key' => 'background_image', 'type' => 'image', 'content' => json_encode(['url' => '', 'alt' => ''])],
            ],
            'page_hero' => [
                ['key' => 'title', 'type' => 'text', 'content' => ''],
                ['key' => 'subtitle', 'type' => 'text', 'content' => ''],
                ['key' => 'background_image', 'type' => 'image', 'content' => json_encode(['url' => '', 'alt' => ''])],
            ],
            'editorial_split' => [
                ['key' => 'heading', 'type' => 'text', 'content' => ''],
                ['key' => 'subheading', 'type' => 'text', 'content' => ''],
                ['key' => 'body', 'type' => 'richtext', 'content' => ''],
                ['key' => 'image', 'type' => 'image', 'content' => json_encode(['url' => '', 'alt' => ''])],
                ['key' => 'cta', 'type' => 'link', 'content' => json_encode(['text' => '', 'url' => '', 'style' => 'primary'])],
            ],
            'destination_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Featured Destinations'],
                ['key' => 'subheading', 'type' => 'text', 'content' => ''],
                ['key' => 'view_all_text', 'type' => 'text', 'content' => 'View All'],
                ['key' => 'view_all_url', 'type' => 'text', 'content' => ''],
                ['key' => 'destinations', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'package_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Featured Expeditions'],
                ['key' => 'subheading', 'type' => 'text', 'content' => ''],
                ['key' => 'view_all_text', 'type' => 'text', 'content' => 'View All'],
                ['key' => 'view_all_url', 'type' => 'text', 'content' => ''],
                ['key' => 'packages', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'experience_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Travel Experiences'],
                ['key' => 'subheading', 'type' => 'text', 'content' => ''],
                ['key' => 'experiences', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'feature_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => ''],
                ['key' => 'features', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'gallery_masonry' => [
                ['key' => 'heading', 'type' => 'text', 'content' => ''],
                ['key' => 'view_all_text', 'type' => 'text', 'content' => 'View Gallery'],
                ['key' => 'view_all_url', 'type' => 'text', 'content' => ''],
                ['key' => 'images', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'testimonial_slider' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Testimonials'],
                ['key' => 'testimonials', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'article_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Latest Articles'],
                ['key' => 'subheading', 'type' => 'text', 'content' => ''],
                ['key' => 'view_all_text', 'type' => 'text', 'content' => 'View All'],
                ['key' => 'view_all_url', 'type' => 'text', 'content' => ''],
                ['key' => 'articles', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'cta_banner' => [
                ['key' => 'heading', 'type' => 'text', 'content' => ''],
                ['key' => 'body', 'type' => 'richtext', 'content' => ''],
                ['key' => 'cta_text', 'type' => 'text', 'content' => ''],
                ['key' => 'cta_url', 'type' => 'text', 'content' => ''],
            ],
            'mission_vision' => [
                ['key' => 'mission_title', 'type' => 'text', 'content' => 'Our Mission'],
                ['key' => 'mission_text', 'type' => 'richtext', 'content' => ''],
                ['key' => 'vision_title', 'type' => 'text', 'content' => 'Our Vision'],
                ['key' => 'vision_text', 'type' => 'richtext', 'content' => ''],
            ],
            'values_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Our Values'],
                ['key' => 'values', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'team_grid' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Our Team'],
                ['key' => 'team', 'type' => 'repeater', 'content' => json_encode([])],
            ],
            'contact_info' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Contact Us'],
            ],
            'enquiry_form' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Enquiry Form'],
            ],
            'faq_accordion' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Frequently Asked Questions'],
                ['key' => 'categories', 'type' => 'json', 'content' => json_encode(['General', 'Packages', 'Enquiries', 'Travel'])],
            ],
            'legal_content' => [
                ['key' => 'body', 'type' => 'richtext', 'content' => ''],
            ],
            'featured_article' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Featured Guide'],
                ['key' => 'article', 'type' => 'json', 'content' => json_encode([])],
            ],
            'article_listing' => [
                ['key' => 'search_placeholder', 'type' => 'text', 'content' => 'Search articles...'],
            ],
            'route_profile' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Route Profile'],
                ['key' => 'chart_data', 'type' => 'json', 'content' => json_encode([])],
            ],
            'elevation_matrix' => [
                ['key' => 'heading', 'type' => 'text', 'content' => 'Elevation Data'],
                ['key' => 'matrix_data', 'type' => 'json', 'content' => json_encode([])],
            ],
            'text_stream' => [
                ['key' => 'heading', 'type' => 'text', 'content' => ''],
                ['key' => 'content', 'type' => 'richtext', 'content' => ''],
            ],
            default => [
                ['key' => 'heading', 'type' => 'text', 'content' => ''],
                ['key' => 'content', 'type' => 'richtext', 'content' => ''],
            ],
        };
    }

    private function getSectionTypesWithFields(): array
    {
        return [
            'hero_cinematic' => [
                'label' => 'Hero Cinematic',
                'description' => 'Full-screen hero with background image/video, headline, subheadline, and dual CTAs',
                'fields' => ['headline', 'subheadline', 'primary_cta', 'secondary_cta', 'background_image'],
            ],
            'page_hero' => [
                'label' => 'Page Hero',
                'description' => 'Standard page header with title, subtitle, and background image',
                'fields' => ['title', 'subtitle', 'background_image'],
            ],
            'editorial_split' => [
                'label' => 'Editorial Split',
                'description' => 'Text and image side-by-side with flexible layout options',
                'fields' => ['heading', 'subheading', 'body', 'image', 'cta'],
            ],
            'destination_grid' => [
                'label' => 'Destination Grid',
                'description' => 'Grid of destination cards with images, names, regions, descriptions',
                'fields' => ['heading', 'subheading', 'destinations', 'view_all_text', 'view_all_url'],
            ],
            'package_grid' => [
                'label' => 'Package Grid',
                'description' => 'Grid of expedition/package cards with key details',
                'fields' => ['heading', 'subheading', 'packages', 'view_all_text', 'view_all_url'],
            ],
            'experience_grid' => [
                'label' => 'Experience Grid',
                'description' => 'Grid of experience cards',
                'fields' => ['heading', 'subheading', 'experiences'],
            ],
            'feature_grid' => [
                'label' => 'Feature Grid',
                'description' => 'Icon + title + description grid (Why Choose Us)',
                'fields' => ['heading', 'features'],
            ],
            'gallery_masonry' => [
                'label' => 'Gallery Masonry',
                'description' => 'Masonry-style image gallery with lightbox',
                'fields' => ['heading', 'images', 'view_all_text', 'view_all_url'],
            ],
            'testimonial_slider' => [
                'label' => 'Testimonial Slider',
                'description' => 'Auto-playing testimonial carousel',
                'fields' => ['heading', 'testimonials'],
            ],
            'article_grid' => [
                'label' => 'Article Grid',
                'description' => 'Blog/article cards with cover image, title, date, excerpt',
                'fields' => ['heading', 'subheading', 'articles', 'view_all_text', 'view_all_url'],
            ],
            'cta_banner' => [
                'label' => 'CTA Banner',
                'description' => 'Call-to-action section with heading, body, and button',
                'fields' => ['heading', 'body', 'cta_text', 'cta_url'],
            ],
            'mission_vision' => [
                'label' => 'Mission & Vision',
                'description' => 'Side-by-side mission and vision statements',
                'fields' => ['mission_title', 'mission_text', 'vision_title', 'vision_text'],
            ],
            'values_grid' => [
                'label' => 'Values Grid',
                'description' => 'Grid of company values',
                'fields' => ['heading', 'values'],
            ],
            'team_grid' => [
                'label' => 'Team Grid',
                'description' => 'Team member cards with photo, name, position, bio',
                'fields' => ['heading', 'team'],
            ],
            'contact_info' => [
                'label' => 'Contact Information',
                'description' => 'Contact details pulled from global settings',
                'fields' => ['heading'],
            ],
            'enquiry_form' => [
                'label' => 'Enquiry Form',
                'description' => 'Context-aware enquiry form',
                'fields' => ['heading'],
            ],
            'faq_accordion' => [
                'label' => 'FAQ Accordion',
                'description' => 'Categorized FAQ with expand/collapse',
                'fields' => ['heading', 'categories'],
            ],
            'legal_content' => [
                'label' => 'Legal Content',
                'description' => 'Full-width rich text for privacy/terms pages',
                'fields' => ['body'],
            ],
            'route_profile' => [
                'label' => 'Route Profile',
                'description' => 'Elevation chart for trekking routes',
                'fields' => ['heading', 'chart_data'],
            ],
            'elevation_matrix' => [
                'label' => 'Elevation Matrix',
                'description' => 'Tabular elevation data for expeditions',
                'fields' => ['heading', 'matrix_data'],
            ],
            'text_stream' => [
                'label' => 'Text Stream',
                'description' => 'Long-form editorial content',
                'fields' => ['heading', 'content'],
            ],
        ];
    }
}