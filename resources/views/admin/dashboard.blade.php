@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('admin.pages.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Total Pages</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pages'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['published_pages'] }} published</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.destinations.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Destinations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['destinations'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['published_destinations'] }} published</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.expeditions.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Expeditions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['expeditions'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['published_expeditions'] }} published</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.experiences.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Experiences</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['experiences'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['published_experiences'] }} published</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <a href="{{ route('admin.blog.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 lg:col-span-2 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Blog & Guides</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['blog_posts'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['published_posts'] }} published</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.testimonials.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Testimonials</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['testimonials'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['published_testimonials'] }} published</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.enquiries.index') }}" class="bg-white p-6 rounded-xl border border-gray-200 hover:border-accent-primary hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 group-hover:text-accent-primary transition-colors">Enquiries</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['enquiries_total'] }}</p>
                    <p class="text-sm text-accent-primary font-medium mt-1">{{ $stats['enquiries_new'] }} new · {{ $stats['enquiries_this_week'] }} this week</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </a>
    </div>
    
    {{-- Recent Enquiries --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-900">Recent Enquiries</h2>
            <a href="{{ route('admin.enquiries.index') }}" class="text-xs text-accent-primary font-bold hover:underline">View all</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Context</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recent_enquiries as $enquiry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $enquiry->reference_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $enquiry->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($enquiry->enquiry_type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $enquiry->getContextName() }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($enquiry->status)
                                    @case('new') bg-red-100 text-red-800 @break
                                    @case('under_review') bg-yellow-100 text-yellow-800 @break
                                    @case('contacted') bg-blue-100 text-blue-800 @break
                                    @case('quotation_sent') bg-purple-100 text-purple-800 @break
                                    @case('follow_up_required') bg-orange-100 text-orange-800 @break
                                    @case('closed') bg-green-100 text-green-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $enquiry->submitted_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="text-accent-primary hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Quick Creation Shortcuts --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-xs">
        <h2 class="text-base font-bold text-gray-900 mb-4">Quick Creation Shortcuts</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('admin.expeditions.create') }}" class="p-3.5 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-orange-50/50 transition-all text-center group">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform text-sm font-bold">🏔️</div>
                <p class="font-bold text-gray-900 text-xs">+ Expedition</p>
                <p class="text-4xs text-gray-400 mt-0.5">Tour package</p>
            </a>

            <a href="{{ route('admin.destinations.create') }}" class="p-3.5 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-orange-50/50 transition-all text-center group">
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform text-sm font-bold">📍</div>
                <p class="font-bold text-gray-900 text-xs">+ Destination</p>
                <p class="text-4xs text-gray-400 mt-0.5">Region or peak</p>
            </a>

            <a href="{{ route('admin.experiences.create') }}" class="p-3.5 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-orange-50/50 transition-all text-center group">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform text-sm font-bold">✨</div>
                <p class="font-bold text-gray-900 text-xs">+ Experience</p>
                <p class="text-4xs text-gray-400 mt-0.5">Curated tour</p>
            </a>

            <a href="{{ route('admin.blog.create') }}" class="p-3.5 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-orange-50/50 transition-all text-center group">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform text-sm font-bold">📰</div>
                <p class="font-bold text-gray-900 text-xs">+ Article</p>
                <p class="text-4xs text-gray-400 mt-0.5">Travel guide</p>
            </a>

            <a href="{{ route('admin.testimonials.create') }}" class="p-3.5 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-orange-50/50 transition-all text-center group">
                <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform text-sm font-bold">💬</div>
                <p class="font-bold text-gray-900 text-xs">+ Review</p>
                <p class="text-4xs text-gray-400 mt-0.5">Client quote</p>
            </a>

            <a href="{{ route('admin.gallery.create') }}" class="p-3.5 border border-gray-200 rounded-xl hover:border-accent-primary hover:bg-orange-50/50 transition-all text-center group">
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform text-sm font-bold">📷</div>
                <p class="font-bold text-gray-900 text-xs">+ Photo</p>
                <p class="text-4xs text-gray-400 mt-0.5">Gallery image</p>
            </a>
        </div>
    </div>
</div>
@endsection
