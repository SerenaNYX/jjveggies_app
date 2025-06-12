@extends('layouts.app')

@section('hero')
<div class="hero container">
    <div class="hero-copy">
        <h1 class="jj-title">J&J Vegetables</h1>
        <p class="jj-desc">Welcome to J&J Vegetables online.<br>Buy fresh vegetables from J&J Vegetables.<br>You order, we deliver.</p>
        <div class="hero-buttons">
            <a href="/product" class="button intro-button button-white">Shop now</a>
            <a href="/about" class="button intro-button button-white">About us</a>
        <!--    <a href="#" class="button button-white">How to order</a>-->
        </div>
    </div> <!-- end hero-copy -->
    <div class="hero-image">
        <img src="img/vege1.jpg" alt="hero image">
    </div>
</div> <!-- end hero -->
@endsection

@section('content')
<div class="welcomeproduct-section">
    <div class="container">
        <h1 class="text-center">Our Products</h1>
        <div class="text-center button-container">
            <button id="featured-button" class="button active">Featured</button>
            <button id="clearance-button" class="button">Clearance</button>
        </div>

        <!-- Featured Section -->
        <br>
        <div class="products text-center" id="featured-section">
            @include('partials.welcomeproducts', ['products' => $products])
        </div> <!-- End Featured Section -->

        <!-- Featured Pagination -->
        <div class="pagination" id="featured-pagination">
            {{ $products->links('vendor.pagination.default') }}
        </div>

        <!-- Clearance Section -->
        <div class="products text-center" id="clearance-section" style="display: none;">
            @include('partials.welcomeproducts', ['products' => $clearanceProducts])
        </div> <!-- End Clearance Section -->

        <!-- Clearance Pagination -->
        <div class="pagination" id="clearance-pagination" style="display: none;">
            {{ $clearanceProducts->links('vendor.pagination.default') }}
        </div>
    </div>
</div>

<!-- Modal for Selecting Options -->
<div id="optionModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img id="modalProductImage" src="" alt="Product Image" style="max-width: 50%; height: auto; display:block; margin-left: auto; margin-right: auto;">
        <div id="modalProductName" class="product-name" style="font-weight: bold; text-align: center;"></div>
        <hr>
        <h2>Select an Option</h2>
        <form id="addToCartForm" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">
            <div id="optionsContainer"></div>
            <button type="submit" class="btn">+ <i class="fas fa-shopping-cart"></i></button>
        </form>
    </div>
</div>
<div id="notification" class="notification" style="display: none;"></div>
        
        <div class="text-center button-container">
            <br>
            <a href="/product" class="button">View more products</a>
        </div>
    </div>
</div> <!-- end featured-section -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // =============================================
        // MODAL AND ADD TO CART FUNCTIONALITY
        // =============================================
        
        // Use event delegation for dynamically loaded add-to-cart buttons
        $(document).on('click', '.add-to-cart', function() {
            const productId = $(this).data('product-id');
            $('#modalProductId').val(productId);
            $('#addToCartForm').attr('action', `/cart/add/${productId}`);
            
            // Fetch product details and options
            $.ajax({
                url: `/products/${productId}/options`,
                type: 'GET',
                success: function(response) {
                    // Update modal content with product details
                    $('#modalProductImage').attr('src', response.image);
                    $('#modalProductImage').attr('alt', response.name);
                    $('#modalProductName').text(response.name);

                    // Populate options
                    let optionsHtml = '';
                    response.options.forEach(option => {
                        optionsHtml += `
                            <div class="option">
                                <input type="radio" name="option_id" value="${option.id}" id="option${option.id}" required>
                                <label for="option${option.id}">
                                    ${option.option} - RM${option.price.toFixed(2)}
                                </label>
                            </div>
                        `;
                    });
                    $('#optionsContainer').html(optionsHtml);
                    $('#optionModal').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching product details:', error);
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
            event.preventDefault();
            const formData = $(this).serialize();
            const action = $(this).attr('action');

            $.ajax({
                url: action,
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Show notification
                    const notification = $('#notification');
                    notification.text('Product added to cart!');
                    notification.css('display', 'block');
                    notification.css('opacity', '1');
                    
                    // Hide the modal
                    $('#optionModal').hide();
                    
                    // Auto-hide the notification after 2 seconds
                    setTimeout(function() {
                        notification.css('opacity', '0');
                        setTimeout(function() {
                            notification.css('display', 'none');
                        }, 500);
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error('Error adding to cart:', error);

                    const notification = $('#notification');
                    notification.text('Sign in to add product to cart.');
                    notification.css('background-color', '#f44336');
                    notification.css('display', 'block');
                    notification.css('opacity', '1');
                    
                    setTimeout(function() {
                        notification.css('opacity', '0');
                        setTimeout(function() {
                            notification.css('display', 'none');
                        }, 500);
                    }, 4000);
                }
            });
        });

        // =============================================
        // PAGINATION HANDLING
        // =============================================
        
        // Function to handle AJAX pagination
        function handlePagination(section, pagination) {
            $(document).on('click', `#${pagination} .pagination a`, function(event) {
                event.preventDefault();
                let url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Update the product list and pagination links
                        $(`#${section}`).html($(response).find(`#${section}`).html());
                        $(`#${pagination}`).html($(response).find(`#${pagination}`).html());
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching pagination data:', error);
                    }
                });
            });
        }

        // Initialize AJAX pagination for both sections
        handlePagination('featured-section', 'featured-pagination');
        handlePagination('clearance-section', 'clearance-pagination');

        // =============================================
        // SECTION TOGGLE FUNCTIONALITY
        // =============================================

        // Function to show the Featured section
        function showFeatured() {
            $('#featured-section').css('display', 'grid');
            $('#clearance-section').hide();
            $('#featured-pagination').show();
            $('#clearance-pagination').hide();
            $('#featured-button').addClass('active');
            $('#clearance-button').removeClass('active');
        }

        // Function to show the Clearance section
        function showClearance() {
            $('#featured-section').hide();
            $('#clearance-section').css('display', 'grid');
            $('#featured-pagination').hide();
            $('#clearance-pagination').show();
            $('#featured-button').removeClass('active');
            $('#clearance-button').addClass('active');
        }

        // Set Featured as default on page load
        showFeatured();

        // Add click handlers for the section toggle buttons
        $('#featured-button').on('click', showFeatured);
        $('#clearance-button').on('click', showClearance);
    });

    // =============================================
    // SEARCH FUNCTIONALITY (if needed)
    // =============================================
    $(function() {
        $('#searchForm').on('submit', function(event) {
            event.preventDefault();
            let query = $('#searchInput').val();

            $.ajax({
                url: '{{ route("product.show") }}',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    $('#productsList').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                }
            });
        });

        // Hover to show description (using event delegation)
        $(document).on('mouseenter', '.product', function() {
            var description = $(this).data('description');
            var descriptionElement = $('<div class="product-description"></div>').text(description);
            $(this).append(descriptionElement);
        }).on('mouseleave', '.product', function() {
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

<style>
    @media (max-width: 767px) {
        .jj-title {
            font-size: 7rem;
        }
    }
    @media (max-width: 480px) {
        .jj-title {
            font-size: 7rem;
        }

        .intro-button {
            margin-bottom: 0.5rem;
            padding: 1rem !important;
            font-size: 16px;
            width: 8rem;
        }
    }

</style>