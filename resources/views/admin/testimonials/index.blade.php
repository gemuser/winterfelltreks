@extends('admin.layout')

@section('title', 'Testimonials & Reviews')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Testimonials & Client Reviews</h1>
            <p class="text-xs text-gray-500 mt-1">Manage traveler quotes, star ratings, and trek feedback</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Testimonial</span>
        </a>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Traveler</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Trek / Destination</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Verified</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($testimonials as $test)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($test->customer_photo)
                                <img src="{{ $test->customer_photo }}" alt="{{ $test->customer_name }}" class="w-10 h-10 object-cover rounded-full border border-gray-200 shrink-0">
                                @else
                                <div class="w-10 h-10 rounded-full bg-orange-100 text-accent-primary font-bold flex items-center justify-center text-xs border border-orange-200 shrink-0">
                                    {{ strtoupper(substr($test->customer_name, 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 text-sm">{{ $test->customer_name }}</div>
                                    <p class="text-3xs text-gray-500 line-clamp-1 max-w-xs">&ldquo;{{ $test->testimonial_text }}&rdquo;</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-700">
                            {{ $test->destination_or_package ?: 'Himalayan Expedition' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center text-amber-400 text-xs gap-0.5">
                                @for($i = 0; $i < ($test->rating ?? 5); $i++)
                                <span>★</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($test->is_verified)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-4xs font-extrabold uppercase bg-sky-100 text-sky-800 border border-sky-200">Verified</span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($test->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.testimonials.edit', $test) }}" class="text-accent-primary hover:underline font-semibold text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $test) }}" class="inline" onsubmit="return confirm('Delete testimonial from &quot;{{ $test->customer_name }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">No testimonials added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($testimonials->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $testimonials->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

