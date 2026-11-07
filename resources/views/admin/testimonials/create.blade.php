@extends('admin.layout')

@section('title', 'Add Testimonial')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Testimonial</h1>
            <p class="text-xs text-gray-500 mt-1">Add client feedback, trek review, or traveler quote</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary text-xs">Back to Testimonials</a>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs space-y-1">
        @foreach($errors->all() as $err)
        <div>• {{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Review Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Traveler Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="form-input w-full text-sm font-semibold" placeholder="e.g. Sarah Jenkins (UK)">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Trek / Expedition Completed</label>
                    <input type="text" name="destination_or_package" value="{{ old('destination_or_package') }}" class="form-input w-full text-sm" placeholder="e.g. Everest Base Camp Luxury Trek">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Star Rating (1-5)</label>
                    <select name="rating" class="form-input w-full text-sm">
                        <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>★★★★★ (5 Stars)</option>
                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>★★★★☆ (4 Stars)</option>
                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>★★★☆☆ (3 Stars)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Display Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Client Avatar / Photo URL</label>
                <input type="text" name="customer_photo" value="{{ old('customer_photo') }}" class="form-input w-full text-sm font-mono" placeholder="https://... or /images/testimonials/client.jpg">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Review Quote / Testimonial Text *</label>
                <textarea name="testimonial_text" rows="5" required class="form-input w-full text-sm leading-relaxed" placeholder="Client's review and impressions of the expedition...">{{ old('testimonial_text') }}</textarea>
            </div>

            <div class="pt-2 flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', true) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Verified Traveler Badge</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Add Testimonial</button>
        </div>
    </form>
</div>
@endsection

