@extends('admin.layout')

@section('title', 'Edit Photo')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Photo</h1>
            <p class="text-xs text-gray-500 mt-1">{{ $image->title }}</p>
        </div>
        <a href="{{ route('admin.gallery.index') }}" class="btn-secondary text-xs">Back to Gallery</a>
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

    <form method="POST" action="{{ route('admin.gallery.update', $image) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Photo Details</h2>
            
            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
                <img src="{{ $image->image }}" alt="{{ $image->title }}" class="w-24 h-16 object-cover rounded-lg border border-gray-300">
                <div class="text-xs text-gray-500">
                    <span class="font-bold text-gray-800 block">Preview Image</span>
                    <span class="font-mono text-3xs text-gray-400 break-all">{{ $image->image }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Photo Title *</label>
                    <input type="text" name="title" value="{{ old('title', $image->title) }}" required class="form-input w-full text-sm font-semibold">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Image URL *</label>
                    <input type="text" name="image" value="{{ old('image', $image->image) }}" required class="form-input w-full text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Gallery Category</label>
                    <select name="category_id" class="form-input w-full text-sm">
                        <option value="">Select Category...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $image->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $image->location) }}" class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alt Text (Accessibility & SEO)</label>
                    <input type="text" name="alt_text" value="{{ old('alt_text', $image->alt_text) }}" class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $image->sort_order) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Caption / Story</label>
                <textarea name="caption" rows="3" class="form-input w-full text-sm">{{ old('caption', $image->caption) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $image->is_visible) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Visible in Public Gallery</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.gallery.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Update Photo</button>
        </div>
    </form>
</div>
@endsection

