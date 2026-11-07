<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::with('category')->orderBy('sort_order')->latest('id')->paginate(30);
        return view('admin.gallery.index', compact('images'));
    }

    public function create()
    {
        $categories = GalleryCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.gallery.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_visible' => $request->boolean('is_visible'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:500',
            'category_id' => 'nullable|exists:gallery_categories,id',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image') || $request->hasFile('image_file')) {
            $file = $request->file('image') ?: $request->file('image_file');
            $filename = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
            $file->move(public_path('images/uploads'), $filename);
            $validated['image'] = '/images/uploads/' . $filename;
        }

        GalleryImage::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image added successfully.');
    }

    public function edit(GalleryImage $gallery)
    {
        $categories = GalleryCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.gallery.edit', [
            'image' => $gallery,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $request->merge([
            'is_visible' => $request->boolean('is_visible'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:500',
            'category_id' => 'nullable|exists:gallery_categories,id',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image') || $request->hasFile('image_file')) {
            $file = $request->file('image') ?: $request->file('image_file');
            $filename = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
            $file->move(public_path('images/uploads'), $filename);
            $validated['image'] = '/images/uploads/' . $filename;
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image updated successfully.');
    }

    public function destroy(GalleryImage $gallery)
    {
        $gallery->delete();
        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image deleted successfully.');
    }
}

