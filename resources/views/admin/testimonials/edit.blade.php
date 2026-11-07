@extends('admin.layout')

@section('title', 'Edit Testimonial')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Testimonial</h1>
            <p class="text-xs text-gray-500 mt-1">{{ $testimonial->customer_name }}</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary text-xs">Back to Testimonials</a>
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

    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Review Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Traveler Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', $testimonial->customer_name) }}" required class="form-input w-full text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Trek / Expedition Completed</label>
                    <input type="text" name="destination_or_package" value="{{ old('destination_or_package', $testimonial->destination_or_package) }}" class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Star Rating (1-5)</label>
                    <select name="rating" class="form-input w-full text-sm">
                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>★★★★★ (5 Stars)</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>★★★★☆ (4 Stars)</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>★★★☆☆ (3 Stars)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Display Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <x-admin.image-upload name="customer_photo" label="Client Avatar / Photo" aspectRatio="1/1" :value="old('customer_photo', $testimonial->customer_photo)" />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Review Quote / Testimonial Text *</label>
                <textarea name="testimonial_text" rows="5" required class="form-input w-full text-sm leading-relaxed">{{ old('testimonial_text', $testimonial->testimonial_text) }}</textarea>
            </div>

            <div class="pt-2 flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $testimonial->is_published) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $testimonial->is_verified) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Verified Traveler Badge</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Update Testimonial</button>
        </div>
    </form>
</div>
@endsection

