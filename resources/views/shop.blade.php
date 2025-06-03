@extends('layout')

@section('title', 'Products')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Shop</span>
    @endcomponent

    <div class="container">
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
                @foreach ($categories as $category)
                    <li class="{{ setActiveCategory($category->slug) }}"><a href="{{ route('shop.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div> <!-- end sidebar -->
        <div>
            <div class="products-header">
                <h1 class="stylish-heading">{{ $categoryName }}</h1>
                <div>
                    <strong>Price: </strong>
                    <a href="{{ route('shop.index', ['category'=> request()->category, 'sort' => 'low_high']) }}">Low to High</a> |
                    <a href="{{ route('shop.index', ['category'=> request()->category, 'sort' => 'high_low']) }}">High to Low</a>

                </div>
            </div>

            <div class="products text-center">
                @forelse ($products as $product)
                    <div class="product">
                        <a href="{{ route('shop.show', $product->slug) }}"><img src="{{ productImage($product->image) }}" alt="product"></a>
                        <a href="{{ route('shop.show', $product->slug) }}"><div class="product-name">{{ $product->name }}</div></a>
                        <div class="product-price">{{ $product->presentPrice() }}</div>
                    </div>
                @empty
                    <div style="text-align: left">No items found</div>
                @endforelse
            </div> <!-- end products -->

            <div class="spacer"></div>
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>

@endsection

@section('extra-js')
    <!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script src="{{ asset('js/algolia.js') }}"></script>
@endsection

<style>
    /* Mobile Products Page Styles */
@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }

    .breadcrumbs {
        padding: 10px 0;
        font-size: 14px;
    }

    .breadcrumb-separator {
        margin: 0 5px;
    }

    .products-section {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        margin-bottom: 20px;
        order: -1; /* Move sidebar to top */
    }

    .sidebar h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .sidebar ul {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .sidebar ul li {
        margin: 0;
    }

    .sidebar ul li a {
        padding: 5px 10px;
        background: #f5f5f5;
        border-radius: 4px;
        display: block;
        font-size: 14px;
    }

    .products-header {
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .stylish-heading {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .products-header div {
        font-size: 14px;
    }

    .products {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .product {
        padding: 10px;
    }

    .product img {
        max-height: 120px;
    }

    .product-name {
        font-size: 14px;
        margin: 8px 0;
    }

    .product-price {
        font-size: 15px;
    }

    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .pagination li {
        margin: 0 3px;
    }

    .pagination a, 
    .pagination span {
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Alerts */
    .alert {
        padding: 10px 15px;
        font-size: 14px;
        margin: 10px 0;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .products {
        grid-template-columns: 1fr;
    }

    .product img {
        max-height: 150px;
    }

    .sidebar ul {
        gap: 5px;
    }

    .sidebar ul li a {
        padding: 4px 8px;
        font-size: 13px;
    }

    .stylish-heading {
        font-size: 18px;
    }

    .products-header div {
        font-size: 13px;
    }
}
</style>