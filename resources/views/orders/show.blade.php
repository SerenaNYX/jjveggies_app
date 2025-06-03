@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('orders.index') }}" class="btn" style="color: white; margin-top: 1rem;">
        &larr; 
    </a>

    <div>
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="">#{{ $order->order_number }}</h3>
                <!--    <small>Placed on {{ $order->created_at->format('F j, Y \a\t g:i a') }}</small>-->
                    <span class="badge 
                        @if($order->status === 'completed') bg-primary
                        @elseif($order->status === 'cancelled') bg-primary
                        @else bg-primary
                        @endif">
                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                    </span>
                </div>
                <div class="card-body">                 
                    <div class="">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Option</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->product->image) }}" 
                                            alt="{{ $item->product->name }}" 
                                            height="70" width="70" style="background-color:white; object-fit: scale-down;"
                                            class="img-thumbnail">
                                    </td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->option->option }}</td>
                                    <td>RM {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>RM {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-start"><strong>Subtotal:</strong></td>
                                    <td><strong>RM {{ number_format($order->subtotal, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-start"><strong>Delivery Fee:</strong></td>
                                    <td><strong>RM {{ number_format($order->delivery_fee, 2) }}</strong></td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="5" class="text-start"><strong>Discount:</strong></td>
                                    <td><strong>-RM {{ number_format($order->discount_amount, 2) }}</strong></td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="5" class="text-start"><strong>Total:</strong></td>
                                    <td><strong>RM {{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <p>Placed on {{ $order->created_at->format('F j, Y \a\t g:i a') }}</p>
                        <p>Payment via {{ strtoupper($order->payment_method) }}</p>
                    </div>
                    <hr>
                    <!-- Status History -->
                    <h3>Status History</h3>
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

            <hr>
            <div class="">
                <div class="">
                    <h3>Shipping Information</h3>
                    @if($order->address)
                        <p><strong>Address:</strong><br>
                        {{ $order->address->address }}<br>
                        {{ $order->address->postal_code }}</p>
                        <p><strong>Phone:</strong> {{ $order->address->phone }}</p>
                    @else
                        <p class="text-danger">No shipping address recorded</p>
                    @endif
                </div>
            </div>
            
                </div>
            </div>
        </div>
     

      <!--      <h3>Payment Information</h3>
            <div class="card">
                <div class="card-body">
                    <p><strong>Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                    <p><strong>Total Paid:</strong> RM{{ number_format($order->total, 2) }}</p>
                </div>
            </div>-->
        
    </div>
    
</div>
@endsection

<style>
    @media (max-width: 768px) {
        .timeline {
            padding-left: 0;
        }

    }
    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 0.5rem;
        border-left: 2px solid #9a9a9a;
    }

    .timeline-item:last-child {
        padding-bottom: 15px;
    }

    .timeline-item.active {
        border-left-color: #4a7c52;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -9px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #9a9a9a;
    }

    .timeline-item.active::before {
        background: #4a7c52;
        border: 2px solid #63966b;
    }

    .timeline-content {
        padding-left: 1rem;
    }
    
    .text-start {
        text-align: justify; 
        text-align-last: left;
        padding-left: 2rem;
    }

    /* Card Styles */
    .card {
        margin-top: 1rem;
        background-color: #f9f9f9;
        border: 1px solid #cdcccc;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #63966b;
        color: white;
        padding: 12px 20px;
        font-weight: bold;
        border-radius: 8px 8px 0px 0px;
    }

    .card-body {
        padding: 20px;
    }
    
    .card-body > div:last-child {
        padding-left: 20px;
        background: #f7fef4;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
    }

    /* Order Status Badge */
    .badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
    }

    .bg-primary {
        background-color: rgb(200, 220, 181);
        color: black;
    }

</style>

<style>
/* Mobile Order Details Styles */
@media (max-width: 768px) {
    /* Card styling */
    .card {
        margin-top: 0.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card-header {
        padding: 12px 15px;
        border-radius: 8px 8px 0 0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .card-header h3 {
        font-size: 18px;
        margin: 0;
        flex: 1;
        min-width: 60%;
    }

    .badge {
        font-size: 13px;
        padding: 5px 10px;
    }

    /* Order table */
    .order-table {
        width: 100%;
    }

    .order-table thead {
        display: none;
    }

    .order-table tbody tr {
        display: flex;
        flex-wrap: wrap;
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .order-table tbody tr:last-child {
        border-bottom: none;
    }

    .order-table td {
        padding: 5px;
        width: 50%;
        display: flex;
        align-items: center;
    }

    /* Specific column styling */
    .order-table td:nth-child(1) { /* Image */
        width: 30%;
        justify-content: center;
    }

    .order-table td:nth-child(2) { /* Product name */
        width: 70%;
        font-weight: bold;
    }

    .order-table td:nth-child(3), /* Option */
    .order-table td:nth-child(4), /* Price */
    .order-table td:nth-child(5), /* Quantity */
    .order-table td:nth-child(6) { /* Subtotal */
        width: 50%;
    }

    .order-table td:nth-child(3)::before {
        content: "Option: ";
        color: #666;
        margin-right: 5px;
    }

    .order-table td:nth-child(4)::before {
        content: "Price: ";
        color: #666;
        margin-right: 5px;
    }

    .order-table td:nth-child(5)::before {
        content: "Quantity: ";
        color: #666;
        margin-right: 5px;
    }

    .order-table td:nth-child(6)::before {
        content: "Subtotal: ";
        color: #666;
        margin-right: 5px;
        font-weight: bold;
    }

    .img-thumbnail {
        min-width: 60px;
        height: 60px;
        object-fit: scale-down;
    }

    /* Order summary */
    .order-table tfoot tr {
        display: flex;
        flex-wrap: wrap;
        padding: 3px 0;
        border-top: 1px solid #ddd;
    }

    .order-table tfoot td {
        width: 100%;
        text-align: right !important;
    }

    .order-table tfoot td[colspan="5"] {
        text-align: left !important;
        padding-left: 20px;
        width: 60%;
        justify-content: flex-start;
    }

    .order-table tfoot td:last-child {
        width: 40%;
        font-weight: bold;
    }

    /* Shipping info */
    .card-body > div:last-child {
        margin-top: 20px;
        padding-left: 15px;
    }

    .card-body h3 {
        font-size: 18px;
        color: #63966b;
    }

    .card-body p {
        margin-bottom: 8px;
        font-size: 15px;
    }

    /* Back button */
    .btn {
        padding: 8px 15px;
        background: #63966b;
        color: white;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .btn:hover {
        background: #44684a;
    }

    /* Meta info */
    .card-body > p {
        font-size: 14px;
        color: #666;
        margin: 5px 0;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .order-table td {
        font-size: 14px;
    }

    .order-table td:nth-child(1) {
        width: 25%;
    }

    .order-table td:nth-child(2) {
        width: 75%;
    }

    .img-thumbnail {
        min-width: 50px;
        height: 50px;
        object-fit: scale-down;
    }

    .card-header h3 {
        font-size: 16px;
    }

    .badge {
        font-size: 12px;
    }
}
</style>