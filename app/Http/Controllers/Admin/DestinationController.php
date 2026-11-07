<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::latest()->paginate(20);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $categories = DestinationCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:destinations,slug',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'gallery' => 'nullable',
            'best_time_to_visit' => 'nullable|string',
            'travel_information' => 'nullable|string',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (isset($validated['gallery']) && is_string($validated['gallery'])) {
            $validated['gallery'] = array_filter(array_map('trim', explode("\n", str_replace(["\r", ','], ["\n", "\n"], $validated['gallery']))));
        }

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination)
    {
        $categories = DestinationCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:destinations,slug,' . $destination->id,
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'gallery' => 'nullable',
            'best_time_to_visit' => 'nullable|string',
            'travel_information' => 'nullable|string',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (isset($validated['gallery']) && is_string($validated['gallery'])) {
            $validated['gallery'] = array_filter(array_map('trim', explode("\n", str_replace(["\r", ','], ["\n", "\n"], $validated['gallery']))));
        }

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination deleted successfully.');
    }
}

