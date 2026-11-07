@extends('admin.layout')

@section('title', 'Expeditions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Expeditions</h1>
        <a href="{{ route('admin.expeditions.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Expedition
        </a>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Max Elevation</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($expeditions as $exp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $exp->title }}</div>
                            <div class="text-sm text-gray-500 font-mono">{{ $exp->slug }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $exp->region }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $exp->duration_days }} days</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $exp->max_elevation }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $exp->technical_grade }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($exp->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($exp->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.expeditions.edit', $exp) }}" class="text-accent-primary hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.expeditions.destroy', $exp) }}" class="inline" onsubmit="return confirm('Delete this expedition?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($expeditions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $expeditions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection