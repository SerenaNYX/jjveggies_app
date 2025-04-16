@extends('layouts.employee')

@section('content')
<div class="container">
    <div class="row">
        <div class="">
            <div class="card ">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="">Order #{{ $order->id }}</h2>
                    <span class="badge 
                        @if($order->status === 'delivered') bg-success
                        @elseif($order->status === 'cancelled') bg-danger
                        @else bg-primary
                        @endif">
                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="">
                            <h3>Customer Information</h3>
                            <p>{{ $order->user->name }}<br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->contact ?? 'N/A' }}</p>
                        </div>
                        <div class="">
                            <h3>Shipping Address</h3>
                            @if($order->address)
                                <p>{{ $order->address->address }}<br>
                                {{ $order->address->postal_code }}<br>
                                Phone: {{ $order->address->phone }}</p>
                            @else
                                <p class="text-danger">No address recorded</p>
                            @endif
                        </div>
                    </div>

                    <h2>Order Items</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Option</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->product->image) }}" 
                                             width="50" 
                                             class="img-thumbnail">
                                    </td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->option->option }}</td>
                                    <td>RM{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>RM{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="card">
                <div class="card-header">
                    <h2>Status History</h2>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($order->statusHistory as $status)
                        <div class="timeline-item @if($loop->first) active @endif">
                            <div class="timeline-content">
                                <h3>{{ str_replace('_', ' ', ucfirst($status->status)) }}</h3>
                                <p class="text-muted small ">
                                    {{ $status->created_at->format('M j, Y \a\t g:i a') }}
                                    @if($status->changed_by)
                                        by {{ $status->changed_by }}
                                    @endif
                                </p>
                                @if($status->notes)
                                <p class="small">{{ $status->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <!-- Order Summary -->
            <div class="card ">
                <div class="card-header">
                    <h2>Order Summary</h2>
                </div>
                <div class="card-body">
                    <div class="">
                        <span>Subtotal:</span>
                        <span>RM{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="">
                        <span>Delivery Fee:</span>
                        <span>RM{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="">
                        <span>Total:</span>
                        <span>RM{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Update (for staff and driver) -->
            @if(auth('employee')->user()->role === 'staff' || auth('employee')->user()->role === 'driver')
            <div class="card">
                <div class="card-header">
                    <h2>Update Status</h2>
                </div>
                <div class="card-body">
                    @php
                        $currentStatus = $order->status;
                        $nextStatus = null;
                        
                        if (auth('employee')->user()->role === 'staff') {
                            $nextStatus = match($currentStatus) {
                                'order_placed' => 'preparing',
                                'preparing' => 'packed',
                                default => null
                            };
                        } elseif (auth('employee')->user()->role === 'driver') {
                            $nextStatus = match($currentStatus) {
                                'packed' => 'delivering',
                                'delivering' => 'delivered',
                                default => null
                            };
                        }
                    @endphp

                    @if($nextStatus)
                        <form action="{{ route(auth('employee')->user()->role . '.orders.update-status', $order->id) }}" method="POST" id="statusForm">
                            @csrf
                            <div class="">
                                <label class="form-label">Current Status</label>
                                <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $currentStatus)) }}" readonly>
                            </div>
                            <div class="">
                                <label class="form-label">Change to</label>
                                <select name="status" class="form-select form-select-sm" id="statusSelect"> 
                                    <option value="{{ $nextStatus }}">
                                        {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}
                                    </option>
                                </select>
                            </div>
                            <div class="">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">
                                Update Status
                            </button>
                        </form>
                        <script>
                            function confirmStatusUpdate() {
                                if (confirm('Are you sure you want to update the status to {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}?')) {
                                    document.getElementById('statusForm').submit();
                                }
                            }
                        </script>
                    @else
                        <div class="alert alert-info">
                            No further status changes available for this order.
                        </div>
                        <div class="">
                            <label class="form-label">Current Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $currentStatus)) }}" readonly>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection