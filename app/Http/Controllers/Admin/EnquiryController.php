<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

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

        $enquiry->update($request->only(['status', 'internal_notes', 'assigned_staff_id']));

        return back()->with('success', 'Enquiry status updated.');
    }
}