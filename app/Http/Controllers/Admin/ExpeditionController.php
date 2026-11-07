<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expedition;
use Illuminate\Http\Request;

class ExpeditionController extends Controller
{
    public function index()
    {
        $expeditions = Expedition::latest()->paginate(20);
        return view('admin.expeditions.index', compact('expeditions'));
    }

    public function create()
    {
        return view('admin.expeditions.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:expeditions,slug',
            'region' => 'required|string|max:100',
            'max_elevation' => 'required|string|max:50',
            'duration_days' => 'required|integer|min:1',
            'technical_grade' => 'required|string|max:50',
            'route_overview' => 'nullable|string',
            'itinerary' => 'nullable|json',
            'gear_checklist' => 'nullable|json',
            'included_services' => 'nullable|json',
            'excluded_services' => 'nullable|json',
            'featured_image' => 'nullable|string|max:500',
            'gallery' => 'nullable|json',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        Expedition::create($validated);

        return redirect()->route('admin.expeditions.index')
            ->with('success', 'Expedition created successfully.');
    }

    public function edit(Expedition $expedition)
    {
        return view('admin.expeditions.edit', compact('expedition'));
    }

    public function update(Request $request, Expedition $expedition)
    {
        $request->merge([
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:191|unique:expeditions,slug,' . $expedition->id,
            'region' => 'required|string|max:100',
            'max_elevation' => 'required|string|max:50',
            'duration_days' => 'required|integer|min:1',
            'technical_grade' => 'required|string|max:50',
            'route_overview' => 'nullable|string',
            'itinerary' => 'nullable|json',
            'gear_checklist' => 'nullable|json',
            'included_services' => 'nullable|json',
            'excluded_services' => 'nullable|json',
            'featured_image' => 'nullable|string|max:500',
            'gallery' => 'nullable|json',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $expedition->update($validated);

        return back()->with('success', 'Expedition updated successfully.');
    }

    public function destroy(Expedition $expedition)
    {
        $expedition->delete();
        return redirect()->route('admin.expeditions.index')
            ->with('success', 'Expedition deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:expeditions,id',
        ]);

        foreach ($request->order as $index => $id) {
            Expedition::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}