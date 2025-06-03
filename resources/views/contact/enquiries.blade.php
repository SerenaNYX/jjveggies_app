@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">My Enquiries</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="enquiries-list">
        @forelse($enquiries as $enquiry)
            <div class="enquiry-card" onclick="window.location='{{ route('enquiries.show', $enquiry) }}'" style="cursor: pointer;">
                <div class="enquiry-header">
                    <h3>#{{ $enquiry->enquiry_number }}</h3>
                    <span class="status-badge {{ $enquiry->status }}">{{ str_replace('_', ' ', ucfirst($enquiry->status)) }}</span>
                </div>         
                <p><strong>Message:</strong> {{ Str::limit($enquiry->message, 100) }}</p>
                <p><strong>Submitted:</strong> {{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
                @if($enquiry->response)
                <hr>
                    <p><strong>Response:</strong> {{ Str::limit($enquiry->response, 100) }}</p>
                @endif
        <!--        <a href="{{ route('enquiries.show', $enquiry) }}" class="btn">View Details</a>-->
            </div>
        @empty
            <p>You haven't submitted any enquiries yet.</p>
        @endforelse
    </div>
</div>

<style>
    
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .enquiries-list {
        display: grid;
        gap: 1rem;
    }
    
    .enquiry-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .enquiry-card:hover {
        transition: background-color 0.3s, color 0.3s;
        background-color: #eafee9;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        h3 {
            color:#207634;
        }
    }

    .enquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
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
    
</style>

@endsection