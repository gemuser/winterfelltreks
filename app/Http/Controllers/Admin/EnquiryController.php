<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\SiteSetting;
use App\Mail\EnquiryStatusUpdated;
use App\Mail\NewEnquiryCustomerConfirmation;
use App\Mail\NewEnquiryCompanyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::with(['destination', 'expedition', 'experience', 'assignedStaff'])
            ->latest('submitted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('enquiry_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->paginate(20)->withQueryString();

        $statuses = ['new', 'under_review', 'contacted', 'quotation_sent', 'follow_up_required', 'closed'];
        $types = ['general', 'destination', 'package', 'experience'];

        return view('admin.enquiries.index', compact('enquiries', 'statuses', 'types'));
    }

    public function show(Enquiry $enquiry)
    {
        $enquiry->load(['destination', 'expedition', 'experience', 'assignedStaff']);
        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'status' => 'required|in:new,under_review,contacted,quotation_sent,follow_up_required,closed',
            'internal_notes' => 'nullable|string',
            'assigned_staff_id' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $enquiry->status;
        $enquiry->update($request->only(['status', 'internal_notes', 'assigned_staff_id']));

        // When any status is changed and saved in enquiries, send mail to the user
        $emailSent = false;
        if ($oldStatus !== $enquiry->status && !empty($enquiry->email)) {
            try {
                Mail::to($enquiry->email)->send(
                    new EnquiryStatusUpdated($enquiry, $oldStatus, $enquiry->status)
                );
                $emailSent = true;
                Log::info("Enquiry status update email sent to customer: {$enquiry->email} for [{$enquiry->reference_number}]: {$oldStatus} -> {$enquiry->status}");
            } catch (\Throwable $e) {
                Log::error("Failed to send enquiry status update email to {$enquiry->email}: " . $e->getMessage(), [
                    'reference' => $enquiry->reference_number,
                    'old_status' => $oldStatus,
                    'new_status' => $enquiry->status,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $msg = $emailSent 
            ? 'Enquiry status updated and notification email sent to customer.'
            : 'Enquiry status updated.';

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'status' => $enquiry->status,
                'email_sent' => $emailSent,
            ]);
        }

        return back()->with('success', $msg);
    }

    public function sendAutoReply(Enquiry $enquiry)
    {
        if (empty($enquiry->email)) {
            return back()->with('error', 'Customer does not have an email address.');
        }

        try {
            Mail::to($enquiry->email)->send(new NewEnquiryCustomerConfirmation($enquiry));
            return back()->with('success', "Confirmation email sent to {$enquiry->email} successfully.");
        } catch (\Throwable $e) {
            Log::error("Manual confirmation sending failed: " . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendTeamNotification(Enquiry $enquiry)
    {
        $companyEmail = SiteSetting::getValue('enquiry_notification_email')
            ?: SiteSetting::getValue('email_primary')
            ?: config('mail.from.address');

        if (empty($companyEmail)) {
            return back()->with('error', 'Company notification email is not configured.');
        }

        try {
            Mail::to($companyEmail)->send(new NewEnquiryCompanyNotification($enquiry));
            return back()->with('success', "Notification email sent to company ({$companyEmail}) successfully.");
        } catch (\Throwable $e) {
            Log::error("Manual team notification sending failed: " . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
