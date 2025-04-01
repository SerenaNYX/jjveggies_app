@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-warning">
        <h2>Payment Cancelled</h2>
        <p>Your payment was not completed. You can try again if you wish.</p>
        <a href="{{ route('checkout') }}" class="btn btn-primary">Return to Checkout</a>
    </div>
</div>
@endsection