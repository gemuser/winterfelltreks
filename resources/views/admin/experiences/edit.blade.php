@extends('admin.layout')

@section('title', 'Edit ' . $experience->name)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Experience</h1>
            <p class="text-xs text-gray-500 mt-1">{{ $experience->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url('/experiences/' . $experience->slug) }}" target="_blank" class="btn-secondary text-xs">View Public Page</a>
            <a href="{{ route('admin.experiences.index') }}" class="btn-secondary text-xs">Back to Experiences</a>
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

    <form method="POST" action="{{ route('admin.experiences.update', $experience) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Experience Overview</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Experience Name *</label>
                    <input type="text" name="name" value="{{ old('name', $experience->name) }}" required class="form-input w-full text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $experience->slug) }}" class="form-input w-full text-sm font-mono text-gray-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category *</label>
                    <input type="text" name="category" value="{{ old('category', $experience->category) }}" required class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Recommended Duration</label>
                    <input type="text" name="recommended_duration" value="{{ old('recommended_duration', $experience->recommended_duration) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Hero Image URL</label>
                <input type="text" name="hero_image" value="{{ old('hero_image', $experience->hero_image) }}" class="form-input w-full text-sm font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Short Description (Excerpt)</label>
                <textarea name="short_description" rows="2" class="form-input w-full text-sm">{{ old('short_description', $experience->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Overview</label>
                <textarea name="overview" rows="3" class="form-input w-full text-sm">{{ old('overview', $experience->overview) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Detailed Description & Itinerary Highlights</label>
                <textarea name="detailed_description" rows="5" class="form-input w-full text-sm font-mono">{{ old('detailed_description', $experience->detailed_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Important Information & Gear</label>
                <textarea name="important_information" rows="3" class="form-input w-full text-sm">{{ old('important_information', $experience->important_information) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $experience->is_published) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published (Visible on site)</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.experiences.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Update Experience</button>
        </div>
    </form>
</div>
@endsection

