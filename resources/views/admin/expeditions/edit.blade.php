@extends('admin.layout')

@section('title', 'Edit Expedition: ' . $expedition->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Expedition: {{ $expedition->title }}</h1>
            <p class="text-gray-500 mt-1">Update package details, itinerary, and specifications</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('packages.show', $expedition->slug) }}" target="_blank" class="btn-secondary">View on Site</a>
            <a href="{{ route('admin.expeditions.index') }}" class="btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.expeditions.update', $expedition) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Expedition Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" required class="form-input w-full" value="{{ old('title', $expedition->title) }}">
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug <span class="text-red-500">*</span></label>
                <input type="text" id="slug" name="slug" required class="form-input w-full" value="{{ old('slug', $expedition->slug) }}">
            </div>

            <div>
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Region <span class="text-red-500">*</span></label>
                <input type="text" id="region" name="region" required class="form-input w-full" value="{{ old('region', $expedition->region) }}">
            </div>

            <div>
                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Duration (Days) <span class="text-red-500">*</span></label>
                <input type="number" id="duration_days" name="duration_days" required min="1" class="form-input w-full" value="{{ old('duration_days', $expedition->duration_days) }}">
            </div>

            <div>
                <label for="max_elevation" class="block text-sm font-medium text-gray-700 mb-2">Max Elevation <span class="text-red-500">*</span></label>
                <input type="text" id="max_elevation" name="max_elevation" required class="form-input w-full" value="{{ old('max_elevation', $expedition->max_elevation) }}">
            </div>

            <div>
                <label for="technical_grade" class="block text-sm font-medium text-gray-700 mb-2">Technical Grade <span class="text-red-500">*</span></label>
                <select id="technical_grade" name="technical_grade" required class="form-input w-full">
                    @foreach(['Moderate', 'Challenging', 'Strenuous', 'Technical', 'Extreme'] as $grade)
                    <option value="{{ $grade }}" {{ old('technical_grade', $expedition->technical_grade) === $grade ? 'selected' : '' }}>{{ $grade }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <x-admin.image-upload name="featured_image" label="Featured Image" aspectRatio="16/9" :value="old('featured_image', $expedition->featured_image)" />
            </div>

            <div class="md:col-span-2">
                <label for="route_overview" class="block text-sm font-medium text-gray-700 mb-2">Route Overview</label>
                <textarea id="route_overview" name="route_overview" rows="4" class="form-input w-full">{{ old('route_overview', $expedition->route_overview) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="itinerary" class="block text-sm font-medium text-gray-700 mb-2">Itinerary (JSON Array)</label>
                <textarea id="itinerary" name="itinerary" rows="6" class="form-input w-full font-mono text-sm">{{ old('itinerary', is_string($expedition->itinerary) ? $expedition->itinerary : json_encode($expedition->itinerary, JSON_PRETTY_PRINT)) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="gear_checklist" class="block text-sm font-medium text-gray-700 mb-2">Gear Checklist (JSON - Optional)</label>
                <textarea id="gear_checklist" name="gear_checklist" rows="4" class="form-input w-full font-mono text-sm">{{ old('gear_checklist', is_string($expedition->gear_checklist) ? $expedition->gear_checklist : json_encode($expedition->gear_checklist, JSON_PRETTY_PRINT)) }}</textarea>
            </div>

            <div>
                <label for="included_services" class="block text-sm font-medium text-gray-700 mb-2">Included Services (JSON)</label>
                <textarea id="included_services" name="included_services" rows="4" class="form-input w-full font-mono text-sm">{{ old('included_services', is_string($expedition->included_services) ? $expedition->included_services : json_encode($expedition->included_services, JSON_PRETTY_PRINT)) }}</textarea>
            </div>

            <div>
                <label for="excluded_services" class="block text-sm font-medium text-gray-700 mb-2">Excluded Services (JSON)</label>
                <textarea id="excluded_services" name="excluded_services" rows="4" class="form-input w-full font-mono text-sm">{{ old('excluded_services', is_string($expedition->excluded_services) ? $expedition->excluded_services : json_encode($expedition->excluded_services, JSON_PRETTY_PRINT)) }}</textarea>
            </div>

            <div class="flex items-center gap-6 md:col-span-2 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="form-checkbox h-4 w-4" {{ old('is_published', $expedition->is_published) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Published</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="form-checkbox h-4 w-4" {{ old('is_featured', $expedition->is_featured) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Featured Package</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.expeditions.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Expedition</button>
        </div>
    </form>
</div>
@endsection

