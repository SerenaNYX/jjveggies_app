@extends('layouts.app')

@section('content')
    <div class="product-catalog">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Our Products</h1>
                    <p class="section-description text-center">Explore our wide range of fresh vegetables and fruits available for you.</p>
                </div>
            </div>
        </div>
        <div class="products-container">
            <div class="row categories-products-container">
                <!-- Sidebar for Categories -->
                <div class="col-md-3 categories">
                    <a href="{{ route('product.show') }}" class="category-link {{ is_null($categorySlug) ? 'active' : '' }}">All</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('product.show', ['category' => $category->slug]) }}" class="category-link {{ $categorySlug === $category->slug ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                <!-- Products Section -->
                <div class="col-md-9 products text-center">
                    @foreach ($products as $product)
                        <div class="product">
                            <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
                            <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                            <div class="product-price">RM{{ number_format($product->price, 2) }}</div>
                            <button class="add-to-cart"><img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart"></button>
                        </div>
                    @endforeach
                </div>
            </div> <!-- end row -->
        </div> <!-- end container -->
    </div> <!-- end product-catalog -->
@endsection
