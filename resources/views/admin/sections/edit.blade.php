@extends('admin.layout')

@section('title', 'Edit Section')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Section</h1>
            @php
                $secType = $sectionTypes[$section->section_type] ?? null;
                $secLabel = is_array($secType) ? ($secType['label'] ?? $section->section_type) : ($secType ?? $section->section_type);
            @endphp
            <p class="text-gray-500 mt-1">{{ $secLabel }} on {{ $page->title }}</p>
        </div>
        <a href="{{ route('admin.pages.edit', $page) }}" class="btn-secondary">Back to Page</a>
    </div>
    
    <form method="POST" action="{{ route('admin.pages.sections.update', [$page, $section]) }}" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Section Type</label>
            <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 font-mono text-sm text-gray-900">
                {{ $secLabel }}
            </div>
            <input type="hidden" name="section_type" value="{{ $section->section_type }}">
        </div>
        
        <div>
            <label for="custom_anchor_id" class="block text-sm font-medium text-gray-700 mb-2">Custom Anchor ID (optional)</label>
            <input type="text" id="custom_anchor_id" name="custom_anchor_id" class="form-input w-full" value="{{ $section->custom_anchor_id }}" pattern="[a-z0-9-]+" maxlength="100">
            <p class="mt-1 text-sm text-gray-500">Used for deep linking (e.g., #featured-destinations). Lowercase, numbers, hyphens only.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" min="0" class="form-input w-full" value="{{ $section->sort_order }}">
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_visible" name="is_visible" class="form-checkbox h-4 w-4" {{ $section->is_visible ? 'checked' : '' }}>
                <label for="is_visible" class="text-sm text-gray-700">Visible on page</label>
            </div>
        </div>
        
        <div>
            <label for="settings" class="block text-sm font-medium text-gray-700 mb-2">Settings (JSON)</label>
            <textarea id="settings" name="settings" rows="6" class="form-input w-full font-mono text-sm">{{ $section->settings ? json_encode($section->settings, JSON_PRETTY_PRINT) : '' }}</textarea>
            <p class="mt-1 text-sm text-gray-500">Section-specific configuration options</p>
        </div>
        
        <div class="pt-4 border-t border-gray-200 flex justify-end gap-4">
            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Section</button>
        </div>
    </form>
    
    {{-- Elements Manager --}}
    <div class="mt-8 bg-white rounded-lg border border-gray-200" x-data="{ showAddElement: false, selectedType: '' }">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Section Elements</h2>
            <button type="button" class="btn-primary text-sm" @click="showAddElement = true">Add Element</button>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($section->elements as $element)
            <div class="element-row p-4 hover:bg-gray-50 transition-colors" id="element-{{ $element->id }}">
                <div class="flex items-center gap-4">
                    <button type="button" class="text-gray-400 hover:text-gray-600 cursor-grab" title="Drag to reorder">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                    </button>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <span class="font-medium text-gray-900">{{ $element->element_key }}</span>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700">{{ $element->element_type }}</span>
                        </div>
                        <div class="text-sm text-gray-500">Order: {{ $element->sort_order }}</div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.pages.sections.elements.edit', [$page, $section, $element]) }}" class="text-accent-primary hover:underline text-sm">Edit</a>
                        
                        <form method="POST" action="{{ route('admin.pages.sections.elements.destroy', [$page, $section, $element]) }}" class="inline" onsubmit="return confirm('Delete this element?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="mb-4">No elements added yet</p>
                <button type="button" class="btn-primary" @click="showAddElement = true">Add First Element</button>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Element Modal --}}
<div x-data="{ showAddElement: false, selectedType: '' }" x-show="showAddElement" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="fixed inset-0 bg-black/50" @click="showAddElement = false"></div>
    
    <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto" x-show="showAddElement">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Add Element</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" @click="showAddElement = false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.pages.sections.elements.store', [$page, $section]) }}" class="p-6" @submit.prevent="submitElement">
            @csrf
            
            <div class="mb-4">
                <label for="element_key" class="block text-sm font-medium text-gray-700 mb-2">Element Key <span class="text-red-500">*</span></label>
                <input type="text" id="element_key" name="element_key" required class="form-input w-full" placeholder="e.g., headline, cta_button, gallery_items" pattern="[a-z_]+">
            </div>
            
            <div class="mb-4">
                <label for="element_type" class="block text-sm font-medium text-gray-700 mb-2">Element Type <span class="text-red-500">*</span></label>
                <select id="element_type" name="element_type" required class="form-input w-full" x-model="selectedType">
                    <option value="">Select type</option>
                    @foreach($elementTypes as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div x-show="selectedType" x-transition class="space-y-4">
                <div x-show="selectedType === 'text' || selectedType === 'richtext'">
                    <label for="content_text_modal" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea id="content_text_modal" name="content" :rows="selectedType === 'richtext' ? 6 : 3" class="form-input w-full"></textarea>
                </div>
                
                <div x-show="selectedType === 'image'" class="space-y-3">
                    <div>
                        <label for="image_file_modal" class="block text-sm font-medium text-gray-700 mb-2">Image File</label>
                        <input type="file" id="image_file_modal" name="image_file" accept="image/*" class="form-input w-full">
                    </div>
                    <div>
                        <label for="image_alt_modal" class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                        <input type="text" id="image_alt_modal" name="image_alt" class="form-input w-full" placeholder="Descriptive alt text for accessibility">
                    </div>
                </div>
                
                <div x-show="selectedType === 'link'" class="space-y-3">
                    <div>
                        <label for="link_text_modal" class="block text-sm font-medium text-gray-700 mb-2">Link Text</label>
                        <input type="text" id="link_text_modal" name="link_text" class="form-input w-full">
                    </div>
                    <div>
                        <label for="link_url_modal" class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                        <input type="url" id="link_url_modal" name="link_url" class="form-input w-full">
                    </div>
                    <div>
                        <label for="link_style_modal" class="block text-sm font-medium text-gray-700 mb-2">Button Style</label>
                        <select id="link_style_modal" name="link_style" class="form-input w-full">
                            <option value="primary">Primary (filled)</option>
                            <option value="secondary">Secondary (outline)</option>
                        </select>
                    </div>
                </div>
                
                <div x-show="selectedType === 'repeater'">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Repeater Data (JSON Array)</label>
                    <textarea name="repeater_data" rows="6" class="form-input w-full font-mono text-sm" placeholder='[{"name": "Item 1", "description": "Description"}, {"name": "Item 2", "description": "Description"}]'></textarea>
                </div>
                
                <div x-show="selectedType === 'json'">
                    <label class="block text-sm font-medium text-gray-700 mb-2">JSON Data</label>
                    <textarea name="json_data" rows="6" class="form-input w-full font-mono text-sm" placeholder='{"key": "value"}'></textarea>
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" class="btn-secondary" @click="showAddElement = false">Cancel</button>
                <button type="submit" class="btn-primary" :disabled="!selectedType">Add Element</button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmDelete(elementId, elementKey) {
        if (confirm(`Delete element "${elementKey}"?`)) {
            fetch(`/admin/pages/{{ $page->id }}/sections/{{ $section->id }}/elements/${elementId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    document.getElementById(`element-${elementId}`).remove();
                }
            });
        }
    }
    
    function submitElement(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
</script>
@endsection