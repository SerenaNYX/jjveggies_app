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
            <form autocomplete="off" action="{{ route('product.search') }}" method="GET">
                <div class="autocomplete">
                    <input id="productSearch" type="search" class="search-bar" name="query" placeholder="Search for products..." aria-label="Search">
                    <button type="submit" class="button-search">Search</button>
            </div>
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
                <div class="products text-center">
                    @foreach ($products as $product)
                        <div class="product" data-description="{{ $product->description }}">
                            <a href="{{ route('products.show', $product->id) }}"><img class="product-image" src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
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
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function autocomplete(inp) {
            let currentFocus;
            
            inp.addEventListener("input", function(e) {
                const val = this.value;
                closeAllLists();
                if (!val) { return false; }
                currentFocus = -1;
                
                const a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                this.parentNode.appendChild(a);
                
                $.ajax({
                    url: "{{ route('product.autocomplete') }}",
                    method: "GET",
                    data: { query: val },
                    success: function(response) {
                        // Clear previous results
                        a.innerHTML = '';
                        
                        if(response.length === 0) {
                            const noResults = document.createElement("DIV");
                            noResults.innerHTML = "No products found";
                            a.appendChild(noResults);
                            return;
                        }
                        
                        response.forEach(function(item) {
                            const b = document.createElement("DIV");
                            const startMatch = item.toLowerCase().indexOf(val.toLowerCase());
                            
                            if (startMatch >= 0) {
                                // Highlight matching part
                                b.innerHTML = item.substr(0, startMatch) + 
                                             "<strong>" + item.substr(startMatch, val.length) + "</strong>" + 
                                             item.substr(startMatch + val.length);
                            } else {
                                b.innerHTML = item;
                            }
                            
                            b.innerHTML += "<input type='hidden' value='" + item + "'>";
                            
                            b.addEventListener("click", function() {
                                inp.value = this.getElementsByTagName("input")[0].value;
                                closeAllLists();
                                inp.form.submit();
                            });
                            
                            a.appendChild(b);
                        });
                    },
                    error: function(xhr) {
                        console.error("Autocomplete error:", xhr.responseText);
                    }
                });
            });
            
            inp.addEventListener("keydown", function(e) {
                let x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    currentFocus++;
                    addActive(x);
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    currentFocus--;
                    addActive(x);
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (currentFocus > -1 && x) {
                        x[currentFocus].click();
                    } else {
                        this.form.submit();
                    }
                }
            });
            
            function addActive(x) {
                if (!x) return false;
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = x.length - 1;
                x[currentFocus].classList.add("autocomplete-active");
            }
            
            function removeActive(x) {
                for (let i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }
            
            function closeAllLists(elmnt) {
                const x = document.getElementsByClassName("autocomplete-items");
                for (let i = 0; i < x.length; i++) {
                    if (elmnt !== x[i] && elmnt !== inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            
            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }
        
        // Initialize autocomplete when DOM is fully loaded
        const searchInput = document.getElementById("productSearch");
        if (searchInput) {
            autocomplete(searchInput);
        }
    
        // Your existing jQuery code for add-to-cart functionality
        // ... keep all your existing cart-related JavaScript ...
    });
    </script>
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
                                    ${option.option} - RM${option.price.toFixed(2)}
                                </label>
                            </div>
                        `; /*(${option.quantity} in stock)*/
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