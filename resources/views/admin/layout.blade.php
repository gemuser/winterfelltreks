<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} | Winterfell Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/winterfell-nobg.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Sidebar always visible on large screens */
        @media (min-width: 1024px) {
            .sidebar-fixed { transform: translateX(0) !important; display: flex !important; }
        }
    </style>
</head>
@php
    $hideSidebar = View::hasSection('hide-sidebar');
@endphp
<body class="font-body antialiased bg-gray-50 {{ $hideSidebar ? 'h-screen overflow-hidden' : '' }}" x-data="{ sidebarOpen: false }" @toggle-admin-menu.window="sidebarOpen = !sidebarOpen">
    <div class="flex {{ $hideSidebar ? 'h-screen overflow-hidden' : 'min-h-screen' }}">
        @if($hideSidebar)
        {{-- Backdrop when admin sidebar is open in editor mode --}}
        <div class="fixed inset-0 z-[45] bg-black/60 backdrop-blur-2xs transition-opacity duration-300"
             x-show="sidebarOpen"
             @click.stop="sidebarOpen = false"
             x-transition.opacity
             style="display: none;"></div>
        @endif

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-950 text-white transition-transform duration-300 shadow-2xl {{ $hideSidebar ? '' : 'lg:translate-x-0' }}"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full {{ $hideSidebar ? '' : 'lg:translate-x-0' }}'"
               @click.outside="sidebarOpen = false">
            <div class="flex flex-col h-full">
                <div class="p-6 border-b border-gray-800 flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="/images/winterfell-nobg.png" alt="Winterfell Admin" class="h-10 w-auto object-contain p-1 bg-white/10 rounded">
                        <div>
                            <span class="font-display text-lg font-bold text-white block leading-tight">Winterfell</span>
                            <span class="text-accent-primary text-xs font-semibold uppercase tracking-wider">Admin Portal</span>
                        </div>
                    </a>
                    @if($hideSidebar)
                    <button type="button" @click="sidebarOpen = false" class="text-gray-400 hover:text-white p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    @endif
                </div>
                
                <nav class="flex-1 p-3 space-y-1 overflow-y-auto" role="navigation" aria-label="Admin navigation">
                    {{-- Overview --}}
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>

                    {{-- Visual Site Builder --}}
                    <div class="pt-3 pb-1 px-3.5 text-4xs font-extrabold uppercase tracking-wider text-gray-500">Site Builder</div>
                    
                    <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.pages*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Pages & Editor</span>
                    </a>

                    {{-- Expeditions & Travel Dynamic Modules --}}
                    <div class="pt-3 pb-1 px-3.5 text-4xs font-extrabold uppercase tracking-wider text-gray-500">Travel & Expeditions</div>
                    
                    <a href="{{ route('admin.expeditions.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.expeditions*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Expeditions / Treks</span>
                    </a>

                    <a href="{{ route('admin.destinations.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.destinations*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Destinations</span>
                    </a>

                    <a href="{{ route('admin.experiences.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.experiences*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        <span>Experiences</span>
                    </a>

                    {{-- Content & Media --}}
                    <div class="pt-3 pb-1 px-3.5 text-4xs font-extrabold uppercase tracking-wider text-gray-500">Content & Media</div>

                    <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.blog*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        <span>Articles & Guides</span>
                    </a>

                    <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.testimonials*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        <span>Testimonials</span>
                    </a>

                    <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.gallery*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Gallery Photos</span>
                    </a>

                    <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.faqs*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>FAQs</span>
                    </a>

                    {{-- Operations --}}
                    <div class="pt-3 pb-1 px-3.5 text-4xs font-extrabold uppercase tracking-wider text-gray-500">Operations</div>
                    
                    <a href="{{ route('admin.enquiries.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.enquiries*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span>Enquiries</span>
                        @php $newCount = \App\Models\Enquiry::where('status', 'new')->count(); @endphp
                        @if($newCount > 0)
                        <span class="ml-auto bg-accent-primary text-white text-3xs font-extrabold px-1.5 py-0.5 rounded-full">{{ $newCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.site-settings.index') }}" class="flex items-center gap-3 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ request()->routeIs('admin.site-settings*') ? 'bg-accent-primary text-white font-bold' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Site Settings</span>
                    </a>
                </nav>
                
                <div class="p-4 border-t border-gray-800">
                    <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        View Website
                    </a>
                </div>
            </div>
        </aside>
        
        {{-- Mobile sidebar overlay --}}
        <div class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity" x-transition:leave="transition-opacity"></div>

        {{-- Main Content --}}
        <div class="flex-1 {{ $hideSidebar ? 'ml-0 h-screen overflow-hidden flex flex-col' : 'lg:ml-64 min-h-screen' }}">
            @unless($hideSidebar)
            {{-- Top Bar --}}
            <header class="sticky top-0 z-20 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <button class="lg:hidden p-2 text-gray-500 hover:text-gray-700" @click="sidebarOpen = true" aria-label="Open sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div class="flex-1 lg:ml-0">
                        <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Admin' }}</h1>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 hidden sm:block">{{ auth()->user()?->name ?? 'Admin User' }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 font-medium">Logout</button>
                        </form>
                    </div>
                </div>
            </header>
            @endunless

            {{-- Page Content --}}
            <main class="@yield('main-classes', 'p-4 sm:p-6 lg:p-8') {{ $hideSidebar ? 'flex-1 h-full overflow-hidden' : '' }}">
                @if(session('success'))
                <div class="flash-alert mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg transition-opacity duration-300" x-data="{ show: true }" x-show="show">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="flash-alert mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg transition-opacity duration-300" x-data="{ show: true }" x-show="show">
                    {{ session('error') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>
    
    <script>
        // Global admin utilities
        document.addEventListener('DOMContentLoaded', () => {
            // Confirm delete actions
            document.querySelectorAll('[data-confirm]').forEach(el => {
                el.addEventListener('click', (e) => {
                    if (!confirm(el.dataset.confirm)) {
                        e.preventDefault();
                    }
                });
            });
            
            // Auto-hide flash messages
            setTimeout(() => {
                document.querySelectorAll('.flash-alert').forEach(el => {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 300);
                });
            }, 5000);
        });
    </script>
    
    @stack('admin-scripts')
</body>
</html>