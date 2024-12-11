@extends('layouts.app')

@section('content')
    <div class="product-catalog">
        <div class="container">
            <h1 class="text-center">Our Products</h1>
            <p class="section-description text-center">Explore our wide range of fresh vegetables and fruits available for you.</p>

            <!-- Display Categories for Filtering -->
            <div class="categories text-center">
                <a href="{{ route('product.show') }}" class="category-link {{ is_null($categorySlug) ? 'active' : '' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('product.show', ['category' => $category->slug]) }}" class="category-link {{ $categorySlug === $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Loop through Products -->
            <div class="products text-center">
                @foreach ($products as $product)
                    <div class="product">
                        <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
                        <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                        <div class="product-price">RM{{ number_format($product->price, 2) }}</div>
                        <button class="add-to-cart"><img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart"></button>
                    </div>
                @endforeach
            </div> <!-- end products -->
        </div> <!-- end container -->
    </div> <!-- end product-catalog -->
@endsection
