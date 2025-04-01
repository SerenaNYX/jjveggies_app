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

    </style>

@endsection
