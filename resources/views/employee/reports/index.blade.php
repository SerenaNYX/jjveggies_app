@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Generate Report</h1>

    <!-- Charts Container -->
    <div class="charts-grid">
        <!-- Sales Chart -->
        <div class="chart-card">
            <h3>Daily Sales</h3>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Order Count Chart -->
        <div class="chart-card">
            <h3>Daily Orders</h3>
            <div class="chart-container">
                <canvas id="orderChart"></canvas>
            </div>
        </div>

        <!-- Item Count Chart -->
        <div class="chart-card">
            <h3>Daily Items</h3>
            <div class="chart-container">
                <canvas id="itemChart"></canvas>
            </div>
        </div>
    </div>
    <br>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart data from controller
        const labels = @json($labels);
        const salesData = @json($salesData);
        const orderCountData = @json($orderCountData);
        const itemCountData = @json($itemCountData);

        const chartConfig = (id, label, data, color, isCurrency = false) => {
            const ctx = document.getElementById(id).getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: color.replace('1)', '0.2)'),
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    // Show full date in tooltip
                                    const date = new Date(context[0].label + ', ' + new Date().getFullYear());
                                    return date.toLocaleDateString('en-US', { 
                                        month: 'short', 
                                        day: 'numeric', 
                                        year: 'numeric' 
                                    });
                                },
                                label: function(context) {
                                    return isCurrency 
                                        ? `Total: RM ${context.parsed.y.toFixed(2)}`
                                        : `Total: ${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                // Show month labels for the first day of each month
                                callback: function(value, index, values) {
                                    const date = new Date(labels[index] + ', ' + new Date().getFullYear());
                                    if (date.getDate() === 1) {
                                        return date.toLocaleDateString('en-US', { month: 'short' });
                                    }
                                    return '';
                                },
                                maxRotation: 0,
                                autoSkip: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        };

        // Initialize charts
        chartConfig('salesChart', 'Total Sales (RM)', salesData, 'rgba(75, 192, 192, 1)', true);
        chartConfig('orderChart', 'Order Count', orderCountData, 'rgba(54, 162, 235, 1)');
        chartConfig('itemChart', 'Item Count', itemCountData, 'rgba(255, 159, 64, 1)');
    });

</script>
<style>
    /* Add to your existing styles */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 0.5rem;
}

.chart-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    border: 1px solid #e0e6ed;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.chart-card h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #2d3748;
    font-weight: 600;
    font-size: 1.1rem;
}

.chart-container {
    position: relative;
    flex-grow: 1;
    min-height: 250px;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .charts-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-card {
        padding: 1rem;
    }
}
    .report-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        max-width: 1200px;
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
        .report-card, .charts-grid {
            width: 120%;
            margin-left: -10%;
        }

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