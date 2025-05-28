@extends('layouts.employee')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <h1 class="text-center">Manage Orders</h1>

            <div class="status-filter" style="display: flex;">
                <select class="form-control-category" onchange="window.location.href = this.value">
                    <option value="{{ route(auth('employee')->user()->role . '.orders.index') }}" 
                        {{ !request('status') ? 'selected' : '' }}>All Orders</option>
                    @foreach(['order_placed', 'preparing', 'packed', 'delivering', 'delivered', 'completed', 'cancelled', 'refunded'] as $status)
                        <option value="{{ route(auth('employee')->user()->role . '.orders.index', ['status' => $status]) }}" 
                            {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                <input type="text" id="searchQuery" class="form-control" placeholder="Search for orders..." onkeyup="searchOrders()">
            </div>
        </div>
        <div class="">
            <div class="">
                
                <table class="clean-table" id="orderTable">
            <!--    <table class="table table-striped table-product">-->
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Driver</th>
                            <!--<th>Actions</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="hover-row">
                            <td onclick="window.location='{{ route(auth('employee')->user()->role . '.orders.show', $order->id) }}'" style="cursor: pointer;"># {{ $order->order_number }}</td>
                            <td>
                                <a href="#" class="customer-name-link" 
                                    data-customer-name="{{ $order->user->name }}"
                                    data-customer-email="{{ $order->user->email }}"
                                    data-customer-contact="{{ $order->user->contact }}"
                                    data-order-address="{{ $order->address->address ?? 'N/A' }}"
                                    data-order-postal="{{ $order->address->postal_code ?? 'N/A' }}"
                                    data-order-phone="{{ $order->address->phone ?? 'N/A' }}">
                                    {{ $order->user->name }} (ID: # {{$order->user->id}})</td>
                                </a>
                            <td onclick="window.location='{{ route(auth('employee')->user()->role . '.orders.show', $order->id) }}'" style="cursor: pointer;">{{ $order->created_at->format("d/m/Y H:i:s") }}</td>
                            <td onclick="window.location='{{ route(auth('employee')->user()->role . '.orders.show', $order->id) }}'" style="cursor: pointer;">{{ $order->items->sum('quantity') }}</td>
                            <td onclick="window.location='{{ route(auth('employee')->user()->role . '.orders.show', $order->id) }}'" style="cursor: pointer;">RM{{ number_format($order->total, 2) }}</td>
                            <td>
                                @if(auth('employee')->user()->role === 'staff')
                                    @php
                                        $nextStatus = match($order->status) {
                                            'order_placed' => 'preparing',
                                            'preparing' => 'packed',
                                            'delivered' => 'completed',
                                            default => null
                                        };
                                    @endphp
                            
                                    @if($nextStatus)
                                        <form action="{{ route('staff.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <select name="status" class="form-select form-select-sm" 
                                                    onchange="confirmStatusChange(this, '{{ ucfirst(str_replace('_', ' ', $nextStatus)) }}')">
                                                <option value="{{ $order->status }}" selected>
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </option>
                                                <option value="{{ $nextStatus }}">
                                                    {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="badge 
                                            @if($order->status === 'delivered') bg-success
                                            @elseif($order->status === 'cancelled') bg-danger
                                            @else bg-primary
                                            @endif">
                                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                        </span>
                                    @endif
                                @elseif(auth('employee')->user()->role === 'driver')
                                    @php
                                        $nextStatus = match($order->status) {
                                            'packed' => 'delivering',
                                            'delivering' => 'delivered',
                                            default => null
                                        };
                                    @endphp
                            
                                    @if($nextStatus)
                                        <form action="{{ route('driver.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <select name="status" class="form-select form-select-sm" 
                                                    onchange="confirmStatusChange(this, '{{ ucfirst(str_replace('_', ' ', $nextStatus)) }}')">
                                                <option value="{{ $order->status }}" selected>
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </option>
                                                <option value="{{ $nextStatus }}">
                                                    {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="badge 
                                            @if($order->status === 'delivered') bg-success
                                            @elseif($order->status === 'cancelled') bg-danger
                                            @else bg-primary
                                            @endif">
                                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="badge 
                                        @if($order->status === 'delivered') bg-success
                                        @elseif($order->status === 'cancelled') bg-danger
                                        @else bg-primary
                                        @endif">
                                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                    </span>
                                @endif
                            </td>
                         <!--  <td>
                        
                            </td>-->
                            <td>
                                @if($order->status === 'packed' && auth('employee')->user()->role === 'staff')
                                    <form action="{{ route('staff.orders.assign-driver', $order->id) }}" method="POST" class="driver-assign-form">
                                        @csrf
                                        <select name="driver_id" class="form-select form-select-sm driver-select"                        
                                            data-order-id="{{ $order->id }}">
                                            <option value="">Select Driver</option>
                                            @foreach($drivers as $driver)
                                                <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                                                    {{ $driver->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    @if($order->driver)
                                        {{ $order->driver->name }}
                                    @else
                                        Not assigned
                                    @endif
                                @endif
                            </td>
                        </tr>
                        
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Customer Info Modal -->
<div class="modal fade" id="customerInfoModal" tabindex="-1" aria-labelledby="customerInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="customerInfoModalLabel">Customer Information</h3>
        <!--        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
            </div>
            <div class="modal-body">
                <div class="customer-details">
                    <p><strong>Name:</strong> <span id="customer-name"></span></p>
                    <p><strong>Email:</strong> <span id="customer-email"></span></p>
                    <p><strong>Contact:</strong> <span id="customer-contact"></span></p>
                    
                    <hr>
 
                    <p><strong>Address:</strong> <span id="order-address"></span></p>
                    <p><strong>Postal Code:</strong> <span id="order-postal"></span></p>
                    <p><strong>Phone:</strong> <span id="order-phone"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle driver assignment confirmation
    const driverSelects = document.querySelectorAll('.driver-select');
    
    driverSelects.forEach(select => {
        select.addEventListener('change', function(e) {
            if (this.value) {
                e.preventDefault();
                
                const driverName = this.options[this.selectedIndex].text;
                const orderId = this.dataset.orderId;
                
                if (confirm(`Are you sure you want to assign ${driverName} to this order?`)) {
                    this.form.submit();
                } else {
                    // Reset to original value if canceled
                    this.value = '';
                    // If there was a previously selected driver, you might need to restore that value
                    // This would require storing the original value in a data attribute
                }
            }
        });
    });
    
});
</script>

<script>
    function searchOrders() {
        var query = document.getElementById('searchQuery').value.toLowerCase();

        // Filter products table
        var orderRows = document.getElementById('orderTable').getElementsByTagName('tr');
        for (var i = 1; i < orderRows.length; i++) {
            var order_number = orderRows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
            var customer = orderRows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            
            if (order_number.includes(query) || customer.includes(query)) {
                orderRows[i].style.display = '';
            } else {
                orderRows[i].style.display = 'none';
            }
        }
    }

    function confirmStatusChange(selectElement, nextStatus) {
        if (confirm(`Are you sure you want to change status to ${nextStatus}?`)) {
            selectElement.form.submit();
        } else {
            // Reset to original value if canceled
            selectElement.value = selectElement.options[0].value;
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Customer info modal
        const customerLinks = document.querySelectorAll('.customer-name-link');
        const customerModal = new bootstrap.Modal(document.getElementById('customerInfoModal'));
        
        customerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Set the modal content
                document.getElementById('customer-name').textContent = this.dataset.customerName;
                document.getElementById('customer-email').textContent = this.dataset.customerEmail;
                document.getElementById('customer-contact').textContent = this.dataset.customerContact;
                document.getElementById('order-address').textContent = this.dataset.orderAddress;
                document.getElementById('order-postal').textContent = this.dataset.orderPostal;
                document.getElementById('order-phone').textContent = this.dataset.orderPhone;
                
                // Show the modal
                customerModal.show();
            });
        });
    });
</script>

<style>
    .modal {
        z-index: 1002 !important;
    }

    .customer-name-link:hover {
        color: #4c7552;
    }

    .modal-body h3 {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .clean-table td:nth-child(4), .clean-table td:nth-child(7) {
            border-top: 1px #ddd solid;
            display: inline-block !important;
        }

        .clean-table td:nth-child(4)::after {
            content: ' item(s)';
        }

        .clean-table td:nth-child(7)::before {
            content: 'Driver: ';
        }
    }
</style>