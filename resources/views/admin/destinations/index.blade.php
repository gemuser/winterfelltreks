@extends('admin.layout')

@section('title', 'Destinations')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Destinations</h1>
            <p class="text-xs text-gray-500 mt-1">Manage Himalayan regions, peaks, and trek valleys</p>
        </div>
        <a href="{{ route('admin.destinations.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Destination</span>
        </a>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Destination</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Country / Region</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($destinations as $dest)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($dest->featured_image)
                                <img src="{{ $dest->featured_image }}" alt="{{ $dest->name }}" class="w-12 h-10 object-cover rounded-lg border border-gray-200 shrink-0">
                                @else
                                <div class="w-12 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-xs text-gray-400 shrink-0">📍</div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 text-sm">{{ $dest->name }}</div>
                                    <div class="text-3xs text-gray-400 font-mono">/destinations/{{ $dest->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium text-gray-800">{{ $dest->country }}</div>
                            <div class="text-xs text-gray-400">{{ $dest->region ?: '—' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dest->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-bold bg-orange-50 text-orange-700 border border-orange-200">{{ $dest->category }}</span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dest->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ url('/destinations/' . $dest->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-700 text-xs font-semibold" title="View Public Page">View</a>
                                <a href="{{ route('admin.destinations.edit', $dest) }}" class="text-accent-primary hover:underline font-semibold text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.destinations.destroy', $dest) }}" class="inline" onsubmit="return confirm('Delete destination &quot;{{ $dest->name }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">No destinations created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($destinations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $destinations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

