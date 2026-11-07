@extends('admin.layout')

@section('title', 'Create Page')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Create Page</h1>
        <p class="text-gray-500 mt-1">Create a new dynamic page with modular sections</p>
    </div>
    
    <form method="POST" action="{{ route('admin.pages.store') }}" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Page Title <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" required class="form-input w-full" value="{{ old('title') }}">
        </div>
        
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug <span class="text-red-500">*</span></label>
            <input type="text" id="slug" name="slug" required class="form-input w-full" value="{{ old('slug') }}" placeholder="auto-generated-from-title">
            <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
        </div>
        
        <div>
            <label for="layout_type" class="block text-sm font-medium text-gray-700 mb-2">Layout Type <span class="text-red-500">*</span></label>
            <select id="layout_type" name="layout_type" required class="form-input w-full">
                @foreach($layoutTypes as $key => $label)
                <option value="{{ $key }}" {{ old('layout_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" class="form-input w-full" value="{{ old('meta_title') }}">
            </div>
            
            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="2" class="form-input w-full">{{ old('meta_description') }}</textarea>
            </div>
        </div>
        
        <div>
            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
            <input type="text" id="meta_keywords" name="meta_keywords" class="form-input w-full" value="{{ old('meta_keywords') }}">
        </div>
        
        <div>
            <label for="og_image" class="block text-sm font-medium text-gray-700 mb-2">Open Graph Image URL</label>
            <input type="url" id="og_image" name="og_image" class="form-input w-full" value="{{ old('og_image') }}">
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-2">Canonical URL</label>
                <input type="url" id="canonical_url" name="canonical_url" class="form-input w-full" value="{{ old('canonical_url') }}">
            </div>
            
            <div class="flex items-center gap-3">
                <input type="hidden" name="robots_index" value="0">
                <input type="checkbox" id="robots_index" name="robots_index" value="1" class="form-checkbox h-4 w-4" {{ old('robots_index', true) ? 'checked' : '' }}>
                <label for="robots_index" class="text-sm text-gray-700">Allow search engines to index</label>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" id="is_published" name="is_published" value="1" class="form-checkbox h-4 w-4" {{ old('is_published') ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-gray-700">Published</label>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="hidden" name="show_in_navbar" value="0">
                <input type="checkbox" id="show_in_navbar" name="show_in_navbar" value="1" class="form-checkbox h-4 w-4" {{ old('show_in_navbar') ? 'checked' : '' }}>
                <label for="show_in_navbar" class="text-sm text-gray-700">Show in Navbar</label>
            </div>
        </div>
        
        <div id="navbar-fields" class="grid grid-cols-1 sm:grid-cols-3 gap-6" style="display: {{ old('show_in_navbar') ? 'grid' : 'none' }};">
            <div>
                <label for="navbar_title" class="block text-sm font-medium text-gray-700 mb-2">Navbar Title</label>
                <input type="text" id="navbar_title" name="navbar_title" class="form-input w-full" value="{{ old('navbar_title') }}" placeholder="Defaults to page title">
            </div>
            
            <div>
                <label for="navbar_order" class="block text-sm font-medium text-gray-700 mb-2">Navbar Order</label>
                <input type="number" id="navbar_order" name="navbar_order" min="0" class="form-input w-full" value="{{ old('navbar_order', 0) }}">
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex items-center gap-3">
                <input type="hidden" name="show_in_sidebar" value="0">
                <input type="checkbox" id="show_in_sidebar" name="show_in_sidebar" value="1" class="form-checkbox h-4 w-4" {{ old('show_in_sidebar') ? 'checked' : '' }}>
                <label for="show_in_sidebar" class="text-sm text-gray-700">Show in Sidebar</label>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="hidden" name="show_in_footer" value="0">
                <input type="checkbox" id="show_in_footer" name="show_in_footer" value="1" class="form-checkbox h-4 w-4" {{ old('show_in_footer') ? 'checked' : '' }}>
                <label for="show_in_footer" class="text-sm text-gray-700">Show in Footer</label>
            </div>
        </div>
        
        <div id="sidebar-fields" class="grid grid-cols-1 sm:grid-cols-3 gap-6" style="display: {{ old('show_in_sidebar') ? 'grid' : 'none' }};">
            <div>
                <label for="sidebar_title" class="block text-sm font-medium text-gray-700 mb-2">Sidebar Title</label>
                <input type="text" id="sidebar_title" name="sidebar_title" class="form-input w-full" value="{{ old('sidebar_title') }}">
            </div>
            <div>
                <label for="sidebar_order" class="block text-sm font-medium text-gray-700 mb-2">Sidebar Order</label>
                <input type="number" id="sidebar_order" name="sidebar_order" min="0" class="form-input w-full" value="{{ old('sidebar_order', 0) }}">
            </div>
        </div>
        
        <div id="footer-fields" class="grid grid-cols-1 sm:grid-cols-3 gap-6" style="display: {{ old('show_in_footer') ? 'grid' : 'none' }};">
            <div>
                <label for="footer_column" class="block text-sm font-medium text-gray-700 mb-2">Footer Column (1-5)</label>
                <input type="number" id="footer_column" name="footer_column" min="1" max="5" class="form-input w-full" value="{{ old('footer_column', 1) }}">
            </div>
            <div>
                <label for="footer_order" class="block text-sm font-medium text-gray-700 mb-2">Footer Order</label>
                <input type="number" id="footer_order" name="footer_order" min="0" class="form-input w-full" value="{{ old('footer_order', 0) }}">
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-200 flex justify-end gap-4">
            <a href="{{ route('admin.pages.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Page</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const showInNavbar = document.getElementById('show_in_navbar');
        const showInSidebar = document.getElementById('show_in_sidebar');
        const showInFooter = document.getElementById('show_in_footer');
        const navbarFields = document.getElementById('navbar-fields');
        const sidebarFields = document.getElementById('sidebar-fields');
        const footerFields = document.getElementById('footer-fields');
        
        function toggleFields(checkbox, fields) {
            fields.style.display = checkbox.checked ? 'grid' : 'none';
        }
        
        showInNavbar.addEventListener('change', () => toggleFields(showInNavbar, navbarFields));
        showInSidebar.addEventListener('change', () => toggleFields(showInSidebar, sidebarFields));
        showInFooter.addEventListener('change', () => toggleFields(showInFooter, footerFields));
        
        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        let slugEdited = false;
        
        titleInput.addEventListener('input', () => {
            if (!slugEdited) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
            }
        });
        
        slugInput.addEventListener('input', () => {
            slugEdited = true;
        });
    });
</script>
@endsection