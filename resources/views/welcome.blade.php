@extends('layouts.app')

@section('hero')
<div class="hero container">
    <div class="hero-copy">
        <h1>J&J Vegetables</h1>
        <p>Welcome to J&J Vegetables online.<br>Buy fresh vegetables from J&J Vegetables. You order, we deliver.</p>
        <div class="hero-buttons">
            <a href="/product" class="button button-white">Shop now</a>
            <a href="#" class="button button-white">How to order</a>
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
        <h1 class="text-center">Vegetables & Fruits</h1>
        <!--<p class="section-description1 text-center">Browse vegetables and fruits in this section.</p>-->
        <div class="text-center button-container">
            <button id="featured-button" class="button active" onclick="showFeatured()">Featured</button>
            <button id="clearance-button" class="button" onclick="showClearance()">Clearance</button>
        </div>
        <!-- featured section -->
        <div class="products text-center" id="featured-section">
            @foreach ($products as $product)
                <div class="product">
                    <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></a>
                    <a href="{{ route('products.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                    <div class="product-price">RM{{ number_format($product->price, 2) }}</div>

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="">
                        @csrf
                        <button type="submit" class="add-to-cart">
                            <img src="{{ asset('img/blackcart2.png') }}" alt="Add to Cart">
                        </button>
                    </form>
        
                </div>
            @endforeach
        </div> <!-- end featured section -->
        <!-- clearance section -->
        <div class="products text-center" id="clearance-section">
            @foreach ($products as $product)
                @if($product->category->name == 'Clearance')
                    <div class="product">
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
                @endif
            @endforeach
        </div> <!-- end clearance-section -->
        
        <div class="text-center button-container">
            <a href="#" class="button">View more products</a>
        </div>
    </div>

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
                </div>
            </div> <!-- end blog-posts -->
        </div> <!-- end container -->
    </div> <!-- end blog-section -->

</div> <!-- end featured-section -->
@endsection

<script>
function showFeatured() {
    document.getElementById('featured-section').style.display = 'grid';
    document.getElementById('clearance-section').style.display = 'none';
    document.getElementById('featured-button').classList.add('active');
    document.getElementById('clearance-button').classList.remove('active');
}

function showClearance() {
    document.getElementById('featured-section').style.display = 'none';
    document.getElementById('clearance-section').style.display = 'grid';
    document.getElementById('clearance-button').classList.add('active');
    document.getElementById('featured-button').classList.remove('active');
}
</script>
