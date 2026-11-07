@extends('emails.layout')

@section('content')
    <div style="border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 20px;">
        <span class="badge badge-new" style="margin-bottom: 8px;">New Enquiry Received</span>
        <h2 style="margin: 8px 0 4px; font-size: 22px; color: #0f172a;">New Enquiry from {{ $enquiry->full_name }}</h2>
        <p style="margin: 0; color: #64748b; font-size: 13px;">Reference: <strong>{{ $enquiry->reference_number }}</strong> • Submitted {{ $enquiry->submitted_at ? $enquiry->submitted_at->format('M d, Y H:i') : now()->format('M d, Y H:i') }}</p>
    </div>

    <table class="detail-table">
        <tr>
            <td class="label">Customer Name:</td>
            <td class="value">{{ $enquiry->full_name }}</td>
        </tr>
        <tr>
            <td class="label">Customer Email:</td>
            <td class="value"><a href="mailto:{{ $enquiry->email }}" style="color: #ea580c; text-decoration: underline;">{{ $enquiry->email }}</a></td>
        </tr>
        <tr>
            <td class="label">Phone:</td>
            <td class="value">{{ $enquiry->phone ?? 'Not provided' }}</td>
        </tr>
        <tr>
            <td class="label">Country:</td>
            <td class="value">{{ $enquiry->country ?? 'Not provided' }}</td>
        </tr>
        <tr>
            <td class="label">Enquiry Type:</td>
            <td class="value"><strong style="text-transform: capitalize;">{{ $enquiry->enquiry_type }}</strong></td>
        </tr>
        <tr>
            <td class="label">Selected Context:</td>
            <td class="value"><strong>{{ $enquiry->getContextName() }}</strong></td>
        </tr>
        @if($enquiry->expected_departure_date)
        <tr>
            <td class="label">Expected Dates:</td>
            <td class="value">
                {{ $enquiry->expected_departure_date->format('M d, Y') }}
                @if($enquiry->expected_return_date)
                    to {{ $enquiry->expected_return_date->format('M d, Y') }}
                @endif
            </td>
        </tr>
        @endif
        <tr>
            <td class="label">Travellers:</td>
            <td class="value">
                {{ $enquiry->number_of_travellers }} total
                @if($enquiry->number_of_adults || $enquiry->number_of_children)
                    ({{ $enquiry->number_of_adults ?? 0 }} Adults, {{ $enquiry->number_of_children ?? 0 }} Children)
                @endif
            </td>
        </tr>
        @if($enquiry->accommodation_preference)
        <tr>
            <td class="label">Accommodation:</td>
            <td class="value">{{ ucfirst(str_replace('_', ' ', $enquiry->accommodation_preference)) }}</td>
        </tr>
        @endif
        @if($enquiry->budget_range)
        <tr>
            <td class="label">Budget Range:</td>
            <td class="value">{{ $enquiry->budget_range }}</td>
        </tr>
        @endif
        @if($enquiry->travel_flexibility)
        <tr>
            <td class="label">Flexibility:</td>
            <td class="value">{{ ucfirst($enquiry->travel_flexibility) }}</td>
        </tr>
        @endif
        @if(!empty($enquiry->preferred_activities))
        <tr>
            <td class="label">Activities:</td>
            <td class="value">{{ is_array($enquiry->preferred_activities) ? implode(', ', $enquiry->preferred_activities) : $enquiry->preferred_activities }}</td>
        </tr>
        @endif
        @if($enquiry->special_requirements)
        <tr>
            <td class="label">Special Requirements:</td>
            <td class="value">{{ $enquiry->special_requirements }}</td>
        </tr>
        @endif
        @if($enquiry->dietary_requirements)
        <tr>
            <td class="label">Dietary Notes:</td>
            <td class="value">{{ $enquiry->dietary_requirements }}</td>
        </tr>
        @endif
        @if($enquiry->additional_message)
        <tr>
            <td class="label">Customer Message:</td>
            <td class="value" style="font-weight: 400; background-color: #f8fafc; padding: 10px; border-radius: 4px;">{{ $enquiry->additional_message }}</td>
        </tr>
        @endif
    </table>

    <div style="text-align: center; margin: 30px 0 10px;">
        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn-action">
            Open in Admin Dashboard &rarr;
        </a>
    </div>
@endsection

