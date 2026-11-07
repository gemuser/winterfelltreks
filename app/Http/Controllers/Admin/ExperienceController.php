<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ExperienceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::latest()->paginate(20);
        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        $categories = ExperienceCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.experiences.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:experiences,slug',
            'category' => 'required|string|max:100',
            'short_description' => 'nullable|string',
            'overview' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'recommended_duration' => 'nullable|string|max:100',
            'important_information' => 'nullable|string',
            'hero_image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Experience::create($validated);

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience created successfully.');
    }

    public function edit(Experience $experience)
    {
        $categories = ExperienceCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.experiences.edit', compact('experience', 'categories'));
    }

    public function update(Request $request, Experience $experience)
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:experiences,slug,' . $experience->id,
            'category' => 'required|string|max:100',
            'short_description' => 'nullable|string',
            'overview' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'recommended_duration' => 'nullable|string|max:100',
            'important_information' => 'nullable|string',
            'hero_image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $experience->update($validated);

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }
}

