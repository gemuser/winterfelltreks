@extends('admin.layout')

@section('title', 'Enquiries')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Enquiries</h1>
    </div>
    
    {{-- Filters --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200 flex flex-wrap gap-4">
        <div class="flex items-center gap-2">
            <label for="filter-status" class="text-sm font-medium text-gray-700">Status:</label>
            <select id="filter-status" class="form-input w-auto" onchange="filterEnquiries()">
                <option value="">All</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="quotation_sent" {{ request('status') == 'quotation_sent' ? 'selected' : '' }}>Quotation Sent</option>
                <option value="follow_up_required" {{ request('status') == 'follow_up_required' ? 'selected' : '' }}>Follow-up Required</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        
        <div class="flex items-center gap-2">
            <label for="filter-type" class="text-sm font-medium text-gray-700">Type:</label>
            <select id="filter-type" class="form-input w-auto" onchange="filterEnquiries()">
                <option value="">All</option>
                <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                <option value="destination" {{ request('type') == 'destination' ? 'selected' : '' }}>Destination</option>
                <option value="package" {{ request('type') == 'package' ? 'selected' : '' }}>Package</option>
                <option value="experience" {{ request('type') == 'experience' ? 'selected' : '' }}>Experience</option>
            </select>
        </div>
        
        <input type="text" id="search-enquiries" placeholder="Search by name, email, reference..." class="form-input w-64" oninput="debounceEnquirySearch(this.value)">
    </div>
    
    {{-- Enquiries Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="enquiries-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Context</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($enquiries as $enquiry)
                    <tr class="hover:bg-gray-50" data-status="{{ $enquiry->status }}" data-type="{{ $enquiry->enquiry_type }}" data-search="{{ strtolower($enquiry->full_name . ' ' . $enquiry->email . ' ' . $enquiry->reference_number) }}">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $enquiry->reference_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $enquiry->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $enquiry->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ $enquiry->enquiry_type }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $enquiry->getContextName() }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClass = match($enquiry->status) {
                                    'new' => 'bg-red-50 text-red-700',
                                    'under_review' => 'bg-yellow-50 text-yellow-700',
                                    'contacted' => 'bg-blue-50 text-blue-700',
                                    'quotation_sent' => 'bg-purple-50 text-purple-700',
                                    'follow_up_required' => 'bg-orange-50 text-orange-700',
                                    'closed' => 'bg-green-50 text-green-700',
                                    default => 'bg-gray-50 text-gray-700',
                                };
                            @endphp
                            <select onchange="updateStatus({{ $enquiry->id }}, this.value)" class="form-input w-auto text-center text-sm {{ $statusClass }}">
                                <option value="new" {{ $enquiry->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="under_review" {{ $enquiry->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="contacted" {{ $enquiry->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="quotation_sent" {{ $enquiry->status == 'quotation_sent' ? 'selected' : '' }}>Quotation Sent</option>
                                <option value="follow_up_required" {{ $enquiry->status == 'follow_up_required' ? 'selected' : '' }}>Follow-up Required</option>
                                <option value="closed" {{ $enquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $enquiry->submitted_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="text-accent-primary hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($enquiries->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $enquiries->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function filterEnquiries() {
        const status = document.getElementById('filter-status').value;
        const type = document.getElementById('filter-type').value;
        const search = document.getElementById('search-enquiries').value.toLowerCase();
        
        document.querySelectorAll('#enquiries-table tbody tr').forEach(row => {
            let show = true;
            
            if (status && row.dataset.status !== status) show = false;
            if (type && row.dataset.type !== type) show = false;
            if (search && !row.dataset.search.includes(search)) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }
    
    let debounceTimer;
    function debounceEnquirySearch(value) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('search-enquiries').value = value;
            filterEnquiries();
        }, 300);
    }
    
    function updateStatus(enquiryId, status) {
        const select = event.target;
        if (select) {
            select.disabled = true;
            select.style.opacity = '0.6';
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;

        fetch(`/admin/enquiries/${enquiryId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status })
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to update status');
                if (select) {
                    select.disabled = false;
                    select.style.opacity = '1';
                }
                window.location.reload();
            }
        }).catch(err => {
            alert('Network error while updating status');
            if (select) {
                select.disabled = false;
                select.style.opacity = '1';
            }
        });
    }
</script>
@endsection