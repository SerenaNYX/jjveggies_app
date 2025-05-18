@extends('layouts.employee')

@section('content')

<div class="container">
    <h1 class="text-center">Customer Enquiries</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="clean-table">
    <!--<div class="enquiries-table">-->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->id }}</td>
                        <td>{{ $enquiry->name }}</td>
                        <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                        <td><span class="status-badge {{ $enquiry->status }}">{{ str_replace('_', ' ', ucfirst($enquiry->status)) }}</span></td>
                        <td>{{ $enquiry->staff ? $enquiry->staff->name : 'Unassigned' }}</td>
                        <td>
                            <a href="{{ route('staff.enquiries.show', $enquiry) }}" class="btn action-btn">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $enquiries->links() }}
    </div>
</div>

<style>
/*    
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    */

 /*   .enquiries-table {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    th {
        background-color: #f5f5f5;
        font-weight: bold;
    }*/
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: bold;
        display: inline-block;
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
    
    .action-btn {
        transition: background-color 0.3s, color 0.3s;
        padding: 0.3rem 1.1rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .pagination li {
        margin: 0 0.25rem;
    }
    
    .pagination a {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: #f5f5f5;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
    }
    
    .pagination a:hover {
        background-color: #ddd;
    }
    
    .pagination .active a {
        background-color: #44684a;
        color: white;
    }
</style>
@endsection