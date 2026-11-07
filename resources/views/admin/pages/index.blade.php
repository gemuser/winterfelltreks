@extends('admin.layout')

@section('title', 'Pages')

@section('content')
<div class="space-y-6" x-data="pagesTableManager()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pages</h1>
            <p class="text-xs text-gray-500 mt-1">Pages are sorted by their live website navbar placement order</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Page
        </a>
    </div>
    
    {{-- Filters --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200 flex flex-wrap gap-4 items-center justify-between">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="filter-published" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Status:</label>
                <select id="filter-published" class="form-input text-xs py-1.5 w-auto" onchange="filterTable()">
                    <option value="">All</option>
                    <option value="1" {{ request('published') == '1' ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ request('published') == '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <label for="filter-navbar" class="text-xs font-bold text-gray-700 uppercase tracking-wider">In Navbar:</label>
                <select id="filter-navbar" class="form-input text-xs py-1.5 w-auto" onchange="filterTable()">
                    <option value="">All</option>
                    <option value="1" {{ request('navbar') == '1' ? 'selected' : '' }}>In Navbar</option>
                    <option value="0" {{ request('navbar') == '0' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
        </div>
        
        <input type="text" id="search-pages" placeholder="Search pages..." class="form-input text-xs py-1.5 w-64" oninput="debounceSearch(this.value)">
    </div>
    
    {{-- Pages Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full" id="pages-table">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Page Title</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Layout Type</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Navbar Placement</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Sections</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pages as $page)
                    <tr class="hover:bg-gray-50 transition-colors {{ $page->show_in_navbar ? 'bg-white' : 'bg-gray-50/40' }}" 
                        data-published="{{ $page->is_published ? '1' : '0' }}" 
                        data-navbar="{{ $page->show_in_navbar ? '1' : '0' }}" 
                        data-title="{{ strtolower($page->title) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($page->show_in_navbar)
                                <span class="text-xs font-bold font-mono text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" title="Navbar Position">#{{ $page->navbar_order }}</span>
                                @endif
                                <div>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="font-bold text-gray-900 hover:text-accent-primary transition-colors">{{ $page->title }}</a>
                                    <div class="text-3xs text-gray-400 font-mono">{{ $page->layout_type }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-mono">
                            <a href="{{ url($page->slug === 'home' ? '/' : '/' . $page->slug) }}" target="_blank" class="hover:text-accent-primary flex items-center gap-1">
                                <span>/{{ $page->slug === 'home' ? '' : $page->slug }}</span>
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-3xs font-semibold bg-gray-100 text-gray-700">
                                {{ $page->layout_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($page->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Published</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($page->show_in_navbar)
                            <div class="inline-flex items-center gap-1.5 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                <button type="button" @click="movePage({{ $page->id }}, 'up')" class="text-gray-400 hover:text-emerald-700 p-0.5 rounded hover:bg-emerald-100 transition-colors" title="Move Up in Navbar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                                <span class="text-xs font-extrabold text-emerald-800 font-mono tracking-tight">Navbar #{{ $page->navbar_order }}</span>
                                <button type="button" @click="movePage({{ $page->id }}, 'down')" class="text-gray-400 hover:text-emerald-700 p-0.5 rounded hover:bg-emerald-100 transition-colors" title="Move Down in Navbar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                Hidden
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-semibold text-gray-600">{{ $page->sections_count }} blocks</td>
                        <td class="px-6 py-4 text-right text-xs">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn-primary py-1 px-3 text-xs font-bold shadow-2xs">Edit</a>
                                <form method="POST" action="{{ route('admin.pages.duplicate', $page) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 text-xs text-gray-600 hover:text-accent-primary font-semibold hover:bg-gray-100 rounded" title="Duplicate">Duplicate</button>
                                </form>
                                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline" onsubmit="return confirm('Delete this page? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-xs text-red-500 hover:text-red-700 font-semibold hover:bg-red-50 rounded" title="Delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($pages->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pages->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function pagesTableManager() {
        return {
            navOrder: @json($pages->where('show_in_navbar', true)->pluck('id')),

            async movePage(pageId, direction) {
                const list = [...this.navOrder];
                const idx = list.indexOf(pageId);
                if (idx === -1) return;

                const targetIdx = direction === 'up' ? idx - 1 : idx + 1;
                if (targetIdx < 0 || targetIdx >= list.length) return;

                const temp = list[idx];
                list[idx] = list[targetIdx];
                list[targetIdx] = temp;
                this.navOrder = list;

                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                try {
                    await fetch('{{ route("admin.pages.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ order: list })
                    });
                    window.location.reload();
                } catch (e) {
                    console.error('Error reordering pages:', e);
                }
            }
        }
    }

    function filterTable() {
        const published = document.getElementById('filter-published').value;
        const navbar = document.getElementById('filter-navbar').value;
        const search = document.getElementById('search-pages').value.toLowerCase();
        
        document.querySelectorAll('#pages-table tbody tr').forEach(row => {
            let show = true;
            
            if (published !== '' && row.dataset.published !== published) show = false;
            if (navbar !== '' && row.dataset.navbar !== navbar) show = false;
            if (search && !row.dataset.title.includes(search)) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }
    
    let debounceTimer;
    function debounceSearch(value) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('search-pages').value = value;
            filterTable();
        }, 300);
    }
</script>
@endsection