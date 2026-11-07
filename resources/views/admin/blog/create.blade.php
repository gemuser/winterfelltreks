@extends('admin.layout')

@section('title', 'Write Article')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Write Article</h1>
            <p class="text-xs text-gray-500 mt-1">Publish an editorial, trek guide, or mountain safety article</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="btn-secondary text-xs">Back to Articles</a>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs space-y-1">
        @foreach($errors->all() as $err)
        <div>• {{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.blog.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5 shadow-xs">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Article Content</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Article Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="form-input w-full text-base font-bold" placeholder="e.g. Complete Preparation Guide for Everest Base Camp">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="form-input w-full text-sm font-mono text-gray-600" placeholder="auto-generated from title if blank">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="form-input w-full text-sm">
                        <option value="">Select Category...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Author Name</label>
                    <input type="text" name="author" value="{{ old('author', 'Winterfell Expedition Team') }}" class="form-input w-full text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Published Date</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="form-input w-full text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image URL</label>
                <input type="text" name="cover_image" value="{{ old('cover_image') }}" class="form-input w-full text-sm font-mono" placeholder="https://... or /images/blog/cover.jpg">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Excerpt / Brief Summary</label>
                <textarea name="excerpt" rows="2" class="form-input w-full text-sm" placeholder="Catchy teaser for search engines and article cards...">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Article Full Content * (Markdown or HTML)</label>
                <textarea name="content" rows="12" required class="form-input w-full text-sm font-mono leading-relaxed" placeholder="Write your full guide and article content here...">{{ old('content') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-gray-800">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-accent-primary rounded">
                    <span>Published (Visible to readers)</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.blog.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary px-6 py-2">Publish Article</button>
        </div>
    </form>
</div>
@endsection

