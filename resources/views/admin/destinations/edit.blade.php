@extends('admin.layout')

@section('title', 'Edit ' . $destination->name)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Destination</h1>
            <p class="text-xs text-gray-500 mt-1">{{ $destination->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url('/destinations/' . $destination->slug) }}" target="_blank" class="btn-secondary text-xs">View Public Page</a>
            <a href="{{ route('admin.destinations.index') }}" class="btn-secondary text-xs">Back to Destinations</a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-semibold">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs space-y-1">
        @foreach($errors->all() as $err)
        <div>• {{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Destination Overview</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Destination Name *</label>
                    <input type="text" name="name" value="{{ old('name', $destination->name) }}" required class="form-input w-full text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $destination->slug) }}" class="form-input w-full text-sm font-mono text-gray-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Country *</label>
                    <input type="text" name="country" value="{{ old('country', $destination->country) }}" required class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Region / Province</label>
                    <input type="text" name="region" value="{{ old('region', $destination->region) }}" class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category', $destination->category) }}" class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Best Time to Visit</label>
                    <input type="text" name="best_time_to_visit" value="{{ old('best_time_to_visit', $destination->best_time_to_visit) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <x-admin.image-upload name="featured_image" label="Featured Image" :value="old('featured_image', $destination->featured_image)" />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Short Summary (Excerpt)</label>
                <textarea name="short_description" rows="2" class="form-input w-full text-sm">{{ old('short_description', $destination->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Full Detailed Description</label>
                <textarea name="full_description" rows="5" class="form-input w-full text-sm font-mono">{{ old('full_description', $destination->full_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Travel & Permit Information</label>
                <textarea name="travel_information" rows="3" class="form-input w-full text-sm">{{ old('travel_information', $destination->travel_information) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $destination->is_published) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published (Visible on site)</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.destinations.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Update Destination</button>
        </div>
    </form>
</div>
@endsection

