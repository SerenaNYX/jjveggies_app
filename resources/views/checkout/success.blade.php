@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your order has been placed!</h2>
            
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
                        width="70"
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
    
    <div class="card">
        <h3>Shipping Information</h3>
        <hr>
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

@endsection

<style>
    .card {
        padding-left: 2rem;
        padding-right: 2rem;
        background-color: #f9f9f9;
        border: 1px solid #cdcccc;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    </style>