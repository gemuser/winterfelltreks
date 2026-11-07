@extends('admin.layout')

@section('title', 'Add Destination')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Destination</h1>
            <p class="text-xs text-gray-500 mt-1">Create a new Himalayan region or travel destination</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="btn-secondary text-xs">Back to Destinations</a>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs space-y-1">
        @foreach($errors->all() as $err)
        <div>• {{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.destinations.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Destination Overview</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Destination Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input w-full text-sm font-semibold" placeholder="e.g. Everest Region (Khumbu)">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="form-input w-full text-sm font-mono text-gray-600" placeholder="auto-generated from name if blank">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Country *</label>
                    <input type="text" name="country" value="{{ old('country', 'Nepal') }}" required class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Region / Province</label>
                    <input type="text" name="region" value="{{ old('region') }}" class="form-input w-full text-sm" placeholder="e.g. Solukhumbu, Eastern Nepal">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" class="form-input w-full text-sm" placeholder="e.g. High Altitude, Culture, Wildlife">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Best Time to Visit</label>
                    <input type="text" name="best_time_to_visit" value="{{ old('best_time_to_visit') }}" class="form-input w-full text-sm" placeholder="e.g. March - May & September - November">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Featured Image URL</label>
                <input type="text" name="featured_image" value="{{ old('featured_image') }}" class="form-input w-full text-sm font-mono" placeholder="https://... or /images/destinations/everest.jpg">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Short Summary (Excerpt)</label>
                <textarea name="short_description" rows="2" class="form-input w-full text-sm" placeholder="Brief teaser for grid cards...">{{ old('short_description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Full Detailed Description</label>
                <textarea name="full_description" rows="5" class="form-input w-full text-sm font-mono" placeholder="Rich description of geography, history, and mountains...">{{ old('full_description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Travel & Permit Information</label>
                <textarea name="travel_information" rows="3" class="form-input w-full text-sm" placeholder="Permit requirements, logistics, flights (e.g. Lukla), accommodations...">{{ old('travel_information') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published (Visible on site)</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.destinations.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Create Destination</button>
        </div>
    </form>
</div>
@endsection

