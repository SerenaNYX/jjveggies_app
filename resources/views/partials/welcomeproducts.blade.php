<!--@foreach ($products as $product)
    <div class="product" data-description="{{ $product->description }}">
        <a href="{{ route('products.show', $product->id) }}">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
        </a>
        <a href="{{ route('products.show', $product->id) }}">
            <div class="product-name">{{ $product->name }}</div>
        </a>
        <div class="product-price">RM{{ number_format($product->price, 2) }}</div>

        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="">
            @csrf
            <button type="submit" class="add-to-cart">
                <img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart">
            </button>
        </form>
    </div>
@endforeach-->

@foreach ($products as $product)
    <div class="product" data-description="{{ $product->description }}">
        <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
        <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
        <div class="product-price">From RM{{ number_format($product->options->min('price'), 2) }}</div>
        
        @if ($product->options->isEmpty())
            <!-- If no options, add directly to cart -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                @csrf
                <button type="submit" class="add-to-cart">
                    <img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart">
                </button>
            </form>
        @else
            <!-- If options exist, show modal -->
            <button type="button" class="add-to-cart" data-product-id="{{ $product->id }}">
                <img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart">
            </button>
        @endif
    </div>
@endforeach