@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <h2>Your order has been placed!</h2>
    </div>

    <div class="order-address">
        <h4>Shipping To:</h4>
        @if($order->address)
            <p>{{ $order->address->address }}</p>
            <p>{{ $order->address->postal_code }}</p>
            <p>Phone: {{ $order->address->phone }}</p>
        @else
            <p class="text-danger">No shipping address recorded</p>
        @endif
    </div>
</div>

@endsection