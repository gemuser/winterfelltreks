@extends('admin.layout')

@section('title', 'Articles & Travel Guide')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Articles & Travel Guide</h1>
            <p class="text-xs text-gray-500 mt-1">Manage Himalayan guides, advice, and expedition stories</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Write Article</span>
        </a>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Article</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Published Date</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($post->cover_image)
                                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-12 h-10 object-cover rounded-lg border border-gray-200 shrink-0">
                                @else
                                <div class="w-12 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-xs text-gray-400 shrink-0">📰</div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 text-sm max-w-md truncate">{{ $post->title }}</div>
                                    <div class="text-3xs text-gray-400 font-mono">/travel-guide/{{ $post->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($post->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-bold bg-sky-50 text-sky-700 border border-sky-200">{{ $post->category->name }}</span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-700">
                            {{ $post->author ?: 'Winterfell Team' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-mono text-gray-500">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($post->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ url('/travel-guide/' . $post->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-700 text-xs font-semibold" title="View Public Page">View</a>
                                <a href="{{ route('admin.blog.edit', $post) }}" class="text-accent-primary hover:underline font-semibold text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete article &quot;{{ $post->title }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">No articles published yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($posts->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

