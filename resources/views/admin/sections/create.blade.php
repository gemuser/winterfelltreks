@extends('admin.layout')

@section('title', 'Add Section')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ selectedType: '{{ old('section_type', $sectionType ?? '') }}' }">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Add Section to {{ $page->title }}</h1>
        <p class="text-gray-500 mt-1">Select a section type to add to this page</p>
    </div>
    
    <form method="POST" action="{{ route('admin.pages.sections.store', $page) }}" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf
        
        <div>
            <label for="section_type" class="block text-sm font-medium text-gray-700 mb-2">Section Type <span class="text-red-500">*</span></label>
            <select id="section_type" name="section_type" required class="form-input w-full" x-model="selectedType">
                <option value="">Select a section type</option>
                @foreach($sectionTypes as $type => $config)
                @php
                    $optLabel = is_array($config) ? (($config['label'] ?? $type) . (!empty($config['description']) ? ' - ' . $config['description'] : '')) : $config;
                @endphp
                <option value="{{ $type }}" {{ old('section_type') == $type || $sectionType == $type ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div x-show="selectedType" x-transition class="space-y-6">
            <div>
                <label for="custom_anchor_id" class="block text-sm font-medium text-gray-700 mb-2">Custom Anchor ID (optional)</label>
                <input type="text" id="custom_anchor_id" name="custom_anchor_id" class="form-input w-full" placeholder="e.g., featured-destinations" pattern="[a-z0-9-]+" maxlength="100">
                <p class="mt-1 text-sm text-gray-500">Used for deep linking (e.g., #featured-destinations). Lowercase, numbers, hyphens only.</p>
            </div>
            
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" min="0" class="form-input w-full" value="{{ old('sort_order', $maxOrder + 1) }}">
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_visible" name="is_visible" class="form-checkbox h-4 w-4" {{ old('is_visible', true) ? 'checked' : '' }}>
                <label for="is_visible" class="text-sm text-gray-700">Visible on page</label>
            </div>
            
            <div>
                <label for="settings" class="block text-sm font-medium text-gray-700 mb-2">Settings (JSON)</label>
                <textarea id="settings" name="settings" rows="4" class="form-input w-full font-mono text-sm" placeholder='{"background_type": "image", "overlay_opacity": 0.4, "columns": 3}'>{{ old('settings') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Section-specific configuration options</p>
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-200 flex justify-end gap-4">
            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary" :disabled="!selectedType">Create Section</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-set sort order to next available
        const sortOrderInput = document.getElementById('sort_order');
        if (sortOrderInput && !sortOrderInput.value) {
            // Value already set from controller
        }
    });
</script>
@endsection