@extends('layouts.app')

@section('hero')
<div class="hero container">
    <div class="hero-copy">
        <h1>J&J Vegetables</h1>
        <p>Welcome to J&J Vegetables online.<br>Buy fresh vegetables from J&J Vegetables. You order, we deliver.</p>
        <div class="hero-buttons">
            <a href="/product" class="button button-white">Shop now</a>
            <a href="/about" class="button button-white">About us</a>
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
            <button id="featured-button" class="button active" onclick="showFeatured()">Featured</button>
            <button id="clearance-button" class="button" onclick="showClearance()">Clearance</button>
        </div>

        <!-- Featured Section -->
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
        <h2>Select an Option</h2>
        <form id="addToCartForm" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">
            <div id="optionsContainer"></div>
            <button type="submit" class="btn">Add to Cart</button>
        </form>
    </div>
</div>
<div id="notification" class="notification" style="display: none;"></div>
        
        <div class="text-center button-container">
            <a href="/product" class="button">View more products</a>
        </div>
    </div>
    
<!--
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
                </div>-->
            </div> <!-- end blog-posts -->
        </div> <!-- end container -->
    </div> <!-- end blog-section -->

</div> <!-- end featured-section -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Open modal when "Add to Cart" is clicked
        $('.add-to-cart').on('click', function() {
            const productId = $(this).data('product-id');
            $('#modalProductId').val(productId); // Set the product ID in the form
            $('#addToCartForm').attr('action', `/cart/add/${productId}`);
            // Fetch product details and options
            $.ajax({
                url: `/products/${productId}/options`, // Updated endpoint name
                type: 'GET',
                success: function(response) {
                    // Update modal content with product details
                    $('#modalProductImage').attr('src', response.image); // Set product image
                    $('#modalProductImage').attr('alt', response.name); // Set alt text for image
                    $('#modalProductName').text(response.name); // Set product name

                    // Populate options
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
            event.preventDefault(); // Prevent default form submission

            const formData = $(this).serialize(); // Serialize form data
            const action = $(this).attr('action'); // Get the form action

            $.ajax({
                url: action, // Use the dynamically set action
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
                        }, 500); // Wait for fade out to complete
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error('Error adding to cart:', error);

                    const notification = $('#notification');
                    notification.text('Failed to add product to cart.');
                    notification.css('background-color', '#f44336'); // Red color for error
                    notification.css('display', 'block');
                    notification.css('opacity', '1');
                    
                    setTimeout(function() {
                        notification.css('opacity', '0');
                        setTimeout(function() {
                            notification.css('display', 'none');
                      //      notification.css('background-color', '#4CAF50');
                        }, 500);
                    }, 4000);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Function to handle AJAX pagination
        function handlePagination(section, pagination) {
            $(document).on('click', `#${pagination} .pagination a`, function(event) {
                event.preventDefault(); // Prevent default link behavior

                // Get the URL from the clicked link
                let url = $(this).attr('href');

                // Make an AJAX request to fetch the new page
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

        // Initialize AJAX pagination for Featured and Clearance sections
        handlePagination('featured-section', 'featured-pagination');
        handlePagination('clearance-section', 'clearance-pagination');
    });
</script>

<script>
    // Function to show the Featured section and hide the Clearance section
    function showFeatured() {
        document.getElementById('featured-section').style.display = 'grid';
        document.getElementById('clearance-section').style.display = 'none';
        document.getElementById('featured-pagination').style.display = 'block';
        document.getElementById('clearance-pagination').style.display = 'none';
        document.getElementById('featured-button').classList.add('active');
        document.getElementById('clearance-button').classList.remove('active');
    }

    // Function to show the Clearance section and hide the Featured section
    function showClearance() {
        document.getElementById('featured-section').style.display = 'none';
        document.getElementById('clearance-section').style.display = 'grid';
        document.getElementById('featured-pagination').style.display = 'none';
        document.getElementById('clearance-pagination').style.display = 'block';
        document.getElementById('clearance-button').classList.add('active');
        document.getElementById('featured-button').classList.remove('active');
    }

    // Set the Featured section as the default visible section on page load
    document.addEventListener('DOMContentLoaded', function () {
        showFeatured(); // Show Featured section by default
    });
</script>

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
