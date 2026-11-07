@extends('admin.layout')

@section('title', 'Add Expedition')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Expedition</h1>
            <p class="text-gray-500 mt-1">Create an expedition package with itineraries and technical specs</p>
        </div>
        <a href="{{ route('admin.expeditions.index') }}" class="btn-secondary">Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.expeditions.store') }}" class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Expedition Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" required class="form-input w-full" value="{{ old('title') }}" placeholder="e.g. Everest Base Camp Trek">
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" id="slug" name="slug" class="form-input w-full" value="{{ old('slug') }}" placeholder="auto-generated-from-title">
            </div>

            <div>
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Region <span class="text-red-500">*</span></label>
                <input type="text" id="region" name="region" required class="form-input w-full" value="{{ old('region') }}" placeholder="e.g. Everest, Annapurna, Langtang">
            </div>

            <div>
                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Duration (Days) <span class="text-red-500">*</span></label>
                <input type="number" id="duration_days" name="duration_days" required min="1" class="form-input w-full" value="{{ old('duration_days', 14) }}">
            </div>

            <div>
                <label for="max_elevation" class="block text-sm font-medium text-gray-700 mb-2">Max Elevation <span class="text-red-500">*</span></label>
                <input type="text" id="max_elevation" name="max_elevation" required class="form-input w-full" value="{{ old('max_elevation') }}" placeholder="e.g. 5,364m / 17,598ft">
            </div>

            <div>
                <label for="technical_grade" class="block text-sm font-medium text-gray-700 mb-2">Technical Grade <span class="text-red-500">*</span></label>
                <select id="technical_grade" name="technical_grade" required class="form-input w-full">
                    <option value="Moderate" {{ old('technical_grade') === 'Moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="Challenging" {{ old('technical_grade') === 'Challenging' ? 'selected' : '' }}>Challenging</option>
                    <option value="Strenuous" {{ old('technical_grade') === 'Strenuous' ? 'selected' : '' }}>Strenuous</option>
                    <option value="Technical" {{ old('technical_grade') === 'Technical' ? 'selected' : '' }}>Technical</option>
                    <option value="Extreme" {{ old('technical_grade') === 'Extreme' ? 'selected' : '' }}>Extreme</option>
                </select>
            </div>

            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image URL</label>
                <input type="text" id="featured_image" name="featured_image" class="form-input w-full" value="{{ old('featured_image') }}" placeholder="https://... or /images/...">
            </div>

            <div class="md:col-span-2">
                <label for="route_overview" class="block text-sm font-medium text-gray-700 mb-2">Route Overview</label>
                <textarea id="route_overview" name="route_overview" rows="4" class="form-input w-full" placeholder="Brief summary of the trail and mountain experience...">{{ old('route_overview') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="itinerary" class="block text-sm font-medium text-gray-700 mb-2">Itinerary (JSON Array)</label>
                <textarea id="itinerary" name="itinerary" rows="6" class="form-input w-full font-mono text-sm" placeholder='[{"day": 1, "title": "Arrival in Kathmandu", "desc": "Transfer to hotel"}]'>{{ old('itinerary') }}</textarea>
            </div>

            <div>
                <label for="included_services" class="block text-sm font-medium text-gray-700 mb-2">Included Services (JSON)</label>
                <textarea id="included_services" name="included_services" rows="4" class="form-input w-full font-mono text-sm" placeholder='["Airport transfers", "Permits", "Guide & porters"]'>{{ old('included_services') }}</textarea>
            </div>

            <div>
                <label for="excluded_services" class="block text-sm font-medium text-gray-700 mb-2">Excluded Services (JSON)</label>
                <textarea id="excluded_services" name="excluded_services" rows="4" class="form-input w-full font-mono text-sm" placeholder='["International flights", "Travel insurance", "Personal expenses"]'>{{ old('excluded_services') }}</textarea>
            </div>

            <div class="flex items-center gap-6 md:col-span-2 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="form-checkbox h-4 w-4" {{ old('is_published', true) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Published</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="form-checkbox h-4 w-4" {{ old('is_featured') ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Featured Package</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.expeditions.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Expedition</button>
        </div>
    </form>
</div>
@endsection

