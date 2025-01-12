@extends('layouts.app')

@section('content')
    <div class="product-catalog">
        <div class="container">
            <div class="row"> <!-- is row class needed? -->
                <div class="col-12"> <!-- remove this div -->
                    <h1 class="text-center">Our Products</h1>
                    <p class="section-description text-center">Explore our wide range of fresh vegetables and fruits available for you.</p>
                </div>
            </div>
        </div>
        <div class="search-container">
            <form action="{{ route('product.search') }}" method="GET">
                <input type="search" class="search-bar" name="query" placeholder="Search for products..." aria-label="Search">
                <button type="submit" class="button-search">Search</button>
            </form>
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
                <div class="col-md-9 products text-center"> <!-- is col-md-9 needed? -->
                    @foreach ($products as $product)
                        <div class="product" data-description="{{ $product->description }}">
                            <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
                            <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                            <div class="product-price">RM{{ number_format($product->price, 2) }}</div>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="">
                                @csrf
                                <button type="submit" class="add-to-cart" style="width:100%;">
                                    <img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart" style="height: 25px; width: 25px;">
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div> <!-- end row -->
        </div> <!-- end container -->
    </div> <!-- end product-catalog -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX search handling
        $('#searchForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get the search query
            let query = $('#searchInput').val();

            // Make the AJAX request
            $.ajax({
                url: '{{ route('product.show') }}', // The same route used for products
                type: 'GET',
                data: { query: query }, // Send the search query
                success: function(response) {
                    // Update the products section with the new content
                    $('#productsList').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                }
            });
        });

        // Hover to show description
        $('.product').hover(function() {
            var description = $(this).data('description');
            var descriptionElement = $('<div class="product-description"></div>').text(description);
            $(this).append(descriptionElement);
        }, function() {
            $(this).find('.product-description').remove();
        });
    });
</script>

<style>
    .product {
        position: relative;
    }
    .product-description {
        height: 45px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 10px;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    .product:hover .product-description {
        opacity: 1;
    }
</style>
