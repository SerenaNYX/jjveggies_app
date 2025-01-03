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
    <style>
        #go-to-top {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed/sticky position */
            bottom: 20px; /* Place the button at the bottom */
            right: 30px; /* Place the button 30px from the right */
            z-index: 99; /* Make sure it does not overlap */
            border: none; /* Remove borders */
            outline: none; /* Remove outline */
            background-color: #63966b; /* Set a background color */
            color: white; /* Text color */
            cursor: pointer; /* Add a mouse pointer on hover */
            padding: 15px; /* Some padding */
            border-radius: 50px; /* Rounded corners */
            font-size: 18px; /* Increase font size */
            padding-left: 20px;
            padding-right: 20px;
        }

        #go-to-top:hover {
            background-color: #44684a; /* Add a dark-grey background on hover */
        }
    </style>
</head>
<body>
    <header>
        <div class="account">
            @guest
                <div class="login">
                    <a href="/loginpage">Login</a>
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
                    <img src="{{ asset('img/logo.jpg') }}" alt="J&J Vegetables"> J&J Vegetables
                </a>
            </div>
            <ul>
                @guest
                    <li><a href="/product">Shop</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                @else
                    <li><a href="/product">Shop</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#"><img src={{ asset('img/cart.png') }} alt=""></a></li>
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
