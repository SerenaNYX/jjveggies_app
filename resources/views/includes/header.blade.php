<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>J&J Vegetables</title>
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <a href="/profile"><i class="fas fa-user"></i>&nbsp;Account</a>
                </div>
                <div>
                    <a href="/vouchers"><i class="fas fa-gift"></i>&nbsp;Reward</a>
                </div>
                <div class="logout">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="logout-button"><i class="fa fa-sign-out"></i>&nbsp;Logout</button>
                    </form>
                </div>
            @endguest
        </div>

        <div class="top-nav container">
            <div>
                <a class="logo"href="/">
                    <img src="{{ asset('img/J & J Vegetables Marketing (1).png') }}" alt="J&J Vegetables">
                </a>
            </div>
            <ul>
                @guest
                    <li class="hide-on-mobile"></li>
                    <li class="hide-on-mobile"></li>
                    <li><a href="/product">Shop</a></li>
                    <li><a href="/about">About</a></li>
                @else
                    <li><a href="/product">Shop</a></li>
                    <li><a href="/contact">Message</a></li>
                 <!--   <li><a href="#">Rewards</a></li>-->
                    <li><a href="/orders">Orders</a></li>
                <!--    <li><a href="/cart"><img src={{ asset('img/cart.png') }} alt="Cart"></li> -->
                    <li><a href="/cart"><i class="fas fa-shopping-cart"></i></a></li>
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

<style>

    @media (max-width: 1500px){
        .account {
            margin-right: -100px;
        }
    }

    @media (max-width: 1023px) {
        .account {
            margin-right: -200px;
        }

        .top-nav .logo img {
            margin-left: 100px;
        }

        .top-nav ul {
            margin-right: 30px;
        }
    }

/* Mobile Header Styles */
@media (max-width: 768px) {
    header {
        padding-bottom: 15px;
    }

    .hide-on-mobile {
        display: none;
    }

    .account {
        margin-right: 0;
        position: static;
        display: flex;
        justify-content: center;
        padding: 10px 15px;
        gap: 15px;
        flex-wrap: wrap;
        background-color: #3a4d3d;
    }

    .account > div {
        font-size: 14px;
        margin: 0 5px;

    }

    .account a, .account .logout-button, .account .login, .account .signup, .account .profile, .account .logout {
        &:hover {
            color: #beffb2;
        }
    }

    .top-nav {
        flex-direction: column;
        padding: 15px 0;
        align-items: center;
    }

    .top-nav .logo {
        margin-top: 0.5rem;
        padding-bottom: 10px;
    }

    .top-nav .logo img {
        width: 20rem;
        margin: 0 auto;
    }

    .top-nav ul {
        width: 100%;
        justify-content: space-around;
        padding: 0 10px;
    }

    .top-nav ul li {
        margin: 0 5px;
    }

    .top-nav ul a {
        font-size: 18px;
    }

    /*.top-nav ul img {
        width: 24px;
    }*/

    #go-to-top {
        bottom: 15px;
        right: 15px;
        padding: 10px 15px;
        font-size: 16px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .account {
        gap: 10px;
    }

    .account > div {
        font-size: 13px;
    }

    .top-nav .logo img {
        width: 15rem;
    }
}
</style>