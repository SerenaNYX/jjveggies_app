@extends('layouts.employee')

@section('content')
    <div class="container">
        <h1>Enquiry Details</h1>
        
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
                    <textarea id="response" name="response" rows="5" required>{{ old('response', $enquiry->response) }}</textarea>
                </div>
                
                <button type="submit" class="submit-btn">Update Enquiry</button>
                <a href="{{ route('staff.enquiries.index') }}" class="back-btn">Back to Enquiries</a>
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
    
    .submit-btn {
        background-color: #44684a;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s;
        margin-right: 1rem;
    }
    
    .submit-btn:hover {
        background-color: #63966b;
    }
    
    .back-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: #f5f5f5;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    
    .back-btn:hover {
        background-color: #ddd;
    }
</style>
@endsection