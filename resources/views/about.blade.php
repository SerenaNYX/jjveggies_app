@extends('layouts.app')

@section('content')

<div class="blog-section">
    <div class="container">
        <h1 class="text-center">Learn more about us</h1>
        <p class="section-description text-center"><strong>Welcome to J&J Vegetables, your trusted supplier for fresh and high-quality vegetables and fruits. </strong></p>

        <p>With a commitment to delivering farm-to-table goodness, we source our produce from the finest growers to ensure unmatched freshness, flavor, and nutritional value.
        We pride ourselves on meeting the diverse needs of our clients, whether you're a restaurant, grocer, or individual seeking top-notch produce. 
        From vibrant greens to succulent fruits, our extensive range is tailored to suit every season and culinary desire.
        Let us be your go-to choice for sustainable and superior vegetables and fruits.</p>

        <div class="blog-posts">
            <div class="blog-post" id="blog1">
                <a href="#"><img src="img/map.png" alt="blog image"></a>
                <a href="#"><h2 class="blog-title">Delivery Coverage</h2></a>
                <div class="blog-description">Permas Jaya, Johor Bahru, Austin Heights, Skudai, Iskandar Puteri.</div>
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

@endsection