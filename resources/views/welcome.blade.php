@extends('layouts.app')

@section('hero')
<div class="hero container">
    <div class="hero-copy">
        <h1>J&J Vegetables</h1>
        <p>Welcome to J&J Vegetables online.<br>Buy fresh vegetables from J&J Vegetables. You order, we deliver.</p>
        <div class="hero-buttons">
            <a href="/product" class="button button-white">Shop now</a>
            <a href="#" class="button button-white">How to order</a>
        </div>
    </div> <!-- end hero-copy -->
    <div class="hero-image">
        <img src="img/vege1.jpg" alt="hero image">
    </div>
</div> <!-- end hero -->
@endsection

@section('content')
<div class="featured-section">
    <div class="container">
        <h1 class="text-center">Vegetables & Fruits</h1>
        <p class="section-description text-center">Browse vegetables and fruits in this section.</p>
        <div class="text-center button-container">
            <a href="#" class="button">Featured</a>
            <a href="#" class="button">Clearance</a>
        </div>

        <div class="products text-center">
            @foreach ($products as $product)
                <div class="product">
                    <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
                    <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                    <div class="product-price">RM{{ number_format($product->price, 2) }}</div>
                    <button class="add-to-cart"><img src="{{ asset('img/cart.png') }}" alt="Add to Cart"></button>
                </div>
            @endforeach
        </div> <!-- end products -->

        <div class="text-center button-container">
            <a href="#" class="button">View more products</a>
        </div>
    </div>

    <div class="blog-section">
        <div class="container">
            <h1 class="text-center">Learn more about us</h1>

            <p class="section-description text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et sed accusantium maxime dolore cum provident itaque ea, a architecto alias quod reiciendis ex ullam id, soluta deleniti eaque neque perferendis.</p>

            <div class="blog-posts">
                <div class="blog-post" id="blog1">
                    <a href="#"><img src="img/logo.jpg" alt="blog image"></a>
                    <a href="#"><h2 class="blog-title">J&J Vegetables</h2></a>
                    <div class="blog-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est ullam, ipsa quasi?</div>
                </div>
                <div class="blog-post" id="blog2">
                    <a href="#"><img src="img/logo.jpg" alt="blog image"></a>
                    <a href="#"><h2 class="blog-title">J&J Vegetables</h2></a>
                    <div class="blog-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est ullam, ipsa quasi?</div>
                </div>
                <div class="blog-post" id="blog3">
                    <a href="#"><img src="img/logo.jpg" alt="blog image"></a>
                    <a href="#"><h2 class="blog-title">J&J Vegetables</h2></a>
                    <div class="blog-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est ullam, ipsa quasi?</div>
                </div>
            </div> <!-- end blog-posts -->
        </div> <!-- end container -->
    </div> <!-- end blog-section -->

</div> <!-- end featured-section -->
@endsection
