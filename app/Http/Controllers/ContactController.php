<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Models\EnquiryAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
            'attachments.*' => 'sometimes|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $enquiryData = [
            'user_id' => Auth::id(),
            'enquiry_number' => Enquiry::generateEnquiryNumber(),
            'name' => $request->name,
            'contact_number' => $request->phone,
            'email' => $request->email,
            'message' => $request->message,
        ];

        $enquiry = Enquiry::create($enquiryData);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('enquiry_attachments');
                
                EnquiryAttachment::create([
                    'enquiry_id' => $enquiry->id,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('enquiries.index')->with('success', 'Your enquiry has been submitted successfully!');
    }

    public function userEnquiries()
    {
        $enquiries = Enquiry::where('user_id', Auth::id())->latest()->get();
        return view('contact.enquiries', compact('enquiries'));
    }

    public function show(Enquiry $enquiry)
    {
        if ($enquiry->user_id !== Auth::id()) {
            abort(403);
        }

        return view('contact.show', compact('enquiry'));
    }

    public function downloadAttachment(EnquiryAttachment $attachment)
    {
        if ($attachment->enquiry->user_id !== Auth::id()) {
            abort(403);
        }

        return Storage::download($attachment->path, $attachment->original_name);
    }
}