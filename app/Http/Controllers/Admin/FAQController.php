<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::orderBy('category')->orderBy('sort_order')->latest('id')->paginate(30);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = FAQ::select('category')->distinct()->pluck('category')->filter()->values();
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        FAQ::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(FAQ $faq)
    {
        $categories = FAQ::select('category')->distinct()->pluck('category')->filter()->values();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, FAQ $faq)
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(FAQ $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}

