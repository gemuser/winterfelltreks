@extends('emails.layout')

@section('content')
    @php
        $statusLabels = [
            'new' => 'New / Received',
            'under_review' => 'Under Review',
            'contacted' => 'In Contact / Discussing',
            'quotation_sent' => 'Quotation Sent',
            'follow_up_required' => 'Follow-up Required',
            'closed' => 'Completed / Closed',
        ];
        $statusDescriptions = [
            'new' => 'Your enquiry has been received and added to our review queue.',
            'under_review' => 'Our senior travel specialists are currently reviewing your preferences and assembling customized itinerary options for your Himalayan adventure.',
            'contacted' => 'Our team has initiated contact or replied to your inquiry. Please check your inbox or phone for details from our expedition consultant.',
            'quotation_sent' => 'We have prepared a complete, customized quotation and itinerary breakdown for your trip. Please review the details sent to your email.',
            'follow_up_required' => 'We have sent you questions or quotation options and are looking forward to your feedback to finalize your booking.',
            'closed' => 'This enquiry has been successfully finalized. Thank you for choosing Winterfell Treks & Expedition.',
        ];
        $currentLabel = $statusLabels[$newStatus] ?? ucfirst(str_replace('_', ' ', $newStatus));
        $currentDesc = $statusDescriptions[$newStatus] ?? 'Your enquiry status has been updated by our expedition coordination team.';
    @endphp

    <h2>Hello, {{ $enquiry->full_name }}! 👋</h2>
    
    <p>We are writing to let you know that the status of your Himalayan travel enquiry has been updated.</p>

    <div class="info-box" style="text-align: center; padding: 24px;">
        <p style="margin: 0; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Current Status</p>
        <div style="margin: 10px 0;">
            <span class="badge badge-{{ $newStatus }}" style="font-size: 14px; padding: 8px 18px;">
                {{ $currentLabel }}
            </span>
        </div>
        <p style="margin: 8px 0 0; font-size: 14px; color: #334155; line-height: 1.5;">
            {{ $currentDesc }}
        </p>
    </div>

    <table class="detail-table">
        <tr>
            <td class="label">Reference Number:</td>
            <td class="value"><span style="font-family: monospace; color: #ea580c;">{{ $enquiry->reference_number }}</span></td>
        </tr>
        <tr>
            <td class="label">Trip / Context:</td>
            <td class="value">{{ $enquiry->getContextName() }}</td>
        </tr>
        @if($enquiry->expected_departure_date)
        <tr>
            <td class="label">Planned Dates:</td>
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
            <td class="value">{{ $enquiry->number_of_travellers }} {{ Str::plural('person', $enquiry->number_of_travellers) }}</td>
        </tr>
    </table>

    <div style="background-color: #f8fafc; border-radius: 8px; padding: 18px; margin: 24px 0;">
        <h4 style="margin: 0 0 8px; font-size: 14px; color: #0f172a; font-weight: 700;">Have questions or want to adjust your plan?</h4>
        <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.6;">
            Simply reply directly to this email or reach us anytime at <a href="mailto:{{ \App\Models\SiteSetting::getValue('email_primary') ?: 'winterfelltreks@gmail.com' }}" style="color: #ea580c; text-decoration: underline;">{{ \App\Models\SiteSetting::getValue('email_primary') ?: 'winterfelltreks@gmail.com' }}</a>. Mention your reference number <strong>{{ $enquiry->reference_number }}</strong> for quick assistance.
        </p>
    </div>

    <p style="margin-top: 24px; color: #334155; font-weight: 600;">
        With adventure in mind,<br>
        <span style="color: #ea580c;">The Winterfell Treks & Expedition Team</span>
    </p>
@endsection

