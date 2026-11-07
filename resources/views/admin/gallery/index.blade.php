@extends('admin.layout')

@section('title', 'Gallery & Photography')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gallery & Photography</h1>
            <p class="text-xs text-gray-500 mt-1">Manage Himalayan landscape photos and expedition galleries</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Upload / Add Photo</span>
        </a>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Title / Location</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($images as $img)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 w-28">
                            <div class="w-20 h-14 rounded-lg overflow-hidden border border-gray-200 bg-gray-900 shrink-0">
                                <img src="{{ $img->image }}" alt="{{ $img->title }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 text-sm">{{ $img->title }}</div>
                            <div class="text-xs text-gray-400">{{ $img->location ?: 'Nepal Himalayas' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($img->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $img->category->name }}</span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-mono text-gray-500">
                            #{{ $img->sort_order }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($img->is_visible)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Visible</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Hidden</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.gallery.edit', $img) }}" class="text-accent-primary hover:underline font-semibold text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $img) }}" class="inline" onsubmit="return confirm('Delete photo &quot;{{ $img->title }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">No gallery photos added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($images->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $images->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

