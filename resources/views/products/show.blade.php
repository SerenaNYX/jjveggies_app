@extends('layouts.app')

@section('content')
    <div class="product-container">
        <div class="product-detail">
            <h1>{{ $product->name }}</h1>
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            <div class="product-price">
                <span id="selected-price">Select an option to view price</span>
            </div>
            <p>{{ $product->description }}</p>

            <!-- Product Options Dropdown -->
            <div class="form-group">
                <label for="product-option">Select Option:</label>
                <select id="product-option" class="form-control">
                    <option value="">Choose an option</option>
                    @foreach ($product->options as $option)
                        <option value="{{ $option->id }}" data-price="{{ $option->price }}">
                            {{ $option->option }} - RM{{ number_format($option->price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="option_id" id="selected-option-id">
                <button type="submit" class="add-to-cart2" id="add-to-cart-button" disabled>
                    <img src="{{ asset('img/cart.png') }}" alt="Add to Cart">
                </button>
            </form>
        </div>
    </div>

    <!-- Frequently Bought Together & Same Category Recommendations -->
    <div class="recommendations-container">
        <h2>Frequently Bought Together</h2>
        <div class="recommendations-grid">
            @foreach($recommendedProducts as $recommended)
                <div class="recommended-product">
                    <a href="{{ route('products.show',  $recommended->id) }}">
                        <img height="50" width="50" style="object-fit: scale-down;" src="{{ asset($recommended->image) }}" alt="{{ $recommended->name }}">
                        <h3>{{ $recommended->name }}</h3>
                        <div class="price">
                            @if($recommended->options->count() > 0)
                                From RM{{ number_format($recommended->options->min('price'), 2) }}
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Products Section -->
<!--    <div class="recommendations-container">
        <h2>Popular Choices</h2>
        <div class="recommendations-grid">
            @foreach($popularProducts as $popular)
                <div class="recommended-product">
                    <a href="{{ route('products.show',  $popular->id) }}">
                        <img height="50" width="50" style="object-fit: scale-down;" src="{{ asset($popular->image) }}" alt="{{ $popular->name }}">
                        <h3>{{ $popular->name }}</h3>
                        <div class="price">
                            @if($popular->options->count() > 0)
                                From RM{{ number_format($popular->options->min('price'), 2) }}
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>-->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const optionSelect = document.getElementById('product-option');
            const selectedPrice = document.getElementById('selected-price');
            const selectedOptionId = document.getElementById('selected-option-id');
            const addToCartButton = document.getElementById('add-to-cart-button');

            optionSelect.addEventListener('change', function() {
                const selectedOption = optionSelect.options[optionSelect.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                if (price) {
                    selectedPrice.textContent = `RM${parseFloat(price).toFixed(2)}`;
                    selectedOptionId.value = selectedOption.value;
                    addToCartButton.disabled = false;
                } else {
                    selectedPrice.textContent = 'Select an option to view price';
                    selectedOptionId.value = '';
                    addToCartButton.disabled = true;
                }
            });
        });
    </script>

    <style>
        .product-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-detail {
            text-align: center;
        }

        .product-detail h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .product-detail img {
            max-height: 15rem;
            max-width: 100%;
            border-radius: 10px;
        }

        .product-price {
            font-size: 1.5rem;
            color: #6e924a;
            font-weight: bold;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .add-to-cart-form {
            margin-top: 20px;
        }

        .add-to-cart2 {
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: background-color 0.3s;
        }

        .add-to-cart2 img {
            height: 2rem;
            width: auto;
        }

        #add-to-cart-button:disabled {
            background-color: #aaa;
            cursor: default;
        }

        /* Recommendations Styles */
        .recommendations-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .recommendations-container h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .recommendations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .recommended-product {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .recommended-product:hover {
            transform: translateY(-5px);
        }

        .recommended-product a {
            text-decoration: none;
            color: #333;
        }

        .recommended-product img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .recommended-product h3 {
            font-size: 1rem;
            padding: 10px;
            margin: 0;
        }

        .recommended-product .price {
            padding: 0 10px 10px;
            color: #6e924a;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 29px !important;
            }

            p {
                font-size: 18px !important;
            }

            .product-container {
                width: 90%;
            }

            #selected-price {
                font-size: 18px;
            }
        }
    </style>
@endsection