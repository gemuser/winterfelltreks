@extends('admin.layout')

@section('title', 'Edit Element')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ selectedType: '{{ $element->element_type }}' }">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Element</h1>
            <p class="text-gray-500 mt-1">{{ $element->element_key }} ({{ $element->element_type }}) on {{ $page->title }}</p>
        </div>
        <a href="{{ route('admin.pages.sections.edit', [$page, $section]) }}" class="btn-secondary">Back to Section</a>
    </div>
    
    <form method="POST" action="{{ route('admin.pages.sections.elements.update', [$page, $section, $element]) }}" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Element Key</label>
            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 font-mono text-sm text-gray-900">
                {{ $element->element_key }}
            </div>
            <input type="hidden" name="element_key" value="{{ $element->element_key }}">
        </div>
        
        <div>
            <label for="element_type" class="block text-sm font-medium text-gray-700 mb-2">Element Type <span class="text-red-500">*</span></label>
            <select id="element_type" name="element_type" required class="form-input w-full" x-model="selectedType">
                @foreach($elementTypes as $type => $label)
                <option value="{{ $type }}" {{ $element->element_type == $type ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        
        <div x-show="selectedType" x-transition class="space-y-6">
            <div x-show="selectedType === 'text'">
                <label for="content_text" class="block text-sm font-medium text-gray-700 mb-2">Text Content</label>
                <textarea id="content_text" name="content_text" rows="3" class="form-input w-full">{{ $element->element_type === 'text' ? (is_string($element->content) ? $element->content : json_encode($element->content)) : '' }}</textarea>
            </div>
            
            <div x-show="selectedType === 'richtext'">
                <label for="content_richtext" class="block text-sm font-medium text-gray-700 mb-2">Rich Text Content (HTML)</label>
                <textarea id="content_richtext" name="content_richtext" rows="8" class="form-input w-full">{{ $element->element_type === 'richtext' ? (is_string($element->content) ? $element->content : json_encode($element->content)) : '' }}</textarea>
            </div>
            
            <div x-show="selectedType === 'image'" class="space-y-4">
                <div>
                    <label for="image_file" class="block text-sm font-medium text-gray-700 mb-2">Replace Image (optional)</label>
                    <input type="file" id="image_file" name="image_file" accept="image/*" class="form-input w-full">
                </div>
                <div>
                    <label for="image_alt" class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                    <input type="text" id="image_alt" name="image_alt" class="form-input w-full" value="{{ $element->getImageAlt() }}">
                </div>
                @if($element->getImageUrl())
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <img src="{{ $element->getImageUrl() }}" alt="{{ $element->getImageAlt() }}" class="max-h-48 rounded-lg border border-gray-200">
                </div>
                @endif
            </div>
            
            <div x-show="selectedType === 'link'" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="link_text" class="block text-sm font-medium text-gray-700 mb-2">Link Text</label>
                    <input type="text" id="link_text" name="link_text" class="form-input w-full" value="{{ $element->getLinkText() }}">
                </div>
                <div>
                    <label for="link_url" class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                    <input type="url" id="link_url" name="link_url" class="form-input w-full" value="{{ $element->getLinkUrl() }}">
                </div>
                <div class="sm:col-span-2">
                    <label for="link_style" class="block text-sm font-medium text-gray-700 mb-2">Button Style</label>
                    <select id="link_style" name="link_style" class="form-input w-full">
                        <option value="primary" {{ ($element->getContent()['style'] ?? '') === 'primary' ? 'selected' : '' }}>Primary (filled)</option>
                        <option value="secondary" {{ ($element->getContent()['style'] ?? '') === 'secondary' ? 'selected' : '' }}>Secondary (outline)</option>
                    </select>
                </div>
            </div>
            
            <div x-show="selectedType === 'repeater'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Repeater Items (JSON Array)</label>
                <textarea name="repeater_data" rows="8" class="form-input w-full font-mono text-sm">{{ $element->getRepeaterData() }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Each object in the array represents one item in the repeater</p>
            </div>
            
            <div x-show="selectedType === 'json'">
                <label class="block text-sm font-medium text-gray-700 mb-2">JSON Data</label>
                <textarea name="json_data" rows="8" class="form-input w-full font-mono text-sm">{{ is_string($element->content) ? $element->content : json_encode($element->content, JSON_PRETTY_PRINT) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Raw JSON for advanced configurations</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" min="0" class="form-input w-full" value="{{ $element->sort_order }}">
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-200 flex justify-end gap-4">
            <a href="{{ route('admin.pages.sections.edit', [$page, $section]) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary" :disabled="!selectedType">Update Element</button>
        </div>
    </form>
</div>
@endsection