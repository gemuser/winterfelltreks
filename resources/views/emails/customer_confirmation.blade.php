@extends('emails.layout')

@section('content')
    <h2>Namaste, {{ $enquiry->full_name }}! 🙏</h2>
    
    <p>Thank you for reaching out to <strong>Winterfell Treks & Expedition</strong>. We have successfully received your enquiry and our travel specialists are already reviewing your details.</p>
    
    <div class="info-box">
        <p style="margin: 0; font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Your Reference Number</p>
        <p style="margin: 4px 0 0; font-size: 20px; font-weight: 800; color: #ea580c; font-family: monospace;">{{ $enquiry->reference_number }}</p>
        <p style="margin: 6px 0 0; font-size: 13px; color: #475569;">Please quote this reference number whenever you correspond with us.</p>
    </div>

    <h3 style="font-size: 16px; color: #0f172a; margin: 24px 0 12px; font-weight: 700;">Summary of Your Request</h3>
    
    <table class="detail-table">
        <tr>
            <td class="label">Enquiry Type:</td>
            <td class="value">{{ ucfirst($enquiry->enquiry_type) }} ({{ $enquiry->getContextName() }})</td>
        </tr>
        @if($enquiry->expected_departure_date)
        <tr>
            <td class="label">Preferred Dates:</td>
            <td class="value">
                {{ $enquiry->expected_departure_date->format('M d, Y') }}
                @if($enquiry->expected_return_date)
                    to {{ $enquiry->expected_return_date->format('M d, Y') }}
                @endif
            </td>
        </tr>
        @endif
        <tr>
            <td class="label">Number of Travellers:</td>
            <td class="value">
                {{ $enquiry->number_of_travellers }} {{ Str::plural('person', $enquiry->number_of_travellers) }}
                @if($enquiry->number_of_adults || $enquiry->number_of_children)
                    ({{ $enquiry->number_of_adults ?? 0 }} adults, {{ $enquiry->number_of_children ?? 0 }} children)
                @endif
            </td>
        </tr>
        @if($enquiry->country)
        <tr>
            <td class="label">Country of Residence:</td>
            <td class="value">{{ $enquiry->country }}</td>
        </tr>
        @endif
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
        @if($enquiry->additional_message)
        <tr>
            <td class="label">Your Message:</td>
            <td class="value" style="font-weight: 400; font-style: italic;">"{{ $enquiry->additional_message }}"</td>
        </tr>
        @endif
    </table>

    <h3 style="font-size: 16px; color: #0f172a; margin: 24px 0 8px; font-weight: 700;">What Happens Next?</h3>
    <p>Our dedicated travel consultant will review your request and craft a tailored itinerary with full cost estimates. We typically respond within <strong>12 to 24 hours</strong>.</p>
    
    <p>If you have urgent questions or need immediate expedition assistance, please feel free to reply directly to this email or call our 24/7 rescue & coordination line at <strong>{{ \App\Models\SiteSetting::getValue('phone_emergency') ?: '+977-9800000000' }}</strong>.</p>

    <p style="margin-top: 24px; color: #334155; font-weight: 600;">
        Warm regards,<br>
        <span style="color: #ea580c;">Winterfell Treks & Expedition Team</span>
    </p>
@endsection

