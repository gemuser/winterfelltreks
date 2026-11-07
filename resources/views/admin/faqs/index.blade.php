@extends('admin.layout')

@section('title', 'FAQs')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Frequently Asked Questions</h1>
            <p class="text-xs text-gray-500 mt-1">Manage traveler Q&A regarding permits, packing, altitude and gear</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add FAQ</span>
        </a>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Question & Answer</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($faqs as $faq)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 max-w-lg">
                            <div class="font-bold text-gray-900 text-sm mb-1">{{ $faq->question }}</div>
                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ strip_tags($faq->answer) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-bold bg-purple-50 text-purple-700 border border-purple-200">{{ $faq->category }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-mono text-gray-500">
                            #{{ $faq->sort_order }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($faq->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-accent-primary hover:underline font-semibold text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="inline" onsubmit="return confirm('Delete FAQ &quot;{{ $faq->question }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">No FAQs added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($faqs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

