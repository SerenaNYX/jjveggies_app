<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>J&J Vegetables</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


</head>
<body>
    <header>
        <div class="account">
            @guest
                <div class="login">
                    <a href="/login">Login</a>
                </div>
                <div class="signup">
                    <a href="/registerpage">Sign Up</a>
                </div>
            @else
                <div class="profile">
                    <a href="/profile">My Profile</a>
                </div>
                <div class="logout">
                    <form action="/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </div>
            @endguest
        </div>

        <div class="top-nav container">
            <div class="logo">
                <a href="/">
                    <img src="{{ asset('img/J & J Vegetables Marketing (1).png') }}" alt="J&J Vegetables">
                </a>
            </div>
            <ul>
                @guest
                    <li></li>
                    <li></li>
                    <li></li>
                    <li><a href="/product">Shop</a></li>
                    <li><a href="/about">About</a></li>
                <!--    <li><a href="#">Contact</a></li> -->
                @else
                    <li><a href="/product">Shop</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/messages">Message</a></li>
                    <li><a href="#">Rewards</a></li>
                    <li><a href="/cart"><img src={{ asset('img/cart.png') }} alt=""></a></li>
                @endguest
            </ul>
        </div> <!-- end top-nav -->
        
        {{-- Conditionally include the hero section --}}
        @yield('hero')
    </header>

    <button id="go-to-top" title="Go to top">&#8679;</button>

    <script>
        //Get the button:
        let mybutton = document.getElementById("go-to-top");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        mybutton.onclick = function() {
            window.scroll({ 
                top: 0, 
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
