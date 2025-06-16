@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('enquiries.index') }}" class="btn" style="color: white; margin-top: 1rem;">&larr;</a>
    <h1 class="text-center">Enquiry Details</h1>
    
    <div class="enquiry-detail">
        <p><strong>#{{ $enquiry->enquiry_number }}</strong></p>
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
                    <li>Email: jnj.vegetablesmarketing@gmail.com</li>
                </ul>
            </div>
        @endif
        
        <a href="{{ route('enquiries.index') }}" class="btn back-enquiry">Back to Enquiries</a>
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
    
    .back-btn:hover {
        background-color: #63966b;
    }
</style>

<style>

@media (max-width: 768px) {
    .enquiry-detail {
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .enquiry-meta {
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
    }

    .enquiry-meta p {
        margin: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        font-size: 14px;
    }

    .enquiry-section h3,
    .response-section h3 {
        font-size: 18px;
        margin-bottom: 0.75rem;
    }

    .enquiry-section p,
    .response-section p {
        font-size: 18px;
        line-height: 1.5;
    }

    .attachments-section {
        margin-top: 1.5rem;
    }

    .attachments-list {
        padding-left: 1rem;
    }

    .attachments-list li {
        font-size: 16px;
        margin-bottom: 0.5rem;
        word-break: break-all;
    }

    .response-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
    }

    .response-meta {
        font-size: 13px !important;
        margin-top: 0.75rem;
    }

    .no-response {
        padding: 1rem;
    }

    .no-response p {
        font-size: 15px;
        margin-bottom: 0.75rem;
    }

    .no-response ul {
        padding-left: 1rem;
        font-size: 14px;
    }

    .no-response li {
        margin-bottom: 0.5rem;
    }

    .back-enquiry {
        display: block;
        width: 100%;
        padding: 0.75rem;
        text-align: center;
        margin-top: 1.5rem;
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
}

/* For very small screens */
@media (max-width: 480px) {
    
    .enquiry-meta p {
        font-size: 16px;
    }
/*
    .enquiry-section p,
    .response-section p,
    .no-response p {
        font-size: 14px;
    }

    .attachments-list li,
    .no-response ul {
        font-size: 13px;
    }

    .btn {
        padding: 0.65rem;
        font-size: 15px;
    }*/
}

</style>
@endsection