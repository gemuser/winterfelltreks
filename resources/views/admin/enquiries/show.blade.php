@extends('admin.layout')

@section('title', 'Enquiry: ' . $enquiry->reference_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Enquiry Details</h1>
            <p class="text-gray-500 mt-1">Reference: {{ $enquiry->reference_number }}</p>
        </div>
        <a href="{{ route('admin.enquiries.index') }}" class="btn-secondary">Back to List</a>
    </div>
    
    {{-- Status & Quick Info --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Status</p>
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
                <div class="relative">
                    <select id="top-status-select" onchange="syncAndSaveStatus(this.value)" class="form-input w-full text-center font-bold text-sm rounded-lg cursor-pointer transition-opacity {{ $statusClass }}">
                        <option value="new" {{ $enquiry->status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="under_review" {{ $enquiry->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="contacted" {{ $enquiry->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="quotation_sent" {{ $enquiry->status == 'quotation_sent' ? 'selected' : '' }}>Quotation Sent</option>
                        <option value="follow_up_required" {{ $enquiry->status == 'follow_up_required' ? 'selected' : '' }}>Follow-up Required</option>
                        <option value="closed" {{ $enquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <div id="status-saving-hint" class="hidden text-3xs text-accent-primary font-bold mt-1 text-center">
                        Updating status & sending email...
                    </div>
                </div>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="font-medium text-gray-900 capitalize">{{ $enquiry->enquiry_type }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Context</p>
                <p class="font-medium text-gray-900">{{ $enquiry->getContextName() }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Submitted</p>
                <p class="font-medium text-gray-900">{{ $enquiry->submitted_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" class="btn-secondary" onclick="sendAutoReply()">Send Auto-Reply</button>
            <button type="button" class="btn-secondary" onclick="sendNotification()">Notify Team</button>
        </div>
    </div>
    
    {{-- Customer Info --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Full Name</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->full_name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Email</dt>
                <dd class="font-medium text-gray-900"><a href="mailto:{{ $enquiry->email }}" class="text-accent-primary hover:underline">{{ $enquiry->email }}</a></dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Phone</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->phone ?? 'Not provided' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Country</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->country ?? 'Not provided' }}</dd>
            </div>
        </dl>
    </div>
    
    {{-- Travel Info --}}
    @if($enquiry->enquiry_type !== 'general')
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Travel Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @if($enquiry->destination)
            <div>
                <dt class="text-sm text-gray-500">Destination</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->destination->name }}</dd>
            </div>
            @endif
            @if($enquiry->expedition)
            <div>
                <dt class="text-sm text-gray-500">Package</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->expedition->title }}</dd>
            </div>
            @endif
            @if($enquiry->experience)
            <div>
                <dt class="text-sm text-gray-500">Experience</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->experience->name }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-sm text-gray-500">Travel Type</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->travel_type ?? 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Departure Date</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->expected_departure_date ? $enquiry->expected_departure_date->format('M d, Y') : 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Return Date</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->expected_return_date ? $enquiry->expected_return_date->format('M d, Y') : 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Travellers</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->number_of_travellers }} ({{ $enquiry->number_of_adults ?? 0 }} adults, {{ $enquiry->number_of_children ?? 0 }} children)</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Accommodation</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->accommodation_preference ?? 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Budget Range</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->budget_range ?? 'Not specified' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Flexibility</dt>
                <dd class="font-medium text-gray-900">{{ $enquiry->travel_flexibility ?? 'Not specified' }}</dd>
            </div>
        </dl>
    </div>
    @endif
    
    {{-- Preferences --}}
    @if($enquiry->preferred_activities || $enquiry->additional_message || $enquiry->special_requirements || $enquiry->dietary_requirements || $enquiry->accessibility_requirements)
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Preferences & Requirements</h2>
        <div class="space-y-4">
            @if($enquiry->preferred_activities)
            <div>
                <dt class="text-sm text-gray-500">Preferred Activities</dt>
                <dd class="font-medium text-gray-900 flex flex-wrap gap-2">
                    @foreach(json_decode($enquiry->preferred_activities, true) as $activity)
                    <span class="px-2 py-1 bg-gray-100 rounded text-sm">{{ $activity }}</span>
                    @endforeach
                </dd>
            </div>
            @endif
            
            @if($enquiry->additional_message)
            <div>
                <dt class="text-sm text-gray-500">Additional Message</dt>
                <dd class="font-medium text-gray-900 whitespace-pre-wrap">{{ $enquiry->additional_message }}</dd>
            </div>
            @endif
            
            @if($enquiry->special_requirements)
            <div>
                <dt class="text-sm text-gray-500">Special Requirements</dt>
                <dd class="font-medium text-gray-900 whitespace-pre-wrap">{{ $enquiry->special_requirements }}</dd>
            </div>
            @endif
            
            @if($enquiry->dietary_requirements)
            <div>
                <dt class="text-sm text-gray-500">Dietary Requirements</dt>
                <dd class="font-medium text-gray-900 whitespace-pre-wrap">{{ $enquiry->dietary_requirements }}</dd>
            </div>
            @endif
            
            @if($enquiry->accessibility_requirements)
            <div>
                <dt class="text-sm text-gray-500">Accessibility Requirements</dt>
                <dd class="font-medium text-gray-900 whitespace-pre-wrap">{{ $enquiry->accessibility_requirements }}</dd>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    {{-- Internal Notes --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Internal Management</h2>
        
        <form method="POST" action="{{ route('admin.enquiries.status', $enquiry) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="assigned_staff_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Staff</label>
                    <select id="assigned_staff_id" name="assigned_staff_id" class="form-input w-full">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ $enquiry->assigned_staff_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" onchange="const t = document.getElementById('top-status-select'); if (t) t.value = this.value;" class="form-input w-full font-semibold">
                        <option value="new" {{ $enquiry->status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="under_review" {{ $enquiry->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="contacted" {{ $enquiry->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="quotation_sent" {{ $enquiry->status == 'quotation_sent' ? 'selected' : '' }}>Quotation Sent</option>
                        <option value="follow_up_required" {{ $enquiry->status == 'follow_up_required' ? 'selected' : '' }}>Follow-up Required</option>
                        <option value="closed" {{ $enquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="internal_notes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                <textarea id="internal_notes" name="internal_notes" rows="4" class="form-input w-full">{{ $enquiry->internal_notes }}</textarea>
            </div>
            
            <button type="submit" id="save-management-btn" class="btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function syncAndSaveStatus(newStatus) {
        const bottomSelect = document.getElementById('status');
        if (bottomSelect) bottomSelect.value = newStatus;

        const topSelect = document.getElementById('top-status-select');
        const hint = document.getElementById('status-saving-hint');
        const saveBtn = document.getElementById('save-management-btn');

        if (topSelect) topSelect.disabled = true;
        if (bottomSelect) bottomSelect.disabled = true;
        if (saveBtn) saveBtn.disabled = true;
        if (hint) hint.classList.remove('hidden');

        const token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;

        fetch(`/admin/enquiries/{{ $enquiry->id }}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to update status.');
                if (topSelect) topSelect.disabled = false;
                if (bottomSelect) bottomSelect.disabled = false;
                if (saveBtn) saveBtn.disabled = false;
                if (hint) hint.classList.add('hidden');
            }
        }).catch(err => {
            alert('Network error while updating status.');
            if (topSelect) topSelect.disabled = false;
            if (bottomSelect) bottomSelect.disabled = false;
            if (saveBtn) saveBtn.disabled = false;
            if (hint) hint.classList.add('hidden');
        });
    }

    // Attach submit listener to bottom form to prevent double-submitting
    document.querySelector('form[action="{{ route('admin.enquiries.status', $enquiry) }}"]')?.addEventListener('submit', function() {
        const btn = document.getElementById('save-management-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Saving Changes...';
        }
    });
    
    function sendAutoReply() {
        if (!confirm('Send confirmation email to customer ({{ $enquiry->email }})?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.enquiries.auto-reply', $enquiry) }}';
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }
    
    function sendNotification() {
        if (!confirm('Send notification email to the company team?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.enquiries.notify-team', $enquiry) }}';
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection