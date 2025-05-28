@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Generate Report</h1>

    <div class="report-card">
        <form action="{{ route(auth('employee')->user()->role . '.reports.generate') }}" method="POST">
            @csrf

            <!-- Date Range -->
            <div class="form-row date-range">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
            </div>

            <!-- Report Type -->
            <div class="form-section">
                <h3>Report Type</h3>
                <div class="form-check-group">
                    <label class="form-check-inline">
                        <input type="radio" name="report_type" value="summary" checked>
                        Sales Summary
                    </label>
                    <label class="form-check-inline">
                        <input type="radio" name="report_type" value="detailed">
                        Order Summary
                    </label>
                </div>
            </div>

            <!-- Order Statuses -->
            <div class="form-section">
                <h3>Order Status</h3>
                <div class="checkbox-grid" style="margin-bottom: 1rem;">
                    <label class="checkbox-item">
                        <input type="checkbox" id="select-all-toggle" name="toggle-all" value="All" onclick="toggleSelectAll()"></input>Select all
                    </label>
                </div>
                <div class="checkbox-grid">
                    @foreach(['order_placed', 'preparing', 'packed', 'delivering', 'delivered', 'completed', 'cancelled', 'refunded'] as $status)
                    <label class="checkbox-item">
                        <input type="checkbox" class="checkbox-status" name="statuses[]" value="{{ $status }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit -->
            <div class="form-submit">
                <button type="submit" class="btn">
                    Generate Report
                </button>
            </div>
        </form>
    </div>
    <br><br><br>
</div>
@endsection

<script>

    function toggleSelectAll() {
        const selectAllToggle = document.getElementById('select-all-toggle');
        const checkboxes = document.querySelectorAll('input[type="checkbox"].checkbox-status');
        
        checkboxes.forEach( checkbox => {
            checkbox.checked = selectAllToggle.checked;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(endDate.getDate() - 30);

        document.getElementById('start_date').valueAsDate = startDate;
        document.getElementById('end_date').valueAsDate = endDate;
    });
</script>
<style>
    .report-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid #e0e6ed;
    }

    .form-row.date-range {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background-color: #fff;
    }

    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: #f8fafc;
        border-radius: 10px;
    }

    .form-section h3 {
        color: #2d3748;
        margin-top: 0;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .form-check-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-check-inline {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .checkbox-item:hover {
        background-color: #edf2f7;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #667eea;
    }

    .form-submit {
        text-align: center;
        margin-top: 2.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-row.date-range {
            flex-direction: column;
            gap: 1rem;
        }
        
        .checkbox-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        
        .report-card {
            padding: 1.5rem;
        }
    }
</style>