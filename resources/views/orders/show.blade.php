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
                    <h3 class="">Order #{{ $order->id }}</h3>
                <!--    <small>Placed on {{ $order->created_at->format('F j, Y \a\t g:i a') }}</small>-->
                    <span class="badge 
                        @if($order->status === 'completed') bg-success
                        @elseif($order->status === 'cancelled') bg-danger
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
                                <tr>
                                    <td colspan="5" class="text-start"><strong>Total:</strong></td>
                                    <td><strong>RM {{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <p>Placed on {{ $order->created_at->format('F j, Y \a\t g:i a') }}</p>
                    </div>

            <div class="">
                <div class="" style="padding-top: 0.1rem; padding-bottom: 0.1rem;">
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

    /* Order Status Badge */
    .badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
    }

    .bg-primary {
        background-color: rgb(200, 220, 181);
        color: black;
    }

</style>
