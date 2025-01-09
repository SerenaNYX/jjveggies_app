@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="product-detail">
            <h1>{{ $product->name }}</h1>
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            <div class="product-price">RM{{ number_format($product->price, 2) }}</div>
            <p>{{ $product->description }}</p>
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="">
                @csrf
                <button type="submit" class="add-to-cart2">
                    <img src="{{ asset('img/cart.png') }}" alt="Add to Cart">
                </button>
            </form>
        </div>
    </div>
@endsection