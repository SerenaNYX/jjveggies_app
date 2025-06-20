<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Notifications\EnquiryResponse;
use Illuminate\Support\Facades\Notification;

class StaffContactController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::latest()->paginate(10);
        return view('staff.enquiries.index', compact('enquiries'));
    }

    public function show(Enquiry $enquiry)
    {
        // Only assign to staff if user is staff
        if (auth('employee')->user()->role === 'staff' && is_null($enquiry->staff_id)) {
            $enquiry->update(['staff_id' => auth('employee')->id()]);
        }

        return view('staff.enquiries.show', [
            'enquiry' => $enquiry,
            'isAdmin' => auth('employee')->user()->role === 'admin'
        ]);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        // Prevent admin from updating
        if (auth('employee')->user()->role === 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'response' => 'required|string|max:1000',
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $enquiry->update([
            'response' => $request->response,
            'status' => $request->status,
            'staff_id' => auth('employee')->id(),
        ]);

        // Send email notification
        if ($enquiry->user) {
            Notification::send($enquiry->user, new EnquiryResponse($enquiry));
        }

        return redirect()->route('staff.enquiries.index')->with('success', 'Enquiry updated successfully!');
    }
}