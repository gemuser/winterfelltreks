@extends('admin.layout')

@section('title', 'Add Element')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ selectedType: '{{ old('element_type', '') }}' }">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Add Element to Section</h1>
        <p class="text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $section->section_type)) }} on {{ $page->title }}</p>
    </div>
    
    <form method="POST" action="{{ route('admin.pages.sections.elements.store', [$page, $section]) }}" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf
        
        <div>
            <label for="element_key" class="block text-sm font-medium text-gray-700 mb-2">Element Key <span class="text-red-500">*</span></label>
            <input type="text" id="element_key" name="element_key" required class="form-input w-full" placeholder="e.g., headline, cta_button, gallery_items" pattern="[a-z_]+">
            <p class="mt-1 text-sm text-gray-500">Unique identifier for this element within the section</p>
        </div>
        
        <div>
            <label for="element_type" class="block text-sm font-medium text-gray-700 mb-2">Element Type <span class="text-red-500">*</span></label>
            <select id="element_type" name="element_type" required class="form-input w-full" x-model="selectedType">
                <option value="">Select type</option>
                @foreach($elementTypes as $type => $label)
                <option value="{{ $type }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        
        <div x-show="selectedType" x-transition class="space-y-6">
            <div x-show="selectedType === 'text'">
                <label for="content_text" class="block text-sm font-medium text-gray-700 mb-2">Text Content</label>
                <textarea id="content_text" name="content_text" rows="3" class="form-input w-full"></textarea>
            </div>
            
            <div x-show="selectedType === 'richtext'">
                <label for="content_richtext" class="block text-sm font-medium text-gray-700 mb-2">Rich Text Content (HTML)</label>
                <textarea id="content_richtext" name="content_richtext" rows="8" class="form-input w-full"></textarea>
            </div>
            
            <div x-show="selectedType === 'image'" class="space-y-4">
                <div>
                    <label for="image_file" class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                    <input type="file" id="image_file" name="image_file" accept="image/*" class="form-input w-full">
                </div>
                <div>
                    <label for="image_alt" class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                    <input type="text" id="image_alt" name="image_alt" class="form-input w-full" placeholder="Descriptive alt text for accessibility">
                </div>
            </div>
            
            <div x-show="selectedType === 'link'" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="link_text" class="block text-sm font-medium text-gray-700 mb-2">Link Text</label>
                    <input type="text" id="link_text" name="link_text" class="form-input w-full">
                </div>
                <div>
                    <label for="link_url" class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                    <input type="url" id="link_url" name="link_url" class="form-input w-full">
                </div>
                <div class="sm:col-span-2">
                    <label for="link_style" class="block text-sm font-medium text-gray-700 mb-2">Button Style</label>
                    <select id="link_style" name="link_style" class="form-input w-full">
                        <option value="primary">Primary (filled)</option>
                        <option value="secondary">Secondary (outline)</option>
                    </select>
                </div>
            </div>
            
            <div x-show="selectedType === 'repeater'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Repeater Items (JSON Array)</label>
                <textarea name="repeater_data" rows="8" class="form-input w-full font-mono text-sm" placeholder='[{"name": "Item 1", "description": "Description", "image": "/path.jpg"}, {"name": "Item 2", "description": "Description"}]'></textarea>
                <p class="mt-1 text-sm text-gray-500">Each object in the array represents one item in the repeater</p>
            </div>
            
            <div x-show="selectedType === 'json'">
                <label class="block text-sm font-medium text-gray-700 mb-2">JSON Data</label>
                <textarea name="json_data" rows="8" class="form-input w-full font-mono text-sm" placeholder='{"key": "value", "nested": {"prop": "value"}}'></textarea>
                <p class="mt-1 text-sm text-gray-500">Raw JSON for advanced configurations</p>
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-200 flex justify-end gap-4">
            <a href="{{ route('admin.pages.sections.edit', [$page, $section]) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary" :disabled="!selectedType">Add Element</button>
        </div>
    </form>
</div>
@endsection