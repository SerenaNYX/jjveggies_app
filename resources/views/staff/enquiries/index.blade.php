@extends('layouts.employee')

@section('content')
<div class="container">
    <div class="enquiry-detail">   
    <h1 class="text-center">Customer Enquiries</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="searchQuery" placeholder="Search for enquiries..." class="form-control" onkeyup="searchEnquiries()">
    </div>
    
    <div class="clean-table">
        <table id="enquiryTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Submitted On</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enquiries as $enquiry)
                    <tr class="hover-row" onclick="window.location='{{ route(auth('employee')->user()->role . '.enquiries.show', $enquiry->id) }}'" style="cursor: pointer;">
                        <td>{{ $enquiry->enquiry_number }}</td>
                        <td>{{ $enquiry->name }} ({{ $enquiry->user->uid }})</td>
                        <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                        <td><span class="status-badge {{ $enquiry->status }}">{{ str_replace('_', ' ', ucfirst($enquiry->status)) }}</span></td>
                        <td>{{ $enquiry->staff ? $enquiry->staff->name : 'Unassigned' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $enquiries->links() }}
    </div>
</div></div>

<style>    
    .search-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    
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
        background-color: #63966b;
        color: white;
    }
    
    .action-btn:hover {
        background-color: #44684a;
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

    @media (max-width: 768px) {
        .enquiry-detail {
            width: 120%;
            margin-left: -10%;
        }
    }
</style>

<script>
    function searchEnquiries() {
        var query = document.getElementById('searchQuery').value.toLowerCase();
        var enquiryRows = document.getElementById('enquiryTable').getElementsByTagName('tr');
        
        // Start from index 1 to skip header row
        for (var i = 1; i < enquiryRows.length; i++) {
            var enquiryNumber = enquiryRows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
            var customerName = enquiryRows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            
            if (enquiryNumber.includes(query) || customerName.includes(query)) {
                enquiryRows[i].style.display = '';
            } else {
                enquiryRows[i].style.display = 'none';
            }
        }
    }
</script>
@endsection