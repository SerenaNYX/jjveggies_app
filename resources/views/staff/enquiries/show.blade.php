@extends('layouts.employee')

@section('content')
    <div class="container">
        <h1>Enquiry Details</h1>
        <a href="{{ route('staff.enquiries.index') }}" class="btn back-btn" style="margin-bottom:1rem;">&larr;</a>
        <div class="enquiry-detail">   
            <div class="enquiry-meta">
                <div>
                    <p><strong>Customer:</strong> {{ $enquiry->name }}</p>
                    <p><strong>Contact:</strong> {{ $enquiry->contact_number }}</p>
                    <p><strong>Email:</strong> {{ $enquiry->email }}</p>
                </div>
                <div>
                    <p><strong>Submitted:</strong> {{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
                    <p><strong>Status:</strong> <span class="status-badge {{ $enquiry->status }}">{{ str_replace('_', ' ', ucfirst($enquiry->status)) }}</span></p>
                </div>
            </div>
            
            <div class="enquiry-section">
                <h3>Customer Message</h3>
                <p>{{ $enquiry->message }}</p>
                @if($enquiry->attachments->count() > 0)
                    <div class="attachments-section">
                        <h3>Attachments</h3>
                        <ul class="attachments-list">
                            @foreach($enquiry->attachments as $attachment)
                                <li>
                                    <a href="{{ route('enquiries.attachment.download', $attachment) }}" target="_blank">
                                        {{ $attachment->original_name }}
                                    </a>
                                    ({{ round($attachment->size / 1024) }} KB)
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            
            <form action="{{ route('staff.enquiries.update', $enquiry) }}" method="POST" class="response-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="pending" {{ $enquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $enquiry->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $enquiry->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="response">Your Response:</label>
                    <textarea id="response" name="response" rows="5" style="resize: none;" required>{{ old('response', $enquiry->response) }}</textarea>
                </div>
                
                <button type="submit" class="btn">Update Enquiry</button>
            </form>
        </div>
    </div>


<style>
    .enquiry-detail {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .enquiry-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: bold;
    }
    
    .status-badge.pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-badge.in_progress {
        background-color: #cce5ff;
        color: #004085;
    }
    
    .status-badge.resolved {
        background-color: #d4edda;
        color: #155724;
    }
    
    .enquiry-section {
        margin-bottom: 2rem;
    }
    
    .response-form {
        margin-top: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }
    
    select, textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    textarea {
        min-height: 150px;
    }
</style>
<style>
    /* Mobile Enquiry Details Styles */
@media (max-width: 768px) {

    .enquiry-detail {
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .enquiry-meta {
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
    }

    .enquiry-meta div {
        width: 100%;
    }

    .enquiry-meta p {
        margin: 0.5rem 0;
        font-size: 17px;
    }

    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        font-size: 13px;
    }

    .enquiry-section h3,
    .attachments-section h3 {
        font-size: 18px;
        margin-bottom: 0.75rem;
    }

    .enquiry-section p {
        font-size: 17px;
        line-height: 1.5;
    }

    .attachments-section {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f9f9f9;
        border-radius: 6px;
    }

    .attachments-list {
        padding-left: 1rem;
    }

    .attachments-list li {
        font-size: 14px;
        margin-bottom: 0.5rem;
        word-break: break-all;
    }

    .response-form {
        margin-top: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    label {
        font-size: 15px;
    }

    select, textarea {
        padding: 0.65rem;
        font-size: 15px;
    }

    textarea {
        min-height: 120px;
    }

    /* Status badge colors remain the same */
    .status-badge.pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-badge.in_progress {
        background-color: #cce5ff;
        color: #004085;
    }
    
    .status-badge.resolved {
        background-color: #d4edda;
        color: #155724;
    }

    .btn {
        font-size: 13px;
    }

    .back-btn {
        padding-top: 5px;
        padding-bottom: 5px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    h1 {
        font-size: 22px;
    }

    .enquiry-meta p {
        font-size: 15px;
    }

    .enquiry-section p,
    .attachments-list li {
        font-size: 16px;
    }

    select, textarea {
        font-size: 14px;
    }

}
</style>
@endsection