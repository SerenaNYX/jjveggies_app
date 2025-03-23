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
        <div class="search-container">
            <form action="{{ route('product.search') }}" method="GET">
                <input type="search" class="search-bar" name="query" placeholder="Search for products..." aria-label="Search">
                <button type="submit" class="button-search">Search</button>
            </form>
        </div>
        <br>
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
                        <div class="product" data-description="{{ $product->description }}">
                            <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
                            <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                            <div class="product-price">From RM{{ number_format($product->options->min('price'), 2) }}</div>
                            <button type="button" class="add-to-cart" data-product-id="{{ $product->id }}" style="width:100%;">
                                <img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart" style="height: 25px; width: 25px;">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Selecting Options -->
    <div id="optionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
            <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
            <h2>Select an Option</h2>
            <form id="addToCartForm" method="POST" action="">
                @csrf
                <input type="hidden" name="product_id" id="modalProductId">
                <div id="optionsContainer"></div>
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
    // Open modal when "Add to Cart" is clicked
    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('product-id');
        $('#modalProductId').val(productId); // Set the product ID in the form

        // Set the form action dynamically
        $('#addToCartForm').attr('action', `/cart/add/${productId}`);

        // Fetch options for the selected product
        $.ajax({
            url: `/products/${productId}/options`,
            type: 'GET',
            success: function(response) {
                let optionsHtml = '';
                response.options.forEach(option => {
                    optionsHtml += `
                        <div class="option">
                            <input type="radio" name="option_id" value="${option.id}" id="option${option.id}" required>
                            <label for="option${option.id}">
                                ${option.option} - RM${option.price.toFixed(2)} (${option.quantity} in stock)
                            </label>
                        </div>
                    `;
                });
                $('#optionsContainer').html(optionsHtml); // Populate options in the modal
                $('#optionModal').show(); // Show the modal
            },
            error: function(xhr, status, error) {
                console.error('Error fetching options:', error);
            }
        });
    });

    // Close modal when the "X" button is clicked
    $('.close').on('click', function() {
        $('#optionModal').hide();
    });

    // Close modal when clicking outside the modal
    $(window).on('click', function(event) {
        if (event.target === $('#optionModal')[0]) {
            $('#optionModal').hide();
        }
    });

    // Submit the form via AJAX
    $('#addToCartForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = $(this).serialize(); // Serialize form data
        const action = $(this).attr('action'); // Get the form action

        $.ajax({
            url: action, // Use the dynamically set action
            type: 'POST',
            data: formData,
            success: function(response) {
                alert('Product added to cart!');
                $('#optionModal').hide(); // Hide the modal
            },
            error: function(xhr, status, error) {
                console.error('Error adding to cart:', error);
                alert('Failed to add product to cart.');
            }
        });
    });
});
    </script>
@endsection