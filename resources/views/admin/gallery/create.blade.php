@extends('admin.layout')

@section('title', 'Add Gallery Photo')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Gallery Photo</h1>
            <p class="text-xs text-gray-500 mt-1">Upload or link high-resolution mountain photography</p>
        </div>
        <a href="{{ route('admin.gallery.index') }}" class="btn-secondary text-xs">Back to Gallery</a>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs space-y-1">
        @foreach($errors->all() as $err)
        <div>• {{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Photo Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Photo Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="form-input w-full text-sm font-semibold" placeholder="e.g. Ama Dablam Sunrise from Chhukung">
                </div>

                <div class="md:col-span-2">
                    <x-admin.image-upload name="image" label="Photo File" aspectRatio="4/3" :value="old('image')" :required="true" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Gallery Category</label>
                    <select name="category_id" class="form-input w-full text-sm">
                        <option value="">Select Category...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="form-input w-full text-sm" placeholder="e.g. Khumbu, Solukhumbu, Nepal">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alt Text (Accessibility & SEO)</label>
                    <input type="text" name="alt_text" value="{{ old('alt_text') }}" class="form-input w-full text-sm" placeholder="e.g. Snow-capped peak of Ama Dablam against blue sky">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Caption / Story</label>
                <textarea name="caption" rows="3" class="form-input w-full text-sm" placeholder="Optional story, camera settings or trek memory...">{{ old('caption') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Visible in Public Gallery</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.gallery.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Add Photo</button>
        </div>
    </form>
</div>
@endsection

