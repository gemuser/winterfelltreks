<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionElement;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    public function create(Page $page, PageSection $section)
    {
        $elementTypes = $this->getElementTypes();
        return view('admin.elements.create', compact('page', 'section', 'elementTypes'));
    }

    public function store(Request $request, Page $page, PageSection $section)
    {
        $validated = $request->validate([
            'element_key' => 'required|string|max:100|unique:section_elements,element_key,NULL,id,section_id,' . $section->id,
            'element_type' => 'required|in:text,richtext,image,link,repeater,json,component',
            'content' => 'nullable',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $maxOrder = $section->elements()->max('sort_order') ?? -1;
        $validated['sort_order'] = $validated['sort_order'] ?? $maxOrder + 1;
        $validated['section_id'] = $section->id;

        // Handle content based on type
        if ($validated['element_type'] === 'text') {
            $validated['content'] = $request->input('content_text', $request->input('content', ''));
        } elseif ($validated['element_type'] === 'richtext') {
            $validated['content'] = $request->input('content_richtext', $request->input('content', ''));
        } elseif ($validated['element_type'] === 'image') {
            $imageUrl = '/images/placeholder.jpg';
            if ($request->hasFile('image_file')) {
                $path = $request->file('image_file')->store('elements', 'public');
                $imageUrl = '/storage/' . $path;
            } elseif ($request->filled('image_url')) {
                $imageUrl = $request->input('image_url');
            }
            $validated['content'] = json_encode([
                'url' => $imageUrl,
                'alt' => $request->input('image_alt', ''),
            ]);
        } elseif ($validated['element_type'] === 'link') {
            $validated['content'] = json_encode([
                'text' => $request->input('link_text', ''),
                'url' => $request->input('link_url', ''),
                'style' => $request->input('link_style', 'primary'),
            ]);
        } elseif ($validated['element_type'] === 'repeater') {
            $validated['content'] = $request->input('repeater_data', '[]');
        } elseif ($validated['element_type'] === 'json' || $validated['element_type'] === 'component') {
            $validated['content'] = $request->input('content', $request->input('json_data', '{}'));
        }

        $element = SectionElement::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'element' => $element]);
        }

        return back()->with('success', 'Element added successfully.');
    }

    public function edit(Page $page, PageSection $section, SectionElement $element)
    {
        $elementTypes = $this->getElementTypes();
        return view('admin.elements.edit', compact('page', 'section', 'element', 'elementTypes'));
    }

    public function update(Request $request, Page $page, PageSection $section, SectionElement $element)
    {
        $validated = $request->validate([
            'element_key' => 'required|string|max:100|unique:section_elements,element_key,' . $element->id . ',id,section_id,' . $section->id,
            'element_type' => 'required|in:text,richtext,image,link,repeater,json,component',
            'content' => 'nullable',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Handle content based on type
        if ($validated['element_type'] === 'text') {
            $validated['content'] = $request->input('content_text', $request->input('content', ''));
        } elseif ($validated['element_type'] === 'richtext') {
            $validated['content'] = $request->input('content_richtext', $request->input('content', ''));
        } elseif ($validated['element_type'] === 'image') {
            $currentData = $element->getContent();
            $currentUrl = is_array($currentData) ? ($currentData['url'] ?? '/images/placeholder.jpg') : '/images/placeholder.jpg';
            if ($request->hasFile('image_file')) {
                $path = $request->file('image_file')->store('elements', 'public');
                $currentUrl = '/storage/' . $path;
            } elseif ($request->filled('image_url')) {
                $currentUrl = $request->input('image_url');
            }
            $validated['content'] = json_encode([
                'url' => $currentUrl,
                'alt' => $request->input('image_alt', is_array($currentData) ? ($currentData['alt'] ?? '') : ''),
            ]);
        } elseif ($validated['element_type'] === 'link') {
            $validated['content'] = json_encode([
                'text' => $request->input('link_text', ''),
                'url' => $request->input('link_url', ''),
                'style' => $request->input('link_style', 'primary'),
            ]);
        } elseif ($validated['element_type'] === 'repeater') {
            $validated['content'] = $request->input('repeater_data', '[]');
        } elseif ($validated['element_type'] === 'json' || $validated['element_type'] === 'component') {
            $validated['content'] = $request->input('content', $request->input('json_data', '{}'));
        }

        $element->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'element' => $element]);
        }

        return back()->with('success', 'Element updated successfully.');
    }

    public function destroy(Page $page, PageSection $section, SectionElement $element)
    {
        $element->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Element deleted successfully.');
    }

    public function reorder(Request $request, Page $page, PageSection $section)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:section_elements,id',
        ]);

        foreach ($request->order as $index => $id) {
            SectionElement::where('id', $id)->where('section_id', $section->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function getElementTypes(): array
    {
        return [
            'text' => 'Plain Text',
            'richtext' => 'Rich Text (HTML)',
            'image' => 'Image (with alt text)',
            'link' => 'Link/Button (text, URL, style)',
            'repeater' => 'Repeater (dynamic list of items)',
            'json' => 'JSON (raw structured data)',
        ];
    }
}