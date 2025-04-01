<!-- IS THIS STILL NEEDED??? -->


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
@endforeach