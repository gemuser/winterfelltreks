<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->latest('id')->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_verified' => $request->boolean('is_verified'),
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_photo' => 'nullable|string|max:500',
            'testimonial_text' => 'required|string',
            'destination_or_package' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_verified' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->merge([
            'is_verified' => $request->boolean('is_verified'),
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_photo' => 'nullable|string|max:500',
            'testimonial_text' => 'required|string',
            'destination_or_package' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_verified' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}

