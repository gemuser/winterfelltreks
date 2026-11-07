<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Destination;
use App\Models\Expedition;
use App\Models\Experience;
use App\Models\SiteSetting;
use App\Mail\NewEnquiryCustomerConfirmation;
use App\Mail\NewEnquiryCompanyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enquiry_type' => ['required', Rule::in(['general', 'destination', 'package', 'experience'])],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'package_id' => ['nullable', 'exists:expeditions,id'],
            'experience_id' => ['nullable', 'exists:experiences,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'travel_type' => ['nullable', 'string', 'max:100'],
            'expected_departure_date' => ['nullable', 'date', 'after_or_equal:today'],
            'expected_return_date' => ['nullable', 'date', 'after_or_equal:expected_departure_date'],
            'number_of_travellers' => ['required', 'integer', 'min:1', 'max:50'],
            'number_of_adults' => ['nullable', 'integer', 'min:0'],
            'number_of_children' => ['nullable', 'integer', 'min:0'],
            'accommodation_preference' => ['nullable', 'string', 'max:100'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'preferred_activities' => ['nullable', 'array'],
            'travel_flexibility' => ['nullable', 'string', 'max:100'],
            'additional_message' => ['nullable', 'string'],
            'special_requirements' => ['nullable', 'string'],
            'dietary_requirements' => ['nullable', 'string'],
            'accessibility_requirements' => ['nullable', 'string'],
            'consent' => ['required', 'accepted'],
        ], [
            'consent.accepted' => 'You must agree to the privacy policy to submit an enquiry.',
            'expected_return_date.after_or_equal' => 'Return date must be after or equal to departure date.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below.');
        }

        // Auto-fill context from route parameters if not provided
        $enquiryData = $validator->validated();
        
        if (empty($enquiryData['destination_id']) && $request->filled('destination_slug')) {
            $destination = Destination::where('slug', $request->destination_slug)->first();
            if ($destination) {
                $enquiryData['destination_id'] = $destination->id;
                $enquiryData['enquiry_type'] = 'destination';
            }
        }

        if (empty($enquiryData['package_id']) && $request->filled('package_slug')) {
            $expedition = Expedition::where('slug', $request->package_slug)->first();
            if ($expedition) {
                $enquiryData['package_id'] = $expedition->id;
                $enquiryData['enquiry_type'] = 'package';
            }
        }

        if (empty($enquiryData['experience_id']) && $request->filled('experience_slug')) {
            $experience = Experience::where('slug', $request->experience_slug)->first();
            if ($experience) {
                $enquiryData['experience_id'] = $experience->id;
                $enquiryData['enquiry_type'] = 'experience';
            }
        }

        // Generate reference number
        $enquiryData['reference_number'] = 'WTE-' . strtoupper(uniqid());
        $enquiryData['submitted_at'] = now();

        // Create enquiry
        $enquiry = Enquiry::create($enquiryData);

        // Send notification emails
        $this->sendNotifications($enquiry);

        // Return success
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reference_number' => $enquiry->reference_number,
                'redirect' => route('enquiry.success', $enquiry->reference_number),
            ]);
        }

        return redirect()->route('enquiry.success', $enquiry->reference_number);
    }

    public function success(string $reference)
    {
        $enquiry = Enquiry::where('reference_number', $reference)->firstOrFail();
        
        return view('pages.enquiry-success', compact('enquiry'));
    }

    private function sendNotifications(Enquiry $enquiry): void
    {
        // 1. Send confirmation email to customer (to the email inserted by the user)
        if (!empty($enquiry->email)) {
            try {
                Mail::to($enquiry->email)->send(new NewEnquiryCustomerConfirmation($enquiry));
                Log::info("Enquiry confirmation email sent to user: {$enquiry->email} for [{$enquiry->reference_number}]");
            } catch (\Throwable $e) {
                Log::error("Failed to send customer confirmation email to {$enquiry->email}: " . $e->getMessage(), [
                    'reference' => $enquiry->reference_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 2. Send notification email to company
        $companyEmail = SiteSetting::getValue('enquiry_notification_email')
            ?: SiteSetting::getValue('email_primary')
            ?: SiteSetting::getValue('email_enquiries')
            ?: config('mail.from.address');

        if (!empty($companyEmail)) {
            try {
                Mail::to($companyEmail)->send(new NewEnquiryCompanyNotification($enquiry));
                Log::info("New enquiry notification email sent to company: {$companyEmail} for [{$enquiry->reference_number}]");
            } catch (\Throwable $e) {
                Log::error("Failed to send company notification email to {$companyEmail}: " . $e->getMessage(), [
                    'reference' => $enquiry->reference_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}