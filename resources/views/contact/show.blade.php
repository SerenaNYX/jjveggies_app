@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Enquiry Details</h1>
    
    <div class="enquiry-detail">
        <div class="enquiry-meta">
            <p><strong>Submitted:</strong> {{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
            <p><strong>Status:</strong> <span class="status-badge {{ $enquiry->status }}">{{ str_replace('_', ' ', ucfirst($enquiry->status)) }}</span></p>
        </div>
        
        <div class="enquiry-section">
            <h3>Your Message</h3>
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
        
        @if($enquiry->response)
            <div class="response-section">
                <h3>Staff Response</h3>
                <p>{{ $enquiry->response }}</p>
                @if($enquiry->staff)
                    <p class="response-meta">Responded by: {{ $enquiry->staff->name }} on {{ $enquiry->updated_at->format('d M Y, h:i A') }}</p>
                @endif
            </div>
        @else
            <div class="no-response">
                <p>Your enquiry is still being processed. Our staff will respond soon.</p>
                <p>You can also contact us directly at:</p>
                <ul>
                    <li>Phone: +6012-598 5295</li>
                    <li>WhatsApp: <a href="https://wa.me/####" target="_blank">+60XXXXX</a></li>
                    <li>Email: jjveggies@gmail.com</li>
                </ul>
            </div>
        @endif
        
        <a href="{{ route('enquiries.index') }}" class="btn">Back to Enquiries</a>
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
    
    .enquiry-section, .response-section {
        margin-bottom: 2rem;
    }
    
    .response-meta {
        font-style: italic;
        color: #666;
        margin-top: 1rem;
    }
    
    .no-response {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 4px;
        margin-bottom: 2rem;
    }
    
    .no-response ul {
        margin-top: 1rem;
        padding-left: 1.5rem;
    }
    
    .back-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: #44684a;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    
    .back-btn:hover {
        background-color: #63966b;
    }
</style>
@endsection