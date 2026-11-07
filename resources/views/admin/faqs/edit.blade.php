@extends('admin.layout')

@section('title', 'Edit FAQ')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit FAQ</h1>
            <p class="text-xs text-gray-500 mt-1 max-w-lg truncate">{{ $faq->question }}</p>
        </div>
        <a href="{{ route('admin.faqs.index') }}" class="btn-secondary text-xs">Back to FAQs</a>
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

    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">FAQ Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category *</label>
                    <input type="text" name="category" value="{{ old('category', $faq->category) }}" required list="categories-list" class="form-input w-full text-sm font-semibold">
                    <datalist id="categories-list">
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}">
                        @endforeach
                        <option value="General & Booking">
                        <option value="Permits & Documentation">
                        <option value="Health, Altitude & Safety">
                        <option value="Packing & Equipment">
                        <option value="Accommodations & Food">
                    </datalist>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Question *</label>
                <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="form-input w-full text-base font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Answer *</label>
                <textarea name="answer" rows="6" required class="form-input w-full text-sm leading-relaxed">{{ old('answer', $faq->answer) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $faq->is_published) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published (Visible in accordions and FAQ page)</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.faqs.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Update FAQ</button>
        </div>
    </form>
</div>
@endsection

