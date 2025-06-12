@extends('layouts.app')

@section('content')

<div class="blog-section">
    <div class="container">
        <h1 class="text-center">Learn more about us</h1>
        <p class="section-description text-center">Welcome to J&J Vegetables, your trusted supplier for fresh and high-quality vegetables and fruits.</p>

        <p class="section-description text-center">Committed to delivering farm-to-table goodness, we source our produce from the finest growers to ensure unmatched freshness and nutritional value.
        We pride ourselves on meeting the diverse needs of our clients, whether you're a restaurant, grocer, or individual seeking top-notch produce.</p>

        <div class="blog-posts">
            <div class="blog-post" id="blog1">
                <img src="img/map.png" alt="" >
                <h2 class="blog-title">Delivery Coverage</h2>
                <div class="blog-description">We deliver fresh groceries to <strong>
                    <ul>
                        <li>Permas Jaya: 81750</li>
                        <li>Johor Bahru: 80000 to 81300 (main range)</li>
                        <li>Austin Heights: 81100</li>
                        <li>Skudai: 81300</li>
                        <li>Iskandar Puteri: 79100 (Nusajaya area)</li>
                    </ul>
                    </strong>
                </div>
            </div>
            <div class="blog-post" id="blog2">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d662.0049891411493!2d103.82755792994057!3d1.5086852083713673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da6b7bf3f34ab1%3A0x2b6fd1089d9b6300!2sJ%20%26%20J%20Vegetables%20Marketing!5e0!3m2!1sen!2smy!4v1748675467579!5m2!1sen!2smy" 
                    style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <h2 class="blog-title">Location</h2>
                <div class="blog-description">Find us at
                    <br><strong>9, Jalan Permas 3/18, Bandar Baru Permas Jaya, 81750 Johor Bahru, Johor Darul Ta'zim.</strong></div>
            </div>
    </div> <!-- end container -->
</div> <!-- end blog-section -->

@endsection

<style>

.blog-section {
    background: #f5f5f5;
    border-top: 1px solid #cdcdcd;
    padding: 50px 0;
    margin-bottom: -150px;

    .blog-posts {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 30px;
        margin: 60px 0;
    }

    .blog-description {
        font-weight: 300;
        font-size: 16px;
    }
}

.blog-post img {
    object-fit: cover;
    height: 300px;
}

.blog-post iframe {
    height: 300px;
    width: 100%;
}

@media (min-width: 768px) and (max-width: 1024px) {
    .blog-section {
        padding: 40px 0;
    }

    .blog-posts {
        grid-template-columns: 1fr 1fr !important; 
        gap: 30px !important;
        margin: 30px 0 !important;
    }

    .blog-post {
        padding: 20px;
        
    }

   /* .blog-post img,
    .blog-post iframe {
        width: 100% !important;
        height: 200px !important;
    }*/

    .blog-title {
        font-size: 22px;
        margin: 15px 0 10px;
    }

    .blog-description {
        font-size: 16px !important;
        line-height: 1.5;
    }

    .blog-description ul {
        padding-left: 20px;
        text-align: left;
    }
}

@media (max-width: 768px) {
    .blog-section {
        padding: 30px 0;
        margin-bottom: 0;
    }

    h1.text-center {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .section-description,
    .text-center p {
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .blog-posts {
        grid-template-columns: 1fr !important; /* Single column */
        gap: 30px !important;
        margin: 30px 0 !important;
    }

    .blog-post {
        text-align: center;
    }
/*
    .blog-post img,
    .blog-post iframe {
        object-fit: contain;
        width: 100% !important;
        height: auto !important;
        max-height: 200px;
    }*/

    .blog-title {
        font-size: 20px;
        margin: 15px 0 10px;
    }

    .blog-description {
        font-size: 15px !important;
        line-height: 1.5;
        padding: 0 10px;
    }

    .blog-description strong {
        display: inline-block;
        margin-top: 8px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    h1.text-center {
        font-size: 22px;
    }

    .section-description,
    .text-center p {
        font-size: 15px;
    }

    .blog-title {
        font-size: 18px;
    }

    .blog-description {
        font-size: 14px !important;
    }

    .blog-post img,
    .blog-post iframe {
        max-height: 180px;
    }
}
</style>